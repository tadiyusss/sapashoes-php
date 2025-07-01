<?php 
    require_once '../config.php';
    require_once '../utils/flash.php';
    $message = '';
    $error = false;
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $upload_dir = '../assets/images/shoes';

    if (!isset($_GET['id'])) {
        header('Location: index.php');
        exit;
    }

    $id = $_GET['id'];
    

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

        if ($_SERVER['REQUEST_METHOD'] === "POST"){
        $name = $_POST['name'] ?? '';
        $price = $_POST['price'] ?? '';
        $image = $_FILES['image'] ?? null;
        $stocks = $_POST['stocks'] ?? '';

        if (!$name || !$price || !$image || !$stocks) {
            $message = 'Please fill in all fields.';
            $error = true;
        }


        if ($stocks <= 0) {
            $message = 'Stocks must be greater than zero.';
            $error = true;
        }

       # check if image file type is not allowed
        if ($image && isset($image['name']) && $image['name'] !== '') {
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
        } else {
            $new_name = $row['image']; // Keep the old image if no new image is uploaded
        }


        if (!$error) {
            $query = "UPDATE products SET name = ?, price = ?, image = ?, stocks = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sdsii', $name, $price, $new_name, $stocks, $id);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Product created successfully!';
            } else {
                $message = 'Error creating product: ' . mysqli_error($conn);
                $error = true;
            }
        }
        
    }

?>
<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Edit Product</title>
        <script src='../assets/js/tailwind.js'></script>
        <link rel="stylesheet" href="../assets/css/main.css">
    </head>
    <body class="bg-gray-50 roboto">
        <div class="max-w-xl p-4 my-24 space-y-6 mx-auto">
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
                <h4 class="text-xl font-medium">Edit Product</h4>
                <form method="post" enctype="multipart/form-data">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="text-sm font-medium text-gray-600">Product Name</label>
                            <input type="text" name="name" id="name" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded" value="<?= htmlspecialchars($row['name']) ?>">
                        </div>
                        <div>
                            <label for="price" class="text-sm font-medium text-gray-600">Price</label>
                            <input type="number" name="price" id="price" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="<?= htmlspecialchars($row['price']) ?>">
                        </div>
                        <div>
                            <label for="image" class="text-sm font-medium text-gray-600">Stocks</label>
                            <input type="number" name="stocks" id="stocks" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" value="<?= htmlspecialchars($row['stocks']) ?>">
                        </div>
                        <div>
                            <label for="image" class="text-sm font-medium text-gray-600">Image</label>
                            <input type="file" name="image" id="image" class="cursor-pointer w-full text-sm file:bg-zinc-800 file:text-white file:rounded file:border-0 file:px-2 file:py-1 file:mr-4" accept="image/*">
                        </div>
                        <div>
                            <button class="w-full py-2 rounded text-sm bg-zinc-800 hover:bg-zinc-900 ease duration-200 text-white">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>