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
            <h2 class="title_sub">QUÊN MẬT KHẨU </h2>
            <div class="col-lg-6 col-lg-offset-3">
              <form class="mb200" action="user.php" method="POST" role="form">
                <div class="form-group">
                  <label for="">Email</label>
                  <input type="text" class="form-control" id="" placeholder="info@gmail.com">
                </div>
                <hr>              
                <a href="#" class="btn btnDangnhap"><i class="fa fa-paper-plane fa-lg" aria-hidden="true"></i> SEND</a>
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
