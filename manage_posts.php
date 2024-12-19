<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "papapdol"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete_id'])) {
    $post_id = $_GET['delete_id'];
    $sql = "DELETE FROM posts WHERE id='$post_id'";
    if ($conn->query($sql) !== TRUE) {
        echo "Error deleting record: " . $conn->error;
    }
}

$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Blog Posts - PaPapDol MotoBlogs</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: #ff4500;
            color: white;
        }

        td {
            background-color: #2a2a2a;
            color: white;
        }

        td img {
            width: 100px;
            height: auto;
        }

        .post-actions a {
            padding: 5px 10px;
            margin-right: 10px;
            color: #fff;
            background-color: #ff4500;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .post-actions a:hover {
            background-color: #ff5722;
        }

        .delete-btn {
            background-color: #e53935;
        }

        .delete-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <div class="cms-container">
        <h1>🏍️ Manage Blog Posts 🏍️</h1>

        <p><a href="admin.php">Create New Post</a></p>

        <hr>

        <!-- Display existing posts -->
        <h2>Existing Posts</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Image</th>
                    <th>Content</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['title'] ?></td>
                            <td>
                                <?php if ($row['image'] != ''): ?>
                                    <img src="<?= $row['image'] ?>" alt="Post Image">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?= substr($row['content'], 0, 100) ?>...</td>
                            <td class="post-actions">
                                <a href="edit_post.php?edit_id=<?= $row['id'] ?>">Edit</a>
                                <a href="manage_posts.php?delete_id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">No posts available.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <hr>
    </div>
</body>
</html>
