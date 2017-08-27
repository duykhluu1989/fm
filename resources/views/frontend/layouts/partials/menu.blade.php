<header class="navbar-fixed-top">

    @if(auth()->user())
        <div class="menu_top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="list_menuTop">
                            <li><a href="{{ action('Frontend\UserController@editAccount') }}"><i class="fa fa-user" aria-hidden="true"></i> Xin chào {{ auth()->user()->name }}</a></li>
                            <li><a href="{{ action('Frontend\OrderController@placeOrder') }}"><i class="fa fa-id-card-o" aria-hidden="true"></i> Tạo đơn hàng</a></li>
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