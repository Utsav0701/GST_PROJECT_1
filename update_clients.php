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
if (isset($_POST["query"])) {
    $output = '';
    $query = "SELECT gstin FROM gst_basic_info WHERE gstin LIKE '%" . $_POST["query"] . "%'";
    $result = $conn->query($query);

    $output = '<ul class="list-unstyled">';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $output .= '<li>' . htmlspecialchars($row["gstin"]) . '</li>';
        }
    } else {
        $output .= '<li>No results found</li>';
    }
    $output .= '</ul>';
    echo $output;
    exit(); // Ensure the script stops here to avoid outputting the rest of the HTML
}
$client = null;
$update_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['search'])) {
        $gstin = $_POST['gstin'];
        $sql = "SELECT * FROM gst_basic_info WHERE gstin = '$gstin'";
        $result = mysqli_query($conn, $sql);
        $client = mysqli_fetch_assoc($result);
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $trade_name = $_POST['trade_name'];
        $return_filing_frequency = $_POST['return_filing_frequency'];
        $group_name = $_POST['group_name'];

        $sql = "UPDATE gst_basic_info SET trade_name='$trade_name', return_filing_frequency='$return_filing_frequency', group_name='$group_name' WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            $update_message = "Client updated successfully!";
        } else {
            $update_message = "Error updating client: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['hide'])) {
        $id = $_POST['id'];
        $sql = "UPDATE gst_basic_info SET status=0 WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            $update_message = "Client hidden successfully!";
        } else {
            $update_message = "Error hiding client: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['unhide'])) {
        $id = $_POST['id'];
        $sql = "UPDATE gst_basic_info SET status=1 WHERE id=$id";

        if (mysqli_query($conn, $sql)) {
            $update_message = "Client unhidden successfully!";
        } else {
            $update_message = "Error unhiding client: " . mysqli_error($conn);
        }
    }
}


mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Search Client</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            background-color: #ffff;
            color: #05668d;
        }

        .navbar {
            height: 70px;
            background-color: #343a40;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            color: #f8f9fa;
        }

        .navbar-brand img {
            width: 40px;
            margin-right: 10px;
        }

        .navbar-brand h6 {
            margin: 0;
            color: #f8f9fa;
        }

        .navbar h5 {
            color: #f8f9fa;
            margin: 0;
            margin-left: 20px;
        }

        .navbar .form-control {
            height: 30px;
            font-size: 14px;
        }

        .navbar .btn {
            height: 30px;
            padding: 0 10px;
            font-size: 14px;
        }

        .navbar-nav .nav-link {
            color: #f8f9fa;
        }

        .navbar-nav .nav-link:hover {
            color: #05668d;
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            background-color: #f8f9fa;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .card-header {
            background-color: #00b4d8;
            color: darkblue;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            background-color: #90e0ef;
            color: #05668d;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #0077b6;
            border: none;
            transition: background 0.3s ease-in-out;
            color: #ffff;
        }

        .btn-primary:hover {
            background-color: #03045e;
        }

        .table th,
        .table td {
            color: #05668d;
        }

        .thead-dark th {
            background-color: #028090;
            color: #f0f3bd;
        }

        #list {
            position: absolute;
            z-index: 1000;
            width: calc(100% - 30px);
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            top: calc(100% + 5px);
            left: 0;
            display: none;
        }

        #list li {
            padding: 10px;
            cursor: pointer;
        }

        #list li:hover {
            background: #eee;
        }

        .form-group {
            position: relative;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md" style="background: #00b4d8;">
        <a class="navbar-brand" href="#">
            <img src="images/userlogo.jpg" alt="Profile" class="img-fluid rounded-circle" style="width: 40px;">
            <h6>Welcome,<?php echo $_SESSION["username"]; ?></h6>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h5>Update Client</h5>
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
    <!-- <script>
        // This function will be called when the window loads
        function setFocus() {
            document.getElementById("gstin").focus();
        }
        // Adding event listener to call setFocus on window load
        window.onload = setFocus;
    </script> -->
    <div class="container-fluid mt-2">
        <h5 class="text-center">Search Client by GSTIN</h5>
        <form method="POST" action="" class="form-inline justify-content-center">
            <div class="form-group mb-2">
                <label for="gstin" class="sr-only">GSTIN:</label>
                <input type="text" id="gstin" autocomplete="off" autofocus="on" minlength="15" maxlength="15" name="gstin" class="form-control" placeholder="Enter GSTIN" required>
                <div id="list"></div>
            </div>
            <button type="submit" name="search" class="btn btn-primary mb-2 ml-2">Search</button>
        </form>

        <?php if ($client): ?>
            <h2 class="text-center mt-4">Client Information</h2>
            <form method="POST" action="">
                <input type="hidden" name="id" value="<?php echo $client['id']; ?>">
                <div class="form-group">
                    <label for="trade_name">Trade Name:</label>
                    <input type="text" id="trade_name" name="trade_name" class="form-control"
                        value="<?php echo $client['trade_name']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="return_filing_frequency">Return Filing Frequency:</label>
                    <select id="return_filing_frequency" name="return_filing_frequency" class="form-control">
                        <option value="Composition" <?php echo ($client['return_filing_frequency'] == 'Composition') ? 'selected' : ''; ?>>Composition</option>
                        <option value="Monthly" <?php echo ($client['return_filing_frequency'] == 'Monthly') ? 'selected' : ''; ?>>Monthly</option>
                        <option value="Quarterly" <?php echo ($client['return_filing_frequency'] == 'Quarterly') ? 'selected' : ''; ?>>Quarterly</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="group_name">Group Name:</label>
                    <input type="text" id="group_name" name="group_name" class="form-control"
                        value="<?php echo $client['group_name']; ?>">
                </div>
                <button type="submit" name="update" class="btn btn-success">Update</button>
                <?php if ($client['status'] == 1): ?>
                    <button type="submit" name="hide" class="btn btn-danger">Hide</button>
                <?php else: ?>
                    <button type="submit" name="unhide"
                    <button type="submit" name="unhide" class="btn btn-primary">Unhide</button>
                <?php endif; ?>
            </form>
        <?php endif; ?>

        <?php if ($update_message): ?>
            <div class="alert alert-info update-message"><?php echo $update_message; ?></div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#gstin').keyup(function() {
                var query = $(this).val();
                if (query != '') {
                    $.ajax({
                        url: "update_clients.php",
                        method: "POST",
                        data: { query: query },
                        success: function(data) {
                            $('#list').fadeIn();
                            $('#list').html(data);
                        }
                    });
                } else {
                    $('#list').fadeOut();
                }
            });

            $(document).on('click', '#list li', function() {
                $('#gstin').val($(this).text());
                $('#list').fadeOut();
            });
        });
    </script>
</body>
</html>
