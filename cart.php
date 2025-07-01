<?php 
    require 'config.php';
    $total_price = 0;
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

    $stmt = $conn->prepare("SELECT * FROM cart WHERE owner_id = ? ORDER BY id DESC");
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $cart_items = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

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
            <div class="max-w-6xl mx-auto px-6">
                <div class="space-y-8">
                    <div>
                        <h2 class="roboto font-medium text-2xl mb-4">Shopping Cart</h2>
                    </div>
                    <?php if (count($cart_items) > 0): ?>
                        <?php foreach ($cart_items as $cart_item): ?>
                            <?php $product = get_product_by_id($cart_item['product_id'], $conn); ?>
                            <div class="border-b p-2 pb-4 flex items-center justify-between">
                                <div class="flex items-center space-x-12">
                                    <a href="delete_cart.php?id=<?= $cart_item['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 text-gray-800">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </a>
                                    <img src="assets/images/shoes/<?= $product['image'] ?>" class="size-24 border rounded bg-white">
                                    <div class="block space-y-2">
                                        <h4 class="bebas-neue"><?= $product['name'] ?></h4>
                                    </div>
                                    
                                </div>
                                <div class="block">
                                    <p class="bebas-neue">₱ <?= $product['price'] ?></p>
                                </div>
                            </div>
                            <?php $total_price += $product['price']; ?>
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
                        <form method="post" class="grid md:grid-cols-2 grid-cols-1 gap-4">
                            <div class="md:col-span-1 col-span-2">
                                <label for="card_number" class="text-sm font-medium text-gray-600">Card Number</label>
                                <input type="number" name="card_number" id="card_number" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="md:col-span-1 col-span-2">
                                <label for="expiry_date" class="text-sm font-medium text-gray-600">Expiry Date</label>
                                <div class="md:flex block items-center md:space-x-2 md:space-y-0 space-y-2">
                                    <input type="number" name="expiry_date" id="expiry_date" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="MM" min="1" max="12">
                                    <input type="number" name="expiry_date" id="expiry_date" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" placeholder="YYYY" min="2023" max="2030">
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
                            <div class="md:col-span-1 col-span-2">
                                <label for="city" class="text-sm font-medium text-gray-600">City</label>
                                <input type="text" name="city" id="city" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                            </div>
                            <button type="submit" class="col-span-2 bg-zinc-800 hover:bg-zinc-900 ease duration-200 text-white w-full py-2 mt-4 rounded">Checkout</button>
                        </form>
                    </div>
                    <?php endif; ?>
                </div>
                
                
            </div>
        </div>
    </body>
</html>