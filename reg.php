<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "ca_project1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if form is submitted
$registrationMessage = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];

    // Check if email already exists
    $sqlCheck = "SELECT * FROM user WHERE email='$email'";
    $resultCheck = mysqli_query($conn, $sqlCheck);

    if (mysqli_num_rows($resultCheck) > 0) {
        $registrationMessage = '<div class="alert alert-danger" role="alert">Email already exists. Please choose a different email.</div>';
    } else {
        // Email does not exist, proceed with registration
        // Prepare insert query
        $sqlInsert = "INSERT INTO user (username, password, email) VALUES ('$username', '$password', '$email')";

        // Execute insert query
        if (mysqli_query($conn, $sqlInsert)) {
            $registrationMessage = '<div class="alert alert-success" role="alert">Registration successful!</div>';
        } else {
            $registrationMessage = '<div class="alert alert-danger" role="alert">Error: ' . mysqli_error($conn) . '</div>';
        }
    }
}

// Close database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
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
                        <i class="fas fa-user-plus"></i> Registration Form
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="username">Username:</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" minlength="6" maxlength="8" id="password" name="password" placeholder="Enter your password" required>
                                <!-- <p>Password must contain:</p>
                                    <ul>
                                        <li id="length" class="invalid">At least 8 characters</li>
                                        <li id="uppercase" class="invalid">An uppercase letter</li>
                                        <li id="lowercase" class="invalid">A lowercase letter</li>
                                        <li id="number" class="invalid">A number</li>
                                        <li id="special" class="invalid">A special character (!@#$%^&*)</li>
                                    </ul> -->
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-user-plus"></i> Register</button>
                            <a href="login.php" class="btn btn-link">Login</a>
                        </form>
                        <?php if (!empty($registrationMessage)) { ?>
                            <div class="mt-3">
                                <?php echo $registrationMessage; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
