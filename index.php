<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Tailwind</title>
        <script src='https://cdn.tailwindcss.com'></script>
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>
        <nav>
            <div class="bg-zinc-950 text-white p-2">
                <div class="max-w-7xl mx-auto">
                    <ul class="flex items-center space-x-8 roboto">
                        <li>
                            <a href="login.php">My Account</a>
                        </li>
                        <li>Cart</li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl p-4 flex items-center justify-between mx-auto">
                <h2 class="bebas-neue text-3xl">SapaShoes</h2>
                <ul class="flex items-center space-x-4 roboto">
                    <li>Home</li>
                    <li>About Us</li>
                    <li>Contact Us</li>
                </ul>
            </div>
            
        </nav>
        <img src="assets/images/carousel1.webp" class="w-full h-[400px] object-cover">
        <div class="space-y-8 my-12">
            <div class="max-w-7xl mx-auto px-6">
                <h4 class="bebas-neue text-4xl">New Arrivals</h4>
                <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-4">
                    <?php for ($i = 0; $i <= 7; $i++): ?>
                    <div class="col-span-1">
                        <img src="assets/images/shoes/new-arrival-1.webp" class="size-64 mx-auto">
                        <div class="mt-2 space-y-1">
                            <h4 class="bebas-neue text-lg">Air Jordan 1 Retro High OG 'Yellow Ochre'</h4>
                            <p class="bebas-neue">₱ 9,895.00</p>
                            <button class="bg-zinc-800 hover:bg-zinc-950 ease duration-200 text-white px-2 py-1 rounded">Add to cart</button>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <footer class="bg-zinc-950 p-4 roboto">
            <div class="max-w-7xl mx-auto p-4">
                <div class="flex flex-col md:flex-row text-white justify-between">
                    <div>
                        <h2 class="bebas-neue text-4xl">SapaShoes</h2>
                        <p class="text-sm text-zinc-400 font-medium">All rights reserved 2025</p>
                    </div>
                    <div>
                        <h2 class="text-xl font-medium">Quick Links</h2>
                        <ul class="space-y-1 mt-2 text-sm text-gray-200">
                            <li>About Us</li>
                            <li>Contact Us</li>
                            <li>Careers</li>
                            <li>Store Locator</li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="text-xl font-medium">Store Policy</h2>
                        <ul class="space-y-1 mt-2 text-sm text-gray-200">
                            <li>Shipping Policy</li>
                            <li>Privacy Policy</li>
                            <li>Terms and Services</li>
                            <li>Returns and Exchanges</li>
                        </ul>
                    </div>
                </div>    
            </div>
        </footer>
    </body>
</html>