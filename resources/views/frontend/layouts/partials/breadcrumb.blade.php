<section class="section_breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ action('Frontend\HomeController@home') }}">Trang chủ</a>
                    </li>
                    <li class="active"> {{ $breadcrumbTitle }}</li>
                </ol>
            </div>
        </div>
    </div>
</section>