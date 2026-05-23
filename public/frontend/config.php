<?php
session_start();

define("API_URL", "http://localhost:8080/api");

// store token
function getToken() {
    return $_SESSION['token'] ?? '';
}
?>