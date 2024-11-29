<?php
require_once('./logic/loginvalidation.php');
Validation::isLogin($_COOKIE['auth_token'] ?? null, '../index.php', '../cashier/cashier.php', '../operational/operational.php', '../unrole.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <h1>401 Unauthorized</h1>
    <p>Silahkan Hubungi Admin untuk verifikasi</p>
</body>
</html>