<?php 

    require_once 'config.php';
    require_once 'utils/flash.php';
    
    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        set_flash('error', 'You must be logged in to add items to the cart.');
        header('Location: login.php');
    }

    if (!isset($_GET['id'])) {
        header('Location: index.php');
    }

    $id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    if (!$row) {
        header('Location: index.php');
    }

    $stmt = $conn->prepare('INSERT INTO cart (owner_id, product_id) VALUES (?, ?)');
    $stmt->bind_param('ii', $user_id, $id);
    $stmt->execute();
    $stmt->close();

    header('Location: cart.php');
?>