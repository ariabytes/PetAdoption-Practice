<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "petadoption_db";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Adoption Center | Home</title>
</head>

<body>
    <div class="container text-center mt-5">
        <h1>Welcome to the Adoption Center</h1>
        <p class="lead">Find your perfect pet and give them a loving home!</p>
        <div class="d-grid gap-3 d-md-block">
            <a href="pet.php" class="btn btn-primary btn-sm m-3">View Pets</a>
            <a href="category.php" class="btn btn-secondary btn-sm m-3">View Categories</a>
        </div>

        <hr>

        <div class="container p-4 mb-4">
            <h2>Available Pets</h2>
            <div class="row g-4 mt-3 p-4 mb-4 justify-content-center">
                <?php
                $pets = $conn->query("SELECT * FROM Pet_Tbl WHERE Status='Available'");
                while ($row = $pets->fetch_assoc()) {
                    echo "
                    <div class='col-md-4 mb-4'>
                        <div class='card'>
                            <img src='data:image/jpeg;base64," . base64_encode($row['Pet_Image']) . "' class='card-img-top' style='height:200px; object-fit:cover;'>
                            <div class='card-body'>
                                <h5 class='card-title'>" . $row['Pet_Name'] . "</h5>
                                <p class='card-text'>" . $row['Pet_Description'] . "</p>
                                <span class='badge bg-success'>" . $row['Category_Name'] . "</span>
                                <p class='mt-2'><strong>Fee: ₱" . $row['Adoption_Fee'] . "</strong></p>
                            </div>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>

<?php $conn->close(); ?>