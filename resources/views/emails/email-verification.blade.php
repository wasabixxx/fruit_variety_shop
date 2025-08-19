<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận địa chỉ email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header .icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome {
            font-size: 18px;
            margin-bottom: 20px;
            color: #28a745;
        }
        .message {
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.8;
        }
        .verify-btn {
            display: inline-block;
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .verify-btn:hover {
            transform: translateY(-2px);
            color: white;
        }
        .verify-container {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .footer a {
            color: #28a745;
            text-decoration: none;
        }
        .token-info {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            font-family: monospace;
            font-size: 14px;
            border-left: 4px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">🌱</div>
            <h1>Fruit Variety Shop</h1>
            <p>Xác nhận địa chỉ email của bạn</p>
        </div>
        
        <div class="content">
            <div class="welcome">
                Xin chào {{ $user->name }}!
            </div>
            
            <div class="message">
                Cảm ơn bạn đã đăng ký tài khoản tại <strong>Fruit Variety Shop</strong>. 
                Để hoàn tất quá trình đăng ký và bảo mật tài khoản của bạn, 
                vui lòng xác nhận địa chỉ email bằng cách nhấp vào nút bên dưới.
            </div>
            
            <div class="verify-container">
                <a href="{{ route('email.verify', ['token' => $token]) }}" class="verify-btn">
                    ✓ Xác nhận địa chỉ email
                </a>
            </div>
            
            <div class="message">
                Nếu nút không hoạt động, bạn có thể sao chép và dán liên kết sau vào trình duyệt:
            </div>
            
            <div class="token-info">
                {{ route('email.verify', ['token' => $token]) }}
            </div>
            
            <div class="message">
                <strong>Lưu ý:</strong>
                <ul>
                    <li>Liên kết này sẽ hết hạn sau 24 giờ</li>
                    <li>Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email này</li>
                    <li>Để bảo mật, không chia sẻ liên kết này với ai khác</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p>
                Email này được gửi từ <strong>Fruit Variety Shop</strong><br>
                Chuyên cung cấp hạt giống cây ăn quả chất lượng cao
            </p>
            <p>
                <a href="{{ url('/') }}">Truy cập website</a> | 
                <a href="mailto:fruitvarietyshop@gmail.com">Liên hệ hỗ trợ</a>
            </p>
        </div>
    </div>
</body>
</html>
