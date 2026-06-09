<?php
session_start();
require_once 'config/database.php';

// ========== National Risk Index Calculation ==========
// Fetch all provinces with their latest risk data
$riskProvinces = [];
$provinceData = $pdo->query("
    SELECT p.id, p.name, 
           COALESCE(MAX(pdr.risk_value), 0) AS base_risk,
           (SELECT COUNT(*) FROM disaster_events WHERE province_id = p.id AND start_date > DATE_SUB(NOW(), INTERVAL 10 YEAR)) AS historical_events,
           (SELECT COUNT(*) FROM alerts WHERE province_id = p.id AND is_active = 1 AND end_time > NOW()) AS active_alerts
    FROM provinces p
    LEFT JOIN province_disaster_risk pdr ON p.id = pdr.province_id
    GROUP BY p.id
")->fetchAll();

foreach ($provinceData as $p) {
    // Risk Score formula (0-100)
    $base = $p['base_risk'] * 12.5;           // 0-50
    $hist = min($p['historical_events'] * 2, 25);
    $alert = min($p['active_alerts'] * 10, 25);
    $score = min(round($base + $hist + $alert), 100);
    $riskProvinces[$p['id']] = [
        'name' => $p['name'],
        'score' => $score,
        'level' => $score < 20 ? 'safe' : ($score < 40 ? 'moderate' : ($score < 60 ? 'high' : ($score < 80 ? 'severe' : 'critical')))
    ];
}

// ========== Live Alerts ==========
$liveAlerts = $pdo->query("
    SELECT a.*, d.name_fa as disaster_name, p.name as province_name,
           CASE 
               WHEN a.severity = 3 THEN 'red'
               WHEN a.severity = 2 THEN 'orange'
               ELSE 'yellow'
           END as severity_class
    FROM alerts a
    JOIN disasters d ON a.disaster_id = d.id
    JOIN provinces p ON a.province_id = p.id
    WHERE a.is_active = 1 AND a.end_time > NOW()
    ORDER BY a.severity DESC, a.start_time DESC
    LIMIT 8
")->fetchAll();

// ========== Chart Data: Monthly Events ==========
$monthlyStats = $pdo->query("
    SELECT DATE_FORMAT(start_date, '%Y-%m') as month, COUNT(*) as total
    FROM disaster_events
    WHERE start_date > DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY month
    ORDER BY month ASC
")->fetchAll();
$months = [];
$eventsCount = [];
foreach ($monthlyStats as $row) {
    $months[] = $row['month'];
    $eventsCount[] = $row['total'];
}
$monthsJSON = json_encode($months);
$eventsJSON = json_encode($eventsCount);

// ========== Citizen Reports for Map ==========
$citizenReports = $pdo->query("
    SELECT r.id, r.description, r.severity_estimate, p.name as province_name,
           r.created_at, u.username
    FROM user_reports r
    JOIN provinces p ON r.province_id = p.id
    JOIN users u ON r.user_id = u.id
    WHERE r.status = 'verified'
    ORDER BY r.created_at DESC
    LIMIT 10
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>مرکز فرماندهی بلایای طبیعی ایران</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/command-center.css">
    <link rel="shortcut icon" href="assets/mylogo.ico" type="image/x-icon">
    <style>
        /* Additional critical overrides */
        body { background: var(--bg-dark); font-family: 'Vazirmatn', sans-serif; }
        .command-header { background: rgba(8, 17, 31, 0.95); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0, 198, 255, 0.3); }
        .risk-gauge { transition: all 0.3s ease; }
        .alert-card { transition: transform 0.2s, box-shadow 0.2s; }
        .alert-card:hover { transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0,0,0,0.3); }
    </style>
</head>
<body>

<!-- ========== GLASS NAVIGATION ========== -->
<nav class="command-header">
    <div class="container-fluid">
        <div class="nav-flex">
            <div class="logo-area">
                <img src="assets/logo.png" alt="Logo" class="logo-icon">
                <span class="logo-text"><span class="text-cyan">N</span>atural <span class="text-green">D</span>isaster <span class="text-blue">C</span>ontroller</span>
            </div>
            <div class="nav-links">
                <a href="index.php" class="active"><i class="bi bi-grid-1x2-fill"></i> داشبورد</a>
                <a href="maps.php"><i class="bi bi-map-fill"></i> نقشه تعاملی</a>
                <a href="disasters.php"><i class="bi bi-cloud-lightning-rain-fill"></i> حوادث</a>
                <a href="tips.php"><i class="bi bi-shield-shaded"></i> شبیه‌ساز</a>
                <a href="messenger.php"><i class="bi bi-chat-dots-fill"></i> تماس</a>
            </div>
            <div class="user-area">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <button class="user-btn" id="userMenuBtn"><i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['username']) ?> <i class="bi bi-chevron-down"></i></button>
                        <div class="dropdown-menu" id="userDropdown">
                            <a href="dashboard.php"><i class="bi bi-speedometer2"></i> پنل کاربری</a>
                            <a href="settings.php"><i class="bi bi-gear"></i> تنظیمات</a>
                            <a href="logout.php"><i class="bi bi-box-arrow-right"></i> خروج</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn-outline-cyber">ورود / ثبت‌نام</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- ========== HERO SECTION ========== -->
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <span class="badge-live"><i class="bi bi-broadcast"></i> سیستم هشدار آنی</span>
                <h1>مرکز هوشمند مدیریت<br>بلایای طبیعی ایران</h1>
                <p>پایش لحظه‌ای استان‌ها، پیش‌بینی ریسک، گزارش‌های مردمی و تحلیل داده‌های ماهواره‌ای</p>
                <div class="hero-stats">
                    <div><span class="stat-number" id="totalAlerts"><?= count($liveAlerts) ?></span> <span class="stat-label">هشدار فعال</span></div>
                    <div><span class="stat-number"><?= $pdo->query("SELECT COUNT(*) FROM provinces")->fetchColumn() ?></span> <span class="stat-label">استان تحت پوشش</span></div>
                    <div><span class="stat-number"><?= $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() ?></span> <span class="stat-label">نیروی دیده‌بان</span></div>
                </div>
                <div class="hero-actions">
                    <button class="btn-primary-cyber" id="scrollToMap"><i class="bi bi-map"></i> مشاهده نقشه خطر</button>
                    <button class="btn-outline-cyber" id="openReportModal"><i class="bi bi-plus-circle"></i> گزارش مردمی</button>
                </div>
            </div>
            <div class="hero-visual">
                <div class="risk-globe">
                    <div class="pulse-ring"></div>
                    <i class="bi bi-shield-fill-check"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== NATIONAL RISK INDEX ========== -->
<section class="risk-index-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="bi bi-graph-up"></i> شاخص ملی خطر</span>
            <h2>وضعیت خطر استان‌ها</h2>
            <p>امتیاز ۰ تا ۱۰۰ بر اساس داده‌های زلزله‌شناسی، هواشناسی، جمعیت و هشدارهای فعال</p>
        </div>
        <div class="risk-grid" id="riskGrid">
            <?php foreach ($riskProvinces as $pid => $risk): ?>
                <div class="risk-card" data-province-id="<?= $pid ?>" data-risk-score="<?= $risk['score'] ?>">
                    <div class="risk-header">
                        <span class="province-name"><?= htmlspecialchars($risk['name']) ?></span>
                        <span class="risk-badge level-<?= $risk['level'] ?>"><?= $risk['score'] ?></span>
                    </div>
                    <div class="risk-bar">
                        <div class="risk-fill" style="width: <?= $risk['score'] ?>%; background: <?= $risk['score'] < 20 ? '#2ecc71' : ($risk['score'] < 40 ? '#f1c40f' : ($risk['score'] < 60 ? '#e67e22' : ($risk['score'] < 80 ? '#e74c3c' : '#c0392b'))) ?>"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========== INTERACTIVE MAP + DETAILS PANEL ========== -->
<section class="map-command-section">
    <div class="container-fluid">
        <div class="map-layout">
            <div class="map-wrapper" id="interactiveMap">
                <!-- SVG map loaded dynamically, but for now keep original SVG with enhancements -->
                <?php include 'assets/iran_map_svg.php'; ?>
            </div>
            <div class="province-detail-panel" id="provinceDetailPanel" style="display: none;">
                <div class="panel-header">
                    <h3 id="panelProvinceName">استان</h3>
                    <button class="close-panel" id="closePanelBtn"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="panel-content" id="panelContent">
                    <!-- dynamically loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== LIVE ALERT CENTER ========== -->
<section class="alerts-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="bi bi-bell-fill"></i> هشدارهای زنده</span>
            <h2>مرکز هشدار فوری</h2>
        </div>
        <div class="alerts-grid" id="alertsContainer">
            <?php foreach ($liveAlerts as $alert): ?>
                <div class="alert-card severity-<?= $alert['severity_class'] ?>">
                    <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <div class="alert-content">
                        <div class="alert-title"><?= htmlspecialchars($alert['disaster_name']) ?> - <?= htmlspecialchars($alert['province_name']) ?></div>
                        <div class="alert-message"><?= nl2br(htmlspecialchars($alert['message'])) ?></div>
                        <div class="alert-time"><i class="bi bi-clock"></i> از <?= date('H:i', strtotime($alert['start_time'])) ?> تا <?= date('H:i', strtotime($alert['end_time'])) ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($liveAlerts)): ?>
                <div class="alert-card severity-green"><div class="alert-content">هیچ هشدار فعالی وجود ندارد. وضعیت پایدار است.</div></div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== ANALYTICS DASHBOARD ========== -->
<section class="analytics-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="bi bi-bar-chart-steps"></i> تحلیل داده</span>
            <h2>روند رویدادهای طبیعی</h2>
        </div>
        <div class="analytics-grid">
            <div class="chart-card">
                <div class="chart-header">تعداد حوادث در ۱۲ ماه گذشته</div>
                <canvas id="trendChart" width="400" height="200"></canvas>
            </div>
            <div class="chart-card">
                <div class="chart-header">توزیع نوع حادثه</div>
                <canvas id="disasterPieChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</section>

<!-- ========== CITIZEN REPORTS ========== -->
<section class="reports-section">
    <div class="container">
        <div class="section-header">
            <span class="section-badge"><i class="bi bi-chat-square-quote-fill"></i> گزارش‌های مردمی</span>
            <h2>دیده‌بانان مردمی</h2>
        </div>
        <div class="reports-feed" id="reportsFeed">
            <?php foreach ($citizenReports as $report): ?>
                <div class="report-item">
                    <div class="report-avatar"><i class="bi bi-person-circle"></i></div>
                    <div class="report-details">
                        <div class="report-author"><?= htmlspecialchars($report['username']) ?></div>
                        <div class="report-text"><?= htmlspecialchars($report['description']) ?></div>
                        <div class="report-meta"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($report['province_name']) ?> • <i class="bi bi-calendar"></i> <?= date('Y/m/d H:i', strtotime($report['created_at'])) ?></div>
                    </div>
                    <div class="report-severity severity-<?= $report['severity_estimate'] == 3 ? 'high' : ($report['severity_estimate'] == 2 ? 'medium' : 'low') ?>"><?= $report['severity_estimate'] == 3 ? 'بحرانی' : ($report['severity_estimate'] == 2 ? 'متوسط' : 'کم') ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <button class="btn-outline-cyber" id="openReportModalBtn"><i class="bi bi-pencil-square"></i> ارسال گزارش جدید</button>
        </div>
    </div>
</section>

<!-- ========== AI ASSISTANT ========== -->
<section class="ai-assistant-section">
    <div class="container">
        <div class="ai-card">
            <div class="ai-header">
                <i class="bi bi-robot"></i> دستیار هوشمند مدیریت بحران
                <span class="ai-badge">آزمایشی</span>
            </div>
            <div class="ai-chat" id="aiChat">
                <div class="chat-message bot">سلام! من دستیار هوشمند مرکز فرماندهی هستم. می‌توانید درباره ریسک استان‌ها، اقدامات ایمنی یا هشدارها سوال کنید.</div>
            </div>
            <div class="ai-input">
                <input type="text" id="aiQuestion" placeholder="سوال خود را بپرسید... مثلاً 'وضعیت سیل در خوزستان'">
                <button id="askAiBtn"><i class="bi bi-send"></i></button>
            </div>
        </div>
    </div>
</section>

<!-- ========== MODAL: REPORT FORM ========== -->
<div class="modal" id="reportModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>ثبت گزارش مردمی</h3>
            <button class="modal-close">&times;</button>
        </div>
        <form id="reportForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= bin2hex(random_bytes(32)) ?>">
            <div class="form-group">
                <label>نام شما</label>
                <input type="text" name="fullname" required>
            </div>
            <div class="form-group">
                <label>استان</label>
                <select name="province_id" required>
                    <option value="">انتخاب کنید</option>
                    <?php
                    $provinces = $pdo->query("SELECT id, name FROM provinces ORDER BY name")->fetchAll();
                    foreach ($provinces as $prov) echo "<option value='{$prov['id']}'>{$prov['name']}</option>";
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>نوع حادثه</label>
                <select name="disaster_id" required>
                    <option value="">انتخاب کنید</option>
                    <?php
                    $disasters = $pdo->query("SELECT id, name_fa FROM disasters")->fetchAll();
                    foreach ($disasters as $dis) echo "<option value='{$dis['id']}'>{$dis['name_fa']}</option>";
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>توضیحات</label>
                <textarea name="description" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label>شدت برآوردی (۱ تا ۳)</label>
                <select name="severity">
                    <option value="1">کم</option>
                    <option value="2">متوسط</option>
                    <option value="3">بحرانی</option>
                </select>
            </div>
            <div class="form-group">
                <label>عکس (اختیاری)</label>
                <input type="file" name="photo" accept="image/*">
            </div>
            <button type="submit" class="btn-primary-cyber">ارسال گزارش</button>
        </form>
    </div>
</div>

<!-- ========== FOOTER ========== -->
<footer class="command-footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-copyright">© 1404-1405 مرکز فرماندهی بلایای طبیعی ایران | تمامی حقوق محفوظ است</div>
            <div class="footer-links">
                <a href="#">درباره ما</a>
                <a href="#">تماس با مدیریت بحران</a>
                <a href="#">پایگاه داده باز</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/command-center.js"></script>
<script>
    // Chart initializations
    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= $monthsJSON ?>,
            datasets: [{
                label: 'تعداد حوادث',
                data: <?= $eventsJSON ?>,
                borderColor: '#00C6FF',
                backgroundColor: 'rgba(0,198,255,0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: true }
    });
    
    // Pie chart for disaster types
    <?php
    $pieData = $pdo->query("SELECT d.name_fa, COUNT(de.id) as cnt FROM disasters d LEFT JOIN disaster_events de ON d.id = de.disaster_id GROUP BY d.id")->fetchAll();
    $pieLabels = []; $pieValues = [];
    foreach ($pieData as $row) { $pieLabels[] = $row['name_fa']; $pieValues[] = $row['cnt']; }
    ?>
    new Chart(document.getElementById('disasterPieChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($pieLabels) ?>,
            datasets: [{ data: <?= json_encode($pieValues) ?>, backgroundColor: ['#FF4757','#00C6FF','#00FFB7','#FFB347','#8E44AD','#F39C12'] }]
        }
    });
</script>
</body>
</html>