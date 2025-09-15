<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\User;
use Carbon\Carbon;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first admin user or create one
        $user = User::where('role', 'admin')->first();
        if (!$user) {
            $user = User::first();
        }

        $pages = [
            [
                'title' => 'Về chúng tôi',
                'slug' => 've-chung-toi',
                'content' => '<h2>Câu chuyện của chúng tôi</h2>
                <p>Được thành lập từ năm 2018, <strong>Fruit Variety Shop</strong> đã trở thành một trong những cửa hàng trái cây uy tín hàng đầu tại Việt Nam. Chúng tôi bắt đầu từ một cửa hàng nhỏ với ước mơ mang đến cho khách hàng những trái cây tươi ngon, chất lượng cao với giá cả hợp lý.</p>

                <h3>Sứ mệnh của chúng tôi</h3>
                <p>Chúng tôi cam kết:</p>
                <ul>
                    <li>Cung cấp trái cây tươi ngon, an toàn cho sức khỏe</li>
                    <li>Đảm bảo chất lượng từ nguồn gốc đến tay khách hàng</li>
                    <li>Phục vụ khách hàng với sự tận tâm và chuyên nghiệp</li>
                    <li>Góp phần xây dựng lối sống khỏe mạnh cho cộng đồng</li>
                </ul>

                <h3>Tầm nhìn</h3>
                <p>Trở thành chuỗi cửa hàng trái cây hàng đầu Việt Nam, được khách hàng tin tưởng và lựa chọn.</p>

                <h3>Đội ngũ của chúng tôi</h3>
                <p>Với đội ngũ nhân viên giàu kinh nghiệm và đam mê, chúng tôi luôn sẵn sàng tư vấn và hỗ trợ khách hàng chọn được những sản phẩm phù hợp nhất.</p>',
                'excerpt' => 'Tìm hiểu về câu chuyện và sứ mệnh của Fruit Variety Shop - nơi cung cấp trái cây tươi ngon, chất lượng cao.',
                'meta_title' => 'Về chúng tôi - Fruit Variety Shop',
                'meta_description' => 'Tìm hiểu về Fruit Variety Shop - cửa hàng trái cây uy tín với sứ mệnh mang đến sản phẩm tươi ngon, chất lượng cao cho khách hàng.',
                'meta_keywords' => 'về chúng tôi, fruit variety shop, cửa hàng trái cây, trái cây tươi',
                'template' => 'about',
                'is_published' => true,
                'show_in_menu' => true,
                'menu_order' => 1,
                'featured_image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'
            ],
            [
                'title' => 'Liên hệ',
                'slug' => 'lien-he',
                'content' => '<h2>Thông tin liên hệ</h2>
                <p>Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn. Hãy liên hệ với chúng tôi qua các thông tin dưới đây:</p>

                <h3>Địa chỉ cửa hàng</h3>
                <p><strong>Cửa hàng chính:</strong><br>
                123 Đường ABC, Quận 1, TP. Hồ Chí Minh<br>
                Điện thoại: 0123 456 789</p>

                <p><strong>Chi nhánh 2:</strong><br>
                456 Đường XYZ, Quận 7, TP. Hồ Chí Minh<br>
                Điện thoại: 0987 654 321</p>

                <h3>Thời gian làm việc</h3>
                <ul>
                    <li>Thứ 2 - Thứ 6: 8:00 - 18:00</li>
                    <li>Thứ 7 - Chủ nhật: 9:00 - 17:00</li>
                    <li>Các ngày lễ: 9:00 - 15:00</li>
                </ul>

                <h3>Liên hệ online</h3>
                <p>Email: info@fruitvarietyshop.com<br>
                Website: www.fruitvarietyshop.com<br>
                Hotline: 1900 1234 (miễn phí)</p>',
                'excerpt' => 'Liên hệ với Fruit Variety Shop để được tư vấn và hỗ trợ tốt nhất về các sản phẩm trái cây.',
                'meta_title' => 'Liên hệ - Fruit Variety Shop',
                'meta_description' => 'Liên hệ với Fruit Variety Shop qua hotline, email hoặc đến trực tiếp cửa hàng để được tư vấn về trái cây tươi ngon.',
                'meta_keywords' => 'liên hệ, fruit variety shop, địa chỉ cửa hàng, hotline',
                'template' => 'contact',
                'is_published' => true,
                'show_in_menu' => true,
                'menu_order' => 2
            ],
            [
                'title' => 'Chính sách bảo mật',
                'slug' => 'chinh-sach-bao-mat',
                'content' => '<h1>Chính sách bảo mật thông tin</h1>

                <h2>1. Mục đích thu thập thông tin</h2>
                <p>Chúng tôi thu thập thông tin cá nhân của khách hàng để:</p>
                <ul>
                    <li>Xử lý đơn hàng và giao hàng</li>
                    <li>Cung cấp dịch vụ khách hàng</li>
                    <li>Gửi thông tin khuyến mãi (khi có sự đồng ý)</li>
                    <li>Cải thiện chất lượng dịch vụ</li>
                </ul>

                <h2>2. Phạm vi thu thập thông tin</h2>
                <p>Chúng tôi thu thập các thông tin sau:</p>
                <ul>
                    <li>Họ tên, số điện thoại, email</li>
                    <li>Địa chỉ giao hàng</li>
                    <li>Thông tin thanh toán (được mã hóa bảo mật)</li>
                    <li>Lịch sử mua hàng</li>
                </ul>

                <h2>3. Thời gian lưu trữ thông tin</h2>
                <p>Thông tin cá nhân của khách hàng sẽ được lưu trữ cho đến khi có yêu cầu hủy bỏ hoặc tự khách hàng đăng nhập và thực hiện hủy bỏ.</p>

                <h2>4. Cam kết bảo mật</h2>
                <p>Chúng tôi cam kết:</p>
                <ul>
                    <li>Không bán, chia sẻ thông tin cá nhân cho bên thứ ba</li>
                    <li>Sử dụng công nghệ bảo mật tiên tiến</li>
                    <li>Đào tạo nhân viên về quy định bảo mật</li>
                    <li>Thông báo ngay khi có sự cố bảo mật</li>
                </ul>

                <h2>5. Quyền của khách hàng</h2>
                <p>Khách hàng có quyền:</p>
                <ul>
                    <li>Được biết về việc thu thập, sử dụng thông tin</li>
                    <li>Yêu cầu chỉnh sửa, cập nhật thông tin</li>
                    <li>Yêu cầu xóa thông tin cá nhân</li>
                    <li>Từ chối việc thu thập thông tin</li>
                </ul>',
                'excerpt' => 'Chính sách bảo mật thông tin cá nhân của khách hàng tại Fruit Variety Shop.',
                'meta_title' => 'Chính sách bảo mật - Fruit Variety Shop',
                'meta_description' => 'Tìm hiểu về chính sách bảo mật thông tin cá nhân và cam kết bảo vệ quyền riêng tư của khách hàng tại Fruit Variety Shop.',
                'meta_keywords' => 'chính sách bảo mật, bảo vệ thông tin, quyền riêng tư',
                'template' => 'policy',
                'is_published' => true,
                'show_in_menu' => false,
                'menu_order' => 0
            ],
            [
                'title' => 'Điều khoản sử dụng',
                'slug' => 'dieu-khoan-su-dung',
                'content' => '<h1>Điều khoản sử dụng website</h1>

                <h2>1. Chấp nhận điều khoản</h2>
                <p>Bằng việc truy cập và sử dụng website này, bạn đồng ý tuân thủ các điều khoản và điều kiện được quy định dưới đây.</p>

                <h2>2. Sử dụng website</h2>
                <p>Website này được cung cấp để:</p>
                <ul>
                    <li>Giới thiệu sản phẩm và dịch vụ</li>
                    <li>Nhận đặt hàng trực tuyến</li>
                    <li>Cung cấp thông tin hữu ích</li>
                    <li>Hỗ trợ khách hàng</li>
                </ul>

                <h2>3. Quyền sở hữu trí tuệ</h2>
                <p>Tất cả nội dung trên website bao gồm văn bản, hình ảnh, logo đều thuộc quyền sở hữu của Fruit Variety Shop.</p>

                <h2>4. Trách nhiệm của người dùng</h2>
                <p>Người dùng cam kết:</p>
                <ul>
                    <li>Cung cấp thông tin chính xác</li>
                    <li>Không sử dụng website cho mục đích bất hợp pháp</li>
                    <li>Không gây tổn hại đến hệ thống</li>
                    <li>Tuân thủ các quy định pháp luật</li>
                </ul>

                <h2>5. Giới hạn trách nhiệm</h2>
                <p>Chúng tôi không chịu trách nhiệm về:</p>
                <ul>
                    <li>Thiệt hại do gián đoạn dịch vụ</li>
                    <li>Sai sót thông tin từ bên thứ ba</li>
                    <li>Việc sử dụng sai mục đích</li>
                </ul>',
                'excerpt' => 'Điều khoản và điều kiện sử dụng website Fruit Variety Shop.',
                'meta_title' => 'Điều khoản sử dụng - Fruit Variety Shop',
                'meta_description' => 'Điều khoản và điều kiện sử dụng website Fruit Variety Shop. Vui lòng đọc kỹ trước khi sử dụng dịch vụ.',
                'meta_keywords' => 'điều khoản sử dụng, quy định website, điều kiện sử dụng',
                'template' => 'policy',
                'is_published' => true,
                'show_in_menu' => false,
                'menu_order' => 0
            ],
            [
                'title' => 'Chính sách giao hàng',
                'slug' => 'chinh-sach-giao-hang',
                'content' => '<h1>Chính sách giao hàng</h1>

                <h2>1. Khu vực giao hàng</h2>
                <p>Chúng tôi giao hàng tại:</p>
                <ul>
                    <li><strong>Nội thành TP.HCM:</strong> Tất cả các quận</li>
                    <li><strong>Các tỉnh lân cận:</strong> Bình Dương, Đồng Nai, Long An</li>
                    <li><strong>Toàn quốc:</strong> Qua dịch vụ chuyển phát</li>
                </ul>

                <h2>2. Thời gian giao hàng</h2>
                <p><strong>Nội thành TP.HCM:</strong></p>
                <ul>
                    <li>Giao hàng nhanh: 2-4 tiếng</li>
                    <li>Giao hàng tiêu chuẩn: 1-2 ngày</li>
                </ul>

                <p><strong>Ngoại thành và tỉnh lân cận:</strong> 2-3 ngày</p>
                <p><strong>Các tỉnh khác:</strong> 3-7 ngày</p>

                <h2>3. Phí giao hàng</h2>
                <ul>
                    <li>Miễn phí giao hàng cho đơn từ 500.000đ trong nội thành</li>
                    <li>Phí giao hàng nội thành: 25.000đ</li>
                    <li>Phí giao hàng ngoại thành: 35.000đ</li>
                    <li>Phí giao hàng toàn quốc: Theo biểu phí của đơn vị vận chuyển</li>
                </ul>

                <h2>4. Quy trình giao hàng</h2>
                <ol>
                    <li>Xác nhận đơn hàng qua điện thoại</li>
                    <li>Chuẩn bị và đóng gói sản phẩm</li>
                    <li>Giao hàng theo địa chỉ đã cung cấp</li>
                    <li>Khách hàng kiểm tra và thanh toán</li>
                </ol>',
                'excerpt' => 'Thông tin về chính sách giao hàng, thời gian và phí vận chuyển tại Fruit Variety Shop.',
                'meta_title' => 'Chính sách giao hàng - Fruit Variety Shop',
                'meta_description' => 'Tìm hiểu về chính sách giao hàng, thời gian vận chuyển và phí ship của Fruit Variety Shop.',
                'meta_keywords' => 'chính sách giao hàng, vận chuyển, phí ship, thời gian giao hàng',
                'template' => 'policy',
                'is_published' => true,
                'show_in_menu' => false,
                'menu_order' => 0
            ],
            [
                'title' => 'Chính sách đổi trả',
                'slug' => 'chinh-sach-doi-tra',
                'content' => '<h1>Chính sách đổi trả</h1>

                <h2>1. Điều kiện đổi trả</h2>
                <p>Chúng tôi chấp nhận đổi trả trong các trường hợp:</p>
                <ul>
                    <li>Sản phẩm không đúng chất lượng như cam kết</li>
                    <li>Sản phẩm bị hư hỏng trong quá trình vận chuyển</li>
                    <li>Giao nhầm sản phẩm</li>
                    <li>Sản phẩm không đúng yêu cầu đặt hàng</li>
                </ul>

                <h2>2. Thời gian đổi trả</h2>
                <ul>
                    <li><strong>Trái cây tươi:</strong> Trong vòng 2 giờ kể từ khi nhận hàng</li>
                    <li><strong>Trái cây sấy/đông lạnh:</strong> Trong vòng 24 giờ</li>
                    <li><strong>Quà tặng:</strong> Trong vòng 7 ngày</li>
                </ul>

                <h2>3. Quy trình đổi trả</h2>
                <ol>
                    <li>Liên hệ hotline: 1900 1234</li>
                    <li>Cung cấp thông tin đơn hàng</li>
                    <li>Mô tả lý do đổi trả</li>
                    <li>Chụp ảnh sản phẩm (nếu cần)</li>
                    <li>Chờ xác nhận và hướng dẫn</li>
                </ol>

                <h2>4. Chi phí đổi trả</h2>
                <ul>
                    <li>Miễn phí nếu lỗi từ cửa hàng</li>
                    <li>Khách hàng chịu phí vận chuyển nếu đổi ý</li>
                    <li>Hoàn tiền trong vòng 3-5 ngày làm việc</li>
                </ul>

                <h2>5. Những trường hợp không được đổi trả</h2>
                <ul>
                    <li>Quá thời gian quy định</li>
                    <li>Sản phẩm đã sử dụng/tiêu thụ</li>
                    <li>Không có bằng chứng mua hàng</li>
                    <li>Hư hỏng do bảo quản sai cách</li>
                </ul>',
                'excerpt' => 'Chính sách đổi trả sản phẩm tại Fruit Variety Shop - đảm bảo quyền lợi cho khách hàng.',
                'meta_title' => 'Chính sách đổi trả - Fruit Variety Shop',
                'meta_description' => 'Tìm hiểu về chính sách đổi trả sản phẩm, điều kiện và quy trình tại Fruit Variety Shop.',
                'meta_keywords' => 'chính sách đổi trả, hoàn tiền, bảo hành sản phẩm',
                'template' => 'policy',
                'is_published' => true,
                'show_in_menu' => false,
                'menu_order' => 0
            ]
        ];

        foreach ($pages as $pageData) {
            $pageData['created_by'] = $user->id ?? 1;
            $pageData['updated_by'] = $user->id ?? 1;
            $pageData['created_at'] = Carbon::now();
            $pageData['updated_at'] = Carbon::now();
            
            if ($pageData['is_published']) {
                $pageData['published_at'] = Carbon::now();
            }

            Page::create($pageData);
        }
    }
}
