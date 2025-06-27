<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Tailwind</title>
        <script src='assets/js/tailwind.css'></script>
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>
        <?php include 'templates/navigation.php'; ?>
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
        <?php include 'templates/footer.php'; ?>
    </body>
</html>