
<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
// Initialize an empty array to hold the data
$data = [];

// Create connection
$conn = mysqli_connect("localhost", "root", "", "ca_project1");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT sr_no, gstin, trade_name, return_filing_frequency, group_name FROM gst_basic_info WHERE status = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST Records Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
body {
    background-color: #ffff; /* Light Greenish-Yellow */
    color: #05668d; /* Dark Blue */
}
.navbar {
            height: 70px; /* Adjust height as needed */
            background-color: #343a40; /* Change background color */
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            color: #f8f9fa; /* Change text color */
        }
        .navbar-brand img {
            width: 40px; /* Adjust image size */
            margin-right: 10px;
        }
        .navbar-brand h6 {
            margin: 0;
            color: #f8f9fa; /* Change text color */
        }
        .navbar h5 {
            color: #f8f9fa; /* Change text color */
            margin: 0;
            margin-left: 20px;
        }
        .navbar .form-control {
            height: 30px; /* Reduce height of dropdowns */
            font-size: 14px; /* Smaller font size */
        }
        .navbar .btn {
            height: 30px; /* Reduce button height */
            padding: 0 10px; /* Adjust padding */
            font-size: 14px; /* Smaller font size */
        }
        .navbar-nav .nav-link {
            color: #f8f9fa; /* Change text color */
        }
        .navbar-nav .nav-link:hover {
            color: #05668d; /* Hover color */
        }
        .navbar-toggler {
            border: none;
        }
        .navbar-toggler-icon {
            background-color: #f8f9fa; /* Change toggler color */
        }
        .btn-secondary {
            background-color: #6c757d; /* Change button color */
            border: none;
        }
        .btn-secondary:hover {
            background-color: #5a6268; /* Change hover color */
        }
.card-header {
    background-color: #00b4d8; /* Dark Teal */
    color: darkblue; /* Light Greenish-Yellow */
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}

.card-body {
    background-color: #90e0ef; /* Light Greenish-Yellow */
    color: #05668d; /* Dark Blue */
}

.card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background-color: #0077b6; /* Green */
    border: none;
    transition: background 0.3s ease-in-out;
    color: #ffff; /* Light Greenish-Yellow */
}

.btn-primary:hover {
    background-color: #03045e; /* Teal */
}

.table-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin: 0 auto;
        }

        table.excel-table {
            border-collapse: collapse;
            width: auto;
        }

        table.excel-table th,
        table.excel-table td {
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px; /* Larger font size for readability */
            padding: 8px; /* More padding for better readability */
            margin: 0; /* Remove margin inside cells */
        }

        table.excel-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        table.excel-table td {
            background-color: #fff;
        }

        table.excel-table select,
        table.excel-table input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            font-size: 16px; /* Larger font size for readability */
            padding: 6px; /* More padding for better readability */
            margin: 0; /* Remove margin inside form elements */
            border: none; /* Remove borders for a cleaner look */
        }

        table.excel-table button {
            font-size: 16px; /* Larger font size for readability */
            padding: 6px; /* More padding for better readability */
            margin: 0; /* Remove margin inside buttons */
            border: none; /* Remove borders for a cleaner look */
            background: none; /* Remove background for a cleaner look */
            color: blue; /* Add color to indicate it is a button */
            cursor: pointer; /* Change cursor to pointer for button */
        }
    </style>
</head>
<body>
    <!-- Horizontal Navbar -->
    <nav class="navbar navbar-expand-md" style="background: #00b4d8;">
        <a class="navbar-brand" href="#">
            <img src="images/userlogo.jpg" alt="Profile" class="img-fluid rounded-circle" style="width: 40px;">
            <h6>Welcome, <?php echo $_SESSION["username"]; ?></h6>
        </a>
        <h5 class="text-center">Hidden Clients Data</h5>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <a class="nav-link" href="logout.php" onclick="return confirm('Logout from this website?');">
                        <i class="fas fa-cog"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <br>
    <div class="container col-11">
  
    <?php if (!empty($data)): ?>
        <div class="table-container">
        <table border="1" class="excel-table">
            <thead>
                <tr>
                    <th>SR No</th>
                    <th>GSTIN</th>
                    <th>Trade Name</th>
                    <th>Return Filing Frequency</th>
                    <th>Group Name</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['sr_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['gstin']); ?></td>
                        <td><?php echo htmlspecialchars($row['trade_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['return_filing_frequency']); ?></td>
                        <td><?php echo htmlspecialchars($row['group_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
       
    <?php else: ?>
        <p>No data found with status 0.</p>
    <?php endif; ?>
</div>

</body>
</html>
