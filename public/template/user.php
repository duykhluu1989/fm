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
                <li><a href="quanlydongtien.php"><i class="fa fa-usd" aria-hidden="true"></i> Quản lý dòng tiền</a></li>
                <li><a href="dangdonhangexcel.php"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Đăng đơn hàng bằng excel</a></li>
                <li><a href="dangdonhang.php"><i class="fa fa-folder-open" aria-hidden="true"></i> Đăng đơn hàng mới</a></li>
              </ul>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <h2 class="title_sub">CẬP NHẬT THÔNG TIN CHO SHOP</h2>
              <h4 class="title_user line-on-right">Thông tin cơ bản</h4>
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <form action="" method="POST" role="form">
                    <div class="form-group">
                      <label for="">Tên shop:</label>
                      <input type="text" class="form-control" id="" placeholder="Ninishop">
                    </div>
                    <div class="form-group">
                      <label for="">Điện thoại:</label>
                      <input type="text" class="form-control" id="" placeholder="0908911493">
                    </div>
                    <div class="form-group">
                      <label for="">Email:</label>
                      <input type="text" class="form-control" id="" placeholder="trieu@info.vn">
                    </div>
                    <div class="form-group">
                      <label for="">Mật khẩu mới:</label>
                      <input type="password" class="form-control" id="" placeholder="Ninishop">
                    </div>
                    <div class="form-group">
                      <label for="">Xác nhận mật khẩu mới:</label>
                      <input type="password" class="form-control" id="" placeholder="Ninishop">
                    </div>
                    <div class="form-group">
                      <label for="">Chủ tài khoản ngân hàng:</label>
                      <input type="text" class="form-control" id="" placeholder="NGUYỄN HOÀNG TRIỆU">
                    </div>
                    <div class="form-group">
                      <label for="">Số tài khoản ngân hàng:</label>
                      <input type="text" class="form-control" id="" placeholder="123456789">
                    </div>
                    <div class="form-group">
                      <label for="">Tên ngân hàng:</label>
                      <select name="" id="" class="form-control">
                        <option value="">DONGA - NGAN HANG TMCP DONG A</option>
                        <option value="">DONGA - NGAN HANG TMCP DONG A</option>
                        <option value="">DONGA - NGAN HANG TMCP DONG A</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Chi nhánh ngân hàng:</label>
                      <select name="" id="" class="form-control">
                        <option value="">DONGA BANK HO CHI MINH (HCM)</option>
                        <option value="">DONGA BANK HO CHI MINH (HCM)</option>
                        <option value="">DONGA BANK HO CHI MINH (HCM)</option>
                      </select>
                    </div>
                    <button type="submit" class="btn btnLuuTT"><i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i> LƯU THÔNG TIN</button>
                  </form>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
              </div>

              <h4 class="title_user line-on-right">Địa chỉ lấy hàng - kho hàng 1 - mã kho 137707</h4>
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <form action="" method="POST" role="form">
                    <div class="form-group">
                      <label for="">Tên người liên hệ:</label>
                      <input type="text" class="form-control" id="" placeholder="Ninishop">
                    </div>
                    <div class="form-group">
                      <label for="">Số điện thoại:</label>
                      <input type="text" class="form-control" id="" placeholder="0908911493">
                    </div>
                    <div class="form-group">
                      <label for="">Thành phố:</label>
                      <select name="" id="" class="form-control">
                        <option value="">TP. Hồ Chí Minh</option>
                        <option value="">...</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Địa chỉ lấy hàng:</label>
                      <select name="" id="" class="form-control">
                        <option value="">51D phú mỹ </option>
                        <option value="">51D phú mỹ </option>
                        <option value="">51D phú mỹ </option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Quận huyện:</label>
                      <select name="" id="" class="form-control">
                        <option value="">Quận 1</option>
                        <option value="">Quận 2</option>
                        <option value="">Quận ...</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Phường/xã:</label>
                      <select name="" id="" class="form-control">
                        <option value="">Phường 1</option>
                        <option value="">Phường 2</option>
                        <option value="">Phường ...</option>
                      </select>
                    </div>
                  </form>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
              </div>

              <h4 class="title_user line-on-right">Địa chỉ lấy hàng - kho hàng 2 - mã kho 137708</h4>
              <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                  <form action="" method="POST" role="form">
                    <div class="form-group">
                      <label for="">Tên người liên hệ:</label>
                      <input type="text" class="form-control" id="" placeholder="Betty">
                    </div>
                    <div class="form-group">
                      <label for="">Số điện thoại:</label>
                      <input type="text" class="form-control" id="" placeholder="0908911493">
                    </div>
                    <div class="form-group">
                      <label for="">Thành phố:</label>
                      <select name="" id="" class="form-control">
                        <option value="">TP. Hồ Chí Minh</option>
                        <option value="">...</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Địa chỉ lấy hàng:</label>
                      <select name="" id="" class="form-control">
                        <option value="">248A Nơ trang long</option>
                        <option value="">248A Nơ trang long</option>
                        <option value="">248A Nơ trang long</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Quận huyện:</label>
                      <select name="" id="" class="form-control">
                        <option value="">Quận 1</option>
                        <option value="">Quận 2</option>
                        <option value="">Quận ...</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="">Phường/xã:</label>
                      <select name="" id="" class="form-control">
                        <option value="">Phường 1</option>
                        <option value="">Phường 2</option>
                        <option value="">Phường ...</option>
                      </select>
                    </div>
                    <a href="#" class="btn btnThemDD"><i class="fa fa-plus" aria-hidden="true"></i> THÊM ĐỊA ĐIỂM LẤY HÀNG - KHO HÀNG</a>
                    <a href="#" class="btn btnLuuTT"><i class="fa fa-floppy-o" aria-hidden="true"></i> LƯU ĐỊA CHỈ</a>
                  </form>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"></div>
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
