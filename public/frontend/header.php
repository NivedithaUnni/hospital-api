<div class="navbar">

    <div class="logo">
        🏥 Hospital System
    </div>

    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="patients.php">Patients</a>
        <a href="doctors.php">Doctors</a>
        <a href="appointments.php">Appointments</a>
    </div>

</div>

<style>
.navbar {
    background: #ffffff;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    position: sticky;
    top: 0;
    z-index: 100;
}

.logo {
    font-size: 18px;
    font-weight: bold;
    color: #0984e3;
}

.nav-links a {
    margin-left: 18px;
    text-decoration: none;
    color: #2d3436;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 8px;
    transition: 0.3s;
}

.nav-links a:hover {
    background: #eaf4ff;
    color: #0984e3;
}
</style>