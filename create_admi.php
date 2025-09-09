<?php
require_once __DIR__.'/db.php';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = $mysqli->query("SELECT role_id FROM roles WHERE role_name='Admin'")->fetch_assoc();
$stmt=$mysqli->prepare("INSERT INTO users(username,password,full_name,role_id) VALUES(?,?,?,?)");
$stmt->bind_param('sssi','admin',$password,'System Administrator',(int)$role['role_id']);
$stmt->execute();
echo 'Admin created';