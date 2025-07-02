<?php 
    require_once '../config.php';
    $message = '';
    $error = false;

    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        header('Location: login.php');
    }

    if ($_SESSION['type'] != 'admin') {
        header("Location: ../index.php");
    }
    
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        $brand_name = $_POST['brand_name'] ?? '';

        if (!$brand_name) {
            $message = 'Please enter a brand name.';
            $error = true;
        }

        if (!$error) {
            $query = "INSERT INTO brands (brand_name) VALUES (?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $brand_name);

            if (mysqli_stmt_execute($stmt)) {
                $message = 'Brand created successfully!';
            } else {
                $message = 'Error creating brand: ' . mysqli_error($conn);
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
        <title>Add Brand</title>
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
                <h4 class="text-xl font-medium">Create Brand</h4>
               <form method="post" class="mt-6" enctype="multipart/form-data" accept="image/*">
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="text-sm font-medium text-gray-600">Brand Name</label>
                            <input type="text" name="brand_name" id="brand_name" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
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