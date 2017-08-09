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
                <li><a href="quanlydonhang.php"><i class="fa fa-th-list" aria-hidden="true"></i> Quản lý đơn hàng</a></li>
                <li class="active"><a href="quanlydongtien.php"><i class="fa fa-usd" aria-hidden="true"></i> Quản lý dòng tiền</a></li>
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
              <h4 class="title_user line-on-right">Lượt đối soát sắp tới</h4>
              <div class="table-responsive">
                <table class="table table-bordered table-hover table_QLDH">
                  <thead>
                    <tr>
                      <th>Loại</th>
                      <th>Tiền thu hộ</th>
                      <th>Phí return</th>
                      <th>Tiền đối soát</th>
                      <th>Số ĐH</th>
                      <th>Xem đơn hàng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Tiền đã thu hộ</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                    </tr>
                    <tr>
                      <td>Đơn hàng hoàn trả</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <p>Tổng số tiền shop sẽ nhận được trong lần đối soát tới là: <span>0</span> </p>
              <p>Tổng số hàng shop sẽ được trả lại trong lần đối soát tới là: <span>0</span> đơn hàng </p>
              <p><b>Tiền đối soát =  Tiền thu hộ + Phí Return (nếu có)</b></p>
              <h4 class="title_user line-on-right">Lịch sử các lượt đối soát</h4>
              <div class="table-responsive">
                <table class="table table-bordered table-hover table_QLDH">
                  <thead>
                    <tr>
                      <th>Loại</th>
                      <th>Tiền thu hộ</th>
                      <th>Phí return</th>
                      <th>Tiền đối soát</th>
                      <th>Số ĐH</th>
                      <th>Xem đơn hàng</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Tiền đã thu hộ</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                    </tr>
                    <tr>
                      <td>Đơn hàng hoàn trả</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td>0</td>
                      <td><a class="btn btn-link" href="#">Xem chi tiết →</a></td>
                    </tr>
                  </tbody>
                </table>
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
