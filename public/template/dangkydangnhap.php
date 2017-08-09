<!doctype html>
<html>

<head>
    <?php require 'head.php'; ?>
</head>

<body>
  <div id="page" class="animsition">
    <?php require 'header.php'; ?>
    <main>
      <section class="content mt106">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <h2 class="title_sub">ĐĂNG NHẬP</h2>
              <form action="user.php" method="POST" role="form">
                <div class="form-group">
                  <label for="">Email/ ID Name</label>
                  <input type="text" class="form-control" id="" placeholder="">
                </div>
                <div class="form-group">
                  <label for="">Mật khẩu</label>
                  <input type="text" class="form-control" id="" placeholder="">
                </div>
                <hr>              
                <button type="submit" class="btn btnDangnhap"><i class="fa fa-lock fa-lg" aria-hidden="true"></i> ĐĂNG NHẬP</button>
                <p><a class="btn btn-link" href="quenmatkhau.php">Quên mật khẩu?</a></p>
              </form>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <h2 class="title_sub">ĐĂNG KÝ</h2>
              <form action="user.php" method="POST" role="form">
                <div class="form-group">
                  <label for="">Họ tên (*)</label>
                  <input type="text" class="form-control" id="" placeholder="">
                </div>
                <div class="form-group">
                  <label for="">Số điện thoại  (*)</label>
                  <input type="text" class="form-control" id="" placeholder="">
                </div>
                <div class="form-group">
                  <label for="">Email  (*)</label>
                  <input type="text" class="form-control" id="" placeholder="">
                </div>
                <div class="form-group">
                  <label for="">Mật khẩu  (*)</label>
                  <input type="text" class="form-control" id="" placeholder="">
                </div>
                <div class="form-group">
                  <label for="">Địa chỉ lấy hàng (*)</label>
                  <input type="text" class="form-control" id="" placeholder="">
                </div>
                <div class="form-group">
                  <label for="">Quận/huyện</label>
                  <select name="" id="" class="form-control">
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="">Phường/ xã</label>
                  <select name="" id="" class="form-control">
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                  </select>
                </div>
                <hr>
                <p>Thông tin Ngân hàng sử dụng cho mục đích chuyển trả tiền thu hộ</p>
                <h2 class="title_sub">THÔNG TIN NGÂN HÀNG</h2>
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
                <div class="form-group">
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="" checked>
                      Tôi đồng ý với <a href="chinhsach.php"><span class="red">chính sách dịch vụ</span></a> của ParcelPost
                    </label>
                  </div>
                </div>
                <hr>
                <button type="submit" class="btn btnDangky"><i class="fa fa-user fa-lg" aria-hidden="true"></i> ĐĂNG KÝ</button>
              </form>
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
