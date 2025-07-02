<?php 

    require_once 'config.php';
    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        header('Location: login.php');
    }

    if (!isset($_GET['id'])){
        header("Location: index.php");
    }

    $sale_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM sales WHERE sale_id = ?");
    $stmt->bind_param("s", $sale_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $sale_details = $result->fetch_assoc();

    // get products in the sale
    $stmt = $conn->prepare("SELECT sp.*, p.name, p.price, sp.size FROM sold_products sp JOIN products p ON sp.product_id = p.id WHERE sp.sale_id = ?");
    $stmt->bind_param("s", $sale_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];
    while ($product = $result->fetch_assoc()) {
        $products[] = $product;
    }
?>
<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Sapashoes - View Order</title>
        <script src='assets/js/tailwind.js'></script>
        <link rel="stylesheet" href="assets/css/main.css">    
    </head>
    <body class="bg-gray-50 min-h-screen flex items-center justify-center">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-2xl w-full">
            <div class="flex items center space-x-2 mb-4">
                <h1 class="text-2xl font-bold">Order Details</h1>
                <div class="">
                    <a href="cart.php" class="px-2 py-1 rounded text-white bg-blue-600 hover:bg-blue-700 text-sm">
                        &larr; Return to Cart
                    </a>
                </div>
            </div>
            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Shipping Address</h2>
                <div class="text-gray-700">
                    <?= htmlspecialchars($sale_details['customer_name']) ?><br>
                    <?= htmlspecialchars($sale_details['address']) ?><br>
                    <?= htmlspecialchars($sale_details['city']) ?><br>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="text-lg font-semibold mb-2">Order Status</h2>
                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                    <?= htmlspecialchars($sale_details['status']) ?>
                </span>
            </div>

            <div>
                <h2 class="text-lg font-semibold mb-2">Products</h2>
                <table class="min-w-full bg-white border border-gray-200 rounded">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Product</th>
                            <th class="py-2 px-4 border-b text-left">Size</th>
                            <th class="py-2 px-4 border-b text-left">Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                        <tr>
                            <td class="py-2 px-4 border-b"><?= htmlspecialchars($product['name']) ?></td>
                            <td class="py-2 px-4 border-b"><?= (int)$product['size'] ?></td>
                            <td class="py-2 px-4 border-b">₱<?= number_format($product['price'], 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>