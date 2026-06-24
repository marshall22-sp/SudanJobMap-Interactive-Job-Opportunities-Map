<?php
// ============================================
// بيانات الوظائف (مصفوفة PHP)
// ============================================
$jobs = [
    [
        'title' => 'مطور ويب Full Stack',
        'company' => 'شركة تقنية الخرطوم',
        'location' => 'الخرطوم',
        'category' => 'تكنولوجيا',
        'desc' => 'خبرة 3+ سنوات في React و Node.js',
        'date' => '2026-06-24'
    ],
    [
        'title' => 'مهندس مدني مشاريع',
        'company' => 'مقاولات بورتسودان',
        'location' => 'بورتسودان',
        'category' => 'هندسة',
        'desc' => 'إدارة مشاريع البنية التحتية',
        'date' => '2026-06-23'
    ],
    [
        'title' => 'محاسب مالي',
        'company' => 'مجموعة النيل',
        'location' => 'أم درمان',
        'category' => 'مالية',
        'desc' => 'خبرة في التحليل المالي وإعداد التقارير',
        'date' => '2026-06-22'
    ],
    [
        'title' => 'طبيب عام',
        'company' => 'مستشفى الخرطوم',
        'location' => 'الخرطوم',
        'category' => 'طبية',
        'desc' => 'للعمل في قسم الطوارئ',
        'date' => '2026-06-21'
    ],
    [
        'title' => 'مطور تطبيقات موبايل',
        'company' => 'سودان تك',
        'location' => 'نيالا',
        'category' => 'تكنولوجيا',
        'desc' => 'خبرة في Flutter أو React Native',
        'date' => '2026-06-20'
    ],
    [
        'title' => 'مهندس كهرباء',
        'company' => 'الشركة السودانية للطاقة',
        'location' => 'كسلا',
        'category' => 'هندسة',
        'desc' => 'تركيب وصيانة شبكات الكهرباء',
        'date' => '2026-06-19'
    ],
    [
        'title' => 'أخصائي موارد بشرية',
        'company' => 'المجموعة العربية',
        'location' => 'الخرطوم',
        'category' => 'إدارة',
        'desc' => 'خبرة في التوظيف وإدارة الكفاءات',
        'date' => '2026-06-18'
    ],
    [
        'title' => 'مدرس لغة إنجليزية',
        'company' => 'مدارس النهضة',
        'location' => 'أم درمان',
        'category' => 'تعليم',
        'desc' => 'خبرة في تدريس المرحلة الثانوية',
        'date' => '2026-06-17'
    ]
];

// ============================================
// بيانات الولايات للإحصائيات
// ============================================
$states = [
    ['name' => 'الخرطوم', 'jobs' => 320],
    ['name' => 'أم درمان', 'jobs' => 210],
    ['name' => 'بورتسودان', 'jobs' => 85],
    ['name' => 'نيالا', 'jobs' => 73],
    ['name' => 'كسلا', 'jobs' => 62],
    ['name' => 'الأبيض', 'jobs' => 48]
];

// ============================================
// معالجة الفلتر (GET)
// ============================================
$search = isset($_GET['search']) ? $_GET['search'] : '';
$location = isset($_GET['location']) ? $_GET['location'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$filteredJobs = array_filter($jobs, function($job) use ($search, $location, $category) {
    $matchSearch = !$search || 
                   strpos($job['title'], $search) !== false || 
                   strpos($job['company'], $search) !== false ||
                   strpos($job['location'], $search) !== false;
    $matchLocation = !$location || $job['location'] === $location;
    $matchCategory = !$category || $job['category'] === $category;
    return $matchSearch && $matchLocation && $matchCategory;
});

// ============================================
// إحصائيات سريعة
// ============================================
$totalJobs = count($jobs);
$totalCompanies = count(array_unique(array_column($jobs, 'company')));
$totalStates = count($states);
$newToday = 0;
foreach ($jobs as $job) {
    if ($job['date'] == date('Y-m-d')) {
        $newToday++;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>خريطة الوظائف - السودان</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;900&display=swap" rel="stylesheet" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --primary: #1a3a5c;
            --gold: #f3b33d;
            --dark: #0d1b2a;
            --light: #f8f9fa;
            --shadow: 0 8px 32px rgba(0,0,0,0.12);
            --radius: 16px;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background: var(--light);
            color: var(--dark);
            line-height: 1.7;
        }
        /* شريط التنقل */
        .navbar {
            background: rgba(13, 27, 42, 0.95);
            backdrop-filter: blur(20px);
            padding: 0 2rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            border-bottom: 2px solid var(--gold);
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 72px;
        }
        .nav-logo {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--gold);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-logo i {
            font-size: 1.8rem;
            color: #e8a87c;
        }
        .nav-links {
            display: flex;
            list-style: none;
            gap: 8px;
        }
        .nav-links li a {
            padding: 10px 20px;
            border-radius: 50px;
            color: rgba(255,255,255,0.7);
            font-weight: 600;
            font-size: 0.95rem;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .nav-links li a:hover,
        .nav-links li a.active {
            color: #fff;
            background: var(--gold);
        }
        .nav-toggle {
            display: none;
            color: #fff;
            font-size: 1.5rem;
            cursor: pointer;
        }
        /* تبويبات */
        .tab-content {
            display: none;
            margin-top: 72px;
            animation: fadeIn 0.5s ease;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        /* البطل */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, var(--dark) 100%);
            padding: 80px 20px 60px;
            text-align: center;
        }
        .hero h1 {
            font-size: 3.2rem;
            font-weight: 900;
            color: #fff;
        }
        .hero h1 span { color: var(--gold); }
        .hero p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.8);
            margin: 12px 0 30px;
        }
        .hero-search {
            display: flex;
            max-width: 550px;
            margin: 0 auto;
            background: rgba(255,255,255,0.12);
            border-radius: 60px;
            padding: 5px;
        }
        .hero-search input {
            flex: 1;
            padding: 16px 24px;
            border: none;
            background: transparent;
            color: #fff;
            font-size: 1rem;
            font-family: 'Cairo', sans-serif;
            outline: none;
        }
        .hero-search input::placeholder {
            color: rgba(255,255,255,0.5);
        }
        .hero-search button {
            padding: 14px 32px;
            background: var(--gold);
            border: none;
            border-radius: 60px;
            color: var(--dark);
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            font-family: 'Cairo', sans-serif;
            transition: 0.3s;
        }
        .hero-search button:hover {
            transform: scale(1.04);
            box-shadow: 0 8px 25px rgba(243,179,61,0.4);
        }
        /* بطاقات الإحصائيات */
        .stats-cards {
            max-width: 1280px;
            margin: -40px auto 40px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            position: relative;
            z-index: 2;
        }
        .stat-card {
            background: #fff;
            padding: 24px 20px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 4px solid var(--gold);
            transition: 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }
        .stat-icon {
            width: 56px;
            height: 56px;
            background: rgba(26,58,92,0.08);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            color: var(--primary);
        }
        .stat-info h3 {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--primary);
        }
        .stat-info p {
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
        }
        /* الخريطة */
        .map-section {
            max-width: 1280px;
            margin: 0 auto 50px;
            padding: 0 20px;
        }
        .map-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        #map {
            height: 520px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            border: 3px solid #fff;
            overflow: hidden;
            background: #e8e8e8;
        }
        /* الوظائف */
        .jobs-filter {
            max-width: 1280px;
            margin: 90px auto 30px;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: center;
        }
        .jobs-filter select,
        .jobs-filter input {
            padding: 12px 20px;
            border-radius: 50px;
            border: 2px solid #e0e0e0;
            font-family: 'Cairo', sans-serif;
            font-size: 0.95rem;
            background: #fff;
            outline: none;
            flex: 1;
            min-width: 150px;
        }
        .jobs-filter select:focus,
        .jobs-filter input:focus {
            border-color: var(--gold);
        }
        .jobs-grid {
            max-width: 1280px;
            margin: 0 auto 50px;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }
        .job-card {
            background: #fff;
            border-radius: var(--radius);
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
            transition: 0.3s;
            border-top: 4px solid var(--gold);
        }
        .job-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow);
        }
        .job-card h3 {
            font-size: 1.2rem;
            color: var(--primary);
        }
        .job-card .company-name {
            color: #e8a87c;
            font-weight: 600;
        }
        .job-card .job-location {
            display: inline-block;
            background: rgba(26,58,92,0.06);
            padding: 4px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            color: var(--primary);
            margin-top: 8px;
        }
        .job-card .job-desc {
            color: #666;
            font-size: 0.9rem;
            margin: 12px 0;
        }
        .job-card .apply-btn {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Cairo', sans-serif;
        }
        .job-card .apply-btn:hover {
            background: var(--gold);
            color: var(--dark);
        }
        /* الإحصائيات */
        .stats-page {
            max-width: 1280px;
            margin: 90px auto 40px;
            padding: 0 20px;
        }
        .stats-page h1 {
            font-size: 2.2rem;
            color: var(--primary);
            margin-bottom: 30px;
            text-align: center;
        }
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 30px;
        }
        .chart-box {
            background: #fff;
            padding: 24px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }
        .chart-box h3 {
            text-align: center;
            color: var(--primary);
            margin-bottom: 16px;
        }
        .chart-box canvas {
            max-height: 260px;
            width: 100% !important;
        }
        /* عن الموقع */
        .about-page {
            max-width: 900px;
            margin: 90px auto 40px;
            padding: 0 20px;
        }
        .about-card {
            background: #fff;
            padding: 48px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            text-align: center;
        }
        .about-card .about-icon {
            font-size: 4rem;
            color: var(--gold);
            margin-bottom: 16px;
        }
        .about-card h1 {
            font-size: 2.4rem;
            color: var(--primary);
        }
        .about-card p {
            color: #555;
            font-size: 1.05rem;
            max-width: 600px;
            margin: 0 auto 20px;
        }
        .about-card .highlight {
            background: rgba(243,179,61,0.12);
            padding: 16px 24px;
            border-radius: 12px;
            border-right: 4px solid var(--gold);
            text-align: right;
        }
        .about-card ul {
            list-style: none;
            padding: 0;
            text-align: right;
        }
        .about-card ul li {
            padding: 6px 0;
        }
        /* تذييل */
        .footer {
            background: var(--dark);
            color: rgba(255,255,255,0.6);
            padding: 30px 20px;
            text-align: center;
            border-top: 3px solid var(--gold);
        }
        .footer-social {
            display: flex;
            justify-content: center;
            gap: 18px;
            margin-top: 12px;
        }
        .footer-social a {
            color: rgba(255,255,255,0.5);
            font-size: 1.3rem;
            transition: 0.3s;
        }
        .footer-social a:hover {
            color: var(--gold);
        }
        /* استجابة */
        @media (max-width: 768px) {
            .nav-links {
                display: none;
                flex-direction: column;
                background: var(--dark);
                position: absolute;
                top: 72px;
                right: 0;
                left: 0;
                padding: 20px;
                border-bottom: 2px solid var(--gold);
            }
            .nav-links.open { display: flex; }
            .nav-toggle { display: block; }
            .hero h1 { font-size: 2.2rem; }
            .hero-search {
                flex-direction: column;
                background: transparent;
                padding: 0;
                gap: 12px;
            }
            .hero-search input {
                background: rgba(255,255,255,0.1);
                border-radius: 60px;
            }
            .stats-cards { grid-template-columns: 1fr 1fr; }
            #map { height: 340px; }
            .jobs-grid { grid-template-columns: 1fr; }
            .chart-grid { grid-template-columns: 1fr; }
            .about-card { padding: 28px; }
        }
        @media (max-width: 480px) {
            .stats-cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- ===== شريط التنقل ===== -->
<nav class="navbar">
    <div class="nav-container">
        <a class="nav-logo" onclick="switchTab('home')">
            <i class="fas fa-map-marked-alt"></i> وظائف السودان
        </a>
        <ul class="nav-links" id="navLinks">
            <li><a class="active" onclick="switchTab('home')"><i class="fas fa-home"></i> الرئيسية</a></li>
            <li><a onclick="switchTab('jobs')"><i class="fas fa-briefcase"></i> الوظائف</a></li>
            <li><a onclick="switchTab('stats')"><i class="fas fa-chart-bar"></i> الإحصائيات</a></li>
            <li><a onclick="switchTab('about')"><i class="fas fa-info-circle"></i> عن الموقع</a></li>
        </ul>
        <div class="nav-toggle" id="navToggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</nav>

<!-- ===== التبويب 1: الرئيسية ===== -->
<div class="tab-content active" id="tab-home">
    <header class="hero">
        <div class="hero-content">
            <h1>🇸🇩 خريطة الوظائف التفاعلية</h1>
            <p>اكتشف فرص العمل في جميع ولايات السودان</p>
            <form class="hero-search" method="GET" action="">
                <input type="text" name="search" placeholder="ابحث عن وظيفة أو ولاية..." value="<?php echo htmlspecialchars($search); ?>" />
                <button type="submit"><i class="fas fa-search"></i> بحث</button>
            </form>
        </div>
    </header>

    <!-- بطاقات الإحصائيات -->
    <section class="stats-cards">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-briefcase"></i></div>
            <div class="stat-info">
                <h3><?php echo number_format($totalJobs); ?></h3>
                <p>وظيفة متاحة</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-building"></i></div>
            <div class="stat-info">
                <h3><?php echo $totalCompanies; ?></h3>
                <p>شركة نشطة</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <h3><?php echo $totalStates; ?></h3>
                <p>ولاية</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-info">
                <h3><?php echo $newToday; ?></h3>
                <p>وظيفة جديدة اليوم</p>
            </div>
        </div>
    </section>

    <!-- الخريطة -->
    <section class="map-section">
        <h2><i class="fas fa-map"></i> استكشف الوظائف حسب الولاية</h2>
        <div id="map"></div>
    </section>

    <!-- أحدث الوظائف -->
    <section class="latest-jobs" style="max-width:1280px;margin:0 auto 60px;padding:0 20px;">
        <h2 style="font-size:1.8rem;font-weight:700;color:var(--primary);margin-bottom:20px;display:flex;align-items:center;gap:12px;">
            <i class="fas fa-clock"></i> أحدث الوظائف
        </h2>
        <div class="jobs-list" style="display:grid;gap:14px;">
            <?php
            $latestJobs = array_slice($jobs, 0, 3);
            foreach ($latestJobs as $job):
            ?>
            <div class="job-item" style="background:#fff;padding:18px 24px;border-radius:12px;box-shadow:0 2px 12px rgba(0,0,0,0.06);display:flex;justify-content:space-between;align-items:center;border-right:4px solid var(--gold);">
                <div class="job-info">
                    <h4 style="font-size:1.1rem;font-weight:700;color:var(--primary);"><?php echo $job['title']; ?></h4>
                    <span class="company" style="font-size:0.9rem;color:#555;display:block;"><?php echo $job['company']; ?></span>
                    <span class="location" style="font-size:0.85rem;color:#888;display:flex;align-items:center;gap:6px;margin-top:4px;">
                        <i class="fas fa-map-marker-alt"></i> <?php echo $job['location']; ?>
                    </span>
                </div>
                <span class="job-tag" style="background:#f3b33d;color:#0d1b2a;padding:6px 18px;border-radius:50px;font-weight:700;font-size:0.75rem;">
                    جديد
                </span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<!-- ===== التبويب 2: الوظائف ===== -->
<div class="tab-content" id="tab-jobs">
    <section class="jobs-filter">
        <form method="GET" action="" style="display:flex;flex-wrap:wrap;gap:14px;width:100%;">
            <input type="text" name="search" placeholder="🔍 ابحث عن وظيفة..." value="<?php echo htmlspecialchars($search); ?>" />
            <select name="location">
                <option value="">جميع الولايات</option>
                <?php
                $locations = array_unique(array_column($jobs, 'location'));
                foreach ($locations as $loc):
                ?>
                <option value="<?php echo $loc; ?>" <?php echo ($location == $loc) ? 'selected' : ''; ?>><?php echo $loc; ?></option>
                <?php endforeach; ?>
            </select>
            <select name="category">
                <option value="">جميع التخصصات</option>
                <?php
                $categories = array_unique(array_column($jobs, 'category'));
                foreach ($categories as $cat):
                ?>
                <option value="<?php echo $cat; ?>" <?php echo ($category == $cat) ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" style="padding:12px 30px;background:#f3b33d;border:none;border-radius:50px;font-weight:700;cursor:pointer;font-family:'Cairo',sans-serif;">فلترة</button>
        </form>
    </section>

    <section class="jobs-grid">
        <?php if (count($filteredJobs) == 0): ?>
            <p style="grid-column:1/-1;text-align:center;padding:40px;color:#888;font-size:1.2rem;">😅 لا توجد وظائف مطابقة للبحث</p>
        <?php else: ?>
            <?php foreach ($filteredJobs as $job): ?>
            <div class="job-card">
                <h3><?php echo $job['title']; ?></h3>
                <p class="company-name"><i class="fas fa-building"></i> <?php echo $job['company']; ?></p>
                <span class="job-location"><i class="fas fa-map-marker-alt"></i> <?php echo $job['location']; ?></span>
                <p class="job-desc"><?php echo $job['desc']; ?></p>
                <button class="apply-btn" onclick="alert('✅ تم التقديم على وظيفة <?php echo $job['title']; ?> بنجاح!')">تقديم الآن</button>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>

<!-- ===== التبويب 3: الإحصائيات ===== -->
<div class="tab-content" id="tab-stats">
    <section class="stats-page">
        <h1>📊 تحليلات سوق العمل في السودان</h1>
        <div class="chart-grid">
            <div class="chart-box">
                <h3>الوظائف حسب الولاية</h3>
                <canvas id="stateChart"></canvas>
            </div>
            <div class="chart-box">
                <h3>التوزيع حسب القطاع</h3>
                <canvas id="sectorChart"></canvas>
            </div>
            <div class="chart-box">
                <h3>نمو الوظائف (آخر 6 أشهر)</h3>
                <canvas id="growthChart"></canvas>
            </div>
            <div class="chart-box">
                <h3>نسبة البطالة (تقديري)</h3>
                <canvas id="unemploymentChart"></canvas>
            </div>
        </div>
    </section>
</div>

<!-- ===== التبويب 4: عن الموقع ===== -->
<div class="tab-content" id="tab-about">
    <section class="about-page">
        <div class="about-card">
            <div class="about-icon">🇸🇩</div>
            <h1>عن خريطة الوظائف التفاعلية</h1>
            <p>منصة تهدف إلى ربط الباحثين عن العمل بفرصهم في جميع ولايات السودان.</p>
            <div class="highlight">
                <p><strong>✨ المميزات:</strong></p>
                <ul>
                    <li>🗺️ <strong>خريطة تفاعلية</strong> تعرض الوظائف حسب الولاية</li>
                    <li>🔍 <strong>بحث متقدم</strong> بتخصصات متعددة</li>
                    <li>📊 <strong>إحصائيات</strong> وتحليلات سوق العمل</li>
                    <li>📱 <strong>تصميم متجاوب</strong> مع جميع الأجهزة</li>
                    <li>🌐 <strong>مفتوح المصدر</strong> ويدعم التوسع</li>
                    <li>⚡ <strong>مدعوم بـ PHP</strong> مع فلترة ديناميكية</li>
                </ul>
            </div>
            <p style="margin-top:24px;color:#888;font-size:0.9rem;">
                تم التطوير باستخدام <strong>PHP + HTML + CSS + JavaScript</strong> مع <strong>Leaflet.js</strong> و <strong>Chart.js</strong>
            </p>
        </div>
    </section>
</div>

<!-- ===== تذييل ===== -->
<footer class="footer">
    <p>© 2026 وظائف السودان - جميع الحقوق محفوظة</p>
    <div class="footer-social">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
    </div>
</footer>

<!-- ===== مكتبات خارجية ===== -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ============================================
// 1. نظام التبويبات
// ============================================
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    const target = document.getElementById('tab-' + tabName);
    if (target) target.classList.add('active');
    
    document.querySelectorAll('.nav-links a').forEach(el => el.classList.remove('active'));
    const links = document.querySelectorAll('.nav-links a');
    const tabMap = { 'home': 0, 'jobs': 1, 'stats': 2, 'about': 3 };
    if (tabMap[tabName] !== undefined) links[tabMap[tabName]].classList.add('active');
    
    if (tabName === 'home') setTimeout(initMap, 300);
    if (tabName === 'stats') setTimeout(initCharts, 300);
    document.getElementById('navLinks').classList.remove('open');
}

// ============================================
// 2. القائمة المتنقلة
// ============================================
document.getElementById('navToggle').addEventListener('click', function() {
    document.getElementById('navLinks').classList.toggle('open');
});

// ============================================
// 3. الخريطة
// ============================================
let mapInstance = null;

function initMap() {
    if (mapInstance) return;
    mapInstance = L.map('map').setView([15.5, 32.0], 6);
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap, CartoDB',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(mapInstance);
    
    const states = [
        { name: 'الخرطوم', lat: 15.5007, lng: 32.5599, jobs: 320 },
        { name: 'أم درمان', lat: 15.6443, lng: 32.4777, jobs: 210 },
        { name: 'بورتسودان', lat: 19.6158, lng: 37.2166, jobs: 85 },
        { name: 'نيالا', lat: 12.0490, lng: 24.8990, jobs: 73 },
        { name: 'كسلا', lat: 15.4524, lng: 36.3950, jobs: 62 },
    ];
    
    const customIcon = L.divIcon({
        html: '<i class="fas fa-map-pin" style="color:#f3b33d;font-size:32px;text-shadow:0 2px 8px rgba(0,0,0,0.3);"></i>',
        className: 'custom-marker',
        iconSize: [32, 32],
        iconAnchor: [16, 32]
    });
    
    states.forEach(state => {
        L.marker([state.lat, state.lng], { icon: customIcon })
            .addTo(mapInstance)
            .bindPopup(`
                <div style="font-family:'Cairo',sans-serif;text-align:center;padding:8px;">
                    <h3 style="color:#1a3a5c;margin:0 0 6px;">${state.name}</h3>
                    <p style="margin:0;color:#666;">
                        <i class="fas fa-briefcase" style="color:#f3b33d;"></i> 
                        <strong>${state.jobs}</strong> وظيفة
                    </p>
                    <button onclick="switchTab('jobs')" 
                        style="margin-top:10px;background:#f3b33d;border:none;padding:6px 18px;border-radius:50px;font-weight:700;cursor:pointer;font-family:'Cairo',sans-serif;">
                        عرض الوظائف
                    </button>
                </div>
            `);
    });
    
    setTimeout(() => mapInstance.invalidateSize(), 500);
}

// ============================================
// 4. الرسوم البيانية
// ============================================
let chartsInitialized = false;

function initCharts() {
    if (chartsInitialized) return;
    chartsInitialized = true;
    
    new Chart(document.getElementById('stateChart'), {
        type: 'bar',
        data: {
            labels: ['الخرطوم', 'أم درمان', 'بورتسودان', 'نيالا', 'كسلا'],
            datasets: [{
                label: 'عدد الوظائف',
                data: [320, 210, 85, 73, 62],
                backgroundColor: ['#f3b33d', '#e8a87c', '#41b3a3', '#2a5a8c', '#1a3a5c'],
                borderRadius: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
    
    new Chart(document.getElementById('sectorChart'), {
        type: 'doughnut',
        data: {
            labels: ['تكنولوجيا', 'هندسة', 'مالية', 'طبية', 'تعليم'],
            datasets: [{
                data: [28, 22, 18, 14, 10],
                backgroundColor: ['#f3b33d', '#e8a87c', '#41b3a3', '#2a5a8c', '#1a3a5c']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
            datasets: [{
                label: 'عدد الوظائف الجديدة',
                data: [45, 52, 60, 48, 72, 88],
                borderColor: '#f3b33d',
                backgroundColor: 'rgba(243,179,61,0.1)',
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#f3b33d',
                pointRadius: 5
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
    
    new Chart(document.getElementById('unemploymentChart'), {
        type: 'bar',
        data: {
            labels: ['2021', '2022', '2023', '2024', '2025', '2026'],
            datasets: [{
                label: 'نسبة البطالة %',
                data: [21, 23, 24, 22, 20, 18],
                backgroundColor: ['#1a3a5c', '#2a5a8c', '#41b3a3', '#e8a87c', '#f3b33d', '#d4a373'],
                borderRadius: 8
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
}

// ============================================
// 5. تحميل الصفحة
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(initMap, 500);
});

console.log('🇸🇩 خريطة الوظائف - السودان (PHP + HTML)');
console.log('تم التحميل بنجاح ✅');
</script>

</body>
</html>
