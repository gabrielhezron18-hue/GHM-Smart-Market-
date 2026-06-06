<?php
require_once 'auth.php';
requireLogin();
require_once 'db.php';

$availableProducts = $pdo->query('SELECT name FROM products ORDER BY name ASC')->fetchAll();
$productNames = array_column($availableProducts, 'name');
$paymentMethods = ['M-Pesa', 'Tigo Pesa', 'Airtel Money', 'Bank', 'Cash on Delivery'];

$selectedProduct = $_GET['product'] ?? ($_POST['product'] ?? '');
if (!in_array($selectedProduct, $productNames, true)) {
    $selectedProduct = $productNames[0] ?? '';
}

$errors = [];
$success = false;
$name = '';
$phone = '';
$location = '';
$payment = '';
$paymentReference = '';
$orderId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['customer_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $payment = trim($_POST['payment'] ?? '');
    $paymentReference = trim($_POST['payment_reference'] ?? '');

    if ($name === '') {
        $errors[] = 'Tafadhali jaza majina yako.';
    }

    if ($phone === '') {
        $errors[] = 'Tafadhali jaza namba ya simu.';
    }

    if ($location === '') {
        $errors[] = 'Tafadhali jaza location yako.';
    }

    if (!in_array($payment, $paymentMethods, true)) {
        $errors[] = 'Tafadhali chagua njia ya malipo.';
    }

    if (count($errors) === 0) {
        $statement = $pdo->prepare(
            'INSERT INTO orders (product, customer_name, phone, location, payment_method, payment_reference)
             VALUES (:product, :customer_name, :phone, :location, :payment_method, :payment_reference)'
        );

        $statement->execute([
            ':product' => $selectedProduct,
            ':customer_name' => $name,
            ':phone' => $phone,
            ':location' => $location,
            ':payment_method' => $payment,
            ':payment_reference' => $paymentReference !== '' ? $paymentReference : null,
        ]);

        $orderId = $pdo->lastInsertId();
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Market | Agiza Bidhaa</title>
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
        <a href="products.php#products">Bidhaa</a>
        <a href="products.php#about">Kuhusu</a>
        <a href="products.php#contact">Mawasiliano</a>
      </nav>
      <a class="logout-button" href="logout.php">Logout</a>
    </header>

    <section class="order-page">
      <?php if ($success): ?>
        <div class="success-card">
          <p class="eyebrow">Order imepokelewa</p>
          <h1>Asante, <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>.</h1>
          <p>
            Tumepokea oda yako ya <strong><?php echo htmlspecialchars($selectedProduct, ENT_QUOTES, 'UTF-8'); ?></strong>.
            Namba ya oda yako ni <strong>#<?php echo htmlspecialchars((string) $orderId, ENT_QUOTES, 'UTF-8'); ?></strong>.
            Mzigo wako utaletwa <?php echo htmlspecialchars($location, ENT_QUOTES, 'UTF-8'); ?> baada ya siku chache.
            Tutakupigia kupitia namba <?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?> kuthibitisha malipo ya
            <?php echo htmlspecialchars($payment, ENT_QUOTES, 'UTF-8'); ?> na muda wa kufikisha mzigo.
          </p>
          <p class="comfort-text">
            Usijali, oda yako ipo salama. Timu yetu itakuhudumia kwa makini mpaka mzigo ukufikie.
          </p>
          <a class="primary-button" href="products.php">Rudi kwenye bidhaa</a>
          <a class="text-button link-button" href="track.php?order_id=<?php echo urlencode((string) $orderId); ?>&phone=<?php echo urlencode($phone); ?>">Track order yako</a>
          <?php if (isAdmin()): ?>
            <a class="text-button link-button" href="orders.php">Angalia orders zote</a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <form class="order-form" method="post" action="order.php">
          <div class="form-header">
            <p class="eyebrow">Agiza bidhaa</p>
            <h1>Jaza taarifa zako</h1>
            <p>Unakaribia kuagiza <strong><?php echo htmlspecialchars($selectedProduct, ENT_QUOTES, 'UTF-8'); ?></strong>.</p>
          </div>

          <?php if (count($errors) > 0): ?>
            <div class="alert-error">
              <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <label for="product">Bidhaa</label>
          <select id="product" name="product" required>
            <?php foreach ($productNames as $product): ?>
              <option value="<?php echo htmlspecialchars($product, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selectedProduct === $product ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($product, ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>

          <label for="customer_name">Majina yako</label>
          <input
            type="text"
            id="customer_name"
            name="customer_name"
            placeholder="Mfano: Asha Juma"
            value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"
            required
          >

          <label for="phone">Namba ya simu</label>
          <input
            type="text"
            id="phone"
            name="phone"
            placeholder="Mfano: 0712 345 678"
            value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>"
            required
          >

          <label for="location">Location ulipo</label>
          <input
            type="text"
            id="location"
            name="location"
            placeholder="Mfano: Dar es Salaam, Kariakoo"
            value="<?php echo htmlspecialchars($location, ENT_QUOTES, 'UTF-8'); ?>"
            required
          >

          <label for="payment">Utalipiaje mzigo?</label>
          <select id="payment" name="payment" required>
            <option value="">Chagua njia ya malipo</option>
            <?php foreach ($paymentMethods as $method): ?>
              <option value="<?php echo htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $payment === $method ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); ?>
              </option>
            <?php endforeach; ?>
          </select>

          <label for="payment_reference">Namba ya muamala au kumbukumbu ya malipo</label>
          <input
            type="text"
            id="payment_reference"
            name="payment_reference"
            placeholder="Mfano: MP25052712345"
            value="<?php echo htmlspecialchars($paymentReference, ENT_QUOTES, 'UTF-8'); ?>"
          >

          <button class="primary-button" type="submit">Tuma oda</button>
          <a class="text-button link-button" href="products.php">Rudi kwenye bidhaa</a>
        </form>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
