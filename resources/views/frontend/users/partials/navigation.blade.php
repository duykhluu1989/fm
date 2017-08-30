<section class="section_breadcrumb_user">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="list_breadcrumb_user">
                    <li class="{{ (request()->is('account') ? 'active' : '') }}"><a href="{{ action('Frontend\UserController@editAccount') }}"><i class="fa fa-user" aria-hidden="true"></i> Thông tin tài khoản</a></li>
                    <li class="{{ (request()->is('account/general') ? 'active' : '') }}"><a href="{{ action('Frontend\UserController@general') }}"><i class="fa fa-tasks" aria-hidden="true"></i> Tổng quan chung</a></li>
                    <li class="{{ (request()->is('account/order') ? 'active' : '') }}"><a href="{{ action('Frontend\UserController@adminOrder') }}"><i class="fa fa-th-list" aria-hidden="true"></i> Quản lý đơn hàng</a></li>
                    <li class="{{ (request()->is('order/import') ? 'active' : '') }}"><a href="{{ action('Frontend\OrderController@importExcelPlaceOrder') }}"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Đăng đơn hàng bằng excel</a></li>
                    <li class="{{ (request()->is('order') ? 'active' : '') }}"><a href="{{ action('Frontend\OrderController@placeOrder') }}"><i class="fa fa-folder-open" aria-hidden="true"></i> Đăng đơn hàng mới</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>