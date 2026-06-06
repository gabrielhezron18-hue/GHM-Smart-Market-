<?php
require_once 'auth.php';
requireLogin();
require_once 'db.php';

$orderId = trim($_GET['order_id'] ?? ($_POST['order_id'] ?? ''));
$phone = trim($_GET['phone'] ?? ($_POST['phone'] ?? ''));
$order = null;
$error = '';

if ($orderId !== '' || $phone !== '') {
    if ($orderId === '' || $phone === '') {
        $error = 'Weka namba ya order na namba ya simu.';
    } else {
        $statement = $pdo->prepare('SELECT * FROM orders WHERE id = :id AND phone = :phone LIMIT 1');
        $statement->execute([
            ':id' => (int) $orderId,
            ':phone' => $phone,
        ]);
        $order = $statement->fetch();

        if (!$order) {
            $error = 'Hatukupata order kwa taarifa hizo. Hakikisha namba ya order na simu ni sahihi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GHM Smart Market | Track Order</title>
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
        <a href="products.php#products">Bidhaa</a>
        <a href="track.php">Track Order</a>
        <?php if (isAdmin()): ?>
          <a href="orders.php">Orders</a>
          <a href="admin.php">Admin</a>
        <?php endif; ?>
      </nav>
      <a class="logout-button" href="logout.php">Logout</a>
    </header>

    <section class="order-page">
      <div class="success-card">
        <p class="eyebrow">Track Order</p>
        <h1>Angalia mzigo wako</h1>

        <form class="track-form" method="post" action="track.php">
          <label for="order_id">Namba ya order</label>
          <input type="text" id="order_id" name="order_id" placeholder="Mfano: 12" value="<?php echo htmlspecialchars($orderId, ENT_QUOTES, 'UTF-8'); ?>" required>

          <label for="phone">Namba ya simu uliyotumia</label>
          <input type="text" id="phone" name="phone" placeholder="Mfano: 0712 345 678" value="<?php echo htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'); ?>" required>

          <button class="primary-button" type="submit">Angalia order</button>
        </form>

        <?php if ($error !== ''): ?>
          <p class="alert-error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>

        <?php if ($order): ?>
          <div class="tracking-card">
            <h2>Order #<?php echo htmlspecialchars((string) $order['id'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p><strong>Bidhaa:</strong> <?php echo htmlspecialchars($order['product'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($order['location'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Malipo:</strong> <?php echo htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="comfort-text">Mzigo wako bado upo kwenye uangalizi. Tutakujulisha kila hatua mpaka ukufikie.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>
</body>
</html>
