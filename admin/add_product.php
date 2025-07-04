<?php 
    require_once '../config.php';
    $message = '';
    $error = false;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $upload_dir = '../assets/images/shoes';

    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $image = $_FILES['image'] ?? null;
        $stocks = $_POST['stocks'] ?? '';
        $brand = $_POST['brand'] ?? '';


        if (!$name || !$price || !$image || !$stocks || !$brand) {
            $message = 'Please fill in all fields.';
            $error = true;
        }

        if ($brand)


        if ($stocks <= 0) {
            $message = 'Stocks must be greater than zero.';
            $error = true;
        }

       # check if image file type is not allowed
        $file_extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_extensions)) {
            $message = "Invalid image file type. Allowed types: " . implode(', ', $allowed_extensions);
            $error = true;
        }
        
        $new_name = uniqid() . '.' . $file_extension;
        if (!move_uploaded_file($image['tmp_name'], $upload_dir . '/' . $new_name)) {
            $message = 'Unable to move file to the upload folder.';
            $error = true;
        }
        // Check if product name already exists
        if (!$error) {
            $check_query = "SELECT id FROM products WHERE name = ?";
            $check_stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($check_stmt, 's', $name);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $message = 'A product with this name already exists.';
                $error = true;
            }

            mysqli_stmt_close($check_stmt);
        }

        if (!$error) {
            $query = "INSERT INTO products (name, price, image, stocks, brand_name) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sdsis', $name, $price, $new_name, $stocks, $brand);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Product created successfully!';
            } else {
                $message = 'Error creating product: ' . mysqli_error($conn);
                $error = true;
            }
        }
        
    }

    $result = mysqli_query($conn, "SELECT * FROM brands ORDER BY id DESC");
    $brands = mysqli_fetch_all($result, MYSQLI_ASSOC);


?>

<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Add Product</title>
        <script src='../assets/js/tailwind.js'></script>
        <link rel="stylesheet" href="../assets/css/main.css">
    </head>
    <body class="bg-gray-50 roboto">
        <div class="max-w-xl p-4 my-24 mx-auto space-y-4">
            <a href="index.php" class="flex items-center space-x-2 mb-2 text-gray-600 hover:text-gray-800 ease duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
                <p>Return to Home</p>
            </a>
            <?php if (strlen($message) > 0 && $error == true): ?>
                <div class="w-full bg-red-600 text-white p-2 rounded">
                    <p><?php echo $message; ?></p>
                </div>
            <?php elseif (strlen($message) > 0 && $error == false): ?>
                <div class="w-full bg-green-600 text-white p-2 rounded">
                    <p><?php echo $message; ?></p>
                </div>
            <?php endif; ?>
            <div class="rounded bg-white border p-4">
                <h4 class="text-xl font-medium">Create Product</h4>
               <form method="post" class="mt-6" enctype="multipart/form-data" accept="image/*">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="text-sm font-medium text-gray-600">Product Name</label>
                            <input type="text" name="name" id="name" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
                        </div>
                        <div>
                            <label for="price" class="text-sm font-medium text-gray-600">Price</label>
                            <input type="number" name="price" id="price" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        </div>
                        <div>
                            <label for="image" class="text-sm font-medium text-gray-600">Stocks</label>
                            <input type="number" name="stocks" id="stocks" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                        </div>
                        <div>
                            <label for="brand" class="text-sm font-medium text-gray-600">Brands</label>
                            <select name="brand" id="brand" class="w-full border bg-white ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?= htmlspecialchars($brand['brand_name']); ?>"><?= htmlspecialchars($brand['brand_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="image" class="text-sm font-medium text-gray-600">Image</label>
                            <input type="file" name="image" id="image" class="w-full text-sm file:bg-zinc-800 file:text-white file:rounded file:border-0 file:px-2 file:py-1 file:mr-4">
                        </div>
                        <div>
                            <button class="w-full py-2 rounded text-sm bg-zinc-800 hover:bg-zinc-900 ease duration-200 text-white">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>