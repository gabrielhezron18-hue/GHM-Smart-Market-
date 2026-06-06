<?php
require_once 'auth.php';
requireLogin();
require_once 'db.php';

$products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Market | Bidhaa</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main class="products-page">
    <header class="site-header">
      <a class="logo" href="index.php">
        <span>SM</span>
        Smart Market
      </a>
      <nav aria-label="Main navigation">
        <a href="#products">Bidhaa</a>
        <a href="track.php">Track Order</a>
        <?php if (isAdmin()): ?>
          <a href="orders.php">Orders</a>
          <a href="admin.php">Admin</a>
        <?php endif; ?>
        <a href="#about">Kuhusu</a>
        <a href="#contact">Mawasiliano</a>
      </nav>
      <a class="logout-button" href="logout.php">Logout</a>
    </header>

    <section class="products-hero">
      <div>
        <p class="eyebrow">Bidhaa za Kampuni</p>
        <h1>Karibu, <?php echo displayName($_SESSION['user_id']); ?>.</h1>
        <p>Chagua bidhaa unayohitaji kutoka kwenye orodha yetu ya viatu, vifaa vya sauti, stand, na nguo.</p>
      </div>
    </section>

    <section class="content-section" id="products">
      <div class="section-heading">
        <p class="eyebrow">Tunachouza</p>
        <h2>Bidhaa zinazopatikana</h2>
      </div>

      <div class="product-grid">
        <?php foreach ($products as $product): ?>
          <article class="product-card">
            <img
              src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>"
              alt="<?php echo htmlspecialchars($product['alt_text'], ENT_QUOTES, 'UTF-8'); ?>"
            >
            <div>
              <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
              <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
              <strong><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?></strong>
              <a class="order-button" href="order.php?product=<?php echo urlencode($product['name']); ?>">
                Agiza sasa
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="about-section" id="about">
      <div>
        <p class="eyebrow">Kuhusu sisi</p>
        <h2>Tunatoa bidhaa bora kwa huduma ya haraka.</h2>
      </div>
      <p>
        Smart Market inalenga kurahisisha manunuzi kwa wateja kwa kuonyesha bidhaa muhimu
        sehemu moja na kuweka mawasiliano wazi kwa oda na maswali.
      </p>
    </section>

    <footer class="site-footer" id="contact">
      <p>Smart Market</p>
      <span>Email: gabrielhezron18@gmail.com | Simu: +255 749 516 658/ +255 781 143 615</span>
    </footer>
  </main>
</body>
</html>
