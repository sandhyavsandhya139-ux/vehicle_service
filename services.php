<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$services = db()->query('SELECT name, description, price FROM services WHERE active = 1 ORDER BY name')->fetchAll();
$serviceImages = [
    'Oil & filter change' => 'service-oil.svg',
    'Brake inspection' => 'service-brakes.svg',
    'Tire rotation & balance' => 'service-tires.svg',
    'Battery health check' => 'service-battery.svg',
    'A/C refresh' => 'service-ac.svg',
    'Full vehicle service' => 'service-full.svg',
];
page_header('Services');
?>
<div class="services-intro">
    <div class="page-heading"><p class="eyebrow">THOUGHTFUL CARE, RIGHT ON TIME</p><h1>Find the right care for your car.</h1><p>From routine tune-ups to the little fixes that keep you moving, choose a service and we’ll take it from here.</p></div>
    <img src="/vehicle_service/assets/images/service-full.svg" alt="Illustration of a vehicle being checked by a mechanic">
</div>
<div class="grid three service-grid">
    <?php foreach ($services as $service): ?>
        <?php $image = $serviceImages[$service['name']] ?? 'service-full.svg'; ?>
        <article class="card service-card">
            <img class="service-image" src="/vehicle_service/assets/images/<?= e($image) ?>" alt="" loading="lazy">
            <div class="service-card-content">
                <p class="service-kicker">VEHICLE CARE</p>
                <h2><?= e($service['name']) ?></h2>
                <p><?= e($service['description']) ?></p>
                <div class="service-card-bottom"><strong class="price">From $<?= number_format((float) $service['price'], 2) ?></strong><a class="service-book-link" href="/vehicle_service/register.php" aria-label="Book <?= e($service['name']) ?>">Book <span aria-hidden="true">&rarr;</span></a></div>
            </div>
        </article>
    <?php endforeach; ?>
    <?php if ($services === []): ?><div class="empty-state">Service options will appear here once they are added by an administrator.</div><?php endif; ?>
</div>
<?php page_footer(); ?>
