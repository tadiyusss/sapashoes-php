<nav class="border-b bg-white">
    <div class="bg-zinc-950 text-white p-2">
        <div class="max-w-7xl mx-auto">
            <ul class="flex items-center space-x-8 roboto">
                <li>
                    <a href="cart.php">Cart</a>
                </li>
                <?php if (isset($_SESSION['username']) && isset($_SESSION['type'])): ?>
                    <li>
                        <a href="logout.php">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl p-4 flex items-center justify-between mx-auto">
        <h2 class="bebas-neue text-3xl">SapaShoes</h2>
        <ul class="flex items-center space-x-4 roboto">
            <li>
                <a href="index.php">Home</a>
            </li>
            <li>About Us</li>
            <li>Contact Us</li>
        </ul>
    </div>
</nav>