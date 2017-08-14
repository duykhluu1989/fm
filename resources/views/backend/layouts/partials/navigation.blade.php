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
            <li class="{{ (request()->is('admin/widget*') ? 'active' : '') }}">
                <a href="{{ action('Backend\WidgetController@adminWidget') }}"><i class="fa fa-th"></i><span>Tiện Ích</span></a>
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
        </ul>
    </section>
</aside>