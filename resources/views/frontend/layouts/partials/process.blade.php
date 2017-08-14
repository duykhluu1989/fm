<section class="phuongthuc">
    <div class="container">
        <h2 class="title">Phương thức hoạt động</h2>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <ul class="list_phuongthuc">
                    <?php
                    $wg = \App\Models\Widget::select('code', 'detail')->where('status', \App\Libraries\Helpers\Utility::ACTIVE_DB)->where('code', \App\Models\Widget::BANNER_PROCESS)->first();

                    $sliderItems = array();

                    if(!empty($wg))
                    {
                        if(!empty($wg->detail))
                            $sliderItems = json_decode($wg->detail, true);
                    }
                    ?>

                    @foreach($sliderItems as $sliderItem)
                        <li>
                            <i class="fa fa-{{ isset($sliderItem['icon']) ? $sliderItem['icon'] : '' }} fa-3x" aria-hidden="true"></i>
                            <h4>{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'title') }}</h4>
                            <p>{{ \App\Libraries\Helpers\Utility::getValueByLocale($sliderItem, 'description') }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>