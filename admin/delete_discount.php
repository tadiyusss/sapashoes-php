<?php 
    require_once '../config.php';

    if (!isset($_SESSION['username']) || !isset($_SESSION['type'])) {
        header('Location: login.php');
    }

    if ($_SESSION['type'] != 'admin') {
        header("Location: ../index.php");
    }

    if (!isset($_GET['id'])) {
        header('Location: index.php');
    }

    $id = $_GET['id'];
    $stmt = mysqli_prepare($conn, "DELETE FROM discount_code WHERE id = ?");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    header('Location: index.php');

?>