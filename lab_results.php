<?php
require_once 'auth.php';
require_role(['Admin', 'LabTech', 'Lab Technician']);
require_once 'db.php';

// Handle lab result creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO LabResults (patient_id, test_type, result, result_date) VALUES (?,?,?,NOW())");
    $stmt->execute([
        $_POST['patient_id'],
        $_POST['test_type'],
        $_POST['result']
    ]);
    header("Location: lab_results.php");
    exit;
}

// Fetch data
$patients = $pdo->query("SELECT * FROM Patients ORDER BY name")->fetchAll();
$results = $pdo->query("
    SELECT lr.*, p.name as patient_name
    FROM LabResults lr
    JOIN Patients p ON lr.patient_id = p.patient_id
    ORDER BY lr.result_date DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lab Results - SmartHealth</title>
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
            background-color: #28a745;
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
        <h2>Lab Results</h2>
        <a href="index.php" class="btn btn-secondary btn-sm me-2">Home</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>

        <div class="form-section mt-4">
            <h3>Add Lab Result</h3>
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
                    <label class="form-label">Test Type</label>
                    <input type="text" name="test_type" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Result</label>
                    <textarea name="result" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Result</button>
            </form>
        </div>

        <h3>Existing Lab Results</h3>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Test</th>
                        <th>Result</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $r): ?>
                        <tr>
                            <td><?= $r['result_id'] ?></td>
                            <td><?= htmlspecialchars($r['patient_name']) ?></td>
                            <td><?= htmlspecialchars($r['test_type']) ?></td>
                            <td><?= htmlspecialchars($r['result']) ?></td>
                            <td><?= $r['result_date'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($results)) { ?>
                        <tr>
                            <td colspan="5" class="text-center">No lab results found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>