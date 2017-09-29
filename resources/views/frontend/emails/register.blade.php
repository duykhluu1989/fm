<p>Kính gửi Quý Khách hàng <b>{{ $user->name }}</b>,</p>
<br />
<p>{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_TITLE) }} xin chân thành cảm ơn Quý Khách hàng đã tin tưởng lựa chọn và sử dụng dịch vụ của chúng tôi. Chi tiết thông tin tài khoản của Quý khách như sau:</p>
<br />
<p><b>ID tài khoản:</b> {{ $user->username }}</p>
<p><b>Họ tên:</b> {{ $user->name }}</p>
<p><b>Email:</b> {{ $user->email }}</p>
<p><b>Mật khẩu:</b> {{ $password }}</p>
<br />
<p>Trân trọng</p>