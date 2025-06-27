<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Tailwind</title>
        <script src='assets/js/tailwind.js'></script>
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body class="bg-gray-50">
        <div class="max-w-md mx-auto mt-16">
            <a href="index.php" class="flex items-center space-x-2 mb-6 text-gray-600 hover:text-gray-800 ease duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18" />
                </svg>
                <p>Return to Home</p>
            </a>
            <h4 class="bebas-neue mb-4 text-4xl">SapaShoes</h4>
            <form method="POST" class="border rounded p-6 bg-white shadow">
                <div class="mb-6">
                    <h2 class="text-3xl">Register</h2>
                    <p class="text-gray-600 text-sm">Please fill in the form below to create your account.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="email" class="text-sm font-medium text-gray-600">Email</label>
                        <input type="text" name="email" id="email" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
                    </div>
                    <div>
                        <label for="username" class="text-sm font-medium text-gray-600">Username</label>
                        <input type="text" name="username" id="username" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
                    </div>
                    <div>
                        <label for="password" class="text-sm font-medium text-gray-600">Password</label>
                        <input type="password" name="password" id="password" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
                    </div>
                    <div>
                        <label for="retype_password" class="text-sm font-medium text-gray-600">Retype Password</label>
                        <input type="password" name="retype_password" id="retype_password" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
                    </div>
                    <div>
                        <button type="submit" class="bg-zinc-800 hover:bg-zinc-950 ease duration-200 text-white w-full px-4 py-1 rounded">Login</button>
                    </div>
                </div>
            </form>
            <div class="text-center mt-4">
                <a href="login.php" class="text-gray-500">Already have an account? <span class="underline">Login.</span></a>
            </div>
        </div>
    </body>
</html>