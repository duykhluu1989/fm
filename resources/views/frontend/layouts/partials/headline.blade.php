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