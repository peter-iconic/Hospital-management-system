<?php
require_once __DIR__.'/auth.php';
user_logout();
header('Location: index.php'); exit;