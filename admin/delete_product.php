<?php 

    if (!isset($_GET['id'])) {
        header('Location: index.php');
    }

    $id = $_GET['id'];
    require_once '../config.php';

    # get the product by id
    $query = "SELECT * FROM products WHERE id = ?";
    $result = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($result, 'i', $id);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);
    if ($result->num_rows == 0) {
        header('Location: index.php');
    }

    $product = mysqli_fetch_assoc($result);
    $image_path = '../assets/images/shoes/' . $product['image'];
    if (file_exists($image_path)) {
        unlink($image_path);
    }

    $query = "DELETE FROM products WHERE id = ?";
    $result = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($result, 'i', $id);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);
    header('Location: index.php');
?>