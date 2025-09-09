<?php
require_once 'auth.php';
require_role(['Admin', 'Receptionist']);
require_once 'db.php';

// Handle appointment creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO Appointments (patient_id, doctor_id, appointment_date, appointment_time, status) VALUES (?,?,?,?,?)");
    $stmt->execute([
        $_POST['patient_id'],
        $_POST['doctor_id'],
        $_POST['appointment_date'],
        $_POST['appointment_time'],
        'Scheduled'
    ]);
    header("Location: appointments.php");
    exit;
}

// Fetch data
$patients = $pdo->query("SELECT * FROM Patients ORDER BY name")->fetchAll();
$doctors = $pdo->query("SELECT * FROM Doctors ORDER BY name")->fetchAll();
$appointments = $pdo->query("
    SELECT a.*, p.name as patient_name, d.name as doctor_name
    FROM Appointments a
    JOIN Patients p ON a.patient_id = p.patient_id
    JOIN Doctors d ON a.doctor_id = d.doctor_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Appointments - SmartHealth</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        h2,
        h3 {
            margin-top: 20px;
        }

        .form-section {
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        table {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        td {
            text-align: center;
            vertical-align: middle;
        }

        .btn-primary {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2>Appointments</h2>
        <a href="index.php" class="btn btn-secondary btn-sm me-2">Home</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>

        <div class="form-section mt-4">
            <h3>Schedule New Appointment</h3>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Patient</label>
                    <select name="patient_id" class="form-select" required>
                        <?php foreach ($patients as $p): ?>
                            <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Doctor</label>
                    <select name="doctor_id" class="form-select" required>
                        <?php foreach ($doctors as $d): ?>
                            <option value="<?= $d['doctor_id'] ?>"><?= htmlspecialchars($d['name']) ?>
                                (<?= htmlspecialchars($d['specialization']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="appointment_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Time</label>
                    <input type="time" name="appointment_time" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Schedule Appointment</button>
            </form>
        </div>

        <h3>Existing Appointments</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $a): ?>
                        <tr>
                            <td><?= $a['appointment_id'] ?></td>
                            <td><?= htmlspecialchars($a['patient_name']) ?></td>
                            <td><?= htmlspecialchars($a['doctor_name']) ?></td>
                            <td><?= $a['appointment_date'] ?></td>
                            <td><?= $a['appointment_time'] ?></td>
                            <td><?= $a['status'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($appointments)) { ?>
                        <tr>
                            <td colspan="6" class="text-center">No appointments found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>