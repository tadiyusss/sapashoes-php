<?php
    require_once '../config.php';
    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!doctype html>
<html lang='en'>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Dashboard</title>
    <script src='../assets/js/tailwind.js'></script>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="bg-gray-50 roboto">
    <nav class="border bg-white">
        <div class="container mx-auto p-4 flex items-center justify-between">
            <h1 class="text-lg font-medium text-gray-800">Welcome back, Thaddeus!</h1>
            <a href="#">Logout</a>
        </div>
    </nav>

    <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded my-4 max-w-7xl mx-auto">Product added successfully!</div>
    <?php endif; ?>

    <div class="max-w-7xl mx-auto p-4 mt-10 grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Sales Placeholder -->
        <div class="border rounded shadow h-96 max-h-96 overflow-y-auto bg-white">
            <div class="sticky top-0 flex items-center text-sm justify-between bg-gray-50 p-2 px-4">
                <h5 class="text-gray-500 font-semibold">Sales</h5>
            </div>
            <table class="w-full">
                <thead class="bg-white">
                    <tr class="text-gray-400 border-y">
                        <th class="font-semibold p-2">#</th>
                        <th class="font-semibold p-2">Name</th>
                        <th class="font-semibold p-2">Total</th>
                        <th class="font-semibold p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b text-center hover:bg-gray-50">
                        <td class="py-2">—</td>
                        <td class="py-2">—</td>
                        <td class="py-2">—</td>
                        <td class="py-2">—</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Products List -->
        <div class="border rounded shadow h-96 max-h-96 overflow-y-auto bg-white">
            <div class="sticky top-0 flex items-center text-sm justify-between bg-gray-50 p-2 px-4">
                <h5 class="text-gray-500 font-semibold">Products</h5>
                <a href="add_product.php" class="bg-zinc-800 hover:bg-zinc-900 text-white px-2 py-1 rounded text-xs">Add Product</a>
            </div>
            <table class="w-full">
                <thead class="bg-white">
                    <tr class="text-gray-400 border-y">
                        <th class="font-semibold p-2">#</th>
                        <th class="font-semibold p-2">Name</th>
                        <th class="font-semibold p-2">Price</th>
                        <th class="font-semibold p-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $index => $product): ?>
                        <tr class="border-b text-center hover:bg-gray-50">
                            <td class="py-2"><?php echo $index + 1; ?></td>
                            <td class="py-2"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="py-2">₱<?php echo number_format($product['price'], 2); ?></td>
                            <td class="py-2 flex items-center justify-center space-x-4">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="px-2 py-1 rounded text-white bg-blue-600 hover:bg-blue-700">Edit</a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="px-2 py-1 rounded text-white bg-red-600 hover:bg-red-700">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
