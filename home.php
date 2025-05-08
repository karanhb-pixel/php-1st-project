<?php
$isissue = ['Query', 'Feedback', 'Complaint', 'Other'];

// Get edit data if available
$edit_name = $_POST['edit_name'] ?? '';
$edit_email = $_POST['edit_email'] ?? '';
$edit_issue = $_POST['edit_issue'] ?? '';
$edit_comment = $_POST['edit_comment'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
</head>
<body>
    <div class="container">
    <h2 class="heading">Contact Form</h2>
    <div class="submission-data">
    <form action="display.php" method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($edit_name); ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($edit_email); ?>">
        </div>
        
        <div class="form-group">
            <label for="issue">Issue Type:</label>
            <select id="issue" name="issue" required>
                <option value="">Select an issue</option>
                <?php
                foreach($isissue as $issue) {
                    $selected = ($issue === $edit_issue) ? ' selected' : '';
                    echo "<option value=\"$issue\"$selected>$issue</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required><?php echo htmlspecialchars($edit_comment); ?></textarea>
        </div>
        
        <button type="submit">Submit</button>
    </form>

    <script>
        CKEDITOR.replace('comment');
        
        // Set CKEditor content if editing
        var editComment = <?php echo json_encode($edit_comment); ?>;
        if (editComment) {
            CKEDITOR.instances.comment.setData(editComment);
        }
    </script>
    </div>
    </div>
</body>
</html>

