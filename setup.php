<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$errors = [];
$adminExists = (int) db()->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn() > 0;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if ($adminExists) {
        $errors[] = 'An administrator already exists. Delete this setup file.';
    } else {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $password = (string) ($_POST['password'] ?? '');
        if ($name === '' || mb_strlen($name) > 120) $errors[] = 'Enter a name (up to 120 characters).';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
        if (strlen($password) < 10) $errors[] = 'Password must be at least 10 characters.';
        if ($errors === []) {
            $pdo = db();
            $pdo->beginTransaction();
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, "admin")');
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            $pdo->exec(
                "INSERT IGNORE INTO services (name, description, price) VALUES
                ('Oil change', 'Engine oil and filter replacement.', 59.00),
                ('Brake inspection', 'Brake system inspection and safety check.', 39.00),
                ('Full service', 'Comprehensive vehicle inspection and routine maintenance.', 149.00)"
            );
            $pdo->commit();
            flash('success', 'Administrator created. Sign in, then remove setup.php.');
            redirect('/vehicle_service/login.php');
        }
    }
}

page_header('Initial setup');
?>
<section class="form-card narrow">
    <p class="eyebrow">FIRST-RUN SETUP</p><h1>Create administrator</h1>
    <?php if ($adminExists): ?>
        <div class="alert alert-error">An administrator already exists. Remove <code>setup.php</code> from the project folder.</div>
    <?php else: ?>
        <p>Use this one-time form to create your system administrator.</p>
        <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endforeach; ?>
        <form method="post">
            <?= csrf_field() ?>
            <label>Name<input name="name" maxlength="120" required></label>
            <label>Email<input name="email" type="email" maxlength="190" required></label>
            <label>Password<input name="password" type="password" minlength="10" required autocomplete="new-password"></label>
            <button class="button" type="submit">Create admin</button>
        </form>
    <?php endif; ?>
</section>
<?php page_footer(); ?>
