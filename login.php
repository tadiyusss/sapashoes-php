<?php
    $is_invalid = false;
    require __DIR__ . "/config.php";
    require_once __DIR__ . "/utils/flash.php";
    
    $flash_message = get_flash('error');
    if (isset($_SESSION['username'])){
        header("Location: cart.php");
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $is_invalid = true;
        }

        if (!$is_invalid) {
            $user = $result->fetch_assoc();
            if (!password_verify($password, $user["password_hash"])) {
                $is_invalid = true;
            } else {
                session_start();
                $_SESSION["username"] = $user["username"];
                $_SESSION['type'] = $user["type"];
                $_SESSION['user_id'] = $user['id'];
                header("Location: index.php");
            }
        }

        $stmt->close();
    }
?>

<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Sapashoes - Login</title>
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
            <form  method="POST" class="border rounded p-6 bg-white shadow">
                <div class="mb-6">
                    <h2 class="text-3xl">Login</h2>
                    <p class="text-gray-600 text-sm">Sign in to access your account and continue where you left off.</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label for="username" class="text-sm font-medium text-gray-600">Username</label>
                        <input type="text" name="username" id="username" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>">
                    </div>
                    <div>
                        <label for="password" class="text-sm font-medium text-gray-600">Password</label>
                        <input type="password" name="password" id="password" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded">
                    </div>
                    <div class="flex items-center space-x-1">
                        <input type="checkbox" name="remember_me" id="remember_me">
                        <label for="remember_me"  class="text-sm font-medium text-gray-600">Remember Me</label>
                    </div>
                    <div>
                        <button type="submit" class="bg-zinc-800 hover:bg-zinc-950 ease duration-200 text-white w-full px-4 py-1 rounded">Login</button>
                    </div>
                </div>
            </form>
            <?php if($flash_message): ?>
                <div class="mt-4 bg-red-600 text-white p-2 rounded">
                    <p><?= $flash_message ?></p>
                </div>
            <?php endif; ?>
            <?php if ($is_invalid): ?>
                <div class="mt-4 bg-red-600 text-white p-2 rounded">
                    <p>Invalid username or password</p>
                </div>
            <?php endif; ?>
            <div class="text-center mt-4">
                <a href="register.php" class="text-gray-500">Don't have an account? <span class="underline">Register.</span></a>
            </div>
        </div>
    </body>
</html>