<?php
include("links.php");
require_once('../config/db.php');

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the diary ID is provided in the URL
if (isset($_GET['diary_id'])) {
    $diary_id = $_GET['diary_id'];
    $search_error = '';

    // Handle form submission to add a new topic
    if (isset($_POST['add'])) {
        $topic = $_POST['topic'];

        // Insert query to add new topic
        $query = "INSERT INTO entries (diary_id, title, content) VALUES (?, ?, '')";
        $stmt = $con->prepare($query);
        $stmt->bind_param("is", $diary_id, $topic);
        $stmt->execute();
        $stmt->close();

        // Redirect to refresh the page after adding the topic
        header("Location: {$_SERVER['PHP_SELF']}?diary_id={$diary_id}");
        exit();
    }

    // Handle deletion of topic
    if (isset($_GET['id']) && isset($_GET['type']) && $_GET['type'] == 'delete') {
        $delete_id = $_GET['id'];

        // Query to fetch the topic details for confirmation
        $query = "SELECT title FROM entries WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($title);
            $stmt->fetch();
            
            // Display double confirmation for deletion
            echo "<script>
                    if(confirm('Are you sure you want to delete entry \"{$title}\"?')) {
                        window.location.href = '{$_SERVER['PHP_SELF']}?diary_id={$diary_id}&confirm_delete={$delete_id}';
                    } else {
                        window.location.href = '{$_SERVER['PHP_SELF']}?diary_id={$diary_id}';
                    }
                  </script>";
        } else {
            // If no entry found, redirect back
            header("Location: {$_SERVER['PHP_SELF']}?diary_id={$diary_id}");
            exit();
        }
        
        $stmt->close();
    }

    // Confirm deletion if confirmed by user
    if (isset($_GET['confirm_delete'])) {
        $delete_id = $_GET['confirm_delete'];

        // Delete query
        $query = "DELETE FROM entries WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();

        // Redirect to refresh the page after deletion
        header("Location: {$_SERVER['PHP_SELF']}?diary_id={$diary_id}");
        exit();
    }

    // Query to fetch existing entries for the diary
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];
        $query = "
            SELECT e.id, e.date_created, e.title, e.content 
            FROM entries e
            JOIN tags_entries te ON e.id = te.entry_id
            JOIN tags t ON te.tag_id = t.id
            WHERE e.diary_id = ? AND t.tag_name LIKE ?
        ";
        $stmt = $con->prepare($query);
        $search_param = '%' . $search . '%';
        $stmt->bind_param("is", $diary_id, $search_param);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 0) {
            $_SESSION['search_error'] = "No entries found for the tag \"$search\". Displaying all entries.";
            $query = "SELECT id, date_created, title, content FROM entries WHERE diary_id = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param("i", $diary_id);
            $stmt->execute();
            $result = $stmt->get_result();
        }
        
        $stmt->close();
    } else {
        $query = "SELECT id, date_created, title, content FROM entries WHERE diary_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $diary_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    }
}

mysqli_close($con);
?>

<?php include("../components/navbar.php");?>

<link rel="stylesheet"href="../style/diaries.css">

<div class="container mt-5">
    <div class="dairy_topic">
        <div class="diary_topic_row d-flex">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>?diary_id=<?php echo $diary_id; ?>" method="post" id="addTopicForm">
                <div class="toic_add d-flex">
                    <a href="index.php"><i class="fas fa-arrow-left"></i></a>
                    <input type="text" name="topic" placeholder="Add Your Page Here.." required>
                    <button type="submit" name="add"><i class="fas fa-plus"></i></button>              
                </div>
            </form>
        </div>
        <div class="diary_display">
            <div>
                <h2>Your Added Page</h2>
                <div class="container pl-5 pr-5">
                    <form class="d-flex justify-content-center py-2" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateSearch()" id="searchForm">
                        <input type="hidden" name="diary_id" value="<?php echo $diary_id; ?>">
                        <input class="form-control me-2" type="search" name="search" id="search" placeholder="Search by tag" aria-label="Search">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
                <?php if (isset($_SESSION['search_error'])) { ?>
                    <div id="errorMessage" style="text-align:center;color:red;font-weight:bold;">
                        <?php
                        echo $_SESSION['search_error'];
                        unset($_SESSION['search_error']); // Clear the error message
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="topic_display">
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($res_row = $result->fetch_assoc()) { ?>
                        <div class="topic_body d-flex">
                            <p><?php echo htmlspecialchars($res_row['title']); ?></p>
                            <ul class="d-flex">
                                <li>
                                    <a href="content.php?diary_id=<?php echo $diary_id; ?>&id=<?php echo $res_row['id']; ?>"><i class="fas fa-edit"></i></a>
                                </li>
                                <li>
                                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?diary_id=<?php echo $diary_id; ?>&id=<?php echo $res_row['id']; ?>&type=delete"><i class="fas fa-trash-alt"></i></a>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                <?php } else {
                    echo "<p style='text-align:center;color:red;font-weight:bold'>No Topic Added Please Add Any Topic Name !</p>";
                } ?>
            </div>
        </div>
    </div>
</div>

<script src="../scripts/entries.js"></script>

