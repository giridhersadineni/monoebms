<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Message</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php
        // Get the error message from the URL parameter
        $message = isset($_GET['message']) ? $_GET['message'] : '';

        // Check if the error message is not empty
        if (!empty($message)) {
            // Sanitize the error message to prevent XSS attacks
            $sanitized_message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
            
            // Display the error message
            echo "<div class='alert alert-danger' role='alert'>Error: $sanitized_message</div>";
        } else {
            echo "<div class='alert alert-info' role='alert'>No error message provided.</div>";
        }

        // Get the referrer page URL
        $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'javascript:history.back()';
        ?>

        <!-- Back button -->
        <a href="<?php echo $referrer; ?>" class="btn btn-primary">Go Back</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
