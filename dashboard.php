<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
  header('Location: admin/dashboard.php');
  exit();
}

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit();
}

// آمارها
$disastersCount = $pdo->query("SELECT COUNT(*) as count FROM disasters")->fetch()['count'];
$provincesCount = $pdo->query("SELECT COUNT(*) as count FROM provinces")->fetch()['count'];
$activeAlerts = $pdo->query("SELECT COUNT(*) as count FROM alerts WHERE is_active = 1 AND end_time > NOW()")->fetch()['count'];
$usersCount = $pdo->query("SELECT COUNT(*) as count FROM users")->fetch()['count'];

// هشدارهای فعال - اینجا هشدارهای جدید برای کاربران نمایش داده می‌شود
$latestAlerts = $pdo->query("
    SELECT a.*, d.name_fa as disaster_name, p.name as province_name,
           CASE 
               WHEN a.severity = 3 THEN 'alert-high'
               WHEN a.severity = 2 THEN 'alert-medium'
               ELSE 'alert-low'
           END as alert_class
    FROM alerts a
    JOIN disasters d ON a.disaster_id = d.id
    JOIN provinces p ON a.province_id = p.id
    WHERE a.is_active = 1 AND a.end_time > NOW()
    ORDER BY a.created_at DESC
    LIMIT 10
")->fetchAll();

// استان‌های پرخطر
$highRiskProvinces = $pdo->query("
    SELECT p.name, r.name as risk_name, r.color
    FROM province_disaster_risk pdr
    JOIN provinces p ON pdr.province_id = p.id
    JOIN risk_levels r ON pdr.risk_value = r.level
    GROUP BY p.id
    ORDER BY MAX(pdr.risk_value) DESC
    LIMIT 8
")->fetchAll();

$currentUser = $pdo->prepare("SELECT username, email, full_name, role, last_login FROM users WHERE id = ?");
$currentUser->execute([$_SESSION['user_id']]);
$currentUser = $currentUser->fetch();
?>
<!doctype html>
<html lang="fa" dir="rtl">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Natural Disaster Controller - داشبورد</title>
  <link rel="stylesheet" href="css/navbar.css" />
  <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon" />
  <link rel="stylesheet" href="bootstrap/bootstrap.min.css" />
  <style>
    .dashboard-stats {
      margin-top: 30px;
    }

    .stat-card {
      background: white;
      border-radius: 24px;
      padding: 25px 20px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
      transition: all 0.3s ease;
      height: 100%;
    }

    .stat-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
      width: 70px;
      height: 70px;
      background-color: #2c7da0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
    }

    .stat-icon img {
      width: 35px;
      height: 35px;
    }

    .stat-number {
      font-size: 2.3rem;
      font-weight: bold;
      color: #1e3a5f;
      margin-bottom: 5px;
    }

    .stat-label {
      color: #5e6f8d;
      font-weight: 500;
    }

    .section-title {
      font-size: 1.6rem;
      font-weight: bold;
      color: #1e3a5f;
      margin: 40px 0 25px;
      padding-right: 15px;
      border-right: 5px solid #2c7da0;
    }

    .alert-item {
      background: white;
      border-radius: 16px;
      padding: 15px 20px;
      margin-bottom: 15px;
      border-right: 4px solid;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      cursor: pointer;
      transition: all 0.2s;
    }

    .alert-item:hover {
      transform: translateX(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .alert-high {
      border-right-color: #e53e3e;
      background: linear-gradient(90deg, #fff5f5 0%, white 100%);
    }

    .alert-medium {
      border-right-color: #ed8936;
      background: linear-gradient(90deg, #fffaf0 0%, white 100%);
    }

    .alert-low {
      border-right-color: #38a169;
    }

    .alert-title {
      font-weight: bold;
      font-size: 1.05rem;
      margin-bottom: 5px;
    }

    .alert-location {
      color: #718096;
      font-size: 0.85rem;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .alert-time {
      color: #a0aec0;
      font-size: 0.75rem;
      margin-top: 5px;
    }

    .province-risk-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 0;
      border-bottom: 1px solid #e2e8f0;
    }

    .risk-badge {
      padding: 4px 12px;
      border-radius: 30px;
      font-size: 0.7rem;
      font-weight: bold;
    }

    .quick-action {
      background: linear-gradient(135deg, #2c7da0, #1e5a7a);
      border-radius: 20px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s;
      cursor: pointer;
      height: 100%;
    }

    .quick-action:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(44, 125, 160, 0.3);
    }

    .quick-action-icon {
      width: 55px;
      height: 55px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
    }

    .quick-action-title {
      color: white;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .quick-action-desc {
      color: rgba(255, 255, 255, 0.8);
      font-size: 0.8rem;
    }

    .personal-greeting {
      background: white;
      border-radius: 30px;
      padding: 20px 30px;
      margin-bottom: 30px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
      border-right: 5px solid #2c7da0;
    }

    @media (max-width: 768px) {
      .stat-number {
        font-size: 1.6rem;
      }

      .section-title {
        font-size: 1.3rem;
      }
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
          <?php $dashboardLink = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'admin/dashboard.php' : 'dashboard.php'; ?>
          <div style="position:relative;"><button class="mybtn sign-in" onclick="toggleUserMenu()"
              style="display:flex; align-items:center; gap:8px;">👤
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

  <div class="container mt-4">
    <div class="personal-greeting">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h3 class="mb-1">خوش آمدید،
            <?php echo htmlspecialchars($_SESSION['user_fullname'] ?? $_SESSION['username']); ?>!</h3>
          <p class="text-muted mb-0"><?php echo htmlspecialchars($_SESSION['user_email']); ?> | نقش:
            <?php echo $_SESSION['user_role'] === 'admin' ? 'مدیر' : 'کاربر'; ?></p>
        </div>
        <div><span class="badge bg-success p-2">آخرین ورود:
            <?php echo $currentUser['last_login'] ? date('Y/m/d H:i', strtotime($currentUser['last_login'])) : 'اولین ورود'; ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="container dashboard-stats">
    <div class="row">
      <div class="col-md-3 col-6 mb-4">
        <div class="stat-card">
          <div class="stat-icon"><img src="assets/image.svg" alt="بلایا"></div>
          <div class="stat-number"><?php echo $disastersCount; ?></div>
          <div class="stat-label">نوع بلای طبیعی</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-4">
        <div class="stat-card">
          <div class="stat-icon"><img src="assets/maps.svg" alt="استان"></div>
          <div class="stat-number"><?php echo $provincesCount; ?></div>
          <div class="stat-label">استان تحت پوشش</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-4">
        <div class="stat-card">
          <div class="stat-icon"><img src="assets/weather app.svg" alt="هشدار"></div>
          <div class="stat-number"><?php echo $activeAlerts; ?></div>
          <div class="stat-label">هشدار فعال</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-4">
        <div class="stat-card">
          <div class="stat-icon"><img src="assets/contacts.svg" alt="کاربران"></div>
          <div class="stat-number"><?php echo number_format($usersCount); ?>+</div>
          <div class="stat-label">کاربران فعال</div>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <h2 class="section-title">⚠️ آخرین هشدارها و اخبار</h2>
        <?php if (count($latestAlerts) > 0): ?>
          <?php foreach ($latestAlerts as $alert): ?>
            <div class="alert-item <?php echo $alert['alert_class']; ?>" onclick="location.href='disasters.php'">
              <div class="alert-title">⚠️ <?php echo htmlspecialchars($alert['disaster_name']); ?></div>
              <div class="alert-location">📍 استان <?php echo htmlspecialchars($alert['province_name']); ?></div>
              <div class="alert-time"><?php echo htmlspecialchars($alert['message']); ?></div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="alert-item alert-low">
            <div class="alert-title">✅ وضعیت عادی</div>
            <div class="alert-location">هیچ هشدار فعالی در حال حاضر وجود ندارد</div>
          </div>
        <?php endif; ?>
      </div>
      <div class="col-lg-4">
        <h2 class="section-title">📍 استان‌های پرخطر</h2>
        <div class="chart-container">
          <?php if (count($highRiskProvinces) > 0): ?>
            <?php foreach ($highRiskProvinces as $province): ?>
              <div class="province-risk-item"><span
                  class="province-name"><?php echo htmlspecialchars($province['name']); ?></span><span class="risk-badge"
                  style="background:<?php echo $province['color']; ?>20; color:<?php echo $province['color']; ?>;"><?php echo htmlspecialchars($province['risk_name']); ?></span>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="province-risk-item"><span class="province-name">داده‌ای موجود نیست</span><span
                class="risk-badge risk-low">در حال بروزرسانی</span></div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <h2 class="section-title">⚡ دسترسی سریع</h2>
    <div class="row">
      <div class="col-md-3 col-6 mb-4">
        <div class="quick-action" onclick="location.href='maps.html'">
          <div class="quick-action-icon"><img width="25px" height="25px" src="assets/maps.svg" alt="نقشه"></div>
          <div class="quick-action-title">نقشه مخاطرات</div>
          <div class="quick-action-desc">مشاهده مناطق پرخطر</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-4">
        <div class="quick-action" onclick="location.href='disasters.php'">
          <div class="quick-action-icon"><img width="25px" height="25px" src="assets/image.svg" alt="حوادث"></div>
          <div class="quick-action-title">راهنما و آموزش</div>
          <div class="quick-action-desc">آشنایی با بلایا</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-4">
        <div class="quick-action" onclick="location.href='#'">
          <div class="quick-action-icon"><img width="25px" height="25px" src="assets/tips.svg" alt="تماس"></div>
          <div class="quick-action-title">تماس اضطراری</div>
          <div class="quick-action-desc">۱۲۳ - اورژانس</div>
        </div>
      </div>
      <div class="col-md-3 col-6 mb-4">
        <div class="quick-action" onclick="location.href='#'">
          <div class="quick-action-icon"><img width="25px" height="25px" src="assets/contacts.svg" alt="گزارش"></div>
          <div class="quick-action-title">گزارش بلایا</div>
          <div class="quick-action-desc">ثبت گزارش جدید</div>
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
    function toggleUserMenu() { const d = document.getElementById('userDropdown'); if (d) d.style.display = d.style.display === 'none' ? 'block' : 'none'; }
    document.addEventListener('click', function (e) { if (!e.target.closest('.btn-group')) { const d = document.getElementById('userDropdown'); if (d) d.style.display = 'none'; } });
  </script>
</body>

</html>