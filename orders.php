<?php
require_once 'auth.php';
requireAdmin();
require_once 'db.php';

$statuses = ['Pending', 'Confirmed', 'Delivered', 'Cancelled'];
$paymentStatuses = ['Unverified', 'Verified', 'Failed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    $paymentStatus = $_POST['payment_status'] ?? '';

    if ($orderId > 0 && in_array($status, $statuses, true) && in_array($paymentStatus, $paymentStatuses, true)) {
        $update = $pdo->prepare('UPDATE orders SET status = :status, payment_status = :payment_status WHERE id = :id');
        $update->execute([
            ':status' => $status,
            ':payment_status' => $paymentStatus,
            ':id' => $orderId,
        ]);
    }

    header('Location: orders.php');
    exit;
}

$statement = $pdo->query('SELECT * FROM orders ORDER BY created_at DESC');
$orders = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Market | Orders</title>
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
        <a href="orders.php">Orders</a>
        <a href="admin.php">Admin</a>
        <a href="products.php#contact">Mawasiliano</a>
      </nav>
      <a class="logout-button" href="logout.php">Logout</a>
    </header>

    <section class="content-section">
      <div class="section-heading">
        <p class="eyebrow">Admin</p>
        <h1>Orders za wateja</h1>
      </div>

      <?php if (count($orders) === 0): ?>
        <div class="empty-state">
          <h2>Hakuna order bado.</h2>
          <p>Order mpya zitakazoingizwa na wateja zitaonekana hapa.</p>
          <a class="primary-button" href="products.php">Rudi kwenye bidhaa</a>
        </div>
      <?php else: ?>
        <div class="orders-table">
          <div class="orders-row orders-head">
            <span>ID</span>
            <span>Bidhaa</span>
            <span>Majina</span>
            <span>Simu</span>
            <span>Location</span>
            <span>Malipo</span>
            <span>Payment</span>
            <span>Status</span>
            <span>Tarehe</span>
          </div>

          <?php foreach ($orders as $order): ?>
            <div class="orders-row">
              <span>#<?php echo htmlspecialchars((string) $order['id'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span><?php echo htmlspecialchars($order['product'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span><?php echo htmlspecialchars($order['customer_name'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span><?php echo htmlspecialchars($order['phone'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span><?php echo htmlspecialchars($order['location'], ENT_QUOTES, 'UTF-8'); ?></span>
              <span>
                <?php echo htmlspecialchars($order['payment_method'], ENT_QUOTES, 'UTF-8'); ?>
                <?php if (!empty($order['payment_reference'])): ?>
                  <small><?php echo htmlspecialchars($order['payment_reference'], ENT_QUOTES, 'UTF-8'); ?></small>
                <?php endif; ?>
              </span>
              <span>
                <form class="status-form" method="post" action="orders.php">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars((string) $order['id'], ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="status" value="<?php echo htmlspecialchars($order['status'], ENT_QUOTES, 'UTF-8'); ?>">
                  <select name="payment_status" aria-label="Payment status">
                    <?php foreach ($paymentStatuses as $paymentStatus): ?>
                      <option value="<?php echo htmlspecialchars($paymentStatus, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $order['payment_status'] === $paymentStatus ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($paymentStatus, ENT_QUOTES, 'UTF-8'); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit">Save</button>
                </form>
              </span>
              <span>
                <form class="status-form" method="post" action="orders.php">
                  <input type="hidden" name="order_id" value="<?php echo htmlspecialchars((string) $order['id'], ENT_QUOTES, 'UTF-8'); ?>">
                  <input type="hidden" name="payment_status" value="<?php echo htmlspecialchars($order['payment_status'], ENT_QUOTES, 'UTF-8'); ?>">
                  <select name="status" aria-label="Order status">
                    <?php foreach ($statuses as $status): ?>
                      <option value="<?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $order['status'] === $status ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <button type="submit">Save</button>
                </form>
              </span>
              <span><?php echo htmlspecialchars($order['created_at'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
