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
                <li class="active"><a href="tongquanchung.php"><i class="fa fa-tasks" aria-hidden="true"></i> Tổng quan chung</a></li>
                <li><a href="quanlydonhang.php"><i class="fa fa-th-list" aria-hidden="true"></i> Quản lý đơn hàng</a></li>
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
              <h4 class="title_user line-on-right">Lọc theo thời gian</h4>
              <form class="frm_locthoigian" action="" method="POST" role="form">
                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <select name="" id="" class="i_calendar form-control" required="required">
                        <option value="">Từ 2017-08-01 đến 2017-08-02</option>
                        <option value="">Từ 2017-08-01 đến 2017-08-02</option>
                        <option value="">Từ 2017-08-01 đến 2017-08-02</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-6"></div>
                </div>
                <button type="submit" class="btn btnXemBC"><i class="fa fa-newspaper-o fa-lg" aria-hidden="true"></i> XEM BÁO CÁO</button>
              </form>

              <h4 class="title_user line-on-right">Báo cáo hiệu quả</h4>
              <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table class="table table-bordered table-hover table_tongquanchung">
                      <thead>
                        <tr>
                          <th>Trạng thái</th>
                          <th>Số đơn hàng</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Số đơn hàng</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Hoàn thành</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Không giao được</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Đơn hàng hủy</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Đang giao hàng</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Tỉ lệ hoàn thành</td>
                          <td>0%</td>
                        </tr>
                        <tr>
                          <td>Tỉ lệ không giao được</td>
                          <td>0%</td>
                        </tr>
                        <tr>
                          <td>Tỉ lệ hủy</td>
                          <td>0%</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table class="table table-bordered table-hover table_tongquanchung">
                      <thead>
                        <tr>
                          <th>Trạng thái</th>
                          <th>Số đơn hàng</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Số đơn hàng</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Hoàn thành</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Không giao được</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Đơn hàng hủy</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Đang giao hàng</td>
                          <td>0</td>
                        </tr>
                        <tr>
                          <td>Tỉ lệ hoàn thành</td>
                          <td>0%</td>
                        </tr>
                        <tr>
                          <td>Tỉ lệ không giao được</td>
                          <td>0%</td>
                        </tr>
                        <tr>
                          <td>Tỉ lệ hủy</td>
                          <td>0%</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
              </div>
          </div>
        </div>
      </section>

        

        

        <?php require 'phuongthuc.php'; ?>
    </main>

      <?php require 'footer.php'; ?>
  </div>
</body>

</html>
