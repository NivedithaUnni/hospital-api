<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $data = [
        "patient_id" => $_POST['patient_id'],
        "doctor_id" => $_POST['doctor_id'],
        "appointment_date" => $_POST['date']
    ];

    $ch = curl_init(API_URL . "/appointments");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . getToken()
    ]);

    curl_exec($ch);
    header("Location: appointments.php");
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Add Appointment</h2>

    <form method="post">
        <input name="patient_id" placeholder="Patient ID"><br><br>
        <input name="doctor_id" placeholder="Doctor ID"><br><br>
        <input type="datetime-local" name="date"><br><br>
        <button class="btn">Save</button>
    </form>
</div>

<?php include "footer.php"; ?>