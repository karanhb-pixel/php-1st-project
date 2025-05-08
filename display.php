<?php
session_start();
require_once 'config.php';

// Get form data - either from POST or session
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['save_to_db'])) {
    // First submission from home.php - store in session
    $_SESSION['form_data'] = [
        'name' => $_POST['name'] ?? '',
        'email' => $_POST['email'] ?? '',
        'issue' => $_POST['issue'] ?? '',
        'comment' => $_POST['comment'] ?? ''
    ];
}

// Get data from session
$name = htmlspecialchars($_SESSION['form_data']['name'] ?? '');
$email = htmlspecialchars($_SESSION['form_data']['email'] ?? '');
$issue = htmlspecialchars($_SESSION['form_data']['issue'] ?? '');
$comment = $_SESSION['form_data']['comment'] ?? '';

// Handle database submission
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_to_db'])) {
    // Validate form data
    if (empty($name) || empty($email) || empty($issue) || empty($comment)) {
        $message = "Error - All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Error - Invalid email format!";
    } else {
        // Proceed to save to database
        
        try {
            $sql = "INSERT INTO contacts (name, email, issue_type, comment) VALUES (:name, :email, :issue, :comment)";
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':issue', $issue);
            $stmt->bindParam(':comment', $comment);
            
            $stmt->execute();
            // echo "New record created successfully";
            $message = "Response saved successfully!";
            
            // Clear the session data after successful save
            unset($_SESSION['form_data']);
        } catch(PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, 'Error') !== false ? 'error' : 'success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h2 class="heading">Form Submission Details</h2>
    <div class="submission-data">
        <div class="field">
            <span class="field-label">Name:</span>
            <div><?php echo $name; ?></div>
        </div>
        
        <div class="field">
            <span class="field-label">Email:</span>
            <div><?php echo $email; ?></div>
        </div>
        
        <div class="field">
            <span class="field-label">Issue Type:</span>
            <div><?php echo $issue; ?></div>
        </div>
        
        <div class="field">
            <span class="field-label">Comment:</span>
            <div class="wysiwyg-content"><?php echo $comment; ?></div>
        </div>
    </div>
    
    <div class="button-group">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input type="hidden" name="save_to_db" value="1">
            <button type="submit" class="save-button">Save Response</button>
        </form>
        <form action="home.php" method="POST">
            <button type="submit" class="edit-button">Edit Submission</button>
        </form>
        <a href="home.php" class="back-link">← Back to Form</a>
    </div>
    </div>
</body>
</html>