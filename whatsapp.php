<?php
session_start();
require_once 'config/database.php';
?>
<!doctype html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Natural Disaster Controller - واتساپ</title>
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon" />
  <link rel="stylesheet" href="bootstrap/bootstrap.min.css" />
  <style>
    .whatsapp-card {
      background: white;
      border-radius: 30px;
      padding: 40px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .whatsapp-btn {
      background: #25d366;
      color: white;
      padding: 12px 30px;
      border-radius: 50px;
      font-size: 1.2rem;
      font-weight: bold;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      transition: 0.3s;
    }

    .whatsapp-btn:hover {
      background: #128c7e;
      color: white;
      transform: scale(1.02);
    }

    .support-number {
      direction: ltr;
      font-size: 1.5rem;
      font-weight: bold;
      background: #f0fdf4;
      padding: 10px;
      border-radius: 20px;
    }

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }

    body {
      display: flex;
      flex-direction: column;
    }

    .myfooter {
      margin-top: auto !important;
      margin-bottom: 0 !important;
      width: 100% !important;
    }
  </style>
</head>

<body>
  <div class="mynavbar">
    <div class="logo-area">
      <div class="logo-icon"><img src="assets/logo.png" alt="لوگو" /></div>
      <div class="logo-title"><span><span class="text-success">N</span>atural <span class="text-warning">D</span>isaster
          <span class="text-info">C</span>ontroller</span></div>
    </div>
    <div class="center-nav">
      <div class="circle-btn weather"><a href="weather.php"><img width="30px" height="30px" src="assets/weather app.svg"
            alt="آب و هوا" /></a></div>
      <div class="circle-btn maps"><a href="maps.php"><img width="30px" height="30px" src="assets/maps.svg"
            alt="نقشه" /></a></div>
      <div class="circle-btn home"><a href="index.php"><img width="30px" height="30px" src="assets/house (2).svg"
            alt="خانه" /></a></div>
      <div class="circle-btn image"><a href="disasters.php"><img width="30px" height="30px" src="assets/image.svg"
            alt="حوادث" /></a></div>
      <div class="circle-btn tips"><a href="tips.php"><img width="30px" height="30px" src="assets/tips.svg"
            alt="نکات" /></a></div>
    </div>
    <div class="right-nav">
      <div class="circle-btn messenger"><a href="messenger.php"><img width="30px" height="30px"
            src="assets/messenger.svg" alt="پیام‌رسان" /></a></div>
      <div class="circle-btn whatsapp"><a href="whatsapp.php"><img width="30px" height="30px" src="assets/whatsapp.svg"
            alt="واتساپ" /></a></div>
      <div class="circle-btn contacts"><a href="contacts.php"><img width="30px" height="30px" src="assets/contacts.svg"
            alt="مخاطبین" /></a></div>
      <div class="btn-group">
        <?php if (isset($_SESSION['user_id'])): ?>
          <div style="position:relative;">
            <button class="mybtn sign-in" onclick="toggleUserMenu()" style="display:flex; align-items:center; gap:8px;">👤
              <?php echo htmlspecialchars($_SESSION['username']); ?></button>
            <div id="userDropdown"
              style="display:none; position:absolute; top:100%; left:0; background:white; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); min-width:150px; margin-top:5px; z-index:1000;">
              <a href="dashboard.php" style="display:block; padding:10px 15px; color:#333; text-decoration:none;">📊
                داشبورد</a>
              <a href="settings.php" style="display:block; padding:10px 15px; color:#333; text-decoration:none;">⚙️
                تنظیمات</a>
              <a href="logout.php" style="display:block; padding:10px 15px; color:#c00; text-decoration:none;">🚪 خروج</a>
            </div>
          </div>
        <?php else: ?>
          <a class="mybtn sign-in" href="login.php">ورود / ثبت‌نام</a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="d">
    <img class="m-5" src="assets/Education (4).png" alt="واتساپ" />
    <div class="div-text">
      <h1 class="mytitle2">ارتباط از طریق واتساپ</h1>
      <h4 class="myp">با پیوستن به کانال و گروه پشتیبانی واتساپ، از آخرین اخبار و هشدارهای بحران مطلع شوید.</h4>
    </div>
  </div>

  <div class="container my-5">
    <div class="row">
      <div class="col-md-8 mx-auto">
        <div class="whatsapp-card">
          <img src="assets/whatsapp.svg" width="80" alt="واتساپ" />
          <h2 class="mt-3">پشتیبانی و اطلاع‌رسانی</h2>
          <p>برای ارتباط مستقیم با تیم مدیریت بحران، روی دکمه زیر کلیک کنید و پیام خود را ارسال نمایید.</p>
          <a href="https://web.bale.ai" target="_blank" class="whatsapp-btn"><img src="assets/whatsapp.svg" width="24"
              alt="واتساپ" /> ارسال پیام در بله</a>
          <hr class="my-4" />
          <h5>شماره پشتیبانی بحران:</h5>
          <div class="support-number">+98 917 357 1488</div>
          <p class="mt-3 text-muted">این شماره فقط در شرایط اضطراری فعال است. لطفاً از تماس‌های غیرضروری خودداری کنید.
          </p>
        </div>
      </div>
    </div>
  </div>

  <div class="myfooter">
    <h1>1404-1405 | <span class="text-success">N</span>atural <span class="text-warning">D</span>isaster <span
        class="text-info">C</span>ontroller ©</h1>
    <h4>تمام حقوق محفوظ هستند</h4>
    <h5>تماس با دیگر کاربران | تماس با ما | مرور تمامی مطالب</h5>
  </div>

  <script src="bootstrap/bootstrap.bundle.min.js"></script>
  <script src="js/navbar.js"></script>
  <script src="js/global.js"></script>
  <script>
    function toggleUserMenu() {
      const dropdown = document.getElementById('userDropdown');
      if (dropdown) dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.btn-group')) {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) dropdown.style.display = 'none';
      }
    });
  </script>
</body>

</html>