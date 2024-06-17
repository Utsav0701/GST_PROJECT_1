<?php
session_start();

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ca_project1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
$loginError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Prepare select query to check if user exists
    $sqlSelect = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $sqlSelect);

    // Check if user exists
    if (mysqli_num_rows($result) > 0) {
        // User exists, set session variable and redirect to dashboard or home page
        $_SESSION["username"] = $username;
        header("Location: display.php"); // Redirect to dashboard page
        exit();
    } else {
        // User does not exist, display error message
        $loginError = "Invalid username or password";
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
   body {
    background: linear-gradient(to right, #f7f9fc, #e8eff7); /* Light blue gradient */
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
    color: #333; /* Dark text for contrast */
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    animation: fadeIn 1s ease-in-out;
    background-color: #ffffff; /* White background */
    color: #333; /* Dark text */
}

.card-header {
    background: #3498db; /* Vibrant blue */
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
    text-align: center;
    font-size: 1.5rem;
    color: #ffffff; /* White text */
}

.form-control {
    border-radius: 50px;
    background: #ecf0f1; /* Light grey */
    border: none;
    padding: 15px;
    color: #333; /* Dark text */
}

.form-control::placeholder {
    color: #95a5a6; /* Medium grey for placeholders */
}

.btn-primary {
    border-radius: 50px;
    background: #e74c3c; /* Vibrant red */
    border: none;
    padding: 10px 20px;
    transition: background 0.3s ease-in-out;
    color: #ffffff; /* White text */
}

.btn-primary:hover {
    background: #c0392b; /* Slightly darker red */
}

.btn-secondary {
    border-radius: 50px;
    background: #f39c12; /* Vibrant orange */
    border: none;
    padding: 10px 20px;
    transition: background 0.3s ease-in-out;
    color: #ffffff; /* White text */
}

.btn-secondary:hover {
    background: #e67e22; /* Slightly darker orange */
}

.alert {
    border-radius: 50px;
    background: #3498db; /* Vibrant blue */
    color: #ffffff; /* White text */
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

</style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-lock"></i> Login
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-sign-in-alt"></i> Login</button>
                            <a href="reg.php" class="btn btn-link">New Registeration</a>
                        </form>
                        <?php if (!empty($loginError)) { ?>
                            <div class="alert alert-danger mt-3" role="alert">
                                <?php echo $loginError; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
