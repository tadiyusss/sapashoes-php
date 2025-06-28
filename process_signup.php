<?php
    if (empty($_POST['username'])) {
    die("Username is required");
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    } else if (strlen($_POST['password']) < 8) {
        die("Password must be at least 8 characters long");
    } else if (!preg_match('/[A-Z]/', $_POST['password'])) {
        die("Password must contain at least one uppercase letter");
    } else if (!preg_match('/[0-9]/', $_POST['password'])) {
        die("Password must contain at least one number");
    } else if ($_POST['password'] !== $_POST['retype_password']) {
        die("Passwords do not match");
    }

    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // $password_hash = $_POST['password'];


    $mysqli = require __DIR__ .'/database.php';

    $sql = "INSERT INTO user (email, username, password_hash) VALUES (?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    if( ! $stmt->prepare($sql)){
        die("SQL statement preparation failed: " . $mysqli->error);
    }

    $stmt->bind_param("sss", 
                        $_POST['email'], 
                        $_POST['username'], 
                        $password_hash);

    if ($stmt->execute()){
        echo "Registration successful! You can now <a href='login.php'>login</a>.";
        exit;
    }else{
        if($mysqli->errno === 1062){
            die("Email or username already exists. Please choose a different one.");
        }else{
        die($mysqli->error . " " . $mysqli->errno);
        }
    }
    
    

?>