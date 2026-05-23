<?php include "header.php"; ?>
<?php
include "config.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid Patient ID");
}

$token = getToken();

/* =========================
   🔹 FETCH PATIENT DATA
========================= */
$ch = curl_init(API_URL . "/patients/$id");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$patient = json_decode($response, true);

if (!$patient) {
    die("Patient not found");
}

/* =========================
   🔹 UPDATE PATIENT
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $updateData = [
        "name" => $_POST['name'],
        "email" => $_POST['email'],
        "phone" => $_POST['phone']
    ];

    $ch = curl_init(API_URL . "/patients/$id");

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);

    $updateResponse = curl_exec($ch);

    // Debug if needed
    // echo $updateResponse; exit;

    header("Location: patients.php");
    exit;
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Edit Patient</h2>

    <form method="post">

        <label>Name</label><br>
        <input type="text" name="name" 
               value="<?= $patient['name'] ?>" required>
        <br><br>

        <label>Email</label><br>
        <input type="email" name="email" 
               value="<?= $patient['email'] ?>" required>
        <br><br>

        <label>Phone</label><br>
        <input type="text" name="phone" 
               value="<?= $patient['phone'] ?>" required>
        <br><br>

        <button class="btn">Update</button>
        <a class="btn btn-danger" href="patients.php">Cancel</a>

    </form>
</div>

<?php include "footer.php"; ?>