<?php
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$message = '';
$success = false;

try {
    $pdo = new PDO(
        "mysql:host=$dbHost;charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );

    $pdo->exec(
        "CREATE DATABASE IF NOT EXISTS smartmarket_db
         CHARACTER SET utf8mb4
         COLLATE utf8mb4_general_ci"
    );

    $pdo->exec('USE smartmarket_db');

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS orders (
            id INT AUTO_INCREMENT PRIMARY KEY,
            product VARCHAR(100) NOT NULL,
            customer_name VARCHAR(120) NOT NULL,
            phone VARCHAR(40) NOT NULL,
            location VARCHAR(180) NOT NULL,
            payment_method VARCHAR(80) NOT NULL,
            payment_reference VARCHAR(120) NULL,
            payment_status VARCHAR(40) NOT NULL DEFAULT 'Unverified',
            status VARCHAR(40) NOT NULL DEFAULT 'Pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $statusColumn = $pdo->query("SHOW COLUMNS FROM orders LIKE 'status'")->fetch();
    if (!$statusColumn) {
        $pdo->exec("ALTER TABLE orders ADD status VARCHAR(40) NOT NULL DEFAULT 'Pending' AFTER payment_method");
    }

    $paymentReferenceColumn = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_reference'")->fetch();
    if (!$paymentReferenceColumn) {
        $pdo->exec("ALTER TABLE orders ADD payment_reference VARCHAR(120) NULL AFTER payment_method");
    }

    $paymentStatusColumn = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_status'")->fetch();
    if (!$paymentStatusColumn) {
        $pdo->exec("ALTER TABLE orders ADD payment_status VARCHAR(40) NOT NULL DEFAULT 'Unverified' AFTER payment_reference");
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT NOT NULL,
            price VARCHAR(80) NOT NULL,
            image VARCHAR(500) NOT NULL,
            alt_text VARCHAR(180) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );

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

    $insertProduct = $pdo->prepare(
        "INSERT INTO products (name, description, price, image, alt_text)
         SELECT :name, :description, :price, :image, :alt_text
         WHERE NOT EXISTS (SELECT 1 FROM products WHERE name = :check_name)"
    );

    foreach ($products as $product) {
        $insertProduct->execute([
            ':name' => $product[0],
            ':description' => $product[1],
            ':price' => $product[2],
            ':image' => $product[3],
            ':alt_text' => $product[4],
            ':check_name' => $product[0],
        ]);
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(80) NOT NULL UNIQUE,
            email VARCHAR(120) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            role VARCHAR(40) NOT NULL DEFAULT 'customer',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    );

    $insertUser = $pdo->prepare(
        "INSERT INTO users (username, email, password_hash, role)
         SELECT :username, :email, :password_hash, :role
         WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = :check_username)"
    );

    $insertUser->execute([
        ':username' => 'admin',
        ':email' => 'admin@smartmarket.local',
        ':password_hash' => password_hash('123456', PASSWORD_DEFAULT),
        ':role' => 'admin',
        ':check_username' => 'admin',
    ]);

    $insertUser->execute([
        ':username' => 'mteja',
        ':email' => 'mteja@smartmarket.local',
        ':password_hash' => password_hash('123456', PASSWORD_DEFAULT),
        ':role' => 'customer',
        ':check_username' => 'mteja',
    ]);

    $success = true;
    $message = 'Database, users, products, na orders vimetengenezwa tayari.';
} catch (PDOException $error) {
    $message = 'Imeshindikana kuandaa database: ' . $error->getMessage();
}
?>
<!DOCTYPE html>
<html lang="sw">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GHM Smart Market | Setup</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <main class="products-page">
    <section class="order-page">
      <div class="success-card">
        <p class="eyebrow">Database Setup</p>
        <h1><?php echo $success ? 'Imefanikiwa' : 'Haijafanikiwa'; ?></h1>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php if ($success): ?>
          <a class="primary-button" href="index.php">Fungua website</a>
        <?php else: ?>
          <p class="comfort-text">Hakikisha MySQL imewashwa kwenye XAMPP, kisha refresh ukurasa huu.</p>
        <?php endif; ?>
      </div>
    </section>
  </main>
</body>
</html>
