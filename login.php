<?php
require_once __DIR__.'/auth.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
if(user_login($_POST['username'] ?? '', $_POST['password'] ?? '')){
header('Location: index.php'); exit;
} else {
$error = 'Invalid username or password';
}
}
?>
<div class="row justify-content-center">
<div class="col-md-6">
<div class="card shadow-sm">
<div class="card-body">
<h5 class="card-title">Login</h5>
<?php if(!empty($error)): ?><div class="alert alert-danger"><?= h($error) ?></div><?php endif; ?>
<form method="post">
<input class="form-control mb-2" name="username" placeholder="Username" required>
<input class="form-control mb-2" name="password" type="password" placeholder="Password" required>
<button class="btn btn-primary">Login</button>
</form>
</div>
</div>
</div>
</div>