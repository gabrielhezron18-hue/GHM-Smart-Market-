<?php
require_once 'auth.php';

if (isLoggedIn()) {
    header('Location: products.php');
    exit;
}

$username = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Tafadhali jaza username na password.';
    } elseif (isset($validUsers[$username]) && $validUsers[$username] === $password) {
        $_SESSION['user'] = $username;
        header('Location: products.php');
        exit;
    } else {
        $error = 'Username au password si sahihi. Jaribu admin / 123456.';
    }
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Market | Login</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main>
    <section class="login-page" aria-label="Login page">
      <div class="login-shell">
        <div class="brand-panel">
          <div class="brand-mark">SM</div>
          <p class="eyebrow">Login</p>
          <h2>Ingia kwanza ili uone bidhaa za kampuni.</h2>
          <p>Weka taarifa zako kuendelea kwenye ukurasa wa bidhaa.</p>
        </div>

        <form class="login-card" method="post" action="login.php" novalidate>
          <div class="form-header">
            <h2>Login Page</h2>
            <p>Tumia username na password kuingia.</p>
          </div>

          <?php if ($error !== ''): ?>
            <p class="alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>

          <label for="username">Username</label>
          <input
            type="text"
            id="username"
            name="username"
            autocomplete="username"
            placeholder="mfano: admin"
            value="<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>"
            required
          >

          <label for="password">Password</label>
          <div class="password-wrap">
            <input
              type="password"
              id="password"
              name="password"
              autocomplete="current-password"
              placeholder="Weka password"
              required
            >
            <button type="button" id="togglePassword" aria-label="Onyesha au ficha password">Show</button>
          </div>

          <div class="form-row">
            <label class="remember">
              <input type="checkbox" name="remember">
              <span>Nikumbuke</span>
            </label>
            <a href="#">Umesahau password?</a>
          </div>

          <button class="primary-button" type="submit">Login</button>
          <a class="text-button link-button" href="index.php">Rudi welcome page</a>
          <p class="hint">Demo login: username <strong>admin</strong>, password <strong>123456</strong>.</p>
        </form>
      </div>
    </section>
  </main>

  <script src="script.js"></script>
</body>
</html>
