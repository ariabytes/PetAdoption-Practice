<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "petadoption_db";

$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

$conn->query("CREATE TABLE IF NOT EXISTS pet_tbl(
    Pet_ID INT AUTO_INCREMENT PRIMARY KEY,
    Pet_Name VARCHAR(50) NOT NULL,
    Pet_Description VARCHAR(255) NOT NULL,
    Adoption_Fee DECIMAL(10, 2) NOT NULL,
    Category_Name VARCHAR(50) NOT NULL,
    Status VARCHAR(20) NOT NULL DEFAULT 'Available',
    Pet_Image LONGBLOB NOT NULL
)");

// EDIT — must be first
$edit_id = "";
$edit_petname = "";
$edit_petdesc = "";
$edit_adoption_fee = "";
$edit_category_name = "";
$edit_status = "";
$edit_image = "";

if (isset($_GET['edit_id'])) {
    $edit_id = (int) $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM pet_tbl WHERE Pet_ID = $edit_id");
    $row = $result->fetch_assoc();
    $edit_petname = $row['Pet_Name'];
    $edit_petdesc = $row['Pet_Description'];
    $edit_adoption_fee = $row['Adoption_Fee'];
    $edit_category_name = $row['Category_Name'];
    $edit_status = $row['Status'];
    $edit_image = $row['Pet_Image'];
}

// INSERT / UPDATE
if (isset($_POST['save'])) {
    $id = $_POST['edit_id'] ?? '';
    $pet_name = $_POST['pet_name'];
    $pet_desc = $_POST['pet_desc'];
    $adoption_fee = $_POST['adoption_fee'];
    $category_name = $_POST['category_name'];
    $status = $_POST['status'];
    $image = addslashes(file_get_contents($_FILES['Pet_Image']['tmp_name']));

    if ($id == "") {
        $conn->query("INSERT INTO pet_tbl (Pet_Name, Pet_Description, Adoption_Fee, Category_Name, Status, Pet_Image)
                      VALUES ('$pet_name', '$pet_desc', '$adoption_fee', '$category_name', '$status', '$image')");
        header("Location: pet.php?success=added");
        exit();
    } else {
        $conn->query("UPDATE pet_tbl SET Pet_Name='$pet_name', Pet_Description='$pet_desc',
                      Adoption_Fee='$adoption_fee', Category_Name='$category_name',
                      Status='$status', Pet_Image='$image' WHERE Pet_ID=$id");
        header("Location: pet.php?success=updated");
        exit();
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM pet_tbl WHERE Pet_ID=$id");
    header("Location: pet.php?success=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Adoption Center | Pets</title>
</head>

<body>
    <a href="index.php" class="btn btn-outline-secondary btn-sm m-3">← Back to Home</a>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mx-3">
            <?php
            if ($_GET['success'] == 'added')   echo 'Pet saved successfully!';
            if ($_GET['success'] == 'updated') echo 'Pet updated successfully!';
            if ($_GET['success'] == 'deleted') echo 'Pet deleted successfully!';
            ?>
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Pets</h1>

        <div class="row g-4 justify-content-center">

            <!-- FORM CARD -->
            <div class="card p-4">
                <?php echo $edit_id ? '<h2>Edit Pet</h2>' : '<h2>Add New Pet</h2>'; ?>
                <hr>
                <form method="POST" enctype="multipart/form-data">
                    <?php if ($edit_id): ?>
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>

                    <label class="form-label">Pet Name</label>
                    <input type="text" name="pet_name" class="form-control mb-3"
                        value="<?php echo htmlspecialchars($edit_petname); ?>" required>

                    <label class="form-label">Pet Description</label>
                    <textarea name="pet_desc" class="form-control mb-3" required><?php echo htmlspecialchars($edit_petdesc); ?></textarea>

                    <label class="form-label">Adoption Fee</label>
                    <input type="number" name="adoption_fee" class="form-control mb-3"
                        value="<?php echo $edit_adoption_fee; ?>" required>

                    <label class="form-label">Category</label>
                    <select name="category_name" class="form-select mb-3" required>
                        <option value="">Select a category</option>
                        <?php
                        $cat_result = $conn->query("SELECT * FROM category_tbl");
                        while ($cat_row = $cat_result->fetch_assoc()) {
                            $selected = ($edit_category_name == $cat_row['Category_Name']) ? 'selected' : '';
                            echo "<option value='{$cat_row['Category_Name']}' $selected>{$cat_row['Category_Name']}</option>";
                        }
                        ?>
                    </select>

                    <label class="form-label">Status</label>
                    <select name="status" class="form-select mb-3" required>
                        <option value="Available" <?php echo $edit_status == 'Available' ? 'selected' : ''; ?>>Available</option>
                        <option value="Pending" <?php echo $edit_status == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Reserved" <?php echo $edit_status == 'Reserved' ? 'selected' : ''; ?>>Reserved</option>
                        <option value="Under Treatment" <?php echo $edit_status == 'Under Treatment' ? 'selected' : ''; ?>>Under Treatment</option>
                        <option value="Adopted" <?php echo $edit_status == 'Adopted' ? 'selected' : ''; ?>>Adopted</option>
                    </select>

                    <label class="form-label">Pet Image</label>
                    <input type="file" name="Pet_Image" class="form-control mb-3" required>

                    <button type="submit" name="save" class="btn btn-outline-primary">
                        <?php echo $edit_id ? 'Update Pet' : 'Save Pet'; ?>
                    </button>
                    <?php if ($edit_id): ?>
                        <a href="pet.php" class="btn btn-outline-secondary">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- LIST CARD -->
            <div class="card p-4">
                <h2>Pet List</h2>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Fee</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM pet_tbl");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['Pet_ID'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['Pet_Name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Pet_Description']) . "</td>";
                            echo "<td>₱" . $row['Adoption_Fee'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['Category_Name']) . "</td>";
                            echo "<td>" . $row['Status'] . "</td>";
                            echo "<td><img src='data:image/jpeg;base64," . base64_encode($row['Pet_Image']) . "' width='80' height='90' style='object-fit:cover;'></td>";
                            echo "<td>
                                    <a href='pet.php?edit_id=" . $row['Pet_ID'] . "' class='btn btn-outline-warning btn-sm'>Edit</a>
                                    <a href='pet.php?delete=" . $row['Pet_ID'] . "' class='btn btn-outline-danger btn-sm' onclick=\"return confirm('Are you sure?')\">Delete</a>
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>

<?php $conn->close(); ?>