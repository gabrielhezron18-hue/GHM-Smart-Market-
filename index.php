<?php
require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Market | Karibu</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main>
    <section class="welcome-page" aria-label="Welcome page">
      <header class="top-bar">
        <a class="logo" href="index.php">
          <span>SM</span>
          Smart Market
        </a>
      </header>

      <div class="welcome-content">
        <p class="eyebrow">Karibu Smart Market</p>
        <h1>Karibu kwenye website yetu ya biashara.</h1>
        <p>
          Tunauza bidhaa bora kwa matumizi ya kila siku na shughuli za kitaalamu.
          Unaweza kupata viatu, microphone, stand, nguo, na bidhaa nyingine kwa bei nzuri.
        </p>
      </div>

      <div class="welcome-bottom">
        <a class="primary-button" href="<?php echo isLoggedIn() ? 'products.php' : 'login.php'; ?>">
          Endelea kuona bidhaa
        </a>
      </div>
    </section>
  </main>
</body>
</html>
