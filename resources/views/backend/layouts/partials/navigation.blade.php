<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="treeview{{ (request()->is('admin/order*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-inbox"></i><span>Đơn Hàng</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/order') ? 'active' : '') }}">
                        <a href="{{ action('Backend\OrderController@adminOrder') }}">Đơn Hàng</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (request()->is('admin/area*') ? 'active' : '') }}">
                <a href="{{ action('Backend\AreaController@adminArea') }}"><i class="fa fa-truck"></i><span>Khu Vực Giao Hàng</span></a>
            </li>
            <li class="{{ (request()->is('admin/discount*') ? 'active' : '') }}">
                <a href="{{ action('Backend\DiscountController@adminDiscount') }}"><i class="fa fa-tags"></i><span>Mã Giảm Giá</span></a>
            </li>
            <li class="{{ (request()->is('admin/widget*') ? 'active' : '') }}">
                <a href="{{ action('Backend\WidgetController@adminWidget') }}"><i class="fa fa-th"></i><span>Tiện Ích</span></a>
            </li>
            <li class="{{ (request()->is('admin/article*') ? 'active' : '') }}">
                <a href="{{ action('Backend\ArticleController@adminArticle') }}"><i class="fa fa-file-text"></i><span>Trang Tĩnh</span></a>
            </li>
            <li class="treeview{{ (request()->is('admin/user*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-user"></i><span>Thành Viên</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/user') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUser') }}">Quản Trị Viên</a>
                    </li>
                    <li class="{{ (request()->is('admin/userCustomer') ? 'active' : '') }}">
                        <a href="{{ action('Backend\UserController@adminUserCustomer') }}">Khách Hàng</a>
                    </li>
                </ul>
            </li>
            <li class="{{ (request()->is('admin/role*') ? 'active' : '') }}">
                <a href="{{ action('Backend\RoleController@adminRole') }}"><i class="fa fa-shield"></i><span>Phân Quyền</span></a>
            </li>
            <li class="treeview{{ (request()->is('admin/theme*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-paint-brush"></i><span>Giao Diện</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/theme/menu') ? 'active' : '') }}">
                        <a href="{{ action('Backend\ThemeController@adminMenu') }}">Menu</a>
                    </li>
                </ul>
            </li>
            <li class="treeview{{ (request()->is('admin/setting*') ? ' active' : '') }}">
                <a href="#"><i class="fa fa-cog"></i><span>Cài Đặt</span></a>
                <ul class="treeview-menu">
                    <li class="{{ (request()->is('admin/setting') ? 'active' : '') }}">
                        <a href="{{ action('Backend\SettingController@adminSetting') }}">Tổng quan</a>
                    </li>
                    <li class="{{ (request()->is('admin/setting/api') ? 'active' : '') }}">
                        <a href="{{ action('Backend\SettingController@adminSettingApi') }}">Api</a>
                    </li>
                    <li class="{{ (request()->is('admin/setting/social') ? 'active' : '') }}">
                        <a href="{{ action('Backend\SettingController@adminSettingSocial') }}">Mạng Xã Hội</a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
</aside>