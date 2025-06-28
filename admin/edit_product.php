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
        <div class="max-w-xl p-4 my-24 mx-auto">
            <a href="index.php" class="flex items-center space-x-2 mb-2 text-gray-600 hover:text-gray-800 ease duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
                <p>Return to Home</p>
            </a>
            <div class="rounded bg-white border p-4">
                <h4 class="text-xl font-medium">Edit Product</h4>
                <form method="post" class="mt-6" accept="multipart/form-data">
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