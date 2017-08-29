@extends('frontend.layouts.main')

@section('page_heading', 'Đăng đơn hàng bằng excel')

@section('section')

    @include('frontend.layouts.partials.menu')

    @include('frontend.layouts.partials.headline')

    <main>

        @include('frontend.users.partials.navigation')

        <section class="content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2 class="title_sub">ĐĂNG ĐƠN HÀNG BẰNG EXCEL</h2>
                        <div class="row">
                            <div class="col-lg-12">
                                <form class="frm_donDH" action="{{ action('Frontend\OrderController@importExcelPlaceOrder') }}" method="POST" enctype="multipart/form-data" role="form">
                                    <div class="form-group">
                                        <h3>CHÚ Ý</h3>
                                        <p><b>File excel tải lên phải có định dạng như <a class="text-danger" href="{{ action('Frontend\OrderController@importExcelPlaceOrderTemplate') }}" target="_blank">file mẫu</a>, chỉ có 1 sheet và chỉ chứa ký tự thuần (không được có định dạng, công thức, bảng tính, biểu đồ ...)</b></p>
                                        <p><b>Thông tin người gửi có thể để trống nếu bạn đã đăng nhập tài khoản, hệ thống sẽ lấy thông tin mặc định của bạn làm thông tin người gửi</b></p>
                                        <p><b>Nếu bạn chưa đăng ký tài khoản, vui lòng điền đầy đủ thông tin người gửi, email, người nhận, thông tin ngân hàng (nếu có)</b></p>
                                        <p><b>Phần sử dụng dịch vụ ứng trước tiền thu hộ chỉ có giá trị khi bạn đăng ký sử dụng dịch vụ này lúc đăng ký tài khoản</b></p>
                                        <p><b><a class="text-danger" href="{{ action('Frontend\OrderController@importExcelPlaceOrderTemplate') }}" target="_blank">Tải file mẫu tại đây</a></b></p>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                        <label>Chọn file excel (*)</label>
                                        <input type="file" name="file" required="required" accept="{{ implode(', ', \App\Libraries\Helpers\Utility::getValidExcelExt(true)) }}" />
                                        @if($errors->has('file'))
                                            <span class="has-error">
                                                <span class="help-block">* {{ $errors->first('file') }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    <button type="submit" class="btn btnDangDH"><i class="fa fa-upload fa-lg" aria-hidden="true"></i> ĐĂNG ĐƠN HÀNG</button>
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        @include('frontend.layouts.partials.process')

    </main>

@stop