<?php
session_start();
require_once 'config/database.php';

// دریافت آمار برای نمایش در صفحه درباره ما (اختیاری)
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$totalUsers = $stmt->fetch()['count'];

$stmt = $pdo->query("SELECT COUNT(*) as count FROM alerts WHERE is_active = 1");
$activeAlerts = $stmt->fetch()['count'];
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natural Disaster Controller - درباره ما</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <style>
        .about-header {
            background: linear-gradient(135deg, #2c7da0, #1e5a7a);
            border-radius: 30px;
            padding: 40px;
            color: white;
            text-align: center;
            margin-bottom: 40px;
        }

        .team-card {
            background: white;
            border-radius: 30px;
            padding: 30px;
            text-align: center;
            transition: all 0.3s ease;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .avatar {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #e2e8f0, #cbd5e0);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 3rem;
            color: #2c7da0;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .team-name {
            font-size: 1.6rem;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 10px;
        }

        .team-role {
            color: #2c7da0;
            font-weight: 500;
            margin-bottom: 15px;
        }

        .team-bio {
            color: #4a5568;
            line-height: 1.7;
        }

        .about-text {
            background: white;
            border-radius: 30px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .icon-feature {
            font-size: 2rem;
            color: #2c7da0;
            margin-bottom: 15px;
        }

        .stats-badge {
            display: inline-block;
            background: #2c7da0;
            color: white;
            padding: 5px 15px;
            border-radius: 30px;
            margin: 0 5px;
        }

        @media (max-width: 768px) {
            .team-name {
                font-size: 1.3rem;
            }

            .avatar {
                width: 120px;
                height: 120px;
                font-size: 2.5rem;
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
            <div class="logo-title"><span><span class="text-success">N</span>atural <span
                        class="text-warning">D</span>isaster <span class="text-info">C</span>ontroller</span></div>
        </div>
        <div class="center-nav">
            <div class="circle-btn weather"><a href="weather.php"><img width="30px" height="30px"
                        src="assets/weather app.svg" alt="آب و هوا"></a></div>
            <div class="circle-btn maps"><a href="maps.php"><img width="30px" height="30px" src="assets/maps.svg"
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
            <div class="circle-btn whatsapp"><a href="whatsapp.php"><img width="30px" height="30px"
                        src="assets/whatsapp.svg" alt="واتساپ"></a></div>
            <div class="circle-btn contacts"><a href="contacts.php"><img width="30px" height="30px"
                        src="assets/contacts.svg" alt="مخاطبین"></a></div>
            <div class="btn-group">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div style="position:relative;">
                        <button class="mybtn sign-in" onclick="toggleUserMenu()"
                            style="display:flex; align-items:center; gap:8px;">👤
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </button>
                        <div id="userDropdown"
                            style="display:none; position:absolute; top:100%; left:0; background:white; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); min-width:150px; margin-top:5px; z-index:1000;">
                            <a href="dashboard.php"
                                style="display:block; padding:10px 15px; color:#333; text-decoration:none;">📊 داشبورد</a>
                            <a href="settings.php"
                                style="display:block; padding:10px 15px; color:#333; text-decoration:none;">⚙️ تنظیمات</a>
                            <a href="logout.php"
                                style="display:block; padding:10px 15px; color:#c00; text-decoration:none;">🚪 خروج</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a class="mybtn sign-in" href="login.php">ورود / ثبت‌نام</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container my-4">
        <div class="about-header">
            <h1 class="display-5 fw-bold">درباره ما</h1>
            <p class="lead mt-3">ما با هدف کاهش آسیب‌پذیری جوامع در برابر بلایای طبیعی، دانش و فناوری را در خدمت ایمنی
                ایران عزیز قرار داده‌ایم.</p>
            <div class="mt-3">
                <span class="stats-badge">
                    <?php echo number_format($totalUsers); ?>+ کاربر فعال
                </span>
                <span class="stats-badge">
                    <?php echo $activeAlerts; ?> هشدار فعال
                </span>
                <span class="stats-badge">۳۱ استان تحت پوشش</span>
            </div>
        </div>

        <div class="about-text">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mytitle" style="border-right-color: #2c7da0;"> Natural Disaster Controller</h2>
                    <p style="font-size: 1.05rem; line-height: 1.8;">پلتفرم <strong>کنترل کننده حوادث طبیعی</strong> با
                        تکیه بر داده‌های ماهواره‌ای، مدل‌های پیش‌بینی اقلیمی و تحلیل خطر، تلاش می‌کند تا هشدارهای دقیق و
                        به‌هنگامی را در اختیار مردم و مدیران بحران قرار دهد. ما بر این باوریم که با آگاهی و آمادگی،
                        می‌توان اثرات مخرب زلزله، سیل، خشکسالی و سایر بلایا را به حداقل رساند.</p>
                    <p style="font-size: 1.05rem; line-height: 1.8;">این وب‌سایت حاصل تلاش تیمی جوان و متخصص در حوزه‌های
                        مدیریت بحران، سیستم‌های اطلاعات جغرافیایی (GIS) و برنامه‌نویسی وب است. امیدواریم گامی هرچند کوچک
                        در جهت افزایش تاب‌آوری کشورمان برداشته باشیم.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="icon-feature">🌍</div>
                    <div class="icon-feature">🛰️</div>
                    <div class="icon-feature">⚠️</div>
                </div>
            </div>
        </div>

        <h2 class="mytitle text-center mb-4">سازندگان پلتفرم</h2>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 mb-4">
                <div class="team-card">
                    <div class="avatar">👨‍💻</div>
                    <div class="team-name">فرهام ابراهیمی</div>
                    <div class="team-role">توسعه‌دهنده ارشد و طراح تجربه کاربری</div>
                    <div class="team-bio">
                        کارشناس مدیریت بحران و برنامه‌نویس فرانت‌اند. فرهام ایده‌پرداز اصلی این پروژه است و با تمرکز بر
                        سادگی و کارایی، ساختار بصری و تعاملی سایت را طراحی کرده است.
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-5 mb-4">
                <div class="team-card">
                    <div class="avatar">👨‍🔧</div>
                    <div class="team-name">سعید سیفی</div>
                    <div class="team-role">توسعه‌دهنده بک‌اند و تحلیل‌گر داده</div>
                    <div class="team-bio">
                        متخصص سیستم‌های اطلاعات جغرافیایی و تحلیل داده‌های طبیعی. سعید مسئول پیاده‌سازی نقشه‌های تعاملی،
                        پایگاه داده خطرپذیری و ماژول‌های پیش‌بینی وضعیت جوی است.
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4 mb-5">
            <a href="index.php" class="mybtn" style="background: #2c7da0; padding: 12px 35px;">بازگشت به صفحه اصلی</a>
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