<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "turu_coffe");

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID dari URL
$id = isset($_GET['id']) ? $_GET['id'] : null;

if ($id) {
    // Query untuk mengambil data berdasarkan ID
    $sql = "SELECT * FROM orders WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        die("Order not found.");
    }

    // Pisahkan topping jika disimpan sebagai string
    $order['topping'] = explode(', ', $order['topping']);
} else {
    die("No order ID specified.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Turu Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand custom-logo ms-auto"><b>Turu Coffee</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="sisi-kiri">
            <div class="logo">
                Edit Order - Turu Coffee
            </div>
            <hr>
            <form action="update_order.php" method="POST">    
                <!-- Kirim ID sebagai field tersembunyi -->
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <div class="Menu">
                    <label for="menu"> <b>MENU</b> </label>
                    <br>
                    <select class="form-select" name="menu" id="menu" aria-label="Default select example">
                        <option value="Americano" <?php if ($order['menu'] == 'Americano') echo 'selected'; ?>>Americano</option>
                        <option value="Mochaccino" <?php if ($order['menu'] == 'Mochaccino') echo 'selected'; ?>>Mochaccino</option>
                        <option value="Hazelnut Latte" <?php if ($order['menu'] == 'Hazelnut Latte') echo 'selected'; ?>>Hazelnut Latte</option>
                        <option value="Vanilla Latte" <?php if ($order['menu'] == 'Vanilla Latte') echo 'selected'; ?>>Vanilla Latte</option>
                        <option value="Salted Caramel" <?php if ($order['menu'] == 'Salted Caramel') echo 'selected'; ?>>Salted Caramel</option>
                    </select>
                </div>
                <br>
                <div class="Kondisi">
                    <label><b>CONDITION</b></label>
                    <br>
                    <input class="form-check-input" type="radio" name="condition" id="hot" value="Hot" <?php if ($order['condition_type'] == 'Hot') echo 'checked'; ?>>
                    <label class="form-check-label" for="hot"> Hot </label>
                    <input class="form-check-input" type="radio" name="condition" id="ice" value="Ice" <?php if ($order['condition_type'] == 'Ice') echo 'checked'; ?>>
                    <label class="form-check-label" for="ice"> Ice </label>
                </div>
                <br>
                <div class="Ukuran">
                    <label><b>SIZE</b></label>
                    <br>
                    <input class="form-check-input" type="radio" name="size" id="regular" value="Regular" <?php if ($order['size'] == 'Regular') echo 'checked'; ?>>
                    <label class="form-check-label" for="regular"> Regular </label>
                    <input class="form-check-input" type="radio" name="size" id="large" value="Large" <?php if ($order['size'] == 'Large') echo 'checked'; ?>>
                    <label class="form-check-label" for="large"> Large </label>
                </div>
                <br>
                <div class="Gula">
                    <label><b>SUGAR LEVEL</b></label>
                    <br>
                    <input class="form-check-input" type="radio" name="sugar" id="normal" value="Normal Sugar" <?php if ($order['sugar'] == 'Normal Sugar') echo 'checked'; ?>>
                    <label class="form-check-label" for="normal"> Normal Sugar </label>
                    <input class="form-check-input" type="radio" name="sugar" id="less" value="Less Sugar" <?php if ($order['sugar'] == 'Less Sugar') echo 'checked'; ?>>
                    <label class="form-check-label" for="less"> Less Sugar </label>
                </div>
                <br>
                <div class="Susu">
                    <label><b>DAIRY</b> <sup> (optional) </sup></label>
                    <br>
                    <input class="form-check-input" type="radio" name="dairy" id="milk" value="Milk" <?php if ($order['dairy'] == 'Milk') echo 'checked'; ?>>
                    <label class="form-check-label" for="milk"> Milk </label>
                    <input class="form-check-input" type="radio" name="dairy" id="oat_milk" value="Oat Milk" <?php if ($order['dairy'] == 'Oat Milk') echo 'checked'; ?>>
                    <label class="form-check-label" for="oat_milk"> Oat Milk </label>
                    <input class="form-check-input" type="radio" name="dairy" id="almond_milk" value="Almond Milk" <?php if ($order['dairy'] == 'Almond Milk') echo 'checked'; ?>>
                    <label class="form-check-label" for="almond_milk"> Almond Milk </label>
                </div>
                <br>
                <div class="Topping">
                    <label><b>TOPPINGS</b> <sup> (optional) </sup></label>
                    <br>
                    <input class="form-check-input" type="checkbox" name="topping[]" value="Caramel Sauce" <?php if (in_array('Caramel Sauce', $order['topping'])) echo 'checked'; ?>>
                    <label class="form-check-label"> Caramel Sauce </label>
                    <br>
                    <input class="form-check-input" type="checkbox" name="topping[]" value="Caramel Crumble" <?php if (in_array('Caramel Crumble', $order['topping'])) echo 'checked'; ?>>
                    <label class="form-check-label"> Caramel Crumble </label>
                    <br>
                    <input class="form-check-input" type="checkbox" name="topping[]" value="Choco Granola" <?php if (in_array('Choco Granola', $order['topping'])) echo 'checked'; ?>>
                    <label class="form-check-label"> Choco Granola </label>
                    <br>
                    <input class="form-check-input" type="checkbox" name="topping[]" value="Sea Salt Cream" <?php if (in_array('Sea Salt Cream', $order['topping'])) echo 'checked'; ?>>
                    <label class="form-check-label"> Sea Salt Cream </label>
                </div>
                <br>
                <div class="Note">
                    <label><b>NOTE</b></label>
                    <br>
                    <textarea class="form-control" name="note"><?php echo htmlspecialchars($order['note']); ?></textarea>
                </div>
                <br>
                <div class="Tombol_submit">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>

        <div class="sisi-kanan">
            <img src="https://i.pinimg.com/564x/57/da/ba/57daba61aad2b83b6f8ccbfb6168a0f6.jpg" alt="Gambar Kopi">
        </div>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
