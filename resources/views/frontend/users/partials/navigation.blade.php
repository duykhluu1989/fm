<section class="section_breadcrumb_user">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list_breadcrumb_user">
                    <li><a href="{{ action('Frontend\UserController@tongquanchung') }}"><i class="fa fa-tasks" aria-hidden="true"></i> Tổng quan chung</a></li>
                    <li><a href="{{ action('Frontend\UserController@quanlydonhang') }}"><i class="fa fa-th-list" aria-hidden="true"></i> Quản lý đơn hàng</a></li>
                    <li><a href="{{ action('Frontend\UserController@quanlydongtien') }}"><i class="fa fa-usd" aria-hidden="true"></i> Quản lý dòng tiền</a></li>
                    <li><a href="dangdonhangexcel.php"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Đăng đơn hàng bằng excel</a></li>
                    <li><a href="{{ action('Frontend\OrderController@placeOrder') }}"><i class="fa fa-folder-open" aria-hidden="true"></i> Đăng đơn hàng mới</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>