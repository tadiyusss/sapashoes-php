<?php 
    require 'config.php';
    require_once 'utils/flash.php';
    require_once 'utils/global.php';

    $total_price = 0;
    $error_message = get_flash('error');
    function get_product_by_id($product_id, $conn) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        return $product;
    }

    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        header('Location: login.php');
    }

    $stmt = $conn->prepare("SELECT * FROM products JOIN cart ON products.id = cart.product_id WHERE cart.owner_id = ? AND products.stocks > 0 ORDER BY cart.id DESC;"); 
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);

    # get all sales
    $stmt = $conn->prepare("SELECT * FROM sales WHERE owner_id = ?");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $sales = $result->fetch_all(MYSQLI_ASSOC);

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $card_number = $_POST['card_number'];
        $expiry_month = $_POST['expiry_month'];
        $expiry_year = $_POST['expiry_year'];
        $cvv = $_POST['cvv'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $discount_code = $_POST['discount'] ?? '';
        $sizes = $_POST['size'] ?? [];

        if (empty($card_number) || empty($expiry_month) || empty($expiry_year) || empty($cvv) || empty($name) || empty($address) || empty($city) || empty($sizes)) {
            set_flash('error', 'All fields are required.');
            header('Location: cart.php');
            exit;
        }

        # check if expiry_date is valid
        $expiry_date = DateTime::createFromFormat('Y-m', "$expiry_year-$expiry_month");
        if (!$expiry_date || $expiry_date < new DateTime()) {
            set_flash('error', 'Invalid expiry date.');
            header('Location: cart.php');
            exit;
        }

        # check if cvv is valid
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            set_flash('error', 'Invalid CVV.');
            header('Location: cart.php');
            exit;
        }

        # check if card_number is valid
        if (!preg_match('/^\d{16}$/', $card_number)) {
            set_flash('error', 'Invalid card number.');
            header('Location: cart.php');
            exit;
        }

        # check if discount code is valid
        if (!empty($discount_code)) {
            $stmt = $conn->prepare("SELECT * FROM discount_code WHERE discount_code = ?");
            $stmt->bind_param("s", $discount_code);
            $stmt->execute();
            $result = $stmt->get_result();
            $discount = $result->fetch_assoc();
            $stmt->close();

            if ($discount) {
                $discount_percentage = $discount['percentage'];
                $total_price = array_sum(array_column($cart_items, 'price'));
                $discount_amount = ($total_price * $discount_percentage) / 100;
                $total_price -= $discount_amount;
            } else {
                set_flash('error', 'Invalid discount code.');
                header('Location: cart.php');
                exit;
            }
        }

        $stmt = $conn->prepare("SELECT * FROM products JOIN cart ON products.id = cart.product_id WHERE cart.owner_id = ? AND products.stocks > 0 ORDER BY cart.id DESC;"); 
        $stmt->bind_param("i", $_SESSION["user_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_items = $result->fetch_all(MYSQLI_ASSOC);
        $sale_id = random_string(16);

        foreach ($cart_items as $cart_item){
            # -1 stock for each product in the cart
            $total += $cart_item['price'];
            $product_id = $cart_item['product_id'];
            $stmt = $conn->prepare("UPDATE products SET stocks = stocks - 1 WHERE id = ?");
            $stmt->bind_param("i", $product_id);
            $stmt->execute();
            $stmt->close();

            # insert into sold_prodcts table columns: id, sale_id, product_id, size, sale_id, owner_id
            $size = $sizes[$cart_item['id']] ?? null; // Get the size for the specific cart item
            $stmt = $conn->prepare("INSERT INTO sold_products (product_id, size, sale_id, owner_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iisi", $cart_item['product_id'], $size, $sale_id, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();

            # delete the item from the cart
            $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
            $stmt->bind_param("i", $cart_item['id']);
            $stmt->execute();

        }

        # insert into sales table
        if (!empty($discount_code)) {
            $stmt = $conn->prepare("INSERT INTO sales (total, customer_name, address, city, sale_id, discount_code, owner_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("dsssssi", $total_price, $name, $address, $city, $sale_id, $discount_code, $_SESSION['user_id']);
        } else {
            $stmt = $conn->prepare("INSERT INTO sales (total, customer_name, address, city, sale_id, owner_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("dssss", $total_price, $name, $address, $city, $sale_id, $_SESSION['user_id']);
        }
        $stmt->execute();
        $stmt->close();
        # delete all items in the cart



        
        header('Location: success.php');
    }

?>

<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Sapashoes - Cart</title>
        <script src='assets/js/tailwind.js'></script>
        <link rel="stylesheet" href="assets/css/main.css">    
    </head>
    <body class="bg-gray-50">
        <?php include 'templates/navigation.php'; ?>
        <div class="space-y-8 my-12">
            <form method="POST" class="max-w-6xl mx-auto px-6">
                <div class="space-y-8">
                    <h2 class="roboto font-medium text-2xl mb-4">Orders</h2>
                    <?php if (count($sales) > 0): ?>
                        <div class="grid md:grid-cols-3 grid-cols-1 gap-4">
                            <?php foreach ($sales as $sale): ?>
                                <div class="border p-4 rounded bg-white">
                                    <h4 class="bebas-neue text-xl mb-2">Sale ID: <?= $sale['sale_id'] ?></h4>
                                    <p class="text-gray-600">Total: ₱ <?= format_number($sale['total']) ?></p>
                                    <p class="text-gray-600">Status: <?= $sale['status'] ?></p>
                                    <p class="text-gray-600">Date: <?= date('F j, Y', strtotime($sale['created_at'])) ?></p>
                                    <a href="view_order.php?id=<?= $sale['sale_id'] ?>" class="text-blue-600 hover:underline mt-2 block">View Details</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <h2 class="roboto font-medium text-2xl mb-4">Shopping Cart</h2>
                    <?php if (count($cart_items) > 0): ?>
                        <?php foreach ($cart_items as $cart_item): ?>
                            <div class="border-b p-2 pb-4 flex items-center justify-between">
                                <div class="flex items-center space-x-12">
                                    <a href="delete_cart.php?id=<?= $cart_item['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-800">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                    <img src="assets/images/shoes/<?= $cart_item['image'] ?>" class="size-24 border rounded bg-white">
                                    <div class="block space-y-2">
                                        <h4 class="bebas-neue text-xl"><?= $cart_item['name'] ?></h4>
                                        <div class="grid md:grid-cols-4 grid-cols-1 gap-2 border border-gray-50">
                                            <?php for($index = 5; $index <= 12; $index++): ?>
                                                <?php
                                                    // Make radio group unique per cart item
                                                    $radio_name = "size[{$cart_item['id']}]";
                                                    $radio_id = "size_{$cart_item['id']}_{$index}";
                                                ?>
                                                <div>
                                                    <input type="radio" id="<?= $radio_id; ?>" name="<?= $radio_name; ?>" value="<?= $index ?>" class="hidden peer">
                                                    <label for="<?= $radio_id; ?>" class="inline-flex items-center justify-between w-full px-2 py-1 text-gray-500 bg-white border border-gray-200 rounded cursor-pointer peer-checked:border-zinc-500 hover:text-gray-600 ease duration-200 hover:bg-gray-100 ">
                                                        <p>US M: <?= $index; ?> / Women: <?= $index + 1.5; ?></p>
                                                    </label>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="block">
                                    <p class="bebas-neue">₱ <?= $cart_item['price'] ?></p>
                                </div>
                            </div>
                            <?php $total_price += $cart_item['price']; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center p-4">
                            <p class="text-gray-600 text-3xl">Your cart is empty.</p>
                            <p class="text-gray-500 text-lg">Your cart is currently empty. Start shopping now and fill it with your favorite items!</p>
                        </div>
                    <?php endif; ?>


                    <?php if (count($cart_items) > 0): ?>
                    <div class="w-full md:p-0 p-4">
                        <div class="flex justify-between items-center">
                            <h4 class="bebas-neue text-xl">Total</h4>
                            <p class="bebas-neue text-xl">₱ <?= $total_price ?></p>
                        </div>
                    </div>
                    <div class="w-full md:p-0 p-4">
                        <h2 class="roboto font-medium text-2xl mb-4">Payment Information</h2>
                        <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
                            <div class="md:col-span-1 col-span-2">
                                <label for="card_number" class="text-sm font-medium text-gray-600">Card Number</label>
                                <input type="number" name="card_number" id="card_number" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <label for="expiry_date" class="text-sm font-medium text-gray-600">Expiry Date</label>
                                <div class="md:flex block items-center md:space-x-2 md:space-y-0 space-y-2">
                                    <input type="number" name="expiry_month" id="expiry_date" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="MM" min="1" max="12">
                                    <input type="number" name="expiry_year" id="expiry_date" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="YYYY" min="2023" max="2030">
                                </div>
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <label for="cvv" class="text-sm font-medium text-gray-600">CVV/CVC</label>
                                <input type="number" name="cvv" id="cvv" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="123" min="100" max="999">
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <label for="name" class="text-sm font-medium text-gray-600">Name</label>
                                <input type="text" name="name" id="name" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="John Doe">
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <label for="address" class="text-sm font-medium text-gray-600">Address</label>
                                <input type="text" name="address" id="address" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            </div>
                            <div class="md:col-span-1 col-span-2 md:flex block items-center md:space-x-2 md:space-y-0 space-y-2">
                                <div>
                                    <label for="city" class="text-sm font-medium text-gray-600">City</label>
                                    <input type="text" name="city" id="city" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                </div>
                                <div>
                                    <label for="discount" class="text-sm font-medium text-gray-600">Discount Code</label>
                                    <input type="text" name="discount" id="discount" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="Enter discount code">
                                </div>
                            </div>
                            <?php if ($error_message): ?>
                                <div class="col-span-2 bg-red-600 p-2 text-white rounded">
                                    <p><?= $error_message; ?></p>
                                </div>
                            <?php endif; ?>
                            <button type="submit" class="col-span-2 bg-zinc-800 hover:bg-zinc-900 ease duration-200 text-white w-full py-2 mt-4 rounded">Checkout</button>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                
            </form>
        </div>
    </body>
</html>