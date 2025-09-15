<x-mail::message>
# Đặt lại mật khẩu

Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.

<x-mail::button :url="$url">
Đặt lại mật khẩu
</x-mail::button>

Link đặt lại mật khẩu sẽ hết hạn sau 60 phút.

Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này.

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
