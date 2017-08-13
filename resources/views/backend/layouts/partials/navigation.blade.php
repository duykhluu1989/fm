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
        </ul>
    </section>
</aside>