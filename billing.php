<?php
require_once 'auth.php';
require_role(['Admin', 'Cashier']);
require 'db.php';

// Handle billing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO Billing (patient_id, appointment_id, amount, status, bill_date) VALUES (?,?,?,?,?)");
    $stmt->execute([$_POST['patient_id'], $_POST['appointment_id'], $_POST['amount'], $_POST['status'], $_POST['bill_date']]);
    echo "<div class='alert alert-success'>Bill generated!</div>";
}

$patients = $pdo->query("SELECT * FROM Patients")->fetchAll();
$appointments = $pdo->query("SELECT a.appointment_id, p.name AS patient, d.name AS doctor, a.appointment_date
    FROM Appointments a
    JOIN Patients p ON a.patient_id=p.patient_id
    JOIN Doctors d ON a.doctor_id=d.doctor_id")->fetchAll();
$bills = $pdo->query("SELECT b.*, p.name AS patient 
    FROM Billing b 
    JOIN Patients p ON b.patient_id=p.patient_id 
    ORDER BY b.bill_date DESC")->fetchAll();
?>

<h2>Billing</h2>
<form method="post" class="mb-3">
    <div class="row">
        <div class="col">
            <select name="patient_id" class="form-control" required>
                <option value="">--Patient--</option>
                <?php foreach($patients as $p): ?>
                    <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col">
            <select name="appointment_id" class="form-control" required>
                <option value="">--Appointment--</option>
                <?php foreach($appointments as $a): ?>
                    <option value="<?= $a['appointment_id'] ?>">
                        <?= $a['patient'] ?> with <?= $a['doctor'] ?> (<?= $a['appointment_date'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col"><input name="amount" type="number" step="0.01" class="form-control" placeholder="Amount" required></div>
        <div class="col">
            <select name="status" class="form-control">
                <option>Unpaid</option>
                <option>Paid</option>
            </select>
        </div>
        <div class="col"><input type="date" name="bill_date" class="form-control" required></div>
        <div class="col"><button class="btn btn-primary">Generate</button></div>
    </div>
</form>

<table class="table table-bordered">
<tr><th>ID</th><th>Patient</th><th>Appointment</th><th>Amount</th><th>Status</th><th>Date</th></tr>
<?php foreach($bills as $b): ?>
<tr>
    <td><?= $b['bill_id'] ?></td>
    <td><?= htmlspecialchars($b['patient']) ?></td>
    <td><?= $b['appointment_id'] ?></td>
    <td><?= $b['amount'] ?></td>
    <td><?= $b['status'] ?></td>
    <td><?= $b['bill_date'] ?></td>
</tr>
<?php endforeach; ?>
</table>

