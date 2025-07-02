<?php 

    require_once 'config.php';
    if (isset($_GET['brand'])) {
        $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE brand_name = ? ORDER BY id DESC");
        mysqli_stmt_bind_param($stmt, 's', $_GET['brand']);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
        $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $result = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");
    $brands = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Sapashoes</title>
        <script src='assets/js/tailwind.js'></script>
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>
        <?php include 'templates/navigation.php'; ?>
        <img src="assets/images/carousel1.webp" class="w-full h-[400px] object-cover">
        <div class="space-y-8 my-12">
            <div class="grid md:grid-cols-4 grid-cols-2 gap-4 max-w-7xl mx-auto">
                <?php foreach ($brands as $brand): ?>
                    <a href="index.php?brand=<?= htmlspecialchars($brand['brand_name']); ?>" class="border px-2 text-center py-4 rounded bg-gray-50 shadow"><?= htmlspecialchars($brand['brand_name']); ?></a>
                <?php endforeach; ?>
            </div>
            <div class="max-w-7xl mx-auto px-6">
                <h4 class="bebas-neue text-4xl">New Arrivals</h4>
                <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-4">
                    <?php foreach ($products as $index => $product): ?>
                    <div class="col-span-1">
                        <img src="assets/images/shoes/<?= htmlspecialchars($product['image']); ?>" class="size-64 mx-auto">
                        <div class="mt-2 space-y-1">
                            <h4 class="bebas-neue text-lg"><?= htmlspecialchars($product['name']); ?></h4>
                            <div class="flex items-center space-x-2">
                                <p class="bebas-neue">₱ <?= number_format($product['price'], 2); ?></p>
                                <p class="bebas-neue text-gray-600">Stocks: <?= $product['stocks']; ?></p>
                            </div>
                            <div>
                                <a href="add_cart.php?id=<?= $product['id']; ?>" class="bg-zinc-800 hover:bg-zinc-950 ease duration-200 text-white px-2 py-1 rounded">Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php include 'templates/footer.php'; ?>
    </body>
</html>