<?php
session_start();
require_once 'config/database.php';

$formMessage = '';
$formError = '';

// پردازش فرم تماس
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['contactName'] ?? '');
    $email = trim($_POST['contactEmail'] ?? '');
    $phone = trim($_POST['contactPhone'] ?? '');
    $message = trim($_POST['contactMessage'] ?? '');

    if (empty($name) || empty($email) || empty($message)) {
        $formError = 'لطفاً نام، ایمیل و پیام را وارد کنید';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $formError = 'ایمیل معتبر وارد کنید';
    } else {
        // ذخیره در دیتابیس (ایجاد جدول در صورت نیاز)
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, message, user_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
            $userId = $_SESSION['user_id'] ?? null;
            $stmt->execute([$name, $email, $phone, $message, $userId]);
            $formMessage = 'پیام شما با موفقیت ارسال شد. به زودی با شما تماس می‌گیریم.';
        } catch (PDOException $e) {
            $formError = 'خطا در ارسال پیام، مجدداً تلاش کنید';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Natural Disaster Controller - پیام‌رسان بحران</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon">
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <style>
        .emergency-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            height: 100%;
            border-bottom: 4px solid;
        }

        .emergency-card:hover {
            transform: translateY(-5px);
        }

        .emergency-icon {
            width: 70px;
            height: 70px;
            background: #fee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .emergency-number {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 10px 0;
            direction: ltr;
        }

        .contact-form {
            background: white;
            border-radius: 25px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .form-control-custom {
            border-radius: 30px;
            border: 2px solid #e2e8f0;
            padding: 12px 20px;
            transition: all 0.3s;
            width: 100%;
        }

        .form-control-custom:focus {
            border-color: #2c7da0;
            outline: none;
        }

        .alert-success {
            background: #c6f6d5;
            color: #276749;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .emergency-number {
                font-size: 1.2rem;
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

    <div class="d">
        <img class="m-5" src="assets/Education (4).png" alt="پیام‌رسان">
        <div class="div-text">
            <h1 class="mytitle2">پیام‌رسان بحران</h1>
            <h4 class="myp">کانال ارتباطی سریع با تیم مدیریت بحران و سایر کاربران. در زمان وقوع حادثه، می‌توانید درخواست
                کمک یا گزارش وضعیت ارسال کنید.</h4>
        </div>
    </div>

    <div class="container">
        <h1 class="mytitle mt-5">تماس با ما</h1>
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="contact-form">
                    <?php if ($formMessage): ?>
                        <div class="alert-success">
                            <?php echo htmlspecialchars($formMessage); ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($formError): ?>
                        <div class="alert-error">
                            <?php echo htmlspecialchars($formError); ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3"><input type="text" class="form-control-custom" name="contactName"
                                placeholder="نام و نام خانوادگی" required></div>
                        <div class="mb-3"><input type="email" class="form-control-custom" name="contactEmail"
                                placeholder="ایمیل" required></div>
                        <div class="mb-3"><input type="tel" class="form-control-custom" name="contactPhone"
                                placeholder="شماره تماس"></div>
                        <div class="mb-3"><textarea class="form-control-custom" rows="5" name="contactMessage"
                                placeholder="پیام شما..." required></textarea></div>
                        <button type="submit" name="send_message" class="mybtn w-100">ارسال پیام</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="contact-form">
                    <h4 class="mb-3">اطلاعات تماس</h4>
                    <p><strong>آدرس:</strong> تهران، خیابان انقلاب، پلاک ۱۲۳، ساختمان مدیریت بحران</p>
                    <p><strong>تلفن:</strong> ۰۲۱-۸۸۸۸۸۸۸۸</p>
                    <p><strong>ایمیل:</strong> support@disastercontroller.ir</p>
                    <p><strong>ساعات کاری:</strong> ۲۴ ساعته، ۷ روز هفته</p>
                    <hr>
                    <h5>کانال‌های اطلاع‌رسانی:</h5>
                    <p>📱 ایتا: @disaster_controller<br>📱 سروش: @disaster_controller<br>📱 روبیکا: @disaster_controller
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