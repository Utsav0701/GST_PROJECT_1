<?php
// Start session
session_start();

// Check if user is not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ca_project1");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle autocomplete request
if(isset($_POST["query"])) {
    $output = '';
    $query = "SELECT * FROM gst_basic_info WHERE gstin LIKE '%" . $_POST["query"] . "%'";
    $result = $conn->query($query);
    $output = '<ul class="list-unstyled">';
  
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result)) {
            $output .= '<li>'.$row["gstin"].'</li>';
        }
    } else {
        $output .= '<li>Client not found</li>';
    }
    $output .= '</ul>';
    echo $output;
    exit(); // Ensure the script stops here to avoid outputting the rest of the HTML
}

// Your existing PHP code for handling form submissions, etc.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Client</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        /* Your existing CSS styles */
        /* Additional styles for autocomplete */
        /* Add styles for autocomplete dropdown */
/* Add styles for autocomplete dropdown */
#list {
    position: absolute;
    z-index: 1000;
    width: calc(100% - 30px); /* Adjust width to match the input field */
    background: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd; /* Optional: Add a border to the dropdown */
    border-radius: 5px; /* Optional: Add border radius for rounded corners */
    top: 100%; /* Ensure it is positioned below the input field */
    left: 0; /* Align with the left edge of the input field */
    margin-top: 5px; /* Add some margin to avoid overlap with the input field */
}

#list li {
    padding: 10px;
    cursor: pointer;
}

#list li:hover {
    background: #eee;
}

.form-group {
    position: relative; /* Ensure the dropdown is positioned relative to this element */
}

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-md" style="background: #00b4d8;">
        <a class="navbar-brand" href="#">
            <img src="images/userlogo.jpg" alt="Profile" class="img-fluid rounded-circle" style="width: 40px;">
            <h6>Welcome, <?php echo $_SESSION["username"]; ?></h6>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="display.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_clients.php">
                        <i class="fas fa-users"></i> Clients
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="members.php">
                        <i class="fas fa-user-friends"></i> History
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">
                        <i class="fas fa-cog"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <h1 class="text-center">Search Client by GSTIN</h1>
        <form method="POST" action="" class="form-inline justify-content-center">
            <div class="form-group mb-2">
                <label for="gstin" class="sr-only">GSTIN:</label>
                <input type="text" id="gstin" name="gstin" class="form-control" placeholder="Enter GSTIN" required>
                <div id="list"></div>
            </div>
            <button type="submit" name="search" class="btn btn-primary mb-2 ml-2">Search</button>
        </form>
        
        <!-- Additional form and message display logic -->

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#gstin').keyup(function() {
                    var query = $(this).val();
                    if (query != '') {
                        $.ajax({
                            url: "<?php echo $_SERVER['PHP_SELF']; ?>",
                            method: "POST",
                            data: {query: query},
                            success: function(data) {
                                $('#list').fadeIn();
                                $('#list').html(data);
                            }
                        });
                    } else {
                        $('#list').fadeOut();
                    }
                });

                $(document).on('click', 'li', function() {
                    $('#gstin').val($(this).text());
                    $('#list').fadeOut();
                });
            });
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
