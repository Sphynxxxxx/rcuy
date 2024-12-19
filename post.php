<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "papapdol"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$categories = ['custom-bikes', 'vintage', 'electric', 'maintenance', 'adventure'];

function fetch_posts_by_category($conn, $category) {
    $sql = "SELECT * FROM posts WHERE category = '$category' ORDER BY created_at DESC";
    return $conn->query($sql);
}

function fetch_comments($conn, $post_id) {
    $sql = "SELECT * FROM comments WHERE post_id = $post_id ORDER BY created_at DESC";
    return $conn->query($sql);
}

function fetch_likes_count($conn, $post_id) {
    $sql = "SELECT COUNT(*) AS like_count FROM likes WHERE post_id = $post_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['like_count'];
}

function add_comment($conn, $post_id, $user_name, $comment_text) {
    $sql = "INSERT INTO comments (post_id, user_name, comment_text) VALUES ($post_id, '$user_name', '$comment_text')";
    return $conn->query($sql);
}

function add_like($conn, $post_id, $user_ip) {
    $sql = "INSERT INTO likes (post_id, user_ip) VALUES ($post_id, '$user_ip')";
    return $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['comment_text'], $_POST['post_id'], $_POST['user_name'])) {
        $post_id = $_POST['post_id'];
        $user_name = $_POST['user_name'];
        $comment_text = $_POST['comment_text'];
        add_comment($conn, $post_id, $user_name, $comment_text);
    }

    if (isset($_POST['like_post_id'])) {
        $post_id = $_POST['like_post_id'];
        $user_ip = $_SERVER['REMOTE_ADDR'];  
        add_like($conn, $post_id, $user_ip);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PaPapDol MotoBlogs - Posts</title>
    <link rel="stylesheet" href="css/post.css">
    <link rel="stylesheet" href="css/papap.css">
</head>
<body>

    <header>
        <div class="nav-bar">
            <h1>🔥 PaPapDol: MotoBlogs 🔥</h1>
            <nav>
                <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="post.php">Categories</a></li>
                <li><a href="login.php">Log In</a></li>
                <li><a href="admin.php">Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="post-container">
        <h1>Latest Blog Posts</h1>

        <?php
        foreach ($categories as $category) {
            $result = fetch_posts_by_category($conn, $category);

            if ($result->num_rows > 0) {
                echo '<div class="category-section">';
                echo '<h2>' . ucfirst($category) . ' Posts</h2>';

                while ($row = $result->fetch_assoc()) {
                    $post_id = $row['id'];
                    $post_title = htmlspecialchars($row['title']);
                    $post_content = htmlspecialchars($row['content']);
                    $post_image = $row['image'];

                    echo '<div class="post">';
                    echo '<h3>' . $post_title . '</h3>';
                    echo '<p><strong>Category:</strong> ' . ucfirst($category) . '</p>';

                    if (!empty($post_image)) {
                        echo '<img src="' . $post_image . '" alt="Post Image" class="post-image">';
                    }

                    echo '<p>' . nl2br($post_content) . '</p>';
                    echo '<p><strong>Likes:</strong> ' . fetch_likes_count($conn, $post_id) . '</p>';

                    echo '<form method="POST" action="">
                            <input type="hidden" name="like_post_id" value="' . $post_id . '">
                            <button type="submit">Like</button>
                          </form>';

                    echo '<h4>Comments:</h4>';
                    $comments = fetch_comments($conn, $post_id);
                    if ($comments->num_rows > 0) {
                        while ($comment = $comments->fetch_assoc()) {
                            echo '<p><strong>' . htmlspecialchars($comment['user_name']) . ':</strong> ' . nl2br(htmlspecialchars($comment['comment_text'])) . '</p>';
                        }
                    }

                    echo '<form method="POST" action="">
                            <input type="hidden" name="post_id" value="' . $post_id . '">
                            <input type="text" name="user_name" placeholder="Your name" required>
                            <textarea name="comment_text" placeholder="Your comment" required></textarea>
                            <button type="submit">Post Comment</button>
                          </form>';

                    echo '<hr>';
                    echo '</div>';
                }

                echo '</div>';
            }
        }

        $conn->close();
        ?>

    </div>
</body>
</html>
