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

$conn->query("CREATE TABLE IF NOT EXISTS category_tbl(
    Category_ID INT AUTO_INCREMENT PRIMARY KEY,
    Category_Name VARCHAR(50) NOT NULL,
    Category_Desc VARCHAR(255) NOT NULL
)");

// EDIT — must be first
$edit_id = "";
$edit_catname = "";
$edit_catdesc = "";

if (isset($_GET['edit_id'])) {
    $edit_id = (int) $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM category_tbl WHERE Category_ID = $edit_id");
    $row = $result->fetch_assoc();
    $edit_catname = $row['Category_Name'];
    $edit_catdesc = $row['Category_Desc'];
}

// INSERT / UPDATE
if (isset($_POST['save'])) {
    $id = $_POST['edit_id'] ?? '';
    $category_name = $_POST['category_name'];
    $category_desc = $_POST['category_desc'];

    if ($id == "") {
        $conn->query("INSERT INTO category_tbl (Category_Name, Category_Desc) VALUES ('$category_name', '$category_desc')");
        header("Location: category.php?success=added");
        exit();
    } else {
        $conn->query("UPDATE category_tbl SET Category_Name='$category_name', Category_Desc='$category_desc' WHERE Category_ID=$id");
        header("Location: category.php?success=updated");
        exit();
    }
}

// DELETE
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $conn->query("DELETE FROM category_tbl WHERE Category_ID=$id");
    header("Location: category.php?success=deleted");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Adoption Center | Category</title>
</head>

<body>
    <a href="index.php" class="btn btn-outline-secondary btn-sm m-3">← Back to Home</a>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mx-3">
            <?php
            if ($_GET['success'] == 'added')   echo 'Category saved successfully!';
            if ($_GET['success'] == 'updated') echo 'Category updated successfully!';
            if ($_GET['success'] == 'deleted') echo 'Category deleted successfully!';
            ?>
        </div>
    <?php endif; ?>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Pet Category</h1>

        <div class="row g-4 justify-content-center">

            <!-- FORM CARD -->
            <div class="card p-4">
                <?php echo $edit_id ? '<h2>Edit Category</h2>' : '<h2>Add New Category</h2>'; ?>
                <hr>
                <form method="POST">
                    <?php if ($edit_id): ?>
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>

                    <label class="form-label">Category Name</label>
                    <input type="text" name="category_name" class="form-control mb-3"
                        value="<?php echo htmlspecialchars($edit_catname); ?>" required>

                    <label class="form-label">Category Description</label>
                    <textarea name="category_desc" class="form-control mb-3" required><?php echo htmlspecialchars($edit_catdesc); ?></textarea>

                    <button type="submit" name="save" class="btn btn-outline-primary">
                        <?php echo $edit_id ? 'Update Category' : 'Save Category'; ?>
                    </button>
                    <?php if ($edit_id): ?>
                        <a href="category.php" class="btn btn-outline-secondary">Cancel</a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- LIST CARD -->
            <div class="card p-4">
                <h2>Category List</h2>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM category_tbl");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['Category_ID'] . "</td>";
                            echo "<td>" . htmlspecialchars($row['Category_Name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Category_Desc']) . "</td>";
                            echo "<td>
                                    <a href='category.php?edit_id=" . $row['Category_ID'] . "' class='btn btn-outline-warning btn-sm'>Edit</a>
                                    <a href='category.php?delete=" . $row['Category_ID'] . "' class='btn btn-outline-danger btn-sm' onclick=\"return confirm('Are you sure?')\">Delete</a>
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