<?php
include "config.php";

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Invalid Appointment ID");
}

$token = getToken();

/* =========================
   🔹 FETCH APPOINTMENT
========================= */
$ch = curl_init(API_URL . "/appointments/$id");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);

$response = curl_exec($ch);
$current = json_decode($response, true);

if (!$current) {
    die("Appointment not found");
}

/* =========================
   🔹 UPDATE DATA
========================= */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $updateData = [
        "patient_id" => $_POST['patient_id'],
        "doctor_id" => $_POST['doctor_id'],
        "appointment_date" => $_POST['date'],
        "status" => $_POST['status'] // ✅ FIX
    ];

    $ch = curl_init(API_URL . "/appointments/$id");

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($updateData));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);

    $updateResponse = curl_exec($ch);

    header("Location: appointments.php");
    exit;
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Edit Appointment</h2>

    <form method="post">

        <label>Patient ID</label><br>
        <input name="patient_id" 
               value="<?= $current['patient_id'] ?>" required>
        <br><br>

        <label>Doctor ID</label><br>
        <input name="doctor_id" 
               value="<?= $current['doctor_id'] ?>" required>
        <br><br>

        <label>Date & Time</label><br>
        <input type="datetime-local" name="date"
               value="<?= date('Y-m-d\TH:i', strtotime($current['appointment_date'])) ?>"
               required>
        <br><br>

        <!-- ✅ STATUS DROPDOWN -->
        <label>Status</label><br>
        <select name="status">
            <option value="Scheduled" <?= ($current['status'] == 'Scheduled') ? 'selected' : '' ?>>Scheduled</option>
            <option value="Completed" <?= ($current['status'] == 'Completed') ? 'selected' : '' ?>>Completed</option>
            <option value="Cancelled" <?= ($current['status'] == 'Cancelled') ? 'selected' : '' ?>>Cancelled</option>
        </select>
        <br><br>

        <button class="btn">Update</button>
        <a class="btn btn-danger" href="appointments.php">Cancel</a>

    </form>
</div>

<?php include "footer.php"; ?>