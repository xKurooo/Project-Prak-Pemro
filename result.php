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
        <?php
        $conn = new mysqli("localhost", "root", "", "turu_coffe");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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

        $menu = isset($_GET['menu']) ? $_GET['menu'] : 'Undefined';
        $condition = isset($_GET['condition']) ? $_GET['condition'] : 'Ice';
        $size = isset($_GET['size']) ? $_GET['size'] : 'Regular';
        $sugar = isset($_GET['sugar']) ? $_GET['sugar'] : 'Normal Sugar';
        $dairy = isset($_GET['dairy']) ? $_GET['dairy'] : 'None';
        $topping = isset($_GET['topping']) ? (array)$_GET['topping'] : [];  
        $note = isset($_GET['note']) && !empty(trim($_GET['note'])) ? htmlspecialchars($_GET['note']) : '-';

        // Simpan topping sebagai string untuk ditampilkan di database
        $toppingString = implode(', ', $topping);

        $sql = "INSERT INTO orders (menu, condition_type, size, sugar, dairy, topping, note) 
        VALUES ('$menu', '$condition', '$size', '$sugar', '$dairy', '$toppingString', '$note')";

        if ($conn->query($sql) === TRUE) {
         echo "Order success!";
        } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();

        $totalPrice = 0;
        $tax = 0;

        $hargamenuDipilih = 0;
        if (isset($hargamenu[$menu])) {
            $hargamenuDipilih = $hargamenu[$menu];
            $taxRate = $taxmenu[$menu];
            $tax = $hargamenuDipilih * $taxRate;
            $totalPrice += $hargamenuDipilih + $tax;
        } else {
            echo "<div class='alert alert-danger'>Menu tidak valid atau belum dipilih.</div>";
        }

        $hargasize = isset($hargasize[$size]) ? $hargasize[$size] : 0;
        $totalPrice += $hargasize;

        $hargadairy = isset($hargadairy[$dairy]) ? $hargadairy[$dairy] : 0;
        $totalPrice += $hargadairy;

        // Menghitung total harga topping
        $totalToppingPrice = 0;
        foreach ($topping as $selectedTopping) {
            if (isset($hargatopping[$selectedTopping])) {
                $totalToppingPrice += $hargatopping[$selectedTopping];
            }
        }

        $totalPrice += $totalToppingPrice;
        ?>
    
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
            <li>Size Price: <strong>Rp. <?php echo number_format($hargasize, 0, ',', '.'); ?></strong></li>
            <li>Dairy Price: <strong>Rp. <?php echo number_format($hargadairy, 0, ',', '.'); ?></strong></li>
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
