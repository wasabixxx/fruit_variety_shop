# SepayGui — Hướng dẫn tích hợp thanh toán tự động SePay cho Laravel (shop bán hạt giống)

> Mục tiêu: AI/Dev khác có thể cắm SePay vào project Laravel để **tự động xác nhận đơn** khi khách chuyển khoản (webhook) hoặc dùng **Virtual Account (VA) theo đơn** nếu ngân hàng hỗ trợ.

---

## 1) Tổng quan luồng

1. Khách đặt hàng → Backend tạo `order` (status = `pending`).
2. Trang thanh toán hiển thị **nội dung chuyển khoản** chứa mã (vd: `SE{order_id}`) **hoặc** hiển thị **số VA** của đơn.
3. Khách chuyển khoản.
4. SePay phát hiện giao dịch **tiền vào** và **gọi Webhook** đến server.
5. Server **đối chiếu** (mã trong content *hoặc* VA + **đúng số tiền**) → cập nhật `orders.status = 'paid'`, lưu `payments` (idempotent theo `provider_tx_id`).
6. UI cập nhật realtime (polling/SSE/WebSocket) → hiển thị “Thanh toán thành công”.

---

## 2) Yêu cầu & chuẩn bị

* Laravel 10/11/12.
* Composer cài package **SePay Laravel**.
* Có quyền cấu hình **Webhook** trên `my.sepay.vn` / `my.dev.sepay.vn` (sandbox).
* Có **API Token** (đối soát) &/hoặc **VA theo đơn** (nếu dùng).

---

## 3) Cài đặt package & cấu hình

```bash
composer require sepayvn/laravel-sepay
php artisan vendor:publish --tag="sepay-migrations"
php artisan migrate
php artisan vendor:publish --tag="sepay-config"
```

**.env** (ví dụ)

```env
SEPAY_WEBHOOK_TOKEN=long_bi_mat_ngau_nhien  # bí mật dùng xác thực webhook (API Key)
SEPAY_MATCH_PATTERN=SE                      # prefix mã thanh toán (ví dụ SE{order_id})
SEPAY_API_TOKEN=xxxxxxxxxxxxxxxxxxxxxxxxxx  # nếu dùng API đối soát
```

> Package tự tạo route webhook: `/api/sepay/webhook` và event `SePayWebhookEvent`.

---

## 4) Cấu hình Webhook bên SePay

* URL: `https://<domain>/api/sepay/webhook`
* Loại: **Webhook xác thực thanh toán**
* Kiểu chứng thực: **API Key**
* API Key: **trùng** với `SEPAY_WEBHOOK_TOKEN` (.env)
* Phản hồi thành công: JSON `{"success": true}` với HTTP **201** (hoặc 200 – tùy cấu hình)
* SePay có **retry** khi lỗi (idempotent ở server để chống ghi trùng)

---

## 5) Lược đồ CSDL khuyến nghị

Tạo nếu project chưa có bảng đơn & thanh toán.

```php
// database/migrations/2025_08_19_000000_create_orders_payments.php
Schema::create('orders', function (Blueprint $t) {
  $t->id();
  $t->string('customer_name');
  $t->string('customer_phone')->nullable();
  $t->unsignedBigInteger('total_amount'); // VND
  $t->enum('status', ['pending','paid','cancelled'])->default('pending');
  $t->timestamp('paid_at')->nullable();
  $t->timestamps();
});

Schema::create('payments', function (Blueprint $t) {
  $t->id();
  $t->foreignId('order_id')->constrained()->cascadeOnDelete();
  $t->string('provider'); // 'sepay'
  $t->string('provider_tx_id')->unique();
  $t->unsignedBigInteger('amount');
  $t->string('status'); // 'succeeded','failed'
  $t->json('raw')->nullable();
  $t->timestamps();
});
```

---

## 6) Listener Webhook — logic “đánh dấu đã thanh toán”

Tạo listener:

```bash
php artisan make:listener SePayWebhookListener
```

`app/Listeners/SePayWebhookListener.php`

```php
<?php
namespace App\Listeners;

use Illuminate\Support\Facades\DB;
use SePay\SePay\Events\SePayWebhookEvent;
use App\Models\Order;
use App\Models\Payment;

class SePayWebhookListener
{
    public function handle(SePayWebhookEvent $event): void
    {
        $d = $event->sePayWebhookData; // object từ SePay

        // 1) Chỉ xử lý giao dịch tiền vào
        if (($d->transferType ?? null) !== 'in') return;

        // 2) Idempotent theo transaction id từ SePay
        $txId = (string)($d->id ?? '');
        if ($txId === '') return;

        DB::transaction(function () use ($d, $txId) {
            // 3) Tìm đơn: ưu tiên theo mã trong nội dung CK: "SE{order_id}"
            $content = (string)($d->content ?? $d->transaction_content ?? '');
            $order = null;
            if (preg_match('/\bSE(\d+)\b/i', $content, $m)) {
                $order = Order::lockForUpdate()->find($m[1]);
            }

            // 3b) Nếu dùng VA theo đơn: map theo $d->subAccount hoặc VA đã gán cho order
            // $order = Order::lockForUpdate()->where('va_number', $d->subAccount)->first();

            if (!$order) return;

            // 4) Chặn bản ghi trùng
            $already = Payment::where('provider', 'sepay')
                ->where('provider_tx_id', $txId)
                ->lockForUpdate()->exists();
            if ($already) return;

            // 5) Khớp số tiền tuyệt đối (tuỳ chính sách có thể cho sai số nhỏ)
            $amount = (int)($d->transferAmount ?? 0);
            if ($amount !== (int)$order->total_amount) {
                // cờ tuỳ chọn: pending_review nếu lệch
                return;
            }

            // 6) Lưu payment + cập nhật đơn
            Payment::create([
                'order_id'       => $order->id,
                'provider'       => 'sepay',
                'provider_tx_id' => $txId,
                'amount'         => $amount,
                'status'         => 'succeeded',
                'raw'            => json_encode($d),
            ]);

            $order->update(['status' => 'paid', 'paid_at' => now()]);

            // 7) Phát sự kiện nội bộ (email/sms/stock/websocket)
            event(new \App\Events\OrderPaid($order->id));
        });
    }
}
```

**Đăng ký listener** (nếu chưa auto-discover):

```php
// app/Providers/AppServiceProvider.php
use SePay\SePay\Events\SePayWebhookEvent;
use App\Listeners\SePayWebhookListener;
use Illuminate\Support\Facades\Event;

public function boot(): void
{
    Event::listen(SePayWebhookEvent::class, SePayWebhookListener::class);
}
```

---

## 7) Xác thực Webhook

* Dùng header `Authorization: Apikey <SEPAY_WEBHOOK_TOKEN>` từ SePay.
* Middleware (tuỳ chọn) kiểm tra header khớp `.env` trước khi vào listener.
* Không log secret.

---

## 8) Gợi ý UI/UX cho trang Thanh toán

* Hiển thị rõ **nội dung chuyển khoản** cần gõ: `SE{order_id}` (copy button).
* Nếu có **VA theo đơn**: hiển thị **số VA** + **số tiền chính xác**.
* Sau khi đặt hàng, chuyển đến trang “Chờ thanh toán” và **poll** trạng thái:

```php
// routes/web.php
Route::get('/orders/{order}/status', function (App\Models\Order $order) {
    return response()->json(['status' => $order->status]);
});
```

Frontend gọi mỗi 3–5s; khi thấy `status = paid` → báo thành công.

> Tuỳ chọn: dùng **SSE** hoặc **Laravel Echo** (Pusher/Socket.IO) để realtime không cần polling.

---

## 9) Đối soát (failsafe) bằng API

Dùng khi webhook lỗi/timeout, chạy cron mỗi 5–10 phút:

```php
// app/Console/Commands/SepayReconcile.php (giản lược)
public function handle()
{
    $client = new \GuzzleHttp\Client(['base_uri' => 'https://my.sepay.vn']);
    $res = $client->get('/userapi/transactions/list', [
        'headers' => [
          'Authorization' => 'Bearer '.env('SEPAY_API_TOKEN'),
          'Accept' => 'application/json',
        ],
        'query' => [
          'transaction_date_min' => now()->subDay()->format('Y-m-d 00:00:00'),
          // có thể dùng since_id/reference_number/account_number để lọc chính xác hơn
        ]
    ]);
    $list = json_decode($res->getBody()->getContents(), true)['transactions'] ?? [];
    foreach ($list as $tx) {
        // map bằng regex SE{order_id} hoặc VA/amount → cập nhật giống listener
    }
}
```

---

## 10) Kiểm thử

* **Postman**: giả lập webhook tới `POST /api/sepay/webhook` với header:

  * `Content-Type: application/json`
  * `Authorization: Bearer Apikey <SEPAY_WEBHOOK_TOKEN>`
  * Body theo mẫu payload từ SePay (các trường: `id`, `transactionDate`, `accountNumber`, `content`, `transferType='in'`, `transferAmount`, `referenceCode`, ...)
* **Sandbox**: `my.dev.sepay.vn` → tạo giao dịch test → xem **Nhật ký webhook**.

---

## 11) Quy tắc an toàn & best practices

* **Idempotent** theo `id`/`referenceCode` (unique index `payments(provider, provider_tx_id)`).
* **Transaction + lockForUpdate()** khi cập nhật đơn.
* **Khớp số tiền** tuyệt đối; nếu lệch → `pending_review` để xử lý thủ công.
* **VA theo đơn** (nếu có) → khớp 1–1, ít rủi ro.
* **Ẩn secret** trong `.env`, không in log; giới hạn IP Webhook nếu hạ tầng cho phép.
* **Phản hồi chuẩn** `{"success": true}` + HTTP 201/200 để SePay không retry.

---

## 12) Checklist triển khai nhanh

* [ ] Cài package `sepayvn/laravel-sepay` + migrate + publish config.
* [ ] Cấu hình Webhook trên SePay (URL, API Key = `SEPAY_WEBHOOK_TOKEN`).
* [ ] Quy ước mã thanh toán `SE{order_id}` hoặc bật **VA theo đơn**.
* [ ] Viết **SePayWebhookListener** (như ở mục 6) & đăng ký listener.
* [ ] Tạo route `GET /orders/{id}/status` (hoặc SSE/WS) cho UI chờ.
* [ ] Tạo cron **đối soát** dùng `SEPAY_API_TOKEN` (tuỳ chọn nhưng khuyến nghị).
* [ ] Viết test giả lập webhook (Feature Test) & test end‑to‑end.

---

## 13) Tham khảo nhanh (đặt đúng nơi trong tài liệu nội bộ)

* URL Webhook mặc định của package: `/api/sepay/webhook`
* Event: `SePay\\SePay\\Events\\SePayWebhookEvent`
* Trường payload trọng yếu: `id`, `transferType`, `transferAmount`, `content`, `code`, `referenceCode`, `transactionDate`, `accountNumber`, `subAccount`
* Bộ khớp đơn (ưu tiên theo thứ tự):

  1. **VA theo đơn** (`subAccount`/VA + đủ số tiền)
  2. **Mã trong content** (`SE{order_id}`)
  3. **Số tiền + khoảng thời gian + STK nhận** (dự phòng thủ công)

> Hết. File này đủ để AI/Dev khác thực thi mà không cần tham chiếu ngoài.
\\

https://developers.momo.vn/v3/vi/docs/payment/onboarding/test-instructions/#th%C3%B4ng-tin-test-th%E1%BA%BB-atm