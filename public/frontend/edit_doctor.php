<?php
include "config.php";

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = [
        "name" => $_POST['name'],
        "specialization" => $_POST['specialization'],
        "email" => $_POST['email']
    ];

    $ch = curl_init(API_URL . "/doctors/$id");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . getToken()
    ]);

    curl_exec($ch);
    header("Location: doctors.php");
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Edit Doctor</h2>

    <form method="post">
        <input name="name" placeholder="Name"><br><br>
        <input name="specialization" placeholder="Specialization"><br><br>
        <input name="email" placeholder="Email"><br><br>
        <button class="btn">Update</button>
    </form>
</div>

<?php include "footer.php"; ?>