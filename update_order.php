<?php
$conn = new mysqli("localhost", "root", "", "turu_coffe");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data dari form
$id = $_POST['id'];
$menu = $_POST['menu'];
$condition = $_POST['condition'];
$size = $_POST['size'];
$sugar = $_POST['sugar'];
$dairy = $_POST['dairy'];
$topping = isset($_POST['topping']) ? implode(', ', $_POST['topping']) : '';
$note = $_POST['note'];

// Update data di database
$sql = "UPDATE orders SET menu=?, condition_type=?, size=?, sugar=?, dairy=?, topping=?, note=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssi", $menu, $condition, $size, $sugar, $dairy, $topping, $note, $id);

if ($stmt->execute()) {
    echo "Order updated successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirect ke halaman dashboard atau tempat lain setelah update
header("Location: dashboard.php");
exit();
?>
