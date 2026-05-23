<?php include "header.php"; ?>
<?php 
include "config.php"; 

$token = getToken();

/* =========================
   🔹 FETCH DATA FROM API
========================= */

// Patients
$ch = curl_init(API_URL . "/patients");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
$patients = json_decode(curl_exec($ch), true) ?? [];

// Doctors
$ch = curl_init(API_URL . "/doctors");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
$doctors = json_decode(curl_exec($ch), true) ?? [];

// Appointments
$ch = curl_init(API_URL . "/appointments");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
$appointments = json_decode(curl_exec($ch), true) ?? [];

// COUNTS
$totalPatients = count($patients);
$totalDoctors = count($doctors);
$totalAppointments = count($appointments);

// STATUS COUNT
$scheduled = 0;
$completed = 0;
$cancelled = 0;

foreach ($appointments as $a) {
    $status = $a['status'] ?? 'Scheduled';

    if ($status == 'Completed') $completed++;
    elseif ($status == 'Cancelled') $cancelled++;
    else $scheduled++;
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="container">

    <h2 style="color:#0984e3;">🏥 Hospital Dashboard</h2>

    <!-- SUMMARY CARDS -->
    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">

        <div class="card">
            <h4>Total Patients</h4>
            <h2><?= $totalPatients ?></h2>
        </div>

        <div class="card">
            <h4>Total Doctors</h4>
            <h2><?= $totalDoctors ?></h2>
        </div>

        <div class="card">
            <h4>Total Appointments</h4>
            <h2><?= $totalAppointments ?></h2>
        </div>

    </div>

    <!-- CHARTS -->
    <div style="display:flex; gap:20px; flex-wrap:wrap;">

        <!-- DONUT CHART -->
        <div class="card" style="flex:1; min-width:300px;">
            <h3>Appointment Status</h3>
            <canvas id="statusChart"></canvas>
        </div>

        <!-- BAR CHART -->
        <div class="card" style="flex:1; min-width:300px;">
            <h3>System Overview</h3>
            <canvas id="overviewChart"></canvas>
        </div>

    </div>

</div>

<?php include "footer.php"; ?>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// =====================
// DONUT CHART (STATUS)
// =====================
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Scheduled', 'Completed', 'Cancelled'],
        datasets: [{
            data: [
                <?= $scheduled ?>,
                <?= $completed ?>,
                <?= $cancelled ?>
            ],
            backgroundColor: [
                '#74b9ff',  // scheduled
                '#0984e3',  // completed
                '#dfe6e9'   // cancelled
            ],
            borderWidth: 0
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#2d3436',
                    font: { size: 13 }
                }
            }
        }
    }
});


// =====================
// BAR CHART (OVERVIEW)
// =====================
new Chart(document.getElementById('overviewChart'), {
    type: 'bar',
    data: {
        labels: ['Patients', 'Doctors', 'Appointments'],
        datasets: [{
            data: [
                <?= $totalPatients ?>,
                <?= $totalDoctors ?>,
                <?= $totalAppointments ?>
            ],
            backgroundColor: [
                '#74b9ff',
                '#4dabf7',
                '#0984e3'
            ],
            borderRadius: 12
        }]
    },
    options: {
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: '#555' }
            },
            x: {
                ticks: { color: '#555' }
            }
        }
    }
});

</script>