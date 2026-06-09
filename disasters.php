<?php
session_start();
require_once 'config/database.php';

// دریافت لیست بلایا از دیتابیس
$stmt = $pdo->query("SELECT id, name_fa, name, description FROM disasters ORDER BY id");
$disastersFromDB = $stmt->fetchAll();

// اگر دیتابیس خالی بود، از داده‌های پیش‌فرض استفاده کن
if (empty($disastersFromDB)) {
    $disastersFromDB = [
        ['id' => 1, 'name_fa' => 'زلزله', 'name' => 'Earthquake', 'description' => 'لرزش ناگهانی پوسته زمین که ناشی از آزادسازی انرژی در اثر حرکت تکتونیکی صفحات زمین است.'],
        ['id' => 2, 'name_fa' => 'آتشفشان', 'name' => 'Volcano', 'description' => 'باز شدن شکاف در پوسته زمین که به ماگما، خاکستر و گازها اجازه خروج به سطح را می‌دهد.'],
        ['id' => 3, 'name_fa' => 'سونامی', 'name' => 'Tsunami', 'description' => 'موج عظیم اقیانوسی که اغلب در اثر زلزله‌های زیردریایی یا فوران آتشفشان‌ها ایجاد می‌شود.'],
        ['id' => 4, 'name_fa' => 'سیل', 'name' => 'Flood', 'description' => 'بالا آمدن و طغیان آب بر روی زمین‌ها، اغلب ناشی از بارندگی شدید، ذوب برف یا شکستن سدها.'],
        ['id' => 5, 'name_fa' => 'خشکسالی', 'name' => 'Drought', 'description' => 'دوره‌ای طولانی‌مدت با بارش کمتر از حد معمول که منجر به کمبود آب می‌شود.'],
        ['id' => 6, 'name_fa' => 'طوفان', 'name' => 'Storm', 'description' => 'اختلال جوی شدید با بادهای قوی، باران، برف یا تگرگ.'],
        ['id' => 7, 'name_fa' => 'رانش زمین', 'name' => 'Landslide', 'description' => 'حرکت ناگهانی یا آرامِ خاک و سنگِ سست‌شده روی شیب به سمت پایین است.'],
        ['id' => 8, 'name_fa' => 'آتش‌سوزی جنگل', 'name' => 'Wildfire', 'description' => 'آتش‌سوزی کنترل نشده در مناطق طبیعی مانند جنگل‌ها و مراتع، اغلب ناشی از خشکی و گرما.'],
    ];
}

// تبدیل به فرمت مورد استفاده در جاوااسکریپت
$disastersJson = [];
foreach ($disastersFromDB as $d) {
    $disastersJson[] = [
        'name' => $d['name_fa'],
        'en' => $d['name'],
        'desc' => $d['description'],
        'category' => 'متفرقه'
    ];
}
?>
<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Natural Disaster Controller - حوادث طبیعی</title>
    <link rel="stylesheet" href="css/navbar.css" />
    <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon" />
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css" />
    <style>
        html, body { height: 100%; margin: 0; padding: 0; }
        body { display: flex; flex-direction: column; }
        .myfooter { margin-top: auto !important; margin-bottom: 0 !important; width: 100% !important; }
        .disaster-card { transition: transform 0.3s ease; }
        .disaster-card:hover { transform: translateY(-5px); }
    </style>
</head>
<body>
    <div class="mynavbar">
        <div class="logo-area">
            <div class="logo-icon"><img src="assets/logo.png" alt="لوگو" /></div>
            <div class="logo-title"><span><span class="text-success">N</span>atural <span class="text-warning">D</span>isaster <span class="text-info">C</span>ontroller</span></div>
        </div>
        <div class="center-nav">
            <div class="circle-btn weather"><a href="weather.php"><img width="30px" height="30px" src="assets/weather app.svg" alt="آب و هوا" /></a></div>
            <div class="circle-btn maps"><a href="maps.php"><img width="30px" height="30px" src="assets/maps.svg" alt="نقشه" /></a></div>
            <div class="nav-btn active"><a href="disasters.php"><img width="30px" height="30px" src="assets/image.svg" alt="حوادث" /></a><span class="text-muted">حوادث</span></div>
            <div class="circle-btn home"><a href="index.php"><img width="30px" height="30px" src="assets/house (2).svg" alt="خانه" /></a></div>
            <div class="circle-btn tips"><a href="tips.php"><img width="30px" height="30px" src="assets/tips.svg" alt="نکات" /></a></div>
        </div>
        <div class="right-nav">
            <div class="circle-btn messenger"><a href="messenger.php"><img width="30px" height="30px" src="assets/messenger.svg" alt="پیام‌رسان" /></a></div>
            <div class="circle-btn whatsapp"><a href="whatsapp.php"><img width="30px" height="30px" src="assets/whatsapp.svg" alt="واتساپ" /></a></div>
            <div class="circle-btn contacts"><a href="contacts.php"><img width="30px" height="30px" src="assets/contacts.svg" alt="مخاطبین" /></a></div>
            <div class="btn-group">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div style="position:relative;">
                        <button class="mybtn sign-in" onclick="toggleUserMenu()" style="display:flex; align-items:center; gap:8px;">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></button>
                        <div id="userDropdown" style="display:none; position:absolute; top:100%; left:0; background:white; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); min-width:150px; margin-top:5px; z-index:1000;">
                            <a href="dashboard.php" style="display:block; padding:10px 15px; color:#333; text-decoration:none;">📊 داشبورد</a>
                            <a href="settings.php" style="display:block; padding:10px 15px; color:#333; text-decoration:none;">⚙️ تنظیمات</a>
                            <a href="logout.php" style="display:block; padding:10px 15px; color:#c00; text-decoration:none;">🚪 خروج</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a class="mybtn sign-in" href="login.php">ورود / ثبت‌نام</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="d2">
        <div class="div-text2">
            <h1 class="mytitle2 myfont">حوادث طبیعی</h1>
            <h4 class="myp myfont">
                بلایای طبیعی رویدادهایی هستند که توسط نیروهای طبیعی زمین مانند زلزله، سیل، طوفان و آتشفشان ایجاد می‌شوند و اثرات مخربی بر جوامع انسانی، زیرساخت‌ها و محیط زیست دارند. این رویدادها می‌توانند منجر به تلفات جانی، خسارات اقتصادی گسترده و آسیب‌های زیست‌محیطی شوند.
            </h4>
        </div>
    </div>

    <div class="container mym">
        <div class="row">
            <h1 class="mytitle">حوادث تحت پوشش</h1>
            <div class="search-box">
                <div>
                    <input class="myinput" type="search" name="" id="searchInput" placeholder="جستجوی حادثه..." />
                    <button class="mybutton" type="submit" onclick="searchDisaster()"><img src="assets/search (1).svg" alt="جستجو" /></button>
                </div>
                <div class="badgebox mt-3" id="badgeContainer">
                    <!-- بج‌ها با جاوااسکریپت از دیتابیس ساخته می‌شوند -->
                </div>
            </div>
            <div class="row" id="disastersContainer"></div>
        </div>
    </div>

    <div class="myfooter">
        <h1>1404-1405 | <span class="text-success">N</span>atural <span class="text-warning">D</span>isaster <span class="text-info">C</span>ontroller ©</h1>
        <h4>تمام حقوق محفوظ هستند</h4>
        <h5>تماس با دیگر کاربران | تماس با ما | مرور تمامی مطالب</h5>
    </div>

    <script src="js/global.js"></script>
    <script src="js/navbar.js"></script>
    <script>
        // داده‌های بلایا از PHP به جاوااسکریپت منتقل می‌شود
        const disastersData = <?php echo json_encode($disastersJson); ?>;

        function renderDisasters(filter = "") {
            const container = document.getElementById("disastersContainer");
            if (!container) return;
            const filtered = disastersData.filter(d => d.name.includes(filter) || d.en.toLowerCase().includes(filter.toLowerCase()));
            container.innerHTML = filtered.map(d => `
                <div class="col-md-4 mt-4 disaster-card" data-name="${d.name}">
                    <div class="card text-black mybg">
                        <div class="card-body">
                            <h4 class="myfont">${d.name}<span class="mybadge me-1">${d.en}</span></h4>
                            <p>توضیح: ${d.desc}</p>
                            <p>دسته بندی: ${d.category || 'طبیعی'}</p>
                        </div>
                    </div>
                </div>
            `).join("");
        }

        function renderBadges() {
            const badgeContainer = document.getElementById("badgeContainer");
            if (!badgeContainer) return;
            const uniqueNames = [...new Map(disastersData.map(d => [d.name, d])).values()];
            badgeContainer.innerHTML = uniqueNames.map(d => `<span class="mybadge myspan p-2" data-disaster="${d.name}">${d.name}</span>`).join("");
            document.querySelectorAll(".myspan").forEach(badge => {
                badge.addEventListener("click", () => filterByDisaster(badge.getAttribute("data-disaster")));
            });
        }

        function searchDisaster() {
            const input = document.getElementById("searchInput");
            renderDisasters(input.value);
        }

        function filterByDisaster(name) {
            renderDisasters(name);
            const badges = document.querySelectorAll(".myspan");
            badges.forEach(b => b.classList.remove("myme2"));
            const activeBadge = Array.from(badges).find(b => b.getAttribute("data-disaster") === name);
            if (activeBadge) activeBadge.classList.add("myme2");
            window.scrollTo({ top: 0, behavior: "smooth" });
        }

        document.addEventListener("DOMContentLoaded", () => {
            renderDisasters();
            renderBadges();
        });
        document.getElementById("searchInput")?.addEventListener("keypress", function(e) { if (e.key === "Enter") searchDisaster(); });

        function toggleUserMenu() {
            const dropdown = document.getElementById('userDropdown');
            if (dropdown) dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.btn-group')) {
                const dropdown = document.getElementById('userDropdown');
                if (dropdown) dropdown.style.display = 'none';
            }
        });
    </script>
    <script>
        const images = [
    "assets/upscalemedia-transformed.jpeg",
    "assets/pexels-peter-kovesi-421914941-15211447.jpg",
    "assets/pexels-manfred-langpap-493471790-16114057.jpg",
    "assets/pexels-jplenio-1119974.jpg",
    "assets/pexels-alexandrep-junior-12027849.jpg"
  ];

  let currentImage = 0;

  const divElement = document.querySelector(".d2");

  setInterval(() => {
    currentImage++;

    if (currentImage >= images.length) {
      currentImage = 0;
    }

    divElement.style.backgroundImage = `url(${images[currentImage]})`;
  }, 5000);
    </script>
</body>
</html>