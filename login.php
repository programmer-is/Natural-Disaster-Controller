<?php
session_start();
require_once 'config/database.php';
if (isset($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit();
}
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
  $username = trim($_POST['regUsername'] ?? '');
  $email = trim($_POST['regEmail'] ?? '');
  $password = $_POST['regPassword'] ?? '';
  if (empty($username) || empty($email) || empty($password)) {
    $error = 'لطفاً تمام فیلدها را پر کنید';
  } elseif (strlen($password) < 6) {
    $error = 'رمز عبور باید حداقل ۶ کاراکتر باشد';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'ایمیل معتبر وارد کنید';
  } else {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
      $error = 'نام کاربری یا ایمیل قبلاً ثبت شده است';
    } else {
      $password_hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
      if ($stmt->execute([$username, $email, $password_hash])) {
        $_SESSION['notification'] = ['type' => 'success', 'title' => 'ثبت‌نام موفق', 'message' => 'حساب کاربری شما با موفقیت ایجاد شد'];
        header('Location: login.php');
        exit();
      } else {
        $error = 'خطا در ثبت‌نام';
      }
    }
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
  $username = trim($_POST['loginUsername'] ?? '');
  $password = $_POST['loginPassword'] ?? '';
  if (empty($username) || empty($password)) {
    $error = 'نام کاربری و رمز عبور الزامی است';
  } else {
    $stmt = $pdo->prepare("SELECT id, username, email, password_hash, full_name, role FROM users WHERE username = ? AND is_active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];
      $_SESSION['user_email'] = $user['email'];
      $_SESSION['user_fullname'] = $user['full_name'] ?? $user['username'];
      $_SESSION['user_role'] = $user['role'];
      $_SESSION['login_time'] = date('Y-m-d H:i:s');
      $update = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
      $update->execute([$user['id']]);
      $_SESSION['notification'] = ['type' => 'success', 'title' => 'خوش آمدید', 'message' => "{$username} عزیز، به پلتفرم کنترل حوادث طبیعی خوش آمدید"];
      header('Location: dashboard.php');
      exit();
    } else {
      $error = 'نام کاربری یا رمز عبور اشتباه است';
    }
  }
}
$notification = null;
if (isset($_SESSION['notification'])) {
  $notification = $_SESSION['notification'];
  unset($_SESSION['notification']);
}
?>
<!doctype html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Natural Disaster Controller - ورود و ثبت‌نام</title>
  <link rel="stylesheet" href="css/login & signin.css">
  <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon">
  <style>
    .error-msg {
      background: #fed7d7;
      color: #c53030;
      padding: 10px 15px;
      border-radius: 12px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 0.85rem
    }

    .success-msg {
      background: #c6f6d5;
      color: #276749;
      padding: 10px 15px;
      border-radius: 12px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 0.85rem
    }
  </style>
</head>

<body>
  <div class="container" id="container">
    <div class="form-box login">
      <form method="POST" action="">
        <h1 class="myfont">ورود</h1>
        <?php if ($error && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])): ?>
          <div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
        <div class="input-box"><input type="text" placeholder="Username" name="loginUsername" required /><i
            class="bx bxs-user"></i></div>
        <div class="input-box"><input type="password" placeholder="Password" name="loginPassword" required /><i
            class="bx bxs-lock-alt"></i></div>
        <div class="forgot-link"><a class="myfont" href="#">رمزتان را فراموش کردید ؟</a></div><button type="submit"
          name="login" class="btn myfont">ورود</button>
        <p class="myfont">یا ورود با این پلتفرم‌ها</p>
        <div class="social-icons"><a href="#"><img width="30px" height="30px" src="assets/Facebook.png"
              alt="Facebook"></a><a href="#"><img width="30px" height="30px" src="assets/Discord.png"
              alt="Discord"></a><a href="#"><img width="30px" height="30px" src="assets/LinkedIn.png"
              alt="LinkedIn"></a><a href="#"><img width="30px" height="30px" src="assets/Youtube.png" alt="Youtube"></a>
        </div>
      </form>
    </div>
    <div class="form-box register">
      <form method="POST" action="">
        <h1 class="myfont">ثبت‌نام</h1>
        <?php if ($error && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])): ?>
          <div class="error-msg"><?php echo htmlspecialchars($error); ?></div><?php endif; ?><?php if ($success): ?>
          <div class="success-msg"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
        <div class="input-box"><input type="text" placeholder="Username" name="regUsername" required /><i
            class="bx bxs-user"></i></div>
        <div class="input-box"><input type="email" placeholder="Email" name="regEmail" required /><i
            class="bx bxs-envelope"></i></div>
        <div class="input-box"><input type="password" placeholder="Password" name="regPassword" required /><i
            class="bx bxs-lock-alt"></i></div><button type="submit" name="register" class="btn myfont">ثبت‌نام</button>
        <p class="myfont">یا ثبت‌نام با این پلتفرم‌ها</p>
        <div class="social-icons"><a href="#"><img width="30px" height="30px" src="assets/Facebook.png"
              alt="Facebook"></a><a href="#"><img width="30px" height="30px" src="assets/Discord.png"
              alt="Discord"></a><a href="#"><img width="30px" height="30px" src="assets/LinkedIn.png"
              alt="LinkedIn"></a><a href="#"><img width="30px" height="30px" src="assets/Youtube.png" alt="Youtube"></a>
        </div>
      </form>
    </div>
    <div class="toggle-box">
      <div class="toggle-panel toggle-left">
        <h1 class="myfont">خوش برگشتید</h1>
        <p class="myfont">اکانت ندارید ؟</p><button class="btn register-btn myfont" id="registerBtn">ثبت نام
          کنید</button>
      </div>
      <div class="toggle-panel toggle-right">
        <h1 class="myfont">سلام، خوش‌ آمدید</h1>
        <p class="myfont">از قبل اکانت دارید ؟</p><button class="btn login-btn myfont" id="loginBtn">وارد شوید</button>
      </div>
    </div>
  </div>
  <script src="js/global.js"></script>
  <script src="js/login & signin.js"></script>
  <script src="js/notification.js"></script><?php if ($notification): ?>
    <script>document.addEventListener('DOMContentLoaded', function () { window.notify.<?php echo $notification['type']; ?>('<?php echo addslashes($notification['message']); ?>', '<?php echo addslashes($notification['title']); ?>'); });</script>
  <?php endif; ?>
</body>

</html>