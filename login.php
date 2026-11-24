<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$error = '';
$email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $password = (string) ($_POST['password'] ?? '');
    $stmt = db()->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ? AND active = 1 LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        $destination = match ($user['role']) {
            'admin' => '/vehicle_service/admin/dashboard.php',
            'mechanic' => '/vehicle_service/mechanic/dashboard.php',
            default => '/vehicle_service/customer/dashboard.php',
        };
        redirect($destination);
    }
    $error = 'Email or password is incorrect.';
}

page_header('Sign in');
?>
<section class="form-card narrow">
    <p class="eyebrow">WELCOME BACK</p><h1>Sign in</h1>
    <?php if ($error !== ''): ?><div class="alert alert-error"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <?= csrf_field() ?>
        <label>Email<input name="email" type="email" required autocomplete="username" value="<?= e($email) ?>"></label>
        <label>Password<input name="password" type="password" required autocomplete="current-password"></label>
        <button class="button" type="submit">Sign in</button>
    </form>
    <p class="muted">New customer? <a href="/vehicle_service/register.php">Create an account</a></p>
</section>
<?php page_footer(); ?>
