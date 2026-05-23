<?php include "header.php"; ?>
<?php
include "config.php";

$ch = curl_init(API_URL . "/doctors");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer " . getToken()
]);

$response = curl_exec($ch);
$data = json_decode($response, true);
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">
    <h2>Doctors</h2>

    <a class="btn" href="add_doctor.php">Add Doctor</a>

    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Specialization</th><th>Email</th><th>Action</th>
        </tr>

        <?php foreach ($data as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['specialization'] ?></td>
            <td><?= $row['email'] ?></td>
            <td>
                <a class="btn" href="edit_doctor.php?id=<?= $row['id'] ?>">Edit</a>
                <a class="btn btn-danger" href="delete_doctor.php?id=<?= $row['id'] ?>">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>