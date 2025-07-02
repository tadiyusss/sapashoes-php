<?php 
    require_once '../config.php';

    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        header('Location: login.php');
    }

    if ($_SESSION['type'] != 'admin') {
        header("Location: ../index.php");
    }

    if(!isset($_GET['id'])) {
        header('Location: index.php');
    }

    $sale_id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] === "POST"){
        $status = $_POST['status'] ?? '';
        $message = '';
        $error = false;

        if (empty($status)) {
            $message = 'Status cannot be empty.';
            $error = true;
        } else {
            // Update the sale status
            $stmt = $conn->prepare("UPDATE sales SET status = ? WHERE sale_id = ?");
            $stmt->bind_param("ss", $status, $sale_id);
            if ($stmt->execute()) {
                $message = 'Sale status updated successfully.';
                $error = false;
            } else {
                $message = 'Failed to update sale status.';
                $error = true;
            }
            $stmt->close();
        }
    } else {
        $message = '';
        $error = false;
    }

    # get sale details
    $stmt = $conn->prepare("SELECT * FROM sales WHERE sale_id = ?");
    $stmt->bind_param("s", $sale_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();


    # get product details for the sale
    $stmt = $conn->prepare("SELECT sp.*, p.name, p.price, sp.size FROM sold_products sp JOIN products p ON sp.product_id = p.id WHERE sp.sale_id = ?");
    $stmt->bind_param("s", $sale_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = [];
    while ($item = $result->fetch_assoc()) {
        $items[] = $item;
    }
?>


<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>View Sale</title>
        <script src='../assets/js/tailwind.js'></script>
        <link rel="stylesheet" href="../assets/css/main.css">
    </head>
    <body class="bg-gray-50 roboto">
        <div class="max-w-2xl p-4 my-24 space-y-6 mx-auto">
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
            <div class="rounded bg-white border p-6">
                <h4 class="text-2xl font-semibold mb-4">View Sale</h4>
                <div class="mb-6">
                    <form method="post">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Sale Status</label>
                        <select name="status" id="status" class="w-full border px-2 py-1 rounded bg-white focus:outline-zinc-200 focus:ring-zinc-200">
                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="Processing" <?= $row['status'] == 'Processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="Confirmed" <?= $row['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                            <option value="Out for Delivery" <?= $row['status'] == 'Out for Delivery' ? 'selected' : '' ?>>Out for Delivery</option>
                            <option value="Shipped" <?= $row['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="Cancelled" <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                        <button class="mt-4 w-full py-2 rounded text-sm bg-zinc-800 hover:bg-zinc-900 ease duration-200 text-white">Update Status</button>
                    </form>
                </div>
                <div>
                    <h5 class="text-lg font-medium mb-2">Items Purchased</h5>
                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-3 py-2 border-b text-left">Product</th>
                                    <th class="px-3 py-2 border-b text-left">Size</th>
                                    <th class="px-3 py-2 border-b text-left">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                <tr>
                                    <td class="px-3 py-2 border-b"><?= htmlspecialchars($item['name']) ?></td>
                                    <td class="px-3 py-2 border-b"><?= htmlspecialchars($item['size']) ?></td>
                                    <td class="px-3 py-2 border-b">₱<?= htmlspecialchars($item['price']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <?php if (isset($row['discount_code']) && !empty($row['discount_code'])): ?>
                                    <tr>
                                        <td colspan="2" class="px-3 py-2 text-right font-semibold">Discount Code</td>
                                        <td class="px-3 py-2 font-semibold"><?= htmlspecialchars($row['discount_code']) ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td colspan="2" class="px-3 py-2 text-right font-semibold">Total</td>
                                    <td class="px-3 py-2 font-semibold">₱<?= htmlspecialchars($row['total']) ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="mt-6">
                    <h5 class="text-lg font-medium mb-2">Customer Details</h5>
                    <div class="space-y-1 text-gray-700">
                        <div><span class="font-medium">Name:</span> <?= htmlspecialchars($row['customer_name']) ?></div>
                        <div><span class="font-medium">Address:</span> <?= htmlspecialchars($row['address']) ?>, <?= htmlspecialchars($row['city']) ?></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>