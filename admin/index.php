<?php
    require_once '../config.php';
    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        header('Location: login.php');
    }

    if ($_SESSION['type'] != 'admin') {
        header("Location: ../index.php");
    }

    $result = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($conn, "SELECT * FROM sales ORDER BY id DESC");
    $sales = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");
    $brands = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
            <a href="../logout.php">Logout</a>
        </div>
    </nav>

    <?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded my-4 max-w-7xl mx-auto">Product added successfully!</div>
    <?php endif; ?>
    <div class="mt-10 p-4 max-w-7xl mx-auto space-y-4">
        <div class="grid md:grid-cols-4 grid-cols-2 gap-4">
            <div class="bg-white p-4 rounded shadow">
                <h5 class="text-gray-500 font-semibold">Total Products</h5>
                <p class="text-2xl font-bold"><?= count($products) ?></p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h5 class="text-gray-500 font-semibold">Total Sales</h5>
                <p class="text-2xl font-bold"><?= count($sales) ?></p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h5 class="text-gray-500 font-semibold">Total Revenue</h5>
                <p class="text-2xl font-bold">₱<?= number_format(array_sum(array_column($sales, 'total')), 2) ?></p>
            </div>
            <div class="bg-white p-4 rounded shadow">
                <h5 class="text-gray-500 font-semibold">Total Customers</h5>
                <p class="text-2xl font-bold"><?= count(array_unique(array_column($sales, 'customer_name'))) ?></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                            <th class="font-semibold p-2">Status</th>
                            <th class="font-semibold p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($sales)): ?>
                            <tr class="border-b text-center">
                                <td colspan="4" class="py-2 text-gray-600">No products found.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($sales as $index => $sale): ?>
                            <tr class="border-b text-center hover:bg-gray-50">
                                <td class="py-2"><?= htmlspecialchars($sale['id']) ?></td>
                                <td class="py-2"><?= htmlspecialchars($sale['customer_name']) ?></td>
                                <td class="py-2"><?= htmlspecialchars($sale['total']) ?></td>
                                <td class="py-2"><?= $sale['status'] ?></td>
                                <td class="py-2">
                                    <a href="#">Manage</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
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
                        <?php if (empty($products)): ?>
                            <tr class="border-b text-center">
                                <td colspan="4" class="py-2 text-gray-600">No products found.</td>
                            </tr>
                        <?php endif; ?>
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

            <div class="border rounded shadow h-96 max-h-96 overflow-y-auto bg-white">
                <div class="sticky top-0 flex items-center text-sm justify-between bg-gray-50 p-2 px-4">
                    <h5 class="text-gray-500 font-semibold">Brands</h5>
                    <a href="add_brand.php" class="bg-zinc-800 hover:bg-zinc-900 text-white px-2 py-1 rounded text-xs">Add Brand</a>
                </div>
                <table class="w-full">
                    <thead class="bg-white">
                        <tr class="text-gray-400 border-y">
                            <th class="font-semibold p-2">#</th>
                            <th class="font-semibold p-2">Name</th>
                            <th class="font-semibold p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($brands)): ?>
                            <tr class="border-b text-center">
                                <td colspan="4" class="py-2 text-gray-600">No brands found.</td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($brands as $index => $brand): ?>
                            <tr class="border-b text-center hover:bg-gray-50">
                                <td class="py-2"><?php echo $index + 1; ?></td>
                                <td class="py-2"><?php echo htmlspecialchars($brand['brand_name']); ?></td>
                                <td class="py-2 flex items-center justify-center space-x-4">
                                    <a href="edit_brand.php?id=<?php echo $brand['id']; ?>" class="px-2 py-1 rounded text-white bg-blue-600 hover:bg-blue-700">Edit</a>
                                    <a href="delete_brand.php?id=<?php echo $brand['id']; ?>" class="px-2 py-1 rounded text-white bg-red-600 hover:bg-red-700">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
</html>
