<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TIMELESS TREASURES</title>
    
        <link rel="stylesheet" href="css/home.css">
        <link rel="stylesheet" href="css/add_product.css">
</head>
<body>
    <div id="heading">
        <div>
            <header>&nbsp; &nbsp; &nbsp; &nbsp; we open:Mon-Sat 8:00-17:00 &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
        &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp(+250)788887505 | timelesstre@gmail.com |&nbsp &nbsp </header>
        </div>
        <div id="wlcm">
            <h1><center> WELCOME TO TIMELESS TREASURES</center> </h1>
        <div id="link">
            <nav>
        <a href="index.php">HOME</a>
        <a href="all_products.php">ALL PRODUCTS</a>
        <a href="add_product.php">ADD PRODUCT</a>
        <a href="about.php">ABOUT US</a>
        <a href="contact.php">CONTACT US</a>
        <a href="logout.php">LOG OUT</a>
        </nav>
        </div>
    </div>
</div>

<?php
// Database connection settings
$servername = "localhost";
$username = "root";  // Replace with your database username
$password = "";      // Replace with your database password
$dbname = "product_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the product ID from the URL
$product_id = $_GET['id'];

// Fetch product details from the database
$sql = "SELECT * FROM products WHERE id = '$product_id'";
$result = $conn->query($sql);
$product = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated form data
    $product_name = $_POST['productName'];
    $price = $_POST['price'];
    $published_date = $_POST['publishedDate'];

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/';

        // Create the uploads directory if it doesn't exist
        if (!is_dir($image_folder)) {
            mkdir($image_folder, 0777, true);
        }

        $image_path = $image_folder . basename($image_name);
        move_uploaded_file($image_tmp_name, $image_path);

        // Delete the old image file from the server (optional)
        if (file_exists($product['image_path'])) {
            unlink($product['image_path']);
        }
    } else {
        // Keep the old image if no new image is uploaded
        $image_path = $product['image_path'];
    }

    // Update the product data in the database
    $sql_update = "UPDATE products SET product_name = '$product_name', price = '$price', image_path = '$image_path', published_date = '$published_date' WHERE id = '$product_id'";

    if ($conn->query($sql_update) === TRUE) {

        ?>
<script>
    alert('New product uploaded successfully');
</script>
<?php 
include('all_products.php');
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>

<!-- HTML Form for updating product -->
<h2>Update Product</h2>
<div class="form-container">
<form action="update-product.php?id=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
    <label for="productName">Product Name:</label><br>
    <input type="text" id="productName" name="productName" value="<?php echo $product['product_name']; ?>" required><br><br>

    <label for="price">Price:</label><br>
    <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" step="0.01" required><br><br>

    <label for="publishedDate">Published Date:</label><br>
    <input type="date" id="publishedDate" name="publishedDate" value="<?php echo $product['published_date']; ?>" required><br><br>

    <label for="image">Product Image:</label><br>
    <input type="file" id="image" name="image" accept="image/*"><br><br>

    <input type="submit" value="Update Product">
</form>
</div>