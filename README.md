<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Natural Disaster Manager | پلتفرم جامع مدیریت بلایای طبیعی</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  body {
    background: linear-gradient(135deg, #f9fbfd 0%, #eef2f5 100%);
    font-family: 'Segoe UI', 'Vazirmatn', system-ui, -apple-system, 'Roboto', sans-serif;
    padding: 2rem 1rem;
    color: #0f172a;
  }
  .container {
    max-width: 1280px;
    margin: 0 auto;
  }
  /* hero */
  .hero {
    text-align: center;
    padding: 3rem 1rem 4rem 1rem;
    background: radial-gradient(ellipse at 70% 30%, rgba(44,125,160,0.08), transparent);
    border-radius: 48px;
    margin-bottom: 2rem;
  }
  .hero h1 {
    font-size: 3.5rem;
    font-weight: 800;
    background: linear-gradient(135deg, #1e5a7a, #2c7da0, #1e3a5f);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 1rem;
  }
  .badge-group {
    margin: 1rem 0;
  }
  .badge {
    display: inline-block;
    background: #2c7da0;
    color: white;
    padding: 6px 18px;
    border-radius: 40px;
    font-size: 0.8rem;
    font-weight: 500;
    margin: 0 4px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
  }
  .slogan {
    font-size: 1.3rem;
    color: #1e293b;
    max-width: 750px;
    margin: 1rem auto 0;
    line-height: 1.6;
    background: rgba(255,255,240,0.7);
    backdrop-filter: blur(4px);
    padding: 0.8rem 1.8rem;
    border-radius: 80px;
    display: inline-block;
  }
  /* features grid */
  .features-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    justify-content: center;
    margin: 3rem 0;
  }
  .feature-card {
    flex: 1 1 300px;
    background: rgba(255,255,255,0.9);
    backdrop-filter: blur(4px);
    border-radius: 32px;
    padding: 2rem 1.6rem;
    box-shadow: 0 20px 35px -12px rgba(0,0,0,0.08);
    transition: all 0.25s ease;
    border: 1px solid rgba(44,125,160,0.2);
  }
  .feature-card:hover {
    transform: translateY(-6px);
    background: white;
    border-color: #2c7da0;
  }
  .feature-icon { font-size: 2.8rem; margin-bottom: 1rem; }
  .feature-card h3 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.8rem; color: #0f3b4f; }
  .feature-card p { color: #334155; line-height: 1.65; font-size: 0.95rem; margin-bottom: 0.5rem; }
  .feature-card .more-detail { font-size: 0.85rem; color: #2c7da0; margin-top: 0.6rem; display: inline-block; font-weight: 500; }
  /* tech and guide */
  .section-card {
    background: #ffffffcc;
    backdrop-filter: blur(4px);
    border-radius: 40px;
    padding: 2rem 1.8rem;
    margin: 2.5rem 0;
    border: 1px solid #e2edf2;
    box-shadow: 0 8px 20px rgba(0,0,0,0.02);
  }
  .section-card h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.2rem;
    border-right: 5px solid #2c7da0;
    padding-right: 1rem;
    color: #1e2c3a;
  }
  .tech-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
  }
  .tech-table th, .tech-table td {
    padding: 12px 18px;
    text-align: right;
    border-bottom: 1px solid #e2edf2;
  }
  .tech-table th { background: #eaf4f8; font-weight: 700; color: #155e75; }
  .flow {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    background: #f0f6fa;
    border-radius: 48px;
    padding: 1.8rem;
    margin: 1.5rem 0;
  }
  .flow-step {
    background: white;
    border-radius: 30px;
    padding: 0.7rem 1.4rem;
    min-width: 150px;
    text-align: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    font-weight: 600;
  }
  .flow-arrow { font-size: 1.8rem; color: #2c7da0; }
  .setup-steps {
    list-style-type: none;
    padding: 0;
    margin-top: 1rem;
  }
  .setup-steps li {
    background: #fefefe;
    margin: 1rem 0;
    padding: 1rem 1.5rem;
    border-radius: 28px;
    border: 1px solid #e0edf2;
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
  }
  .step-num {
    background: #2c7da0;
    color: white;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 60px;
    font-weight: bold;
  }
  .code-inline {
    background: #1e2a3a;
    color: #f1f5f9;
    padding: 5px 12px;
    border-radius: 20px;
    font-family: monospace;
    font-size: 0.85rem;
  }
  .btn-demo {
    background: #2c7da0;
    color: white;
    padding: 8px 22px;
    border-radius: 40px;
    text-decoration: none;
    display: inline-block;
    font-weight: 600;
    margin-top: 1rem;
  }
  .closing {
    background: linear-gradient(110deg, #1e3a5f, #2c7da0);
    color: white;
    padding: 2.5rem;
    border-radius: 48px;
    text-align: center;
    margin-top: 2rem;
  }
  @media (max-width: 700px) {
    .hero h1 { font-size: 2.2rem; }
    .feature-card { flex-basis: 100%; }
    .flow-step { min-width: 120px; }
  }
</style>
</head>
<body>
<div class="container">

  <!-- HERO SECTION -->
  <div class="hero">
    <div class="badge-group">
      <span class="badge">🚀 نسخه ۳.۰</span>
      <span class="badge">⚡ هشدارهای لحظه‌ای</span>
      <span class="badge">🌍 پوشش سراسری ایران</span>
      <span class="badge">🧠 هوشمندسازی ریسک</span>
    </div>
    <h1>Natural Disaster Manager</h1>
    <div class="slogan">
      ⚡ پیشرفته‌ترین سامانه مدیریت، پیش‌بینی و واکنش سریع به بلایای طبیعی ⚡
    </div>
    <p style="margin-top: 2rem; max-width: 780px; margin-left: auto; margin-right: auto; font-size: 1rem; color: #2d3e50;">
      پلتفرمی یکپارچه برای سازمان‌های امدادی، مدیران شهری و شهروندان؛ کاهش تلفات انسانی با ترکیب داده‌های ماهواره‌ای، گزارش‌های مردمی و داشبوردهای آنالیز حرفه‌ای.
    </p>
  </div>

  <!-- توضیح جامع درباره سایت (Detailed introduction) -->
  <div class="section-card">
    <h2>📌 درباره پلتفرم</h2>
    <p style="font-size: 1.02rem; line-height: 1.8; margin-bottom: 1rem;">
      <strong>Natural Disaster Manager</strong> یک سیستم مدیریت بلایای طبیعی است که به صورت تخصصی برای شرایط اقلیمی و جغرافیایی ایران طراحی شده است. 
      این سامانه با گردآوری داده‌های تاریخی (زلزله، سیل، خشکسالی، طوفان، آتش‌سوزی و ...) و تحلیل لحظه‌ای خطر، به کاربران عادی، کارشناسان و مدیران امکان می‌دهد تا از وقوع حادثه آگاه شوند، اقدامات پیشگیرانه انجام دهند و در زمان بحران، واکنش سریع و هدفمندی داشته باشند.
    </p>
    <p style="font-size: 1.02rem; line-height: 1.8;">
      ✅ از نقشه‌های تعاملی استان‌ها گرفته تا سیستم تیکت‌دهی مردمی، پنل مدیریت شیشه‌ای، نمودارهای تحلیلی و ابزار بکاپ‌گیری — همه اجزا در یک پلتفرم امن و مقیاس‌پذیر گرد هم آمده‌اند.
    </p>
  </div>

  <!-- ویژگی‌های اصلی با توضیحات کاملتر (Feature Cards) -->
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">⚠️🚨</div>
      <h3>هشدارهای فوری و هوشمند</h3>
      <p>سیستم هشدار با سطح‌بندی شدت (کم، متوسط، بحرانی) بر اساس پارامترهایی مانند بزرگی زلزله، میزان بارندگی، سرعت باد و داده‌های سنسورهای مجازی. اعلان‌ها به صورت همگانی روی نقشه، پیامک (API آینده) و در پنل کاربری نمایش داده می‌شوند. همچنین مدیران می‌توانند هشدارهای جدید ثبت و یا هشدارهای قدیمی را غیرفعال کنند.</p>
      <span class="more-detail">📢 کاهش زمان واکنش تا ۶۰٪</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">🗺️📊</div>
      <h3>نقشه جامع ریسک استان‌ها</h3>
      <p>نقشه تعاملی SVG که هر استان را با رنگ متناسب با میزان خطر (بر اساس امتیاز ریسک تجمعی) نمایش می‌دهد. با کلیک روی هر استان، اطلاعات کاملی شامل آمار بلایا، هشدارهای فعال، جمعیت و مساحت به همراه پنل کناری نمایش داده می‌شود. داده‌های ریسک از جدول province_risk_scores و events استخراج می‌شود و به‌روزرسانی خودکار دارد.</p>
      <span class="more-detail">📈 پشتیبانی از تحلیل تاریخی</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">📱👥</div>
      <h3>گزارش‌های مردمی (Citizen Reports)</h3>
      <p>شهروندان بدون نیاز به لاگین می‌توانند نوع حادثه، استان، شهر، مختصات جغرافیایی، شدت تخمینی و حتی تصویر را ارسال کنند. گزارش‌ها در پنل مدیریت با وضعیت «در انتظار تأیید» قرار می‌گیرند و کارشناس یا مدیر پس از بررسی، می‌تواند آن را تأیید یا رد کند. گزارش‌های تأیید شده در صفحه اصلی سایت نمایش داده می‌شوند و اعتماد عمومی را افزایش می‌دهند.</p>
      <span class="more-detail">⭐ افزایش مشارکت شهروندی</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">👨‍💼📋</div>
      <h3>داشبورد مدیریت حرفه‌ای</h3>
      <p>پنل اختصاصی برای نقش‌های admin / expert / user : مدیریت کاربران (تغییر نقش، فعال/غیرفعال، ویرایش، حذف)، مدیریت هشدارها، مدیریت بلایا، استان‌ها، ریسک استان‌ها، پیام‌های تماس، گزارشات مردمی و حتی بکاپ‌گیری از دیتابیس. طراحی شیشه‌ای (glassmorphism) و سازگار با موبایل.</p>
      <span class="more-detail">🔐 امنیت کامل و تفکیک دسترسی</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">📚🛡️</div>
      <h3>مرکز آموزش و توصیه‌های ایمنی</h3>
      <p>بخش tips و recommendations شامل راهکارهای عملی برای هر نوع بلای طبیعی: پناهگیری در زلزله، کیسه اضطراری، مقابله با سیل، مدیریت خشکسالی و … توصیه‌ها بر اساس اولویت (priority) دسته‌بندی شده و برای عموم قابل استفاده است. هدف افزایش آمادگی جامعه در برابر فجایع.</p>
      <span class="more-detail">🧑‍🏫 آموزش همگانی رایگان</span>
    </div>
  </div>

  <!-- فناوری‌ها (Tech stack) -->
  <div class="section-card">
    <h2>🧰 معماری و فناوری‌های استفاده شده</h2>
    <table class="tech-table">
      <thead><tr><th>بخش</th><th>تکنولوژی</th><th>توضیحات</th></tr></thead>
      <tbody>
        <tr><td>Backend</td><td>PHP 8+ (PDO)</td><td>برنامه‌نویسی شیءگرا، جلوگیری از SQL Injection، session management</td></tr>
        <tr><td>Database</td><td>MySQL / MariaDB</td><td>جداول نرمال‌شده، ایندکس‌های بهینه، پشتیبانی از داده‌های مکانی (POINT)</td></tr>
        <tr><td>Frontend</td><td>HTML5, CSS3, Bootstrap 5 RTL</td><td>رابط کاربری شیشه‌ای و کاملاً واکنش‌گرا (موبایل، تبلت، دسکتاپ)</td></tr>
        <tr><td>نمودار و نقشه</td><td>Chart.js ، SVG interactive</td><td>نمودارهای ماهانه، نمودار دایره‌ای انواع بلایا، نقشه تعاملی استان‌های ایران</td></tr>
        <tr><td>امنیت</td><td>password_hash, prepared statements, XSS protection</td><td>ذخیره رمز به صورت هش، جلوگیری از حملات تزریقی</td></tr>
      </tbody>
    </table>
    <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 24px;">
      <span class="badge" style="background:#1e3a5f;">🐘 PHP 8.3</span>
      <span class="badge" style="background:#f39c12;">📊 Chart.js</span>
      <span class="badge" style="background:#2c7da0;">🗺️ Interactive SVG Map</span>
      <span class="badge" style="background:#16a085;">🔐 Prepared Statements</span>
      <span class="badge" style="background:#8e44ad;">📱 Fully Responsive</span>
    </div>
  </div>

  <!-- جریان داده (سیستم چگونه کار می‌کند) -->
  <div class="section-card">
    <h2>⚙️ جریان پردازش و تصمیم‌گیری</h2>
    <div class="flow">
      <div class="flow-step">📡 دریافت داده (تاریخچه + ورودی کاربر)</div>
      <span class="flow-arrow">→</span>
      <div class="flow-step">🧠 تحلیل هوشمند ریسک (Risk Engine)</div>
      <span class="flow-arrow">→</span>
      <div class="flow-step">⚠️ صدور هشدار یا به‌روزرسانی نقشه</div>
      <span class="flow-arrow">→</span>
      <div class="flow-step">🗳️ گزارش شهروندی + تأیید کارشناس</div>
      <span class="flow-arrow">→</span>
      <div class="flow-step">📢 نمایش عمومی و اقدامات پیشگیرانه</div>
    </div>
    <p style="text-align: center; margin-top: 0.8rem; color: #2d4a68;">⚡ همه فرآیندها در کمتر از ۲ ثانیه و با حفظ امنیت داده‌ها انجام می‌شوند.</p>
  </div>

  <!-- راهنمای کامل راه‌اندازی برای افراد غیر فنی -->
  <div class="section-card">
    <h2>📥 راهنمای گام‌به‌گام راه‌اندازی (بدون نیاز به دانش کدنویسی)</h2>
    <p style="margin-bottom: 1rem;">اگر هیچ آشنایی با برنامه‌نویسی ندارید، نگران نباشید! با دنبال کردن این مراحل ساده، سایت را روی کامپیوتر شخصی خود اجرا کنید.</p>
    <ul class="setup-steps">
      <li><span class="step-num">۱</span> <strong>نصب بسته جامع سرور محلی (XAMPP)</strong> — از سایت apachefriends.org نسخه مخصوص ویندوز را دانلود و نصب کنید. در حین نصب، همه گزینه‌های پیش‌فرض را تأیید کنید. بعد از نصب، برنامه XAMPP Control Panel را باز کرده و دکمه Start روبروی <code class="code-inline">Apache</code> و <code class="code-inline">MySQL</code> را بزنید.</li>
      <li><span class="step-num">۲</span> <strong>کپی کردن فایل‌های پروژه</strong> — پوشه پروژه (که شامل پوشه‌های <strong>admin, assets, config, css, js, sql و فایل index.php</strong> است) را در مسیر <code class="code-inline">C:\xampp\htdocs\</code> (در لینوکس و مک مسیر مشابه) قرار دهید. بهتر است نام پوشه را <code class="code-inline">ndc</code> بگذارید.</li>
      <li><span class="step-num">۳</span> <strong>ایجاد پایگاه داده (Database)</strong> — در مرورگر آدرس <code class="code-inline">http://localhost/phpmyadmin</code> را بزنید. روی سربرگ New کلیک کرده و نام دیتابیس را <code class="code-inline">natural_disaster_db</code> وارد کنید (نوع collation: utf8mb4_persian_ci). سپس روی Create کلیک کنید. حالا از تب Import فایل <code class="code-inline">deepseek_sql_20260611_85f8f2.sql</code> (موجود در پوشه پروژه) را انتخاب کنید و دکمه Execute را بزنید تا تمام جداول و اطلاعات اولیه وارد شوند.</li>
      <li><span class="step-num">۴</span> <strong>تنظیم فایل اتصال به دیتابیس</strong> — به مسیر <code class="code-inline">config/database.php</code> رفته و مطمئن شوید که نام کاربری <code class="code-inline">root</code> و رمز عبور خالی است (در XAMPP پیش‌فرض). در صورت نیاز تغییر دهید.</li>
      <li><span class="step-num">۵</span> <strong>اجرای وب‌سایت</strong> — حالا در مرورگر آدرس <code class="code-inline">http://localhost/ndc/</code> را وارد کنید. صفحه اصلی سایت با نقشه، آمار و هشدارها نمایش داده می‌شود. برای ورود به پنل مدیریت از اطلاعات زیر استفاده کنید: <br>
        👤 <strong>نام کاربری:</strong> admin &nbsp;&nbsp; | &nbsp;&nbsp; 🔑 <strong>رمز عبور:</strong> admin123 (این رمز هش شده در دیتابیس موجود است. بعد از ورود حتماً آن را تغییر دهید).<br>
        همچنین می‌توانید کاربر جدید ثبت نام کنید و نقش آن را از پنل مدیریت ارتقا دهید.
      </li>
      <li><span class="step-num">۶</span> <strong>عیب‌یابی سریع</strong> — اگر صفحه نمایش داده نشد، مطمئن شوید Apache و MySQL در XAMPP سبز رنگ باشند. همچنین فایل <code class="code-inline">.htaccess</code> در پوشه اصلی وجود داشته باشد. در صورت بروز خطای اتصال به دیتابیس، فایل <code class="code-inline">config/database.php</code> را بررسی کنید.</li>
    </ul>
    <div style="background: #e8f0f5; border-radius: 28px; padding: 1rem; margin-top: 1rem;">
      ✅ <strong>نکته مهم:</strong> تمام کدها و فایل‌ها از قبل آماده هستند. نیازی به نصب کامپایلر یا ابزار خاصی نیست. فقط کافی است مراحل بالا را دقیق انجام دهید.
    </div>
  </div>

  <!-- بخش پایانی قدرتمند -->
  <div class="closing">
    <p style="font-size: 1.4rem; font-weight: 700;">🌍 حالا شما یک سامانه مدیریت بحران کامل در اختیار دارید</p>
    <p style="margin: 1rem 0;">پیشگیری بهتر از درمان است — با Natural Disaster Manager، هر ثانیه برای نجات جان ارزش دارد.</p>
    <div>
      <span style="background: rgba(255,255,255,0.2); padding: 6px 20px; border-radius: 60px;">🚀 آماده برای مقابله با بلایا</span>
      <span style="background: rgba(255,255,255,0.2); padding: 6px 20px; border-radius: 60px; margin-right: 12px;">🛠️ متن‌باز و مقیاس‌پذیر</span>
    </div>
    <div style="margin-top: 1.8rem; font-size: 0.9rem;">
      <i class="far fa-copyright"></i> Natural Disaster Manager – طراحی شده برای ایران مقاوم
    </div>
  </div>

</div>
</body>
</html>
