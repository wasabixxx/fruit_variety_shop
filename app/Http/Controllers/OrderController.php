<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\VoucherUsage;

class OrderController extends Controller
{
    // Hiển thị trang chọn phương thức thanh toán và xác nhận đơn hàng
    public function create(Request $request)
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        
        // Get voucher info
        $appliedVoucher = session('applied_voucher');
        $discountAmount = session('voucher_discount_amount', 0);
        $total = $subtotal - $discountAmount;
        
        if ($subtotal <= 0 || count($cart) === 0) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng trống!');
        }
        return view('orders.create', compact('cart', 'subtotal', 'total', 'appliedVoucher', 'discountAmount'));
    }

    // Xử lý đặt hàng với phương thức thanh toán được chọn
    public function store(Request $request)
    {
        $cart = session('cart', []);
        $subtotal = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        
        // Get voucher info
        $appliedVoucher = session('applied_voucher');
        $discountAmount = session('voucher_discount_amount', 0);
        $total = $subtotal - $discountAmount;
        
        if ($subtotal <= 0 || count($cart) === 0) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng trống!');
        }

        $request->validate([
            'payment_method' => 'required|in:cod,momo_atm,momo_card,momo_wallet',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500'
        ]);

        $paymentMethod = $request->input('payment_method');
        
        // Lưu thông tin đơn hàng vào session để hiển thị
        session([
            'order_info' => [
                'items' => $cart,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'customer_name' => $request->input('customer_name'),
                'customer_phone' => $request->input('customer_phone'),
                'customer_address' => $request->input('customer_address'),
                'order_time' => now()->format('d/m/Y H:i:s'),
                'voucher' => $appliedVoucher
            ]
        ]);

        if ($paymentMethod === 'cod') {
            // COD: Lưu đơn hàng ngay
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone, 
                'customer_address' => $request->customer_address,
                'total_amount' => $total,
                'discount_amount' => $discountAmount,
                'voucher_id' => $appliedVoucher ? $appliedVoucher['id'] : null,
                'voucher_code' => $appliedVoucher ? $appliedVoucher['code'] : null,
                'payment_method' => 'cod',
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'note' => $request->note
            ]);

            // Lưu chi tiết đơn hàng
            foreach ($cart as $productId => $item) {
                $order->orderItems()->create([
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            // Record voucher usage if applied
            if ($appliedVoucher && $discountAmount > 0) {
                $this->recordVoucherUsage($appliedVoucher['id'], $order, $discountAmount);
            }

            // Clear session
            session()->forget(['cart', 'applied_voucher', 'voucher_discount_amount']);
            return redirect()->route('orders.success')->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn để giao hàng.');
        } elseif (in_array($paymentMethod, ['momo_atm', 'momo_card', 'momo_wallet'])) {
            // MoMo: Chuyển đến trang thanh toán với method
            $method = str_replace('momo_', '', $paymentMethod); // atm, card, wallet
            return redirect()->route('orders.payment', ['method' => $method]);
        }
    }

    // Hiển thị trang thanh toán MoMo
    public function payment(Request $request)
    {
        $orderInfo = session('order_info');
        $paymentMethod = $request->get('method', 'atm'); // Mặc định ATM
        
        if (!$orderInfo) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin đơn hàng!');
        }

        // Tạo request MoMo theo đúng format API
        $partnerCode = config('app.momo_partner_code');
        $accessKey = config('app.momo_access_key');
        $secretKey = config('app.momo_secret_key');
        $orderId = 'ORDER_' . time() . rand(1000, 9999);
        $requestId = 'REQUEST_' . time() . rand(1000, 9999);
        $amount = $orderInfo['total'];
        $orderInfo_momo = 'Thanh toan FRUIT VARIETY SHOP - ' . $orderInfo['customer_name'];
        $redirectUrl = route('orders.momo-return');
        $ipnUrl = route('orders.momo-notify');
        $extraData = '';
        
        // Xác định requestType theo payment method
        $requestTypes = [
            'atm' => 'payWithATM',      // ATM Online Banking
            'card' => 'payWithCC',      // International Credit/Debit Card  
            'wallet' => 'captureWallet' // MoMo Wallet
        ];
        $requestType = $requestTypes[$paymentMethod] ?? 'payWithATM';

        // Tạo signature theo chuẩn MoMo
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo_momo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo_momo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        try {
            // Gửi request đến MoMo API
            $endpoint = config('app.momo_endpoint');
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);

            // Kiểm tra response từ MoMo
            if (!$jsonResult || !isset($jsonResult['payUrl'])) {
                throw new \Exception('Phản hồi từ MoMo API không hợp lệ: ' . $result);
            }

            // Lưu orderId vào session để xác thực sau
            session(['momo_order_id' => $orderId]);

            return view('orders.payment', compact('orderInfo', 'jsonResult', 'data', 'paymentMethod'));
        } catch (\Exception $e) {
            // Log lỗi cụ thể
            \Log::error('MoMo Payment Error', [
                'error' => $e->getMessage(),
                'order_info' => $orderInfo,
                'payment_method' => $paymentMethod,
                'endpoint' => config('app.momo_endpoint'),
                'data' => $data
            ]);

            // Nếu gặp lỗi API, chuyển về mock payment
            session(['momo_order_id' => $orderId]);
            session()->flash('warning', 'Hệ thống thanh toán MoMo đang bảo trì. Vui lòng sử dụng phương thức thanh toán thử nghiệm.');
            
            return view('orders.payment-mock', compact('orderInfo', 'paymentMethod'));
        }
    }

    // Hàm gửi POST request JSON đến MoMo
    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        //execute post
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        // Log để debug
        \Log::info('MoMo API Call', [
            'url' => $url,
            'data' => $data,
            'http_code' => $httpCode,
            'response' => $result,
            'curl_error' => $error
        ]);
        
        //close connection
        curl_close($ch);
        
        if ($error) {
            throw new \Exception("Lỗi kết nối MoMo API: " . $error);
        }
        
        if ($httpCode !== 200) {
            throw new \Exception("MoMo API trả về lỗi HTTP: " . $httpCode);
        }
        
        return $result;
    }

    // Xử lý kết quả trả về từ MoMo
    public function momoReturn(Request $request)
    {
        $orderInfo = session('order_info');
        $momoOrderId = session('momo_order_id');
        
        if (!$orderInfo || !$momoOrderId) {
            return redirect()->route('cart.index')->with('error', 'Phiên thanh toán không hợp lệ!');
        }

        // Kiểm tra kết quả thanh toán
        $resultCode = $request->get('resultCode', '1');
        
        if ($resultCode == '0') {
            // Thanh toán thành công - Lưu đơn hàng vào database
            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_name' => $orderInfo['customer_name'],
                'customer_phone' => $orderInfo['customer_phone'],
                'customer_address' => $orderInfo['customer_address'],
                'total_amount' => $orderInfo['total'],
                'payment_method' => $orderInfo['payment_method'],
                'payment_status' => 'paid',
                'order_status' => 'confirmed',
                'momo_transaction_id' => $request->input('transId'),
                'note' => null
            ]);

            // Lưu chi tiết đơn hàng
            foreach ($orderInfo['items'] as $productId => $item) {
                $order->orderItems()->create([
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            session()->forget(['cart', 'order_info', 'momo_order_id']);
            return redirect()->route('orders.success')->with('success', 'Thanh toán MoMo thành công! Đơn hàng của bạn đã được xác nhận.');
        } else {
            // Thanh toán thất bại
            $message = $this->getMomoErrorMessage($resultCode);
            return redirect()->route('orders.create')->with('error', 'Thanh toán thất bại: ' . $message);
        }
    }

    // Xử lý IPN từ MoMo (webhook)
    public function momoNotify(Request $request)
    {
        // Log thông tin IPN để debug nếu cần
        \Log::info('MoMo IPN:', $request->all());
        
        return response()->json(['status' => 'success']);
    }

    // Lấy thông báo lỗi MoMo
    private function getMomoErrorMessage($resultCode)
    {
        $messages = [
            '1' => 'Giao dịch thất bại',
            '2' => 'Giao dịch bị từ chối',
            '9' => 'Giao dịch bị hủy bởi người dùng',
            '10' => 'Thẻ/Tài khoản bị khóa',
            '11' => 'Thẻ hết hạn',
            '12' => 'Thẻ chưa đăng ký dịch vụ',
            '13' => 'Thẻ không đủ tiền',
            '21' => 'Số tiền không hợp lệ',
            '99' => 'Lỗi không xác định'
        ];

        return $messages[$resultCode] ?? 'Lỗi không xác định';
    }

    // Trang thành công
    public function success()
    {
        return view('orders.success');
    }

    // Lịch sử đơn hàng của user
    public function myOrders()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem lịch sử đơn hàng.');
        }

        $orders = auth()->user()->orders()
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.my-orders', compact('orders'));
    }

    public function myOrderDetail(Order $order)
    {
        if (!auth()->check() || $order->user_id !== auth()->id()) {
            abort(404);
        }

        $order->load('orderItems.product');
        return view('orders.my-order-detail', compact('order'));
    }

    /**
     * Record voucher usage after successful order
     */
    private function recordVoucherUsage($voucherId, $order, $discountAmount)
    {
        if (!auth()->check()) {
            return;
        }

        $voucher = Voucher::find($voucherId);
        if (!$voucher) {
            return;
        }

        // Create voucher usage record
        VoucherUsage::create([
            'voucher_id' => $voucherId,
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'discount_amount' => $discountAmount,
            'used_at' => now()
        ]);

        // Increment voucher used count
        $voucher->increment('used_count');
    }
    
    // Test kết nối MoMo API
    public function testMomo()
    {
        $endpoint = config('app.momo_endpoint', env('MOMO_ENDPOINT'));
        $partnerCode = config('app.momo_partner_code', env('MOMO_PARTNER_CODE'));
        $accessKey = config('app.momo_access_key', env('MOMO_ACCESS_KEY'));
        $secretKey = config('app.momo_secret_key', env('MOMO_SECRET_KEY'));
        
        // Debug env variables
        $envDebug = [
            'endpoint' => $endpoint,
            'partner_code' => $partnerCode,
            'access_key' => $accessKey,
            'secret_key_length' => $secretKey ? strlen($secretKey) : 0
        ];
        
        if (!$endpoint || !$partnerCode || !$accessKey || !$secretKey) {
            return response()->json([
                'status' => 'error',
                'message' => 'Thiếu cấu hình MoMo environment variables',
                'debug' => $envDebug
            ]);
        }
        
        // Test data đơn giản
        $orderId = 'TEST_' . time();
        $requestId = 'TEST_REQUEST_' . time();
        $amount = 50000;
        $orderInfo = 'Test MoMo connection';
        $redirectUrl = route('orders.momo-return');
        $ipnUrl = route('orders.momo-notify');
        $extraData = '';
        $requestType = 'payWithATM';

        // Tạo signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        ];

        try {
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Kết nối MoMo API thành công',
                'env_debug' => $envDebug,
                'endpoint' => $endpoint,
                'request_data' => $data,
                'response' => $jsonResult
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Lỗi kết nối MoMo API: ' . $e->getMessage(),
                'env_debug' => $envDebug,
                'endpoint' => $endpoint,
                'request_data' => $data
            ]);
        }
    }
}
