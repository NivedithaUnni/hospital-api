<?php include "header.php"; ?>
<?php
include "config.php";

$ch = curl_init(API_URL . "/patients");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . getToken()
]);

$response = curl_exec($ch);
$data = json_decode($response, true);
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Patients</h2>

    <a class="btn" href="add_patient.php">Add Patient</a>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Action</th>
        </tr>

        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td>
                <a class="btn" href="edit_patient.php?id=<?= $row['id'] ?>">Edit</a>
                <a class="btn btn-danger" href="delete_patient.php?id=<?= $row['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>