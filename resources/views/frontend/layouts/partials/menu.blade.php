<header class="navbar-fixed-top">

    @if(auth()->user())
        <div class="menu_top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list_menuTop">
                            <li>
                                <a href="{{ action('Frontend\UserController@editAccount') }}" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-user" aria-hidden="true"></i>  Xin chào {{ auth()->user()->name }} <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ action('Frontend\UserController@editAccount') }}"><i class="fa fa-info-circle" aria-hidden="true"></i> Thông tin tài khoản</a></li>
                                    <li><a href="{{ action('Frontend\UserController@general') }}"><i class="fa fa-tasks" aria-hidden="true"></i> Tổng quan chung</a></li>
                                    <li><a href="{{ action('Frontend\UserController@adminOrder') }}"><i class="fa fa-th-list" aria-hidden="true"></i> Quản lý đơn hàng</a></li>
                                    <li><a href="{{ action('Frontend\OrderController@importExcelPlaceOrder') }}"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Đăng đơn hàng bằng excel</a></li>
                                    <li><a href="{{ action('Frontend\OrderController@placeOrder') }}"><i class="fa fa-folder-open" aria-hidden="true"></i> Đăng đơn hàng mới</a></li>
                                </ul>
                            </li>
                            <li><a href="{{ action('Frontend\UserController@logout') }}"><i class="fa fa-sign-out" aria-hidden="true"></i> Đăng xuất</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="menu">
        <nav role="navigation" class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="{{ action('Frontend\HomeController@home') }}" class="navbar-brand"></a>
                </div>

                <div id="navbarCollapse" class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                        $menus = \App\Models\Menu::getMenuTree(\App\Models\Menu::THEME_POSITION_MENU_DB);
                        ?>

                        @foreach($menus as $menu)
                            <li class="{{ request()->fullUrl() == $menu->getMenuUrl() ? 'active' : '' }}"><a href="{{ $menu->getMenuUrl() }}">{{ $menu->getMenuTitle(false) }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>