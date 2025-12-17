<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$host = "localhost";
$user = "root";
$pass = "";
$db   = "testdb";

$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("Ulanish xatosi: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS `$db`
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

$conn->select_db($db);

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ism VARCHAR(50) NOT NULL,
    familya VARCHAR(50) NOT NULL,
    yoshi INT NOT NULL,
    kasbi VARCHAR(50),
    maoshi DECIMAL(12,2) DEFAULT 0
)");



if (isset($_POST['add'])) {
    $ism     = $conn->real_escape_string($_POST['ism']);
    $familya = $conn->real_escape_string($_POST['familya']);
    $yoshi   = (int)$_POST['yoshi'];
    $kasbi   = $conn->real_escape_string($_POST['kasbi']);
    $maoshi  = (float)$_POST['maoshi'];

    $conn->query("INSERT INTO users (ism,familya,yoshi,kasbi,maoshi)
                  VALUES ('$ism','$familya',$yoshi,'$kasbi',$maoshi)");
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM users WHERE id=$id");
    header("Location: index.php");
    exit;
}

if (isset($_POST['update'])) {
    $id      = (int)$_POST['id'];
    $ism     = $conn->real_escape_string($_POST['ism']);
    $familya = $conn->real_escape_string($_POST['familya']);
    $yoshi   = (int)$_POST['yoshi'];
    $kasbi   = $conn->real_escape_string($_POST['kasbi']);
    $maoshi  = (float)$_POST['maoshi'];

    $conn->query("UPDATE users SET
        ism='$ism', familya='$familya', yoshi=$yoshi,
        kasbi='$kasbi', maoshi=$maoshi WHERE id=$id");
    header("Location: index.php");
    exit;
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<html lang="uz">
<head>
<meta charset="UTF-8">
<title>User CRUD</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

<div class="max-w-7xl mx-auto p-6">

<h1 class="text-3xl font-bold mb-6 text-gray-800">
    User CRUD – Bitta index.php
</h1>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

<!-- FORM -->
<div class="bg-white p-6 rounded-lg shadow">
<h2 class="text-xl font-semibold mb-4">Yangi foydalanuvchi</h2>

<form method="POST" class="space-y-3">
<input name="ism" placeholder="Ism" required class="w-full p-2 border rounded">
<input name="familya" placeholder="Familya" required class="w-full p-2 border rounded">
<input name="yoshi" type="number" placeholder="Yoshi" required class="w-full p-2 border rounded">
<input name="kasbi" placeholder="Kasbi" class="w-full p-2 border rounded">
<input name="maoshi" type="number" step="0.01" placeholder="Maoshi" required class="w-full p-2 border rounded">

<button name="add"
class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
Qo‘shish
</button>
</form>
</div>

<!-- TABLE -->
<div class="lg:col-span-2 bg-white rounded-lg shadow overflow-x-auto">

<table class="w-full text-sm">
<thead class="bg-gray-200">
<tr>
<th class="p-3 text-left">ID</th>
<th class="p-3">Ism</th>
<th class="p-3">Familya</th>
<th class="p-3">Yosh</th>
<th class="p-3">Kasb</th>
<th class="p-3">Maosh</th>
<th class="p-3">Amallar</th>
</tr>
</thead>

<tbody>
<?php while ($row = $users->fetch_assoc()): ?>
<tr class="border-b hover:bg-gray-50">
<td class="p-3"><?= $row['id'] ?></td>
<td class="p-3"><?= htmlspecialchars($row['ism']) ?></td>
<td class="p-3"><?= htmlspecialchars($row['familya']) ?></td>
<td class="p-3 text-center"><?= $row['yoshi'] ?></td>
<td class="p-3"><?= htmlspecialchars($row['kasbi']) ?></td>
<td class="p-3 font-semibold text-green-600">
<?= number_format($row['maoshi'],0,' ',' ') ?>
</td>

<td class="p-3 space-y-2">

<a href="?delete=<?= $row['id'] ?>"
onclick="return confirm('O‘chirasizmi?')"
class="block bg-red-500 hover:bg-red-600 text-white text-center rounded py-1">
O‘chirish
</a>

<form method="POST" class="space-y-1">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input name="ism" value="<?= htmlspecialchars($row['ism']) ?>" class="w-full border p-1 rounded">
<input name="familya" value="<?= htmlspecialchars($row['familya']) ?>" class="w-full border p-1 rounded">
<input name="yoshi" type="number" value="<?= $row['yoshi'] ?>" class="w-full border p-1 rounded">
<input name="kasbi" value="<?= htmlspecialchars($row['kasbi']) ?>" class="w-full border p-1 rounded">
<input name="maoshi" type="number" step="0.01" value="<?= $row['maoshi'] ?>" class="w-full border p-1 rounded">

<button name="update"
class="w-full bg-gray-700 hover:bg-gray-800 text-white py-1 rounded">
Saqlash
</button>
</form>

</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>
</div>

</body>
</html>

