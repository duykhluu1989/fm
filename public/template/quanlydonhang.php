<!doctype html>
<html>

<head>
    <?php require 'head.php'; ?>
</head>

<body>
  <div id="page" class="animsition">
    <?php require 'header2.php'; ?>

    <main>
      <section class="section_breadcrumb_user">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <ul class="list_breadcrumb_user">
                <li><a href="tongquanchung.php"><i class="fa fa-tasks" aria-hidden="true"></i> Tổng quan chung</a></li>
                <li class="active"><a href="quanlydonhang.php"><i class="fa fa-th-list" aria-hidden="true"></i> Quản lý đơn hàng</a></li>
                <li><a href="quanlydongtien.php"><i class="fa fa-usd" aria-hidden="true"></i> Quản lý dòng tiền</a></li>
                <li><a href="dangdonhangexcel.php"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Đăng đơn hàng bằng excel</a></li>
                <li><a href="dangdonhangmoi.php"><i class="fa fa-folder-open" aria-hidden="true"></i> Đăng đơn hàng mới</a></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <h4 class="title_user line-on-right">Danh sách đơn hàng</h4>
              <p class="quitrinh">Tiếp nhận đơn hàng<span>(0)</span> → Shipper đang giao <span>(0)</span> → Đơn hàng thành công hoặc đơn hàng thất bại <span>(0)</span> → Đơn hàng đang giữ tại kho <span>(0)</span> → Đơn hàng hoàn trả <span>(0)</span> </p>
              <h4 class="title_user line-on-right">Tìm kiếm đơn hàng</h4>
              <form class="frm_timkiemdonhang" action="" method="POST" role="form">
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="">Trạng thái đơn hàng:</label>
                      <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Chọn trạng thái đơn hàng">
                    </div>
                    <div class="form-group">
                      <label for="">Mã đơn hàng:</label>
                      <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều mã đơn hàng cách nhau bởi dấu phẩy">
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                      <label for="">Điện thoại khách hàng:</label>
                      <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều số điện thoại khách hàng cách nhau bởi dấu phẩy">
                    </div>
                    <div class="form-group">
                      <label for="">Họ tên khách hàng:</label>
                      <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều họ tên khách hàng cách nhau bởi dấu phẩy">
                    </div>
                    <div class="form-group">
                      <label for="">Email khách hàng:</label>
                      <input type="text" name="" id="" class="form-control" value="" title="" placeholder="Nếu nhiều email khách hàng cách nhau bởi dấu phẩy">
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Thời gian tạo đơn hàng:</label>
                          <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Từ">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">&nbsp;</label>
                          <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Đến">
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">Thời gian đối soát:</label>
                          <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Từ">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                          <label for="">&nbsp;</label>
                          <input type="text" name="" id="" class="form-control datetime" value="" title="" placeholder="Đến">
                        </div>
                      </div>
                    </div>
                    <div class="form-group mb0">
                      <label style="display: block;" for="">Trả ship:</label>
                      <div class="radio">
                        <label>
                          <input type="radio" name="1" id="" value="" checked="checked">
                          Tất cả
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="1" id="" value=""">
                          Shop trả
                        </label>
                      </div>
                      <div class="radio">
                        <label>
                          <input type="radio" name="1" id="" value="">
                          Khách trả
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" value="">
                          Đơn hàng đã huỷ
                        </label>
                      </div>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" value="">
                          Đã trả hàng
                        </label>
                      </div>
                    </div>
                    <hr>
                    <a href="#" class="btn btnTimDH2"><i class="fa fa-search fa-lg" aria-hidden="true"></i> TÌM ĐƠN HÀNG</a>
                    <a href="#" class="btn btninDH"><i class="fa fa-print fa-lg" aria-hidden="true"></i> IN ĐƠN HÀNG ĐÃ CHỌN</a>
                  </div>
                </div>
              </form>
              <hr>
              <table class="table table-bordered table-hover table_QLDH">
                <thead>
                  <tr>
                    <th>Thông tin đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Hàng hoá</th>
                    <th>Ghi chú</th>
                    <th>Thao tác</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                </tbody>
              </table>
              <a href="#" class="btn btninDH"><i class="fa fa-print fa-lg" aria-hidden="true"></i> IN ĐƠN HÀNG</a>
          </div>
        </div>
      </section>

        

        

        <?php require 'phuongthuc.php'; ?>
    </main>

      <?php require 'footer.php'; ?>
  </div>
</body>

</html>
