<?php
/**
 * @var array $modules
 * @var array $stats
 */
?>

<!-- HERO -->
<section class="hero">
    <div class="container">
        <div class="hero-inner">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="badge-dot"></span>
                    AI yordamida o'qitish platformasi
                </div>
                <h1 class="hero-title">
                    Kompozitsion<br>
                    <span class="hero-accent">fikrlashni</span> rivojlantir
                </h1>
                <p class="hero-sub">
                    Analitik, tanqidiy va ijodiy fikrlash ko'nikmalarini
                    interaktiv mashqlar orqali mustahkamlang.
                    Bloom taksonomiyasi asosida AI baholash.
                </p>
                <div class="hero-actions">
                    <?php if (\App\Core\Auth::check()): ?>
                        <a href="/dashboard" class="btn btn-primary btn-lg">Davom etish →</a>
                    <?php else: ?>
                        <a href="/register"  class="btn btn-primary btn-lg">Boshlash →</a>
                        <a href="/login"     class="btn btn-ghost btn-lg">Kirish</a>
                    <?php endif; ?>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="stat-num"><?= (int)($stats['modules_count'] ?? 8) ?></span>
                        <span class="stat-lbl">O'quv moduli</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-num"><?= (int)($stats['exercises_count'] ?? 16) ?>+</span>
                        <span class="stat-lbl">Amaliy mashq</span>
                    </div>
                    <div class="hero-stat">
                        <span class="stat-num"><?= (int)($stats['students_count'] ?? 0) ?></span>
                        <span class="stat-lbl">Faol talaba</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- MODULLAR -->
<section class="section" id="modules">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Amaliy mashg'ulotlar</span>
            <h2 class="section-title">8 ta ko'nikma moduli</h2>
            <p class="section-sub">Har bir modul 2 ta mashqdan iborat va Bloom taksonomiyasi bo'yicha baholanadi</p>
        </div>

        <div class="modules-grid">
            <?php foreach ($modules as $module): ?>
                <?php include __DIR__ . '/../partials/module-card.php'; ?>
            <?php endforeach; ?>
        </div>

        <?php if (!\App\Core\Auth::check()): ?>
            <div class="section-cta">
                <a href="/register" class="btn btn-primary">Barcha modullarni ko'rish →</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- QANDAY ISHLAYDI -->
<section class="section section--alt" id="about">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Jarayon</span>
            <h2 class="section-title">Qanday ishlaydi?</h2>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">01</div>
                <h3 class="step-title">Modul tanlang</h3>
                <p class="step-desc">8 ta ko'nikma modulidan birini tanlang va mashqni o'qing</p>
            </div>
            <div class="step-card">
                <div class="step-num">02</div>
                <h3 class="step-title">Mashqni bajaring</h3>
                <p class="step-desc">Savolga javob yozing. Kerak bo'lsa AI dan yo'nalish so'rang</p>
            </div>
            <div class="step-card">
                <div class="step-num">03</div>
                <h3 class="step-title">AI baholaydi</h3>
                <p class="step-desc">Javobingiz Bloom taksonomiyasi bo'yicha avtomatik baholanadi</p>
            </div>
            <div class="step-card">
                <div class="step-num">04</div>
                <h3 class="step-title">Taraqqiyotni kuzating</h3>
                <p class="step-desc">Dashboard da har bir ko'nikma bo'yicha rivojlanishingizni koring</p>
            </div>
        </div>
    </div>
</section>
