<?php
require_once 'auth.php';
requireLogin();

$products = [
    [
        'name' => 'Ringlight kwa video na picha',
        'description' => 'Ringlights zenye mwanga mkari kwa quality ya videos na pikchazi zako.',
        'price' => 'TSh 26,000+ to 79,000+',
        'image' => 'posta.zangu/lights.jpg',
        'alt' => 'Ringlights original',
    ],
    [
        'name' => 'Microphone',
        'description' => 'Microphone kwa studio, mikutano, matangazo, na content creation.',
        'price' => 'TSh 60,000+',
        'image' => 'posta.zangu/microphone s.jpg',
        'alt' => 'modern Microphone for quality sound recording',
    ],
    [
        'name' => 'Stand',
        'description' => 'Stand imara kwa microphone, simu, kamera, na vifaa vya ofisini.',
        'price' => 'TSh 35,000+',
        'image' => 'posta.zangu/stand.jpg',
        'alt' => 'light, maiki na stand',
    ],
    [
        'name' => 'musical instruments',
        'description' => 'quality sound and music production with modern and high quality instruments.',
        'price' => 'Bei nafuu sana',
        'image' => 'posta.zangu/instrumens.jpg',
        'alt' => 'musical instruments',
    ],
    [
        'name' => 'Posta',
        'description' => 'tangaza nasi matangazo ya biashara kwa njia ya posta, karibu sana GHM SmartMarket.',
        'price' => 'TSh 15,000+',
        'image' => 'posta.zangu/Gii.jpg',
        'alt' => 'Graphics designing',
    ],
];
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GHM Smart Market | Bidhaa</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main class="products-page">
    <header class="site-header">
      <a class="logo" href="index.php">
        <span>GHM</span>
        Smart Market
      </a>
      <nav aria-label="Main navigation">
        <a href="#products">Bidhaa</a>
        <a href="#about">Kuhusu</a>
        <a href="#contact">Mawasiliano</a>
        <a href="#books">Movies and games</a>
      </nav>
      <a class="logout-button" href="logout.php">Logout</a>
    </header>

    <section class="products-hero">
      <div>
        <p class="eyebrow">GHM Smart Market Ltd</p>
        <h1>Karibu, <?php echo displayName($_SESSION['user']); ?>.</h1>
        <p>Chagua na order bidhaa unayohitaji</p>
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
              alt="<?php echo htmlspecialchars($product['alt'], ENT_QUOTES, 'UTF-8'); ?>"
            >
            <div>
              <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
              <p><?php echo htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8'); ?></p>
              <strong><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?></strong>
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
        GHM Smart Market inalenga kurahisisha manunuzi kwa wateja kwa kuonyesha bidhaa muhimu
        sehemu moja na kuweka mawasiliano wazi kwa oda na maswali, the chance to mordenize your living standard.
      </p>
    </section>

    <footer class="site-footer" id="contact">
      <p>GHM Smart Market</p>
      <span>Email: gabrielhezron18@gmail.co.tz | Simu: +255 749 516 658</span>
    </footer>
  </main>
</body>
</html>
