<?php

    require_once 'config.php';
    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        header('Location: login.php');
    }

    if (!isset($_GET['id'])) {
        header('Location: cart.php');
    }

    $product_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND owner_id = ?");
    $stmt->bind_param("ii", $product_id, $user_id);
    $stmt->execute();

    $stmt->close();
    header('Location: cart.php');

?>