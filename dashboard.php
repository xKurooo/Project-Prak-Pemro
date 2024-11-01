<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turu Coffe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand custom-logo ms-auto"><b>Turu Coffe</b></a>
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
        <div class="sisi-atas">
        <h3 class="awal">All Orders</h3>
        <div class="Tombol_order">
            <form action="order.html" method="GET">
                <button type="submit" class="btn btn-primary">Add Order <i class="bi bi-plus-lg"></i></button>
            </form>
        </div>
        </div>

        <br>

        <div class="sisi-bawah"></div>
        <div class="TABEL">
            <table class="table table-bordered">
                <thead>
                  <tr class="table-secondary">
                    <th scope="col">No</th>
                    <th scope="col">Menu</th>
                    <th scope="col">Condition</th>
                    <th scope="col">Size</th>
                    <th scope="col">Sweetness</th>
                    <th scope="col">Dairy</th>
                    <th scope="col">Topping</th>
                    <th scope="col">Note</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody>
            <?php
            $conn = new mysqli("localhost", "root", "", "turu_coffe");
            $sql = "SELECT * FROM orders";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <th scope='row'>{$no}</th>
                            <td>{$row['menu']}</td>
                            <td>{$row['condition_type']}</td>
                            <td>{$row['size']}</td>
                            <td>{$row['sugar']}</td>
                            <td>{$row['dairy']}</td>
                            <td>{$row['topping']}</td>
                            <td>{$row['note']}</td>
                            <td>
                                <a href='view_dashboard.php?id={$row['id']}' class='btn btn-primary'><i class='bi bi-eye'></i></a>
                                <a href='edit.php?id={$row['id']}' class='btn btn-primary'><i class='bi bi-pencil'></i></a>
                                <a href='delete.php?id={$row['id']}' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'><i class='bi bi-trash3'></i></a>
                            </td>
                          </tr>";
                    $no++;
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No orders found</td></tr>";
            }
            $conn->close();
            ?>
        </tbody>
              </table>
        </div>
    </div>
    