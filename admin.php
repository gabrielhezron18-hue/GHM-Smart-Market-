<?php
require_once 'auth.php';
requireAdmin();
require_once 'db.php';

$message = '';
$errors = [];
$editProduct = null;

function uploadProductImage(string $fieldName, array &$errors): ?string
{
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Picha imeshindikana ku-upload.';
        return null;
    }

    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    $mimeType = mime_content_type($_FILES[$fieldName]['tmp_name']);
    if (!isset($allowedTypes[$mimeType])) {
        $errors[] = 'Tumia picha ya JPG, PNG, WEBP, au GIF.';
        return null;
    }

    $uploadDir = __DIR__ . '/uploads';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    $fileName = uniqid('product_', true) . '.' . $allowedTypes[$mimeType];
    $targetPath = $uploadDir . '/' . $fileName;

    if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
        $errors[] = 'Picha imeshindikana kuhifadhiwa.';
        return null;
    }

    return 'uploads/' . $fileName;
}

if (isset($_GET['edit'])) {
    $statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $statement->execute([':id' => (int) $_GET['edit']]);
    $editProduct = $statement->fetch() ?: null;
}

$name = $editProduct['name'] ?? '';
$description = $editProduct['description'] ?? '';
$price = $editProduct['price'] ?? '';
$image = $editProduct['image'] ?? '';
$altText = $editProduct['alt_text'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'create';
    $productId = (int) ($_POST['product_id'] ?? 0);

    if ($action === 'delete' && $productId > 0) {
        $delete = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $delete->execute([':id' => $productId]);
        header('Location: admin.php?message=deleted');
        exit;
    }

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $altText = trim($_POST['alt_text'] ?? '');
    $uploadedImage = uploadProductImage('product_image', $errors);

    if ($uploadedImage !== null) {
        $image = $uploadedImage;
    }

    if ($name === '') {
        $errors[] = 'Jaza jina la bidhaa.';
    }

    if ($description === '') {
        $errors[] = 'Jaza maelezo ya bidhaa.';
    }

    if ($price === '') {
        $errors[] = 'Jaza bei ya bidhaa.';
    }

    if ($image === '') {
        $errors[] = 'Weka link ya picha au upload picha.';
    }

    if ($altText === '') {
        $altText = $name;
    }

    if (count($errors) === 0 && $action === 'update' && $productId > 0) {
        $update = $pdo->prepare(
            'UPDATE products
             SET name = :name, description = :description, price = :price, image = :image, alt_text = :alt_text
             WHERE id = :id'
        );
        $update->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $image,
            ':alt_text' => $altText,
            ':id' => $productId,
        ]);
        header('Location: admin.php?message=updated');
        exit;
    }

    if (count($errors) === 0 && $action === 'create') {
        $insert = $pdo->prepare(
            'INSERT INTO products (name, description, price, image, alt_text)
             VALUES (:name, :description, :price, :image, :alt_text)'
        );
        $insert->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':image' => $image,
            ':alt_text' => $altText,
        ]);

        header('Location: admin.php?message=created');
        exit;
    }
}

if (isset($_GET['message'])) {
    $messages = [
        'created' => 'Bidhaa imeongezwa kwenye database.',
        'updated' => 'Bidhaa imebadilishwa.',
        'deleted' => 'Bidhaa imefutwa.',
    ];
    $message = $messages[$_GET['message']] ?? '';
}

$productCount = (int) $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$orderCount = (int) $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$pendingCount = (int) $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Pending'")->fetchColumn();
$products = $pdo->query('SELECT * FROM products ORDER BY id DESC')->fetchAll();
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Smart Market | Admin</title>
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
      </nav>
      <a class="logout-button" href="logout.php">Logout</a>
    </header>

    <section class="content-section">
      <div class="section-heading">
        <p class="eyebrow">Admin Dashboard</p>
        <h1>Simamia biashara yako</h1>
      </div>

      <div class="dashboard-grid">
        <article>
          <strong><?php echo $productCount; ?></strong>
          <span>Bidhaa</span>
        </article>
        <article>
          <strong><?php echo $orderCount; ?></strong>
          <span>Orders zote</span>
        </article>
        <article>
          <strong><?php echo $pendingCount; ?></strong>
          <span>Pending</span>
        </article>
      </div>

      <div class="admin-layout">
        <form class="order-form admin-form" method="post" action="admin.php" enctype="multipart/form-data">
          <div class="form-header">
            <p class="eyebrow"><?php echo $editProduct ? 'Edit bidhaa' : 'Bidhaa mpya'; ?></p>
            <h2><?php echo $editProduct ? 'Badilisha bidhaa' : 'Ongeza bidhaa'; ?></h2>
          </div>

          <?php if ($message !== ''): ?>
            <p class="alert-success"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
          <?php endif; ?>

          <?php if (count($errors) > 0): ?>
            <div class="alert-error">
              <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <input type="hidden" name="action" value="<?php echo $editProduct ? 'update' : 'create'; ?>">
          <input type="hidden" name="product_id" value="<?php echo htmlspecialchars((string) ($editProduct['id'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>">

          <label for="name">Jina la bidhaa</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>" required>

          <label for="description">Maelezo</label>
          <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></textarea>

          <label for="price">Bei</label>
          <input type="text" id="price" name="price" placeholder="Mfano: TSh 50,000" value="<?php echo htmlspecialchars($price, ENT_QUOTES, 'UTF-8'); ?>" required>

          <label for="image">Link ya picha</label>
          <input type="text" id="image" name="image" placeholder="https://... au uploads/picha.jpg" value="<?php echo htmlspecialchars($image, ENT_QUOTES, 'UTF-8'); ?>">

          <label for="product_image">Au upload picha kutoka computer</label>
          <input type="file" id="product_image" name="product_image" accept="image/*">

          <label for="alt_text">Maelezo ya picha</label>
          <input type="text" id="alt_text" name="alt_text" value="<?php echo htmlspecialchars($altText, ENT_QUOTES, 'UTF-8'); ?>">

          <button class="primary-button" type="submit"><?php echo $editProduct ? 'Save changes' : 'Ongeza bidhaa'; ?></button>
          <?php if ($editProduct): ?>
            <a class="text-button link-button" href="admin.php">Cancel edit</a>
          <?php endif; ?>
        </form>

        <div class="admin-products">
          <h2>Bidhaa zilizopo</h2>
          <?php foreach ($products as $product): ?>
            <article>
              <img src="<?php echo htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($product['alt_text'], ENT_QUOTES, 'UTF-8'); ?>">
              <div>
                <h3><?php echo htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?php echo htmlspecialchars($product['price'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="admin-actions">
                  <a href="admin.php?edit=<?php echo htmlspecialchars((string) $product['id'], ENT_QUOTES, 'UTF-8'); ?>">Edit</a>
                  <form method="post" action="admin.php">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="product_id" value="<?php echo htmlspecialchars((string) $product['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" onclick="return confirm('Una uhakika unataka kufuta bidhaa hii?')">Delete</button>
                  </form>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>
</body>
</html>
