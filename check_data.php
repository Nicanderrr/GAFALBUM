<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=gafalbum', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT COUNT(*) FROM categories;');
    $categoriesCount = $stmt->fetchColumn();
    
    $stmt = $pdo->query('SELECT COUNT(*) FROM images;');
    $imagesCount = $stmt->fetchColumn();

    $stmt = $pdo->query('SELECT COUNT(*) FROM transactions;');
    $transactionsCount = $stmt->fetchColumn();
    
    echo "Categories count: $categoriesCount\n";
    echo "Images count: $imagesCount\n";
    echo "Transactions count: $transactionsCount\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
