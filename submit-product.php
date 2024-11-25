<?php
$servername = "localhost";  // your server, e.g., localhost
$username = "root";         // your database username
$password = "";             // your database password (empty by default for localhost)
$dbname = "product_database"; // the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $product_name = $_POST['productName'];
    $price = $_POST['price'];
    $published_date = $_POST['publishedDate'];

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'uploads/'; // Folder to save uploaded images

        // Check if uploads directory exists, if not, create it
        if (!is_dir($image_folder)) {
            mkdir($image_folder, 0777, true);
        }

        // Generate the path where the image will be stored
        $image_path = $image_folder . basename($image_name);

        // Move the uploaded image to the 'uploads' folder
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            echo "";
        } else {
            echo "Error uploading file.<br>";
        }
    } else {
        echo "No image uploaded or error in file upload.<br>";
    }

    // Insert the data into the database
    $sql = "INSERT INTO products (product_name, price, image_path, published_date) 
            VALUES ('$product_name', '$price', '$image_path', '$published_date')";

    // Execute the query and check if the insert was successful
    if ($conn->query($sql) === TRUE) {
?>
<script>
    alert('New product uploaded succesfully');
</script>
<?php

        include('all_products.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>