<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "papapdol"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Post Creation (When the form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Sanitize the image file name
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        // Validate image file type (optional but recommended)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];

        if (!in_array($file_type, $allowed_types)) {
            echo "Only JPG, PNG, or GIF files are allowed.";
            exit();
        }

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $image = $target_file; // Save the file path in the database
        } else {
            echo "Error uploading image.";
            exit();
        }
    }

    // Insert post into the database
    $sql = "INSERT INTO posts (title, content, category, image) VALUES ('$title', '$content', '$category', '$image')";

    // Perform the query, without echoing success message
    if ($conn->query($sql) !== TRUE) {
        // If the query fails, display the error
        echo "Error: " . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management - PaPapDol MotoBlogs</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="cms-container">
        <h1>🏍️ Create a New Blog Post 🏍️</h1>

        <!-- Form for creating a new post -->
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="title">Post Title</label>
                <input type="text" id="title" name="title" placeholder="Enter post title" required>
            </div>

            <div class="input-group">
                <label for="content">Post Content</label>
                <textarea id="content" name="content" rows="10" placeholder="Write your post here..." required></textarea>
            </div>

            <div class="input-group">
                <label for="category">Post Category</label>
                <select id="category" name="category" required>
                    <option value="">Select a category</option>
                    <option value="custom-bikes">Custom Bikes</option>
                    <option value="vintage">Vintage Bikes</option>
                    <option value="electric">Electric Motorcycles</option>
                    <option value="maintenance">Maintenance Tips</option>
                    <option value="adventure">Adventure Bikes</option>
                </select>
            </div>

            <div class="input-group">
                <label for="image">Upload Image</label>
                <input type="file" id="image" name="image">
            </div>

            <button type="submit" class="submit-btn">Post</button>
        </form>

        <hr>
    </div>
</body>
</html>
