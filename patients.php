<?php
require_once 'auth.php';
require_role(['Admin', 'Receptionist']);
require_once 'db.php';

// Handle patient creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO Patients (name, gender, dob, contact, address) VALUES (?,?,?,?,?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['gender'],
        $_POST['dob'],
        $_POST['contact'],
        $_POST['address']
    ]);
    header("Location: patients.php");
    exit;
}

// Fetch patients
$patients = $pdo->query("SELECT * FROM Patients ORDER BY patient_id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Patients - SmartHealth</title>
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
        }

        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        .btn-primary {
            margin-top: 10px;
        }

        a.nav-link {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2>Patient Records</h2>
        <a href="index.php" class="btn btn-secondary btn-sm me-2">Home</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>

        <div class="form-section mt-4">
            <h3>Add New Patient</h3>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="dob" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Contact</label>
                    <input type="text" name="contact" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add Patient</button>
            </form>
        </div>

        <h3>Existing Patients</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>DOB</th>
                        <th>Contact</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($patients as $p): ?>
                        <tr>
                            <td><?= $p['patient_id'] ?></td>
                            <td><?= htmlspecialchars($p['name']) ?></td>
                            <td><?= $p['gender'] ?></td>
                            <td><?= $p['dob'] ?></td>
                            <td><?= $p['contact'] ?></td>
                            <td><?= htmlspecialchars($p['address']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($patients)) { ?>
                        <tr>
                            <td colspan="6" class="text-center">No patients found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>