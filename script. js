// ===== تهيئة الخريطة =====
const map = L.map('map').setView([15.5, 32.0], 6);

// طبقة الخريطة الأساسية
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; CartoDB',
    subdomains: 'abcd',
    maxZoom: 19
}).addTo(map);

// ===== بيانات الولايات (محاكاة) =====
const states = [
    { name: 'الخرطوم', lat: 15.5007, lng: 32.5599, jobs: 320 },
    { name: 'أم درمان', lat: 15.6443, lng: 32.4777, jobs: 210 },
    { name: 'بورتسودان', lat: 19.6158, lng: 37.2166, jobs: 85 },
    { name: 'كسلا', lat: 15.4524, lng: 36.3950, jobs: 62 },
    { name: 'الأبيض', lat: 13.1667, lng: 30.2167, jobs: 48 },
    { name: 'نيالا', lat: 12.0490, lng: 24.8990, jobs: 73 },
    { name: 'الفاشر', lat: 13.6260, lng: 25.3490, jobs: 41 },
    { name: 'جنوب كردفان', lat: 11.1990, lng: 29.4170, jobs: 35 },
    { name: 'سنار', lat: 13.5490, lng: 33.6020, jobs: 54 },
    { name: 'النيل الأزرق', lat: 11.2740, lng: 34.0760, jobs: 29 },
    { name: 'نهر النيل', lat: 19.6450, lng: 33.4370, jobs: 38 },
    { name: 'البحر الأحمر', lat: 19.5660, lng: 36.2000, jobs: 22 },
];

// ===== إضافة علامات على الخريطة =====
const customIcon = L.divIcon({
    html: '<i class="fas fa-map-pin" style="color:#f3b33d;font-size:32px;text-shadow:0 2px 8px rgba(0,0,0,0.3);"></i>',
    className: 'custom-marker',
    iconSize: [32, 32],
    iconAnchor: [16, 32]
});

states.forEach(state => {
    const marker = L.marker([state.lat, state.lng], { icon: customIcon })
        .addTo(map)
        .bindPopup(`
            <div style="font-family:'Cairo',sans-serif;text-align:center;padding:8px;">
                <h3 style="color:#1a3a5c;margin:0 0 6px;">${state.name}</h3>
                <p style="margin:0;color:#666;">
                    <i class="fas fa-briefcase" style="color:#f3b33d;"></i> 
                    <strong>${state.jobs}</strong> وظيفة
                </p>
                <button onclick="alert('عرض وظائف ${state.name}')" 
                    style="margin-top:10px;background:#f3b33d;border:none;padding:6px 18px;border-radius:50px;font-weight:700;cursor:pointer;font-family:'Cairo',sans-serif;">
                    عرض الوظائف
                </button>
            </div>
        `);
});

// ===== وظيفة البحث =====
function searchJob() {
    const query = document.getElementById('searchInput').value.trim();
    if (query) {
        alert(`🔍 جاري البحث عن: "${query}"\nسيتم توجيهك إلى صفحة النتائج.`);
        // يمكن تحويلها لانتقال فعلي
        // window.location.href = `jobs.html?search=${encodeURIComponent(query)}`;
    }
}

// ===== القائمة المتنقلة =====
document.getElementById('navToggle').addEventListener('click', function() {
    document.querySelector('.nav-links').classList.toggle('open');
});

// ===== إغلاق القائمة عند النقر على رابط =====
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', () => {
        document.querySelector('.nav-links').classList.remove('open');
    });
});

// ===== تأثير التمرير للبطاقات =====
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.stat-card, .job-card, .chart-box').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'all 0.6s ease';
    observer.observe(el);
});

console.log('🇸🇩 خريطة الوظائف التفاعلية - السودان');
console.log('تم التحميل بنجاح ✅');
