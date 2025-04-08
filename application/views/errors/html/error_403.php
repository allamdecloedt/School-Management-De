<?php
http_response_code(403);
$redirect_url = "http://localhost/School-Management-De/";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="/School-Management-De/assets/backend/css/error_403.css">

</head>
<body>
    <div class="container">
        <div class="lock-container">
            <div class="lock">
                <div class="keyhole"></div>
                <div class="shine"></div>
            </div>
        </div>
        <div class="error-number">403</div>
        <h1>Access Forbidden</h1>
        <p>
            You don't have permission to access this resource.
            <br>Please contact the administrator or return to the homepage.
        </p>

        <a href="<?php echo $redirect_url; ?>" class="button">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Back to Home
        </a>
    </div>

</body>
</html>