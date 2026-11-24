<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$featuredServices = db()->query(
    'SELECT name, description, price FROM services WHERE active = 1 ORDER BY price LIMIT 3'
)->fetchAll();
$serviceImages = [
    'Oil & filter change' => 'service-oil.svg',
    'Brake inspection' => 'service-brakes.svg',
    'Tire rotation & balance' => 'service-tires.svg',
    'Battery health check' => 'service-battery.svg',
    'A/C refresh' => 'service-ac.svg',
    'Full vehicle service' => 'service-full.svg',
];

page_header('Vehicle servicing made simple');
?>
<section class="hero">
    <div class="hero-copy">
        <p class="eyebrow"><span class="eyebrow-dot"></span> YOUR LOCAL GARAGE, MADE SIMPLE</p>
        <h1>Good care for every mile ahead.</h1>
        <p>Book trusted vehicle care in minutes. See your appointment, follow the work, and keep every service in one place.</p>
        <div class="actions">
            <a class="button" href="/vehicle_service/register.php">Book your first service <span aria-hidden="true">&rarr;</span></a>
            <a class="button button-secondary" href="/vehicle_service/services.php">Explore services</a>
        </div>
        <div class="hero-note"><span class="check-mark" aria-hidden="true">&#10003;</span> Clear pricing. Skilled mechanics. No guesswork.</div>
    </div>
    <div class="hero-visual">
        <img src="/vehicle_service/assets/images/hero-garage.svg" alt="Illustration of a car ready for service in a modern garage">
        <div class="visual-caption"><span class="caption-icon" aria-hidden="true">&#10003;</span><span><strong>Care you can count on</strong><small>From booking to back on the road</small></span></div>
    </div>
</section>

<section class="trust-strip" aria-label="Our service promise">
    <div><span class="trust-icon" aria-hidden="true">&#9673;</span><span><strong>Easy online booking</strong><small>Pick a time that suits you</small></span></div>
    <div><span class="trust-icon" aria-hidden="true">&#10003;</span><span><strong>Updates at every step</strong><small>Know what is happening</small></span></div>
    <div><span class="trust-icon" aria-hidden="true">&#9733;</span><span><strong>All your records in one place</strong><small>Service history, made simple</small></span></div>
</section>

<section class="section-block">
    <div class="section-title">
        <div><p class="eyebrow">A LITTLE LOVE FOR YOUR VEHICLE</p><h2>Popular services</h2></div>
        <a class="text-link" href="/vehicle_service/services.php">View all services <span aria-hidden="true">&rarr;</span></a>
    </div>
    <div class="grid three service-grid">
        <?php foreach ($featuredServices as $service): ?>
            <?php $image = $serviceImages[$service['name']] ?? 'service-full.svg'; ?>
            <article class="card service-card">
                <img class="service-image" src="/vehicle_service/assets/images/<?= e($image) ?>" alt="" loading="lazy">
                <div class="service-card-content">
                    <h3><?= e($service['name']) ?></h3>
                    <p><?= e($service['description']) ?></p>
                    <strong class="price">From $<?= number_format((float) $service['price'], 2) ?></strong>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<section class="bottom-cta">
    <div><p class="eyebrow">READY WHEN YOU ARE</p><h2>Your next service starts here.</h2><p>Set up your account and book a time that works for you.</p></div>
    <a class="button button-light" href="/vehicle_service/register.php">Get started <span aria-hidden="true">&rarr;</span></a>
</section>
<?php page_footer(); ?>
