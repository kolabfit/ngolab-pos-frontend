<?php
require_once('../logic/loginvalidation.php');
Validation::validateLoginCashier($_COOKIE['auth_token'] ?? null, '../logic/login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Halaman Kasir</h1>
</body>
</html>