<?php 

    function set_flash($key, $message) {
        $_SESSION['flash'][$key] = $message;
    }

    function get_flash($key) {
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }
?>