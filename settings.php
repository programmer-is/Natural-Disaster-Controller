<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

$userId = $_SESSION['user_id'];
$error = '';

$stmt = $pdo->prepare("SELECT id, username, email, full_name FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
  session_destroy();
  header('Location: login.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
  $newUsername = trim($_POST['username'] ?? '');
  $newEmail = trim($_POST['email'] ?? '');
  $newFullname = trim($_POST['fullname'] ?? '');
  $newPassword = $_POST['password'] ?? '';

  if (empty($newUsername) || empty($newEmail)) {
    $error = 'نام کاربری و ایمیل الزامی هستند';
  } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    $error = 'ایمیل معتبر وارد کنید';
  } else {
    $check = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $check->execute([$newUsername, $newEmail, $userId]);
    if ($check->fetch()) {
      $error = 'نام کاربری یا ایمیل قبلاً توسط کاربر دیگری ثبت شده است';
    } else {
      if (!empty($newPassword)) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET username = ?, email = ?, full_name = ?, password_hash = ? WHERE id = ?");
        $update->execute([$newUsername, $newEmail, $newFullname, $hashed, $userId]);
      } else {
        $update = $pdo->prepare("UPDATE users SET username = ?, email = ?, full_name = ? WHERE id = ?");
        $update->execute([$newUsername, $newEmail, $newFullname, $userId]);
      }
      $_SESSION['username'] = $newUsername;
      $_SESSION['user_email'] = $newEmail;
      $_SESSION['user_fullname'] = $newFullname ?: $newUsername;
      $_SESSION['notification'] = ['type' => 'success', 'title' => 'بروزرسانی', 'message' => 'اطلاعات شما با موفقیت به‌روزرسانی شد'];
      header('Location: settings.php');
      exit();
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_notifications'])) {
  setcookie('alertEnabled', $_POST['alertToggle'] ?? 'false', time() + 86400 * 30, '/');
  setcookie('newsletterEnabled', $_POST['newsletterToggle'] ?? 'false', time() + 86400 * 30, '/');
  $_SESSION['notification'] = ['type' => 'success', 'title' => 'تنظیمات', 'message' => 'تنظیمات اعلان با موفقیت ذخیره شد'];
  header('Location: settings.php');
  exit();
}

$alertEnabled = $_COOKIE['alertEnabled'] ?? 'false';
$newsletterEnabled = $_COOKIE['newsletterEnabled'] ?? 'false';

$notification = null;
if (isset($_SESSION['notification'])) {
  $notification = $_SESSION['notification'];
  unset($_SESSION['notification']);
}
?>
<!doctype html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Natural Disaster Controller - تنظیمات</title>
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon" />
  <link rel="stylesheet" href="bootstrap/bootstrap.min.css" />
  <style>
    .settings-card {
      background: white;
      border-radius: 25px;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .theme-toggle {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 15px 0;
      border-bottom: 1px solid #e2e8f0;
    }

    .form-control-custom {
      border-radius: 30px;
      border: 2px solid #e2e8f0;
      padding: 12px 20px;
      width: 100%;
      transition: all 0.3s;
    }

    .form-control-custom:focus {
      border-color: #2c7da0;
      outline: none;
    }

    .mybtn {
      background: #2c7da0;
      color: white;
      border: none;
      padding: 10px 25px;
      border-radius: 30px;
      cursor: pointer;
      transition: 0.3s;
    }

    .mybtn:hover {
      background: #1e5a7a;
    }

    .alert-error {
      background: #fed7d7;
      color: #c53030;
      padding: 12px;
      border-radius: 12px;
      margin-bottom: 20px;
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
      <div class="logo-icon"><img src="assets/logo.png" alt="لوگو"></div>
      <div class="logo-title"><span><span class="text-success">N</span>atural <span class="text-warning">D</span>isaster
          <span class="text-info">C</span>ontroller</span></div>
    </div>
    <div class="center-nav">
      <div class="circle-btn weather"><a href="weather.php"><img width="30px" height="30px" src="assets/weather app.svg"
            alt="آب و هوا"></a></div>
      <div class="circle-btn maps"><a href="maps.html"><img width="30px" height="30px" src="assets/maps.svg"
            alt="نقشه"></a></div>
      <div class="circle-btn home"><a href="index.php"><img width="30px" height="30px" src="assets/house (2).svg"
            alt="خانه"></a></div>
      <div class="circle-btn image"><a href="disasters.php"><img width="30px" height="30px" src="assets/image.svg"
            alt="حوادث"></a></div>
      <div class="circle-btn tips"><a href="tips.php"><img width="30px" height="30px" src="assets/tips.svg"
            alt="نکات"></a></div>
    </div>
    <div class="right-nav">
      <div class="circle-btn messenger"><a href="messenger.php"><img width="30px" height="30px"
            src="assets/messenger.svg" alt="پیام‌رسان"></a></div>
      <div class="circle-btn whatsapp"><a href="whatsapp.php"><img width="30px" height="30px" src="assets/whatsapp.svg"
            alt="واتساپ"></a></div>
      <div class="circle-btn contacts"><a href="contacts.php"><img width="30px" height="30px" src="assets/contacts.svg"
            alt="مخاطبین"></a></div>
      <div class="btn-group">
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php
          $dashboardLink = 'dashboard.php';
          if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            $dashboardLink = 'admin/dashboard.php';
          }
          ?>
          <div style="position:relative;">
            <button class="mybtn sign-in" onclick="toggleUserMenu()" style="display:flex; align-items:center; gap:8px;">👤
              <?php echo htmlspecialchars($_SESSION['username']); ?></button>
            <div id="userDropdown"
              style="display:none; position:absolute; top:100%; left:0; background:white; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); min-width:150px; margin-top:5px; z-index:1000;">
              <a href="<?php echo $dashboardLink; ?>"
                style="display:block; padding:10px 15px; color:#333; text-decoration:none;">📊 داشبورد</a>
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
    <img class="m-5" src="assets/Education (4).png" alt="تنظیمات">
    <div class="div-text">
      <h1 class="mytitle2">تنظیمات حساب کاربری</h1>
      <h4 class="myp">اطلاعات شخصی خود را مدیریت کنید و تنظیمات اعلان را تغییر دهید.</h4>
    </div>
  </div>

  <div class="container my-5">
    <?php if ($error): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="settings-card">
      <h3 class="mb-4">اطلاعات پروفایل</h3>
      <form method="POST" action="">
        <div class="mb-3"><label class="form-label">نام کاربری</label><input type="text" name="username"
            class="form-control-custom" value="<?php echo htmlspecialchars($user['username']); ?>" required /></div>
        <div class="mb-3"><label class="form-label">ایمیل</label><input type="email" name="email"
            class="form-control-custom" value="<?php echo htmlspecialchars($user['email']); ?>" required /></div>
        <div class="mb-3"><label class="form-label">نام کامل (اختیاری)</label><input type="text" name="fullname"
            class="form-control-custom" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" /></div>
        <div class="mb-3"><label class="form-label">رمز عبور جدید (در صورت تمایل به تغییر)</label><input type="password"
            name="password" class="form-control-custom" placeholder="رمز عبور جدید (حداقل ۶ کاراکتر)" /></div>
        <button type="submit" name="update_profile" class="mybtn">ذخیره تغییرات</button>
      </form>
    </div>

    <div class="settings-card">
      <h3 class="mb-4">تنظیمات اعلان</h3>
      <form method="POST" action="">
        <div class="theme-toggle"><span>دریافت هشدارهای فوری</span>
          <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="alertToggle"
              id="alertToggle" style="width:50px; height:24px" <?php echo $alertEnabled === 'true' ? 'checked' : ''; ?> />
          </div>
        </div>
        <div class="theme-toggle"><span>دریافت خبرنامه ایمیلی</span>
          <div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="newsletterToggle"
              id="newsletterToggle" style="width:50px; height:24px" <?php echo $newsletterEnabled === 'true' ? 'checked' : ''; ?> /></div>
        </div>
        <button type="submit" name="save_notifications" class="mybtn mt-3">ذخیره تنظیمات</button>
      </form>
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
  <script src="js/notification.js"></script>
  <script>
    document.getElementById('alertToggle').checked = document.cookie.includes('alertEnabled=true');
    document.getElementById('newsletterToggle').checked = document.cookie.includes('newsletterEnabled=true');

    function toggleUserMenu() { const d = document.getElementById('userDropdown'); if (d) d.style.display = d.style.display === 'none' ? 'block' : 'none'; }
    document.addEventListener('click', function (e) { if (!e.target.closest('.btn-group')) { const d = document.getElementById('userDropdown'); if (d) d.style.display = 'none'; } });
  </script>
  <?php if ($notification): ?>
    <script>document.addEventListener('DOMContentLoaded', function () { window.notify.<?php echo $notification['type']; ?>('<?php echo addslashes($notification['message']); ?>', '<?php echo addslashes($notification['title']); ?>'); });</script>
  <?php endif; ?>
</body>

</html>