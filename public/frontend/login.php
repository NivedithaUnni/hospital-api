<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = [
        "email" => $_POST['email'],
        "password" => $_POST['password']
    ];

    $ch = curl_init(API_URL . "/login");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $response = curl_exec($ch);
    $result = json_decode($response, true);

    if (isset($result['token'])) {
        $_SESSION['token'] = $result['token'];
        header("Location: dashboard.php");
    } else {
        echo "Login failed";
    }
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="email" placeholder="Email"><br><br>
        <input type="password" name="password" placeholder="Password"><br><br>
        <button class="btn">Login</button>
    </form>
</div>