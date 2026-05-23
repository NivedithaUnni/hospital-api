<?php
include "config.php";

$ch = curl_init(API_URL . "/appointments");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . getToken()
]);

$response = curl_exec($ch);
$data = json_decode($response, true);
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Appointments</h2>

    <a class="btn" href="add_appointment.php">Add Appointment</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Patient ID</th>
            <th>Doctor ID</th>
            <th>Date</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if (!empty($data)): ?>
            <?php foreach ($data as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= $row['patient_id'] ?></td>
                <td><?= $row['doctor_id'] ?></td>
                <td><?= $row['appointment_date'] ?></td>
                <td><?= $row['status'] ?></td>
                <td>
                    <!-- ✅ EDIT BUTTON -->
                    <a class="btn" href="edit_appointment.php?id=<?= $row['id'] ?>">Edit</a>

                    <!-- ❌ DELETE -->
                    <a class="btn btn-danger"
                       onclick="return confirm('Delete this appointment?')"
                       href="delete_appointment.php?id=<?= $row['id'] ?>">
                       Delete
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">No appointments found</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<?php include "footer.php"; ?>