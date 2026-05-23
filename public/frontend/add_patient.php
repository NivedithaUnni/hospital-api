<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = [
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "phone" => $_POST['phone']
    ];

    $ch = curl_init(API_URL . "/patients");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . getToken()
    ]);

    curl_exec($ch);

    header("Location: patients.php");
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Add Patient</h2>

    <form method="post">
        <input name="name" placeholder="Name"><br><br>
        <input name="email" placeholder="Email"><br><br>
        <input name="phone" placeholder="Phone"><br><br>
        <button class="btn">Save</button>
    </form>
</div>

<?php include "footer.php"; ?>