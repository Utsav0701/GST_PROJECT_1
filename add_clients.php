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

// Get the next sr_no value
$sqlGetMaxSrNo = "SELECT MAX(sr_no) AS max_sr_no FROM gst_basic_info";
$result = mysqli_query($conn, $sqlGetMaxSrNo);
$row = mysqli_fetch_assoc($result);
$nextSrNo = $row['max_sr_no'] + 1;

// Handle form submission
$clientExistsError = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_client'])) {
    $sr_no = mysqli_real_escape_string($conn, $_POST['sr_no']);
    $gstin = mysqli_real_escape_string($conn, $_POST['gstin']);
    $tradeName = mysqli_real_escape_string($conn, $_POST['trade_name']);
    $returnFilingFrequency = mysqli_real_escape_string($conn, $_POST['return_filing_frequency']);
    $group = mysqli_real_escape_string($conn, $_POST['group_name']);

    // Check if GSTIN already exists
    $sqlCheckGstin = "SELECT * FROM gst_basic_info WHERE gstin = '$gstin'";
    $resultCheckGstin = mysqli_query($conn, $sqlCheckGstin);

    if (mysqli_num_rows($resultCheckGstin) > 0) {
        $clientExistsError = true;
    } else {
        $sql = "INSERT INTO gst_basic_info (sr_no, gstin, trade_name, return_filing_frequency, group_name) VALUES ('$sr_no', '$gstin', '$tradeName', '$returnFilingFrequency', '$group')";

        if (mysqli_query($conn, $sql)) {
            header("Location: display.php"); // Redirect to display page
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}

// Handle CSV import
if (isset($_POST['import_csv'])) {
    $fileName = $_FILES['file']['tmp_name'];

    if ($_FILES['file']['size'] > 0) {
        $file = fopen($fileName, "r");
        
        // Skip the first line (headers)
        fgetcsv($file);

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sr_no = mysqli_real_escape_string($conn, $column[0]);
            $gstin = mysqli_real_escape_string($conn, $column[1]);
            $tradeName = mysqli_real_escape_string($conn, $column[2]);
            $returnFilingFrequency = mysqli_real_escape_string($conn, $column[3]);
            $group = mysqli_real_escape_string($conn, $column[4]);

            // Determine status based on return filing frequency
            $status = ($returnFilingFrequency == 'CANCELLED' || $returnFilingFrequency == 'CANCELED') ? 0 : 1;

            // Check if GSTIN already exists
            $sqlCheckGstin = "SELECT * FROM gst_basic_info WHERE gstin = '$gstin'";
            $resultCheckGstin = mysqli_query($conn, $sqlCheckGstin);

            if (mysqli_num_rows($resultCheckGstin) == 0) {
                $sql = "INSERT INTO gst_basic_info (sr_no, gstin, trade_name, return_filing_frequency, group_name, status) VALUES ('$sr_no', '$gstin', '$tradeName', '$returnFilingFrequency', '$group', '$status')";

                if (!mysqli_query($conn, $sql)) {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }
            }
        }
        fclose($file);
        header("Location: display.php"); // Redirect to display page
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Management</title>
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
.card {
            max-width: 1000px;
            margin: auto;
        }
        .custom-card {
            width: 200px;
            margin: 5px;
            text-align: center;
        }
        .custom-card .card-body {
            padding: 10px;
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

        .table th,
        .table td {
            color: #05668d;
            /* Dark Blue */
        }

        .thead-dark th {
            background-color: #028090;
            /* Dark Teal */
            color: #f0f3bd;
            /* Light Greenish-Yellow */
        }

        .form-container {
            display: none;
        }
    </style>
</head>

<body>
    <!-- Horizontal Navbar -->
    <nav class="navbar navbar-expand-md" style="background: #00b4d8;">
        <a class="navbar-brand" href="#">
            <img src="images/userlogo.jpg" alt="Profile" class="img-fluid rounded-circle">
            <h6>Welcome,<?php echo $_SESSION["username"]; ?></h6>
        </a>
        <h5 class="text-center">Client Management</h5>

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
        <!-- Main Content -->
        <main role="main" class="px-md-4">
        <br> 
            <div class="card mb-4">
        <div class="card-header">
            Manage Clients
        </div>
        <div class="card-body">
            <?php if ($clientExistsError): ?>
                <div class="alert alert-danger" role="alert">Error: GSTIN already exists</div>
            <?php endif; ?>
            <div class="form-row align-items-end">
                <div class="col-3 mb-3">
                    <button id="showAddClientForm" class="btn btn-primary">Add Client</button>
                </div>
                <div class="col-3 mb-3">
                    <button id="redirectToUpdatePage" class="btn btn-primary">Update Client</button>
                </div>
                <div class="col-3 mb-3">
                    <form action="" method="post" enctype="multipart/form-data" class="form-inline">
                        <!-- <p class="card-text">Import CSV File</p> -->
                        <input type="file" class="form-control-file" id="file" name="file" required>
                        <button type="submit" name="import_csv" class="btn btn-primary ml-2 mt-3">Import</button>
                    </form>
                </div>
                <div class="col-3 mb-3">
                    <form method="post" action="hidden_clients.php" class="form-inline">
                        <button type="submit" name="fetchDataButton" class="btn btn-primary">Hidden GST Data</button>
                    </form>
                </div>
            </div>
            <div id="addClientForm" class="form-container mt-4">
                <h4>Add Client</h4>
                <form method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="sr_no">Sr No</label>
                            <input type="number" class="form-control" id="sr_no" name="sr_no"
                                value="<?php echo $nextSrNo; ?>" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="gstin">GSTIN</label>
                            <input type="text" class="form-control" maxlength="15" id="gstin" name="gstin"
                                placeholder="Enter GSTIN" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="trade_name">Trade Name</label>
                            <input type="text" class="form-control" id="trade_name" name="trade_name"
                                placeholder="Enter Trade Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="return_filing_frequency">Return Filing Frequency</label>
                            <select class="form-control" id="return_filing_frequency" name="return_filing_frequency" required>
                                <option value="Composition">Composition</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Quarterly">Quarterly</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="group_name">Group Name</label>
                            <input type="text" class="form-control" id="group_name" name="group_name"
                                placeholder="Enter Group Name" required>
                        </div>
                        <div class="form-group col-md-6">
                            <button type="submit" name="add_client" class="btn btn-primary">Add Client</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#showAddClientForm').click(function () {
                $('#addClientForm').toggle();
            });
            $('#redirectToUpdatePage').click(function () {
                window.location.href = 'update_clients.php';
            });
        });
    </script>
</body>

</html>
