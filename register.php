<?php
    $message = "";
    $error = false;
    require __DIR__ .'/config.php';

    if (isset($_SESSION['username'])){
        header("Location: cart.php");
    }

    if($_SERVER['REQUEST_METHOD'] === "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $retype_password = $_POST['retype_password'];

        if (empty($_POST['username'])) {
            $message = "Username is required";
            $error = true;
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Invalid email format';
            $error = true;
        } else if (strlen($password) < 8) {
            $message = "Password must be at least 8 characters long";
            $error = true;
        } else if (!preg_match('/[A-Z]/', $password)) {
            $message = "Password must contain at least one uppercase letter";
            $error = true;
        } else if (!preg_match('/[0-9]/', $password)) {
            $message = "Password must contain at least one number";
            $error = true;
        } else if (!preg_match('/[\W_]/', $password)) {
            $message = "Password must contain at least one special character";
            $error = true;
        } else if ($password !== $retype_password) {
            $message = "Passwords do not match";
            $error = true;
        }

        $sql = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
        $result = $conn->prepare($sql);
        $result->bind_param("ss", $username, $email);
        $result->execute();
        $result = $result->get_result();
        if ($result->num_rows > 0) {
            $message = "Username or email already exists";
            $error = true;
        }
        if (!$error){
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, username, password_hash) VALUES (?, ?, ?)";
            # get 
            $stmt = $conn->prepare($sql);
            if ($stmt) {
                $stmt->bind_param("sss", $email, $username, $password_hash);
                if ($stmt->execute()) {
                    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
                    $stmt->bind_param("s", $username);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $username;
                    $_SESSION['type'] = 'user';
                    
                    header("Location: index.php");
                } else {
                    $message = "Registration failed. Please try again.";
                    $error = true;
                }
                $stmt->close();
            } else {
                $message = "Database error: " . $conn->error;
                $error = true;
            }
            $conn->close();
        }

    }
?>

<!doctype html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Sapashoes - Register</title>
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
                        <input type="text" name="email" id="email" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded" value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
                    </div>
                    <div>
                        <label for="username" class="text-sm font-medium text-gray-600">Username</label>
                        <input type="text" name="username" id="username" class="w-full border ease duration-200 focus:outline-zinc-200 focus:ring-zinc-200 hover:outline-zinc-200 px-2 py-1 rounded" value="<?= htmlspecialchars($_POST["username"] ?? "") ?>">
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
            <?php if ($error && strlen($message) > 0): ?>
                <div class="mt-4 bg-red-600 text-white p-2 rounded">
                    <p><?= $message; ?></p>
                </div>
            <?php endif; ?>
            <div class="text-center mt-4">
                <a href="login.php" class="text-gray-500">Already have an account? <span class="underline">Login.</span></a>
            </div>
        </div>
    </body>
</html>