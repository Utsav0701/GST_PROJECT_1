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
    $query = $conn->real_escape_string($_POST["query"]);
    $sql = "SELECT gstin FROM gst_basic_info WHERE gstin LIKE '%$query%'";
    $result = $conn->query($sql);
    $output = '<ul class="list-unstyled">';

    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $output .= '<li>' . htmlspecialchars($row["gstin"]) . '</li>';
        }
    } else {
        $output .= '<li>No clients found</li>';
    }
    $output .= '</ul>';
    echo $output;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Clients</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        table.excel-table th, table.excel-table td {
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
            padding: 8px;
            margin: 0;
        }
        table.excel-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        table.excel-table td {
            background-color: #fff;
        }
        table.excel-table select, table.excel-table input[type="text"] {
            width: 100%;
            box-sizing: border-box;
            font-size: 16px;
            padding: 6px;
            margin: 0;
            border: none;
        }
        table.excel-table button {
            font-size: 16px;
            padding: 6px;
            margin: 0;
            border: none;
            background: none;
            color: blue;
            cursor: pointer;
        }
        #list {
            position: absolute;
            z-index: 1000;
            width: 250px; /* Adjust width to match the input field */
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
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h5>Search Clients</h5>
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
    <!-- <script>
        // This function will be called when the window loads
        function setFocus() {
            document.getElementById("search_query").focus();
        }
        // Adding event listener to call setFocus on window load
        window.onload = setFocus;
    </script> -->
    <div class="container-fluid">
        <div class="mt-4">
            <main role="main">
            <div>
                <form action="" method="GET" class="mb-3">
                    <div class="row">
                        <div class="col-3 input-group">
                        <input type="text" id="search_query" autocomplete="off" autofocus="on" minlength="15" maxlength="15" name="search_query" class="form-control" placeholder="Enter GSTIN" required>
                        <div id="list"></div>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        $('#search_query').keyup(function() {
                            var query = $(this).val();
                            if (query != '') {
                                $.ajax({
                                    url: "search_autocomplete.php",
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
                            $('#search_query').val($(this).text());
                            $('#list').fadeOut();
                        });
                    });
                </script>
            </div>
                <form action="" method="post" id="" autocomplete="on">
                <div class="row">
                    <div class="col-3">
                        <label for="month">Month:</label>
                        <select class="form-control" id="dynamic-dropdown-month" name="month">
                            <?php
                            $months = [
                                "January", "February", "March", "April", "May", "June",
                                "July", "August", "September", "October", "November", "December"
                            ];
                            foreach ($months as $monthOption) {
                                $selected = ($month == $monthOption) ? 'selected' : '';
                                echo "<option value='$monthOption' $selected>$monthOption</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-3">
                        <label for="year">Year:</label>
                        <select class="form-control" id="dynamic-dropdown-year" name="year">
                            <?php
                            $currentYear = date("Y");
                            for ($i = $currentYear; $i <= $currentYear + 10; $i++) {
                                $selected = (isset($_COOKIE['Year']) && $_COOKIE['Year'] == $i) ? 'selected' : '';
                                echo "<option value='$i' $selected>$i</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <br>
                <button type="submit" name="btnDate" class="btn btn-primary">Submit</button>
            </form>
            <br>
            <?php
            if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
                $search_query = $_GET['search_query'];

                if (isset($_POST["btnDate"])) {
                    $month = $_POST["month"];
                    $year = $_POST["year"]; 
                    $sql = "SELECT b.*, d.* FROM gst_details d JOIN gst_basic_info b ON d.basic_info_id=b.id WHERE  month='$month' AND year=$year and gstin LIKE '%$search_query%'";
                                                                    // (d.gstr3b_query_solved <> 'NA' and d.gstr1_query_solved <> 'NA') and
                    $query = "select * from additional_note where gstin LIKE '%$search_query%'";
                } else {
                    $sql = "SELECT b.*, d.* FROM gst_details d JOIN gst_basic_info b ON d.basic_info_id=b.id WHERE b.gstin LIKE '%$search_query%'";
                                                                    // (d.gstr3b_query_solved <> 'NA' and d.gstr1_query_solved <> 'NA') and
                    $query = "select * from additional_note where gstin LIKE '%$search_query%'";
                }
                $result = $conn->query($sql);
                $output = $conn->query($query);

                // Fetch client name and GSTIN
                $client_query = "SELECT trade_name, gstin FROM gst_basic_info WHERE gstin LIKE '%$search_query%'";
                $client_result = $conn->query($client_query);
                if ($client_result->num_rows > 0) {
                    $client = $client_result->fetch_assoc();
                    $client_name = $client['trade_name'];
                    $client_gstin = $client['gstin'];
                } else {
                    $client_name = "Not Found";
                    $client_gstin = "Not Found";
                }
                ?>
                <br>
                <div class="row">
                    <div class="col-6">
                        <?php echo "<h3>Client Name: $client_name</h3>"; ?>
                    </div>
                    <div class="col-6">
                        <?php echo "<h3>Client GSTIN: $client_gstin</h3><br>"; ?>
                    </div>
                </div>
                <br>
                <?php
                // Display client name and GSTIN
                result($result);
                insert($search_query);
                output($output);
            }
            ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#updateForm').on('update_note', function(e) {
                e.preventDefault(); // Prevent form from submitting the default way

                $.ajax({
                    type: 'POST',
                    url: '', // URL to send the request to
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle response
                    },
                    error: function(xhr, status, error) {
                        $('#response').html('Error: ' + error);
                    }
                });
            });
        });
        
        $(document).ready(function() {
            $('#insertForm').on('insert_note', function(e) {
                e.preventDefault(); // Prevent form from submitting the default way

                $.ajax({
                    type: 'POST',
                    url: '', // URL to send the request to
                    data: $(this).serialize(),
                    success: function(response) {
                        // Handle response
                    },
                    error: function(xhr, status, error) {
                        $('#response').html('Error: ' + error);
                    }
                });
            });
        });
    </script>

    <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (isset($_POST["update_note"])) {
                $gstin = $_POST["gstin"];
                $remark = $_POST["remark"];
                $user = $_POST["user"];
                $date = $_POST["date"];
                $id = $_POST["id"];
                $attachment = uploadAttachment();
    
                // SQL query to update the remark
                $q = "UPDATE additional_note SET remark='$remark', attachment='$attachment' WHERE gstin='$gstin' AND id='$id'";
                $e = $conn->query($q);
    
                if ($e === TRUE) {
                    $qu = "SELECT * FROM additional_note WHERE gstin LIKE '%$gstin%'";
                    $sql = "SELECT b.*, d.* FROM gst_details d JOIN gst_basic_info b ON d.basic_info_id=b.id WHERE (d.gstr3b_query_solved <> 'NA' and d.gstr1_query_solved <> 'NA') and b.gstin LIKE '%$gstin%'";
                    $result = $conn->query($sql);
                    $output = $conn->query($qu);
                    result($result);
                    insert($gstin);
                    output($output);
                } else {
                    echo "Error updating remark: " . $conn->error;
                }
            }
            
            if (isset($_POST["insert_note"])) {
                $gstin = $_POST["gstin"];
                $remark = $_POST["remark"];
                $user = $_POST["user"];
                $date = date("Y-m-d");
                $attachment = uploadAttachment();
    
                // SQL query to insert the note
                $q = "INSERT INTO additional_note (gstin, remark, user, date, attachment) VALUES ('$gstin', '$remark', '$user', '$date', '$attachment')";
                $e = $conn->query($q);
    
                if ($e === TRUE) {
                    $qu = "SELECT * FROM additional_note WHERE gstin LIKE '%$gstin%'";
                    $sql = "SELECT b.*, d.* FROM gst_details d JOIN gst_basic_info b ON d.basic_info_id=b.id WHERE (d.gstr3b_query_solved <> 'NA' and d.gstr1_query_solved <> 'NA') and b.gstin LIKE '%$gstin%'";
                    $result = $conn->query($sql);
                    $output = $conn->query($qu);
                    result($result);
                    insert($gstin);
                    output($output);
                } else {
                    echo "Error inserting note: " . $conn->error;
                }
            }
            
            if (isset($_POST["delete_attachment"])) {
                $gstin = $_POST["gstin"];
                $id = $_POST["id"];
    
                // Remove the file from server
                $query = "SELECT attachment FROM additional_note WHERE id='$id' AND gstin='$gstin'";
                $result = $conn->query($query);
                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $attachment_path = $row['attachment'];
                    if (file_exists($attachment_path)) {
                        unlink($attachment_path);
                    }
                }
    
                // SQL query to remove the attachment
                $q = "UPDATE additional_note SET attachment='' WHERE gstin='$gstin' AND id='$id'";
                $e = $conn->query($q);
    
                if ($e === TRUE) {
                    $qu = "SELECT * FROM additional_note WHERE gstin LIKE '%$gstin%'";
                    $sql = "SELECT b.*, d.* FROM gst_details d JOIN gst_basic_info b ON d.basic_info_id=b.id WHERE (d.gstr3b_query_solved <> 'NA' and d.gstr1_query_solved <> 'NA') and b.gstin LIKE '%$gstin%'";
                    $result = $conn->query($sql);
                    $output = $conn->query($qu);
                    result($result);
                    insert($gstin);
                    output($output);
                } else {
                    echo "Error deleting attachment: " . $conn->error;
                }
            }        
        }
        function result($result){
            if ($result->num_rows > 0) {
                echo '<div class="table-responsive  table-container">';
                echo '<table  border="1" class="excel-table">';
                echo '<thead>';
                echo '<tr>
                    <th>Sr Nox</th>
                    <th>Month</th><th>Year</th>
                    <th>GSTR3B Query</th>
                    <th>GSTR3B Query Solved</th>
                    <th>GSTR1 Query</th>
                    <th>GSTR1 Query Solved</th>
                    <th>Return Filing Frequancy</th>
                </tr>';
                echo '</thead>';
                echo '<tbody>';
                $i = 1;
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo "<td>$i</td>";
                    echo '<td>'.$row["month"].'</td>';
                    echo '<td>'.$row["year"].'</td>';
                    echo '<td>' . $row["gstr3b_query"] . '</td>';
                    echo '<td>' . $row["gstr3b_query_solved"] . '</td>';
                    echo '<td>' . $row["gstr1_query"] . '</td>';
                    echo '<td>' . $row["gstr1_query_solved"] . '</td>';
                    echo "<td>";
                    switch ($row["return_status"]) {
                        case 'M':
                            echo "Monthly";
                            break;
                        case 'Q':
                            echo "Quarterly";
                            break;
                        case 'C':
                            echo "Composition";
                            break;
                        default:
                            echo "Unknown";
                    }
                    echo "</td>";   
                                     
                    echo '</tr>';
                    $i++;
                }

                echo '</tbody></table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">No matching clients found.</div>';
            }
        }
        function insert($gstin){
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped table-bordered">'; // Removed table-sm class for medium size
            echo '<thead class="thead-dark">';
            echo '<tr>';
            echo '<th style="font-size: 14px; padding: 8px;">REMARK</th>'; // Adjust font size and padding
            echo '<th style="font-size: 14px; padding: 8px;">ATTACHMENT</th>'; // Adjust font size and padding
            echo '<th style="font-size: 14px; padding: 8px;">ACTION</th>'; // Adjust font size and padding
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            echo '<tr>';
            echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post" id="insertForm" enctype="multipart/form-data">';
            echo '<input type="hidden" name="gstin" value="' . $gstin . '">';
            echo '<input type="hidden" name="user" value="' . $_SESSION["username"] . '">';
            echo '<td>';
            echo '<div>';
            echo '<textarea name="remark" rows="3" cols="140" style="font-size: 14px;"></textarea>'; // Adjust font size
            echo '</div>';
            echo '</td>';
            echo '<td>';
            echo '<div>';
            echo '<input type="file" name="attachment" style="font-size: 14px;">'; // Adjust font size
            echo '</div>';
            echo '</td>';
            echo '<td>';
            echo '<button type="submit" name="insert_note" class="btn btn-primary">Insert</button>'; // Default button size
            echo '</td>';
            echo '</form>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
        
        function output($output) {
            if ($output->num_rows > 0) {
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped table-bordered">'; // Removed table-sm class for medium size
                echo '<thead class="thead-dark">';
                echo '<tr>
                    <th style="font-size: 14px; padding: 8px;">ID</th>
                    <th style="font-size: 14px; padding: 8px;">USER</th>
                    <th style="font-size: 14px; padding: 8px;">DATE</th>
                    <th style="font-size: 14px; padding: 8px;">REMARK</th>
                    <th style="font-size: 14px; padding: 8px;">ATTACHMENT</th>
                    <th style="font-size: 14px; padding: 8px;">ACTION</th>';
                echo '</thead>';
                echo '<tbody>';
                $i = 1;
                while ($row = $output->fetch_assoc()) {
                    echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post" id="updateForm" enctype="multipart/form-data">';
                    echo "<input type='hidden' name='gstin' value='" . $row["gstin"] . "'>";
                    echo "<input type='hidden' name='user' value='" . $row["user"] . "'>";
                    echo "<input type='hidden' name='date' value='" . $row["date"] . "'>";
                    echo "<input type='hidden' name='id' value='" . $row["id"] . "'>"; 
                    echo '<tr>';
                    echo '<td style="font-size: 14px; padding: 8px;">'.$i.'</td>';
                    echo '<td style="font-size: 14px; padding: 8px;">' . $row["user"] . '</td>';
                    echo '<td style="font-size: 14px; padding: 8px;">' . $row["date"] . '</td>';
                    echo "<td style='padding: 8px;'><textarea name='remark' rows='3' cols='140' style='font-size: 14px;' data-toggle='tooltip' data-placement='top' title='" . $row["remark"] . "'>".$row["remark"]."</textarea></td>";
                    echo '<td style="padding: 8px;">';
                    if (!empty($row["attachment"])) {
                        echo '<a href="' . $row["attachment"] . '" target="_blank" style="font-size: 14px;">View Attachment</a>';
                        echo '<button type="submit" name="delete_attachment" class="btn btn-danger ml-2"><i class="fas fa-times"></i></button>'; // Removed btn-sm class
                    } else {
                        echo '<span style="font-size: 14px;">No Attachment</span>';
                        echo '<input type="file" name="attachment" class="form-control mt-2">'; // Removed form-control-sm class
                    }
                    echo '</td>';
                    echo "<td style='padding: 8px;'><button type='submit' name='update_note' class='btn btn-primary'>Update</button></td>"; // Removed btn-sm class
                    echo '</tr>';
                    echo "</form>";
                    $i++;
                }
                echo '</tbody></table>';
                echo '</div>';
            } else {
                echo '<div class="alert alert-warning" role="alert">No Additional notes found.</div>';
            }
        }
        
    
    
        function uploadAttachment() {
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["attachment"]["name"]);
                if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
                    return $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    return '';
                }
            }
            return '';
        }        
    ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php
$conn->close();
?>

