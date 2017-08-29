<section class="banner_sub">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
            </div>
        </div>
    </div>
</section>

<section class="search_DH">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <div class="hotline">
                    <p class="numberPhone"><i class="fa fa-phone" aria-hidden="true"></i> HOTLINE: <span>{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::HOT_LINE) }}</span></p>
                    <p class="email"><b><i class="fa fa-envelope" aria-hidden="true"></i> Email:</b> <a href="mailto:{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::CONTACT_EMAIL) }}">{{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::CONTACT_EMAIL) }}</a></p>
                    <p class="timeOP"><b><i class="fa fa-clock-o" aria-hidden="true"></i> Thời gian làm việc:</b> {{ \App\Models\Setting::getSettings(\App\Models\Setting::CATEGORY_GENERAL_DB, \App\Models\Setting::WORKING_TIME) }}</p>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <form class="frm_searchDH" action="{{ action('Frontend\UserController@searchOrder') }}" role="form">
                    <div class="form-group">
                        <input type="text" class="form-control" name="k" placeholder="Nhập mã đơn hàng" />
                        <button type="submit" class="btn btnTimDH"><i class="fa fa-search fa-lg" aria-hidden="true"></i> TRA CỨU ĐƠN HÀNG</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>