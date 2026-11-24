<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';

$errors = [];
$name = $email = $phone = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = strtolower(trim((string) ($_POST['email'] ?? '')));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($name === '' || mb_strlen($name) > 120) $errors[] = 'Enter your name (up to 120 characters).';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190) $errors[] = 'Enter a valid email address.';
    if (mb_strlen($phone) > 30) $errors[] = 'Phone number must be 30 characters or fewer.';
    if (strlen($password) < 10) $errors[] = 'Password must be at least 10 characters.';

    if ($errors === []) {
        try {
            $stmt = db()->prepare('INSERT INTO users (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, "customer")');
            $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT)]);
            flash('success', 'Account created. You can now sign in.');
            redirect('/vehicle_service/login.php');
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') {
                $errors[] = 'That email address is already registered.';
            } else {
                throw $exception;
            }
        }
    }
}

page_header('Create account');
?>
<section class="form-card narrow">
    <p class="eyebrow">CUSTOMER ACCOUNT</p><h1>Create an account</h1>
    <?php foreach ($errors as $error): ?><div class="alert alert-error"><?= e($error) ?></div><?php endforeach; ?>
    <form method="post">
        <?= csrf_field() ?>
        <label>Full name<input name="name" maxlength="120" required value="<?= e($name) ?>"></label>
        <label>Email<input name="email" type="email" maxlength="190" required value="<?= e($email) ?>"></label>
        <label>Phone<input name="phone" maxlength="30" value="<?= e($phone) ?>"></label>
        <label>Password<input name="password" type="password" minlength="10" required autocomplete="new-password"></label>
        <button class="button" type="submit">Register</button>
    </form>
</section>
<?php page_footer(); ?>
