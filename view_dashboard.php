<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "turu_coffe");
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
} else {
    die("No order ID specified.");
}

// Harga dan pajak untuk setiap kategori
$hargamenu = [
    'Americano' => 12000,
    'Mochaccino' => 15000,
    'Hazelnut Latte' => 20000,
    'Vanilla Latte' => 17000,
    'Salted Caramel' => 18000
];
$hargasize = [
    'Regular' => 0,
    'Large' => 5000
];
$hargadairy = [
    'Milk' => 5000,
    'Oat Milk' => 6000,
    'Almond Milk' => 7000,
    'None' => 0
];
$hargatopping = [
    'Caramel Sauce' => 3000,
    'Caramel Crumble' => 4000,
    'Choco Granola' => 4000,
    'Sea Salt Cream' => 5000
];
$taxmenu = [
    'Americano' => 0.10,
    'Mochaccino' => 0.10,
    'Hazelnut Latte' => 0.15,
    'Vanilla Latte' => 0.15,
    'Salted Caramel' => 0.15
];

// Mengambil detail pesanan
$menu = $order['menu'];
$condition = $order['condition_type'];
$size = $order['size'];
$sugar = $order['sugar'];
$dairy = $order['dairy'];
$topping = explode(', ', $order['topping']);
$note = $order['note'];

// Menghitung harga dan pajak
$totalPrice = 0;
$tax = 0;

$hargamenuDipilih = isset($hargamenu[$menu]) ? $hargamenu[$menu] : 0;
$taxRate = isset($taxmenu[$menu]) ? $taxmenu[$menu] : 0;
$tax = $hargamenuDipilih * $taxRate;
$totalPrice += $hargamenuDipilih + $tax;

$hargasizeDipilih = isset($hargasize[$size]) ? $hargasize[$size] : 0;
$totalPrice += $hargasizeDipilih;

$hargadairyDipilih = isset($hargadairy[$dairy]) ? $hargadairy[$dairy] : 0;
$totalPrice += $hargadairyDipilih;

$totalToppingPrice = 0;
foreach ($topping as $selectedTopping) {
    if (isset($hargatopping[$selectedTopping])) {
        $totalToppingPrice += $hargatopping[$selectedTopping];
    }
}
$totalPrice += $totalToppingPrice;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Result - Turu Coffee</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <div class="container" style="position: relative;">
        <div class="PHP">
        <h1>Order Summary</h1>
        <hr>
    
    <div class="alert alert-success" role="alert">
        Menu : <strong><?php echo $menu; ?></strong><br>
        Condition: <strong><?php echo $condition; ?></strong><br>  
        Size: <strong><?php echo $size; ?></strong><br>  
        Sugar: <strong><?php echo $sugar; ?></strong><br>
        Dairy: <strong><?php echo $dairy; ?></strong><br>
        Toppings: <strong><?php echo empty($topping) ? '-' : implode(', ', $topping); ?></strong><br> 
        
        <ul>
            <br>
            <strong> Price : </strong>
            <li>Menu Price: <strong>Rp. <?php echo number_format($hargamenuDipilih, 0, ',', '.'); ?></strong></li>
            <li>Size Price: <strong>Rp. <?php echo number_format($hargasizeDipilih, 0, ',', '.'); ?></strong></li>
            <li>Dairy Price: <strong>Rp. <?php echo number_format($hargadairyDipilih, 0, ',', '.'); ?></strong></li>
            <li>Topping Price: <strong>Rp. <?php echo number_format($totalToppingPrice, 0, ',', '.'); ?></strong></li>
            <li>Tax: <strong>Rp. <?php echo number_format($tax, 0, ',', '.'); ?></strong></li>
        </ul>
        <hr>
        <p style="text-align: center;"><strong>Total Price: Rp <?php echo number_format($totalPrice, 0, ',', '.'); ?></strong></p>
        <hr>
        <p> Note: <strong><?php echo " $note"; ?> </strong> </p>  
    </div>    

        <a href="dashboard.php" class="btn btn-primary mt-3">Back to Dashboard</a>
        </div>
        </div>
        <br>
        
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
