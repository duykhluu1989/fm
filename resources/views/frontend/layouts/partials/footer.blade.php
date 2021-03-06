<footer>
    <section class="footer_top">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 display_table boxmH text-center">
                    <div class="table_content">
                        <a href="{{ action('Frontend\HomeController@home') }}"><img src="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_LOGO) ? \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_LOGO) : asset('themes/images/logo_white.png') }}" alt="Logo" class="img-reponsive"></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 display_table boxmH">
                    <h2>VỀ CHÚNG TÔI</h2>
                    <p>{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::ABOUT_US) }}</p>
                    <p><b>Hotline</b> : {{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::HOT_LINE) }}<br>
                        <b>Website</b> : <a href="{{ action('Frontend\HomeController@home') }}">{{ request()->getHost() }}</a><br>
                        <b>Email</b> : <a href="mailto:{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::CONTACT_EMAIL) }}">{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::CONTACT_EMAIL) }}</a></p>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <h2>DỊCH VỤ</h2>
                    <ul class="list_dichvu_footer">
                        <?php
                        $services = \App\Models\Article::select('id', 'name', 'slug')
                            ->where('status', \App\Models\Article::STATUS_PUBLISH_DB)
                            ->where('group', \App\Models\Article::ARTICLE_GROUP_SERVICE_DB)
                            ->orderBy('order', 'desc')
                            ->get();
                        ?>

                        @foreach($services as $service)
                            <li><a href="{{ action('Frontend\PageController@detailPage', ['id' => $service->id, 'slug' => $service->slug]) }}"><i class="fa fa-check-square-o" aria-hidden="true"></i> {{ $service->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                    <h2>FACEBOOK</h2>
                    <div class="fb-page" data-href="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_SOCIAL_DB, \App\Models\Setting::FACEBOOK_PAGE_URL) }}" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                        <blockquote cite="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_SOCIAL_DB, \App\Models\Setting::FACEBOOK_PAGE_URL) }}" class="fb-xfbml-parse-ignore">
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <p>Copyright © 2017 <a href="{{ action('Frontend\HomeController@home') }}">{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WEB_TITLE) }}</a>. All Rights Reserved.</p>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <ul class="list_socail">

                        @if(!empty(\App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_SOCIAL_DB, \App\Models\Setting::FACEBOOK_PAGE_URL)))
                            <li><a href="{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_SOCIAL_DB, \App\Models\Setting::FACEBOOK_PAGE_URL) }}"><i class="fa fa-facebook-square fa-lg" aria-hidden="true"></i></a></li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </section>
</footer>

<a href='#' class="scroll_to_top"><i class="fa fa-angle-double-up fa-2x" aria-hidden="true"></i></a>