<?php
session_start(); // Ensure session is started
include("links.php");
require_once('../config/db.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if 'id' is provided in the URL
if (!isset($_GET['id']) || !isset($_GET['diary_id'])) {
    header("Location: index.php"); // Redirect to index 
    exit();
}

$entry_id = $_GET['id'];
$diary_id = $_GET['diary_id'];

// Fetch entry details from database
$query = "SELECT title, content, date_created FROM entries WHERE id = ?";
$stmt = $con->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . $con->error);
}
$stmt->bind_param("i", $entry_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $entry = $result->fetch_assoc();
} else {
    echo "Entry not found.";
    exit();
}

$stmt->close();

$new_content = $entry['content'];

// Handle form submission for updating entry
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update'])) {
        $new_content = $con->real_escape_string($_POST['contain_diary']);
        $tags = json_decode($_POST['tags'], true);

        // Update entry in the database
        $update_query = "UPDATE entries SET content = ? WHERE id = ?";
        $update_stmt = $con->prepare($update_query);
        if ($update_stmt === false) {
            die("Prepare failed: " . $con->error);
        }
        $update_stmt->bind_param("si", $new_content, $entry_id);
        if (!$update_stmt->execute()) {
            die("Execute failed: " . $update_stmt->error);
        }

        if ($update_stmt->affected_rows >= 0) { // Success if 0 or more rows affected
            // Delete existing tags
            $delete_tags_query = "DELETE FROM tags_entries WHERE entry_id = ?";
            $delete_stmt = $con->prepare($delete_tags_query);
            if ($delete_stmt === false) {
                die("Prepare failed: " . $con->error);
            }
            $delete_stmt->bind_param("i", $entry_id);
            if (!$delete_stmt->execute()) {
                die("Delete tags failed: " . $delete_stmt->error);
            }

            // Insert new tags
            $insert_tag_query = "INSERT INTO tags_entries (tag_id, entry_id) VALUES (?, ?)";
            $insert_stmt = $con->prepare($insert_tag_query);
            if ($insert_stmt === false) {
                die("Prepare failed: " . $con->error);
            }

            foreach ($tags as $tag_name) {
                // Check if tag exists, insert if not
                $select_tag_query = "SELECT id FROM tags WHERE tag_name = ?";
                $select_stmt = $con->prepare($select_tag_query);
                if ($select_stmt === false) {
                    die("Prepare failed: " . $con->error);
                }
                $select_stmt->bind_param("s", $tag_name);
                $select_stmt->execute();
                $select_result = $select_stmt->get_result();

                if ($select_result->num_rows > 0) {
                    $tag_row = $select_result->fetch_assoc();
                    $tag_id = $tag_row['id'];
                } else {
                    // Insert new tag
                    $insert_new_tag_query = "INSERT INTO tags (tag_name) VALUES (?)";
                    $insert_new_tag_stmt = $con->prepare($insert_new_tag_query);
                    if ($insert_new_tag_stmt === false) {
                        die("Prepare failed: " . $con->error);
                    }
                    $insert_new_tag_stmt->bind_param("s", $tag_name);
                    $insert_new_tag_stmt->execute();
                    $tag_id = $insert_new_tag_stmt->insert_id;
                    $insert_new_tag_stmt->close();
                }

                // Bind parameters and execute insert tag_entry
                $insert_stmt->bind_param("ii", $tag_id, $entry_id);
                if (!$insert_stmt->execute()) {
                    die("Insert tag failed: " . $insert_stmt->error);
                }
            }

            $insert_stmt->close();

            // Redirect to entries.php with diary_id
            header("Location: entries.php?diary_id={$diary_id}");
            exit();
        }

        $update_stmt->close();
    }
}

// Fetch tags associated with the current entry
$tags_query = "SELECT tags.id, tags.tag_name FROM tags 
               JOIN tags_entries ON tags.id = tags_entries.tag_id 
               WHERE tags_entries.entry_id = ?";
$tags_stmt = $con->prepare($tags_query);
if ($tags_stmt === false) {
    die("Prepare failed: " . $con->error);
}
$tags_stmt->bind_param("i", $entry_id);
$tags_stmt->execute();
$tags_result = $tags_stmt->get_result();
$tags = [];
while ($row = $tags_result->fetch_assoc()) {
    $tags[] = $row['tag_name']; // Only store tag names for client-side processing
}
$tags_stmt->close();

mysqli_close($con);
?>

<?php include("../components/navbar.php");?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Diary Entry</title>
    <link rel="stylesheet"href="../style/content.css">
    <script>
        function formatText(command, value = null) {
            document.execCommand(command, false, value);
        }

        function submitForm() {
            const content = document.getElementById('editor').innerHTML;
            document.getElementById('hiddenContent').value = content;

            // Gather tags
            const tagsContainer = document.getElementById('tagsContainer');
            const tags = [];
            tagsContainer.querySelectorAll('.hashtag').forEach(tag => {
                tags.push(tag.textContent.trim().substring(1)); // Remove leading '#'
            });

            // Set tags into a hidden input field
            document.getElementById('hiddenTags').value = JSON.stringify(tags);
        }

        function addTag() {
            const newTag = document.getElementById('newTag').value.trim();
            if (newTag === '') return;

            const tagsContainer = document.getElementById('tagsContainer');
            const tagCount = tagsContainer.querySelectorAll('.hashtag').length;

            if (tagCount >= 5) {
                alert('You can only add up to 5 tags.');
                return;
            }

            const tagElement = document.createElement('span');
            tagElement.className = 'hashtag';
            tagElement.textContent = '#' + newTag;
            tagElement.setAttribute('onclick', 'editTag(this)');

            tagsContainer.appendChild(tagElement);

            // Clear the input field
            document.getElementById('newTag').value = '';
        }

        function editTag(tagElement) {
            // Remove onclick handler temporarily to prevent accidental edits
            tagElement.removeAttribute('onclick');

            // Replace text with input field for editing
            const tagText = tagElement.textContent.substring(1); 
            const inputElement = document.createElement('input');
            inputElement.type = 'text';
            inputElement.value = tagText;
            inputElement.classList.add('tag-input-field');
            inputElement.addEventListener('blur', function() {
                saveTagEdit(tagElement, inputElement);
            });

            tagElement.textContent = ''; // Clear existing text
            tagElement.appendChild(inputElement);

            inputElement.focus(); // Focus on the input field
        }

        function saveTagEdit(tagElement, inputElement) {
            const newTagText = inputElement.value.trim();
            if (newTagText === '') {
                removeTag(tagElement); // If empty, remove the tag
                return;
            }

            tagElement.textContent = '#' + newTagText;
            tagElement.setAttribute('onclick', 'editTag(this)');
        }

        function removeTag(tagElement) {
            tagElement.parentNode.removeChild(tagElement);
        }

        function initTags() {
            const tagsContainer = document.getElementById('tagsContainer');
            const existingTags = <?php echo json_encode($tags); ?>;

            existingTags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'hashtag';
                tagElement.textContent = tag.startsWith('#') ? tag : '#' + tag; // Ensure tag starts with '#'
                tagElement.setAttribute('onclick', 'editTag(this)');

                tagsContainer.appendChild(tagElement);
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            initTags();
        });
        function startsWith($haystack, $needle) {
            $length = strlen($needle);
            return substr($haystack, 0, $length) === $needle;
        }
    </script>
    

</head>
<body>
<!-- Content -->
<div class="container mt-5">
    <div class="containt_diary">
        <form action="" method="post" onsubmit="submitForm()">
            <div class="top_bar d-flex">
                <a href="entries.php?diary_id=<?php echo htmlspecialchars($diary_id); ?>"><i class="fas fa-arrow-left"></i></a>
<h1><?php echo htmlspecialchars($entry['title']); ?></h1>
<button type="submit" name="update"><i class="fas fa-edit"></i> Save Changes</button>
</div>
<div class="mt-3">
<div id="tagsContainer">


</div>
<div class="tag-input">
<input type="text" id="newTag" placeholder="Add new tag">
<button type="button" onclick="addTag()">Add Tag</button>
</div>
</div>
<div class="upadate">
            <p>Created On: <span><?php echo date("d F Y h:i:s", strtotime($entry['date_created'])); ?></span></p>
        </div>
        <div class="editor-toolbar table-responsive">
            <button type="button" onclick="formatText('bold')">Bold</button>
            <button type="button" onclick="formatText('italic')">Italic</button>
            <button type="button" onclick="formatText('underline')">Underline</button>
            <button type="button" onclick="formatText('insertUnorderedList')">Bullet List</button>
            <button type="button" onclick="formatText('insertOrderedList')">Numbered List</button>
            <button type="button" onclick="formatText('justifyLeft')">Align Left</button>
            <button type="button" onclick="formatText('justifyCenter')">Align Center</button>
            <button type="button" onclick="formatText('justifyRight')">Align Right</button>
            <button type="button" onclick="formatText('insertHorizontalRule')">Horizontal Line</button>
        </div>
        <div id="editor" class="editor-content" contenteditable="true"><?php echo $new_content; ?></div>
        <input type="hidden" name="contain_diary" id="hiddenContent">
        <input type="hidden" name="tags" id="hiddenTags">
    </form>
</div>
</div>
</body>
</html>
