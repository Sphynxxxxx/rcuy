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

// Fetch the post to edit
$post = null;  // Initialize the $post variable to avoid undefined error
if (isset($_GET['edit_id'])) {
    $post_id = $_GET['edit_id'];
    $sql = "SELECT * FROM posts WHERE id='$post_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Post exists, fetch data
        $post = $result->fetch_assoc();
    } else {
        // No post found with this ID, redirect or show an error
        echo "Post not found.";
        exit();
    }
}

// Handle Post Editing (When the form is submitted)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $post_id = $_POST['post_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    
    // Handle image upload
    $image = $_POST['existing_image'];  // Keep the existing image if no new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Sanitize the image file name
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        // Validate image file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = $_FILES['image']['type'];

        if (in_array($file_type, $allowed_types)) {
            // Move the uploaded file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $target_file;
            } else {
                echo "Error uploading image.";
                exit();
            }
        } else {
            echo "Only JPG, PNG, or GIF files are allowed.";
            exit();
        }
    }

    // Update post in the database
    $sql = "UPDATE posts SET title='$title', content='$content', category='$category', image='$image' WHERE id='$post_id'";
    if ($conn->query($sql) === TRUE) {
        // Redirect to manage_posts.php after successful update
        header("Location: manage_posts.php");
        exit(); // Stop further execution
    } else {
        echo "Error: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post - PaPapDol MotoBlogs</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="cms-container">
        <h1>Edit Blog Post</h1>

        <!-- Check if the post is fetched before displaying the form -->
        <?php if ($post): ?>
            <!-- Form for editing an existing post -->
            <form action="edit_post.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <input type="hidden" name="existing_image" value="<?= $post['image'] ?>">

                <div class="input-group">
                    <label for="title">Post Title</label>
                    <input type="text" id="title" name="title" value="<?= $post['title'] ?>" required>
                </div>

                <div class="input-group">
                    <label for="content">Post Content</label>
                    <textarea id="content" name="content" rows="10" required><?= $post['content'] ?></textarea>
                </div>

                <div class="input-group">
                    <label for="category">Post Category</label>
                    <select id="category" name="category" required>
                        <option value="custom-bikes" <?= $post['category'] == 'custom-bikes' ? 'selected' : '' ?>>Custom Bikes</option>
                        <option value="vintage" <?= $post['category'] == 'vintage' ? 'selected' : '' ?>>Vintage Bikes</option>
                        <option value="electric" <?= $post['category'] == 'electric' ? 'selected' : '' ?>>Electric Motorcycles</option>
                        <option value="maintenance" <?= $post['category'] == 'maintenance' ? 'selected' : '' ?>>Maintenance Tips</option>
                        <option value="adventure" <?= $post['category'] == 'adventure' ? 'selected' : '' ?>>Adventure Bikes</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="image">Upload Image (Leave blank if no change)</label>
                    <input type="file" id="image" name="image">
                    <?php if ($post['image']): ?>
                        <img src="<?= $post['image'] ?>" alt="Current image" width="150">
                    <?php else: ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="submit-btn">Update Post</button>
            </form>
        <?php else: ?>
            <p>Post not found.</p>
        <?php endif; ?>

    </div>
</body>
</html>
