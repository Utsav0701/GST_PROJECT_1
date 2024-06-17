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
if(!isset($_COOKIE["Month"]))
{
    setcookie("Month","January");
}

// Function to fetch data and update counts
function fetchDataAndCount($conn, $selectedMonth, $selectedYear) {
    
    global $filedCount, $nilCount, $taxCount, $filedCountGSTR1, $nilCountGSTR1, $taxCountGSTR1;
    global $filedCountComp, $nilCountComp, $taxCountComp, $filedCountGSTR1Comp, $nilCountGSTR1Comp, $taxCountGSTR1Comp;
    global $filedCountQtr, $nilCountQtr, $taxCountQtr, $filedCountGSTR1Qtr, $nilCountGSTR1Qtr, $taxCountGSTR1Qtr;
    global $blankCount, $blankCountGSTR1, $blankCountComp, $blankCountGSTR1Comp, $blankCountQtr, $blankCountGSTR1Qtr;
    global $totalCount, $totalCountGSTR1, $totalCountComp, $totalCountGSTR1Comp, $totalCountQtr, $totalCountGSTR1Qtr;
    $filedCount = $nilCount = $taxCount = 0;
    $filedCountGSTR1 = $nilCountGSTR1 = $taxCountGSTR1 = 0;
    $filedCountComp = $nilCountComp = $taxCountComp = 0;
    $filedCountGSTR1Comp = $nilCountGSTR1Comp = $taxCountGSTR1Comp = 0;
    $filedCountQtr = $nilCountQtr = $taxCountQtr = 0;
    $filedCountGSTR1Qtr = $nilCountGSTR1Qtr = $taxCountGSTR1Qtr = 0;
    $blankCount = $blankCountGSTR1 = $blankCountComp = $blankCountGSTR1Comp = $blankCountQtr = $blankCountGSTR1Qtr = 0;
    $totalCount = $totalCountGSTR1 = $totalCountComp = $totalCountGSTR1Comp = $totalCountQtr = $totalCountGSTR1Qtr = 0;
    $noDataFound = false;

    // Process results for MONTHLY
    $sql = "SELECT b.gstin, b.trade_name, d.gstr3b, d.gstr1
            FROM gst_basic_info b
            JOIN gst_details d ON b.id = d.basic_info_id
            WHERE b.return_filing_frequency = 'MONTHLY'
              AND d.month = '$selectedMonth'
              AND d.year = '$selectedYear'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['gstr3b'] === 'FILED') {
                $filedCount++;
            } elseif ($row['gstr3b'] === 'NIL') {
                $nilCount++;
            } elseif ($row['gstr3b'] === 'TAX') {
                $taxCount++;
            }

            if ($row['gstr1'] === 'FILED') {
                $filedCountGSTR1++;
            } elseif ($row['gstr1'] === 'NIL') {
                $nilCountGSTR1++;
            } elseif ($row['gstr1'] === 'TAX') {
                $taxCountGSTR1++;
            }
        }
    }

    $totalCountQuery = "SELECT COUNT(*) AS total
                        FROM gst_basic_info
                        WHERE return_filing_frequency = 'MONTHLY'";

    $totalCountResult = $conn->query($totalCountQuery);
    $totalFilingCount = 0;
    if ($totalCountResult->num_rows > 0) {
        $totalRow = $totalCountResult->fetch_assoc();
        $totalFilingCount = $totalRow['total'];
    }

    $blankCount = $totalFilingCount - $filedCount - $nilCount - $taxCount;
    $blankCountGSTR1 = $totalFilingCount - $filedCountGSTR1 - $nilCountGSTR1 - $taxCountGSTR1;

    $totalCount = $filedCount + $nilCount + $taxCount + $blankCount;
    $totalCountGSTR1 = $filedCountGSTR1 + $nilCountGSTR1 + $taxCountGSTR1 + $blankCountGSTR1;

    // Process results for COMPOSITION
    $sqlComp = "SELECT b.gstin, b.trade_name, d.gstr3b, d.gstr1
                FROM gst_basic_info b
                JOIN gst_details d ON b.id = d.basic_info_id
                WHERE b.return_filing_frequency = 'COMPOSITION'
                  AND d.month = '$selectedMonth'
                  AND d.year = '$selectedYear'";

    $resultComp = $conn->query($sqlComp);

    if ($resultComp->num_rows > 0) {
        while ($row = $resultComp->fetch_assoc()) {
            if ($row['gstr3b'] === 'FILED') {
                $filedCountComp++;
            } elseif ($row['gstr3b'] === 'NIL') {
                $nilCountComp++;
            } elseif ($row['gstr3b'] === 'TAX') {
                $taxCountComp++;
            }

            if ($row['gstr1'] === 'FILED') {
                $filedCountGSTR1Comp++;
            } elseif ($row['gstr1'] === 'NIL') {
                $nilCountGSTR1Comp++;
            } elseif ($row['gstr1'] === 'TAX') {
                $taxCountGSTR1Comp++;
            }
        }
    }

    $totalCountQueryComp = "SELECT COUNT(*) AS total
                            FROM gst_basic_info
                            WHERE return_filing_frequency = 'COMPOSITION'";

    $totalCountResultComp = $conn->query($totalCountQueryComp);
    $totalFilingCountComp = 0;
    if ($totalCountResultComp->num_rows > 0) {
        $totalRowComp = $totalCountResultComp->fetch_assoc();
        $totalFilingCountComp = $totalRowComp['total'];
    }

    $blankCountComp = $totalFilingCountComp - $filedCountComp - $nilCountComp - $taxCountComp;
    $blankCountGSTR1Comp = $totalFilingCountComp - $filedCountGSTR1Comp - $nilCountGSTR1Comp - $taxCountGSTR1Comp;

    $totalCountComp = $filedCountComp + $nilCountComp + $taxCountComp + $blankCountComp;
    $totalCountGSTR1Comp = $filedCountGSTR1Comp + $nilCountGSTR1Comp + $taxCountGSTR1Comp + $blankCountGSTR1Comp;

    // Process results for QUARTERLY
    $sqlQtr = "SELECT b.gstin, b.trade_name, d.gstr3b, d.gstr1
               FROM gst_basic_info b
               JOIN gst_details d ON b.id = d.basic_info_id
               WHERE b.return_filing_frequency = 'QUARTERLY'
                 AND d.month = '$selectedMonth'
                 AND d.year = '$selectedYear'";

    $resultQtr = $conn->query($sqlQtr);

    if ($resultQtr->num_rows > 0) {
        while ($row = $resultQtr->fetch_assoc()) {
            if ($row['gstr3b'] === 'FILED') {
                $filedCountQtr++;
            } elseif ($row['gstr3b'] === 'NIL') {
                $nilCountQtr++;
            } elseif ($row['gstr3b'] === 'TAX') {
                $taxCountQtr++;
            }

            if ($row['gstr1'] === 'FILED') {
                $filedCountGSTR1Qtr++;
            } elseif ($row['gstr1'] === 'NIL') {
                $nilCountGSTR1Qtr++;
            } elseif ($row['gstr1'] === 'TAX') {
                $taxCountGSTR1Qtr++;
            }
        }
    }

    $totalCountQueryQtr = "SELECT COUNT(*) AS total
                           FROM gst_basic_info
                           WHERE return_filing_frequency = 'QUARTERLY'";

    $totalCountResultQtr = $conn->query($totalCountQueryQtr);
    $totalFilingCountQtr = 0;
    if ($totalCountResultQtr->num_rows > 0) {
        $totalRowQtr = $totalCountResultQtr->fetch_assoc();
        $totalFilingCountQtr = $totalRowQtr['total'];
    }

    $blankCountQtr = $totalFilingCountQtr -

    $filedCountQtr - $nilCountQtr - $taxCountQtr;
    $blankCountGSTR1Qtr = $totalFilingCountQtr - $filedCountGSTR1Qtr - $nilCountGSTR1Qtr - $taxCountGSTR1Qtr;

    $totalCountQtr = $filedCountQtr + $nilCountQtr + $taxCountQtr + $blankCountQtr;
    $totalCountGSTR1Qtr = $filedCountGSTR1Qtr + $nilCountGSTR1Qtr + $taxCountGSTR1Qtr + $blankCountGSTR1Qtr;
}

// // Check if data needs to be re-fetched
// $refresh = isset($_GET['refresh']) ? $_GET['refresh'] : false;
// if ($refresh) {
//     // Fetch data for selected month and year
//     $selectedMonth = isset($_SESSION['selected_month']) ? $_SESSION['selected_month'] : date('F');
//     $selectedYear = isset($_SESSION['selected_year']) ? $_SESSION['selected_year'] : date('Y');

//     // fetchDataAndCount($conn, $selectedMonth, $selectedYear);
// } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_data'])) {
//     // Get the selected month and year from the form
//     $selectedMonth = $_POST['month'];
//     $selectedYear = $_POST['year'];

//     // Save selected month and year in session
//     // $_SESSION['selected_month'] = $selectedMonth;
//     // $_SESSION['selected_year'] = $selectedYear;

//     fetchDataAndCount($conn, $selectedMonth, $selectedYear);
// }

// Check if month and year are set in cookies
$month = isset($_COOKIE["Month"]) ? $_COOKIE["Month"] : '';
$year = isset($_COOKIE["Year"]) ? $_COOKIE["Year"] : date("Y");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_gst_data"])) {
        $id = $_POST["id"];
        $sr_no = $_POST["sr_no"];
        $gstin = $_POST["gstin"];
        $trade_name = $_POST["trade_name"];
        $return_filing_frequency = $_POST["return_filing_frequency"];
        $group_name = $_POST["group_name"];


        // Determine return_status based on return_filing_frequency
        $return_status = "";
        $query_filing_frequency = "SELECT return_filing_frequency FROM gst_basic_info WHERE id = $id";
        $result_filing_frequency = mysqli_query($conn, $query_filing_frequency);
        if ($result_filing_frequency) {
            $row = mysqli_fetch_assoc($result_filing_frequency);
            $filing_frequency = $row['return_filing_frequency'];
            if ($filing_frequency == "Monthly" || $filing_frequency == "MONTHLY") {
                $return_status = "M";
            } elseif ($filing_frequency == "Quarterly" || $filing_frequency == "QUARTERLY") {
                $return_status = "Q";
            } elseif ($filing_frequency == "Composition" || $filing_frequency == "COMPOSITION") {
                $return_status = "C";
            }
        }

        // Check if basic info already exists for the selected month and year
        $sqlSelectBasicInfo = "SELECT * FROM gst_basic_info WHERE sr_no='$sr_no' AND gstin='$gstin' AND trade_name='$trade_name' AND return_filing_frequency='$return_filing_frequency' AND group_name='$group_name'";
        $resultBasicInfo = mysqli_query($conn, $sqlSelectBasicInfo);

        if (mysqli_num_rows($resultBasicInfo) > 0) {
            $row = mysqli_fetch_assoc($resultBasicInfo);
            $basic_info_id = $row['id'];

            // Update basic info
            $sqlUpdateBasicInfo = "UPDATE gst_basic_info SET sr_no='$sr_no', gstin='$gstin', trade_name='$trade_name', return_filing_frequency='$return_filing_frequency', group_name='$group_name' WHERE id=$basic_info_id";
            mysqli_query($conn, $sqlUpdateBasicInfo);
        } else {
            // Insert new basic info
            $sqlInsertBasicInfo = "INSERT INTO gst_basic_info (sr_no, gstin, trade_name, return_filing_frequency, group_name) VALUES ('$sr_no', '$gstin', '$trade_name', '$return_filing_frequency', '$group_name')";
            mysqli_query($conn, $sqlInsertBasicInfo);
            $basic_info_id = mysqli_insert_id($conn);
        }

        // Insert or update detailed info
        $gstr3b = $_POST["gstr3b"];
        $gstr3b_query = $_POST["gstr3b_query"];
        $gstr3b_query_solved = $_POST["gstr3b_query_solved"];
        $gstr1 = $_POST["gstr1"];
        $gstr1_query = $_POST["gstr1_query"];
        $gstr1_query_solved = $_POST["gstr1_query_solved"];


        // Determine return_status based on return_filing_frequency
        $return_status = "";
        $query_filing_frequency = "SELECT return_filing_frequency FROM gst_basic_info WHERE id = $id";
        $result_filing_frequency = mysqli_query($conn, $query_filing_frequency);
        if ($result_filing_frequency) {
            $row = mysqli_fetch_assoc($result_filing_frequency);
            $filing_frequency = $row['return_filing_frequency'];
            if ($filing_frequency == "Monthly" || $filing_frequency == "MONTHLY") {
                $return_status = "M";
            } elseif ($filing_frequency == "Quarterly" || $filing_frequency == "QUARTERLY") {
                $return_status = "Q";
            } elseif ($filing_frequency == "Composition" || $filing_frequency == "COMPOSITION") {
                $return_status = "C";
            }
        }

        $sqlSelectDetails = "SELECT * FROM gst_details WHERE basic_info_id=$basic_info_id AND month='$month' AND year=$year";
        $resultDetails = mysqli_query($conn, $sqlSelectDetails);

        if (mysqli_num_rows($resultDetails) > 0) {
            $rowDetails = mysqli_fetch_assoc($resultDetails);
            $details_id = $rowDetails['detid'];

            // Update details
            $sqlUpdateDetails = "UPDATE gst_details SET gstr3b='$gstr3b', gstr3b_query='$gstr3b_query', gstr3b_query_solved='$gstr3b_query_solved', gstr1='$gstr1', gstr1_query='$gstr1_query', gstr1_query_solved='$gstr1_query_solved', month='$month', year=$year,return_status='$return_status' WHERE detid=$details_id";
            mysqli_query($conn, $sqlUpdateDetails);
            } else {
            // Insert new details
            $sqlInsertDetails = "INSERT INTO gst_details (gstr3b, gstr3b_query, gstr3b_query_solved, gstr1, gstr1_query, gstr1_query_solved, month, year,return_status, basic_info_id) VALUES ('$gstr3b', '$gstr3b_query', '$gstr3b_query_solved', '$gstr1', '$gstr1_query', '$gstr1_query_solved', '$month', $year, '$return_status',$basic_info_id)";
            mysqli_query($conn, $sqlInsertDetails);
        }

        echo "<script>alert('Record updated successfully');</script>";
        header("Location: {$_SERVER['PHP_SELF']}?refresh=1");

    }

    if (isset($_POST["btnDate"])) {
        $month = $_POST["month"];
        $year = $_POST["year"];
        setcookie("Month", $month, time() + 3600, "/");
        setcookie("Year", $year, time() + 3600, "/");
    }
}



/// filter code
/// filter code
// $sort_trade_name='';
$sort_filter_frequancy='';
$sort_filter_frequancy1='';
$filter_group='';
$filter_gstr3b='';
$filter_3b_query='';
$filter_gstr1='';
$filter_r1_query='';
$filter_r1_query_solved='';
$filter_3b_query_solved= '';

$selectedMonth = isset($_SESSION['selected_month']) ? $_SESSION['selected_month'] : date('F');
$selectedYear = isset($_SESSION['selected_year']) ? $_SESSION['selected_year'] : date('Y');
$noDataFound = false;

// Check if data needs to be re-fetched
$refresh = isset($_GET['refresh']) ? $_GET['refresh'] : false;
if ($refresh) {
    // Fetch data for selected month and year
    $query = "SELECT gbi.*, gd.* 
              FROM gst_basic_info gbi
              LEFT JOIN gst_details gd ON gbi.id = gd.basic_info_id 
              WHERE gd.month = '$selectedMonth' AND gd.year = '$selectedYear'";
    $result = $conn->query($query);

    // If no data is found for the selected month and year, fetch all clients
    if ($result->num_rows == 0) {
        $query = "SELECT * FROM gst_basic_info";
        $result = $conn->query($query);
        $noDataFound = true;
    }
} else {
    // Apply filter to data
    $query = "SELECT * FROM gst_basic_info";
    if ($filter_group !== '') {
        $query .= " WHERE `group_name` = '$filter_group'"; // Replace 'group_column_name' with the actual column name
    }
    $result = $conn->query($query);
    $noDataFound = $result->num_rows == 0;
}

// Handle form submission for filter
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // if (isset($_POST['sort_trade_name'])) {
    //     $sort_trade_name = $_POST['sort_trade_name'];
    //     $_SESSION['sort_trade_name'] = $sort_trade_name;
    // }
    if (isset($_POST['sort_filter_frequancy'])) {
        $sort_filter_frequancy = $_POST['sort_filter_frequancy'];
        $_SESSION['sort_filter_frequancy'] = $sort_filter_frequancy;
    }
    if (isset($_POST['sort_filter_frequancy1'])) {
        $sort_filter_frequancy1 = $_POST['sort_filter_frequancy1'];
        $_SESSION['sort_filter_frequancy1'] = $sort_filter_frequancy1;
    }
    if (isset($_POST['filter_group'])) {
        $filter_group = $_POST['filter_group'];
        $_SESSION['filter_group'] = $filter_group;
    }
    if (isset($_POST['filter_gstr3b'])) {
        $filter_gstr3b = $_POST['filter_gstr3b'];
        $_SESSION['filter_gstr3b'] = $filter_gstr3b;
    }
    if (isset($_POST['filter_3b_query'])) {
        $filter_3b_query = $_POST['filter_3b_query'];
        $_SESSION['filter_3b_query'] = $filter_3b_query;
    }
    if (isset($_POST['filter_3b_query_solved'])) {
        $filter_3b_query_solved = $_POST['filter_3b_query_solved'];
        $_SESSION['filter_3b_query_solved'] = $filter_3b_query_solved;
    }
    if (isset($_POST['filter_gstr1'])) {
            $filter_gstr1 = $_POST['filter_gstr1'];
            $_SESSION['filter_gstr1'] = $filter_gstr1;
        }
    if (isset($_POST['filter_r1_query'])) {
        $filter_r1_query = $_POST['filter_r1_query'];
        $_SESSION['filter_r1_query'] = $filter_r1_query;
    }
    if (isset($_POST['filter_r1_query_solved'])) {
        $filter_r1_query_solved = $_POST['filter_r1_query_solved'];
        $_SESSION['filter_r1_query_solved'] = $filter_r1_query_solved;
    }

} else {
    // $sort_trade_name = isset($_SESSION['sort_trade_name']) ? $_SESSION['sort_trade_name'] : '';
    $sort_filter_frequancy = isset($_SESSION['sort_filter_frequancy']) ? $_SESSION['sort_filter_frequancy'] : '';
    $sort_filter_frequancy1 = isset($_SESSION['sort_filter_frequancy1']) ? $_SESSION['sort_filter_frequancy1'] : '';
    $filter_group = isset($_SESSION['filter_group']) ? $_SESSION['filter_group'] : '';
    $filter_gstr3b = isset($_SESSION['filter_gstr3b']) ? $_SESSION['filter_gstr3b'] : '';
    $filter_3b_query = isset($_SESSION['filter_3b_query']) ? $_SESSION['filter_3b_query'] : '';
    $filter_gstr1 = isset($_SESSION['filter_gstr1']) ? $_SESSION['filter_gstr1'] : '';
    $filter_r1_query = isset($_SESSION['filter_r1_query']) ? $_SESSION['filter_r1_query'] : '';
    $filter_r1_query_solved = isset($_SESSION['filter_r1_query_solved']) ? $_SESSION['filter_r1_query_solved'] : '';
    $filter_3b_query_solved = isset($_SESSION['filter_3b_query_solved']) ? $_SESSION['filter_3b_query_solved'] : '';

}




// $filter_group= isset($_POST["filter_group"]) ? $_POST["filter_group"] : '';
// $sort_trade_name = isset($_POST["sort_trade_name"]) ? $_POST["sort_trade_name"] : '';
// $filter_gstr3b = isset($_POST["filter_gstr3b"]) ? $_POST["filter_gstr3b"] : '';
// $filter_gstr1 = isset($_POST["filter_gstr1"]) ? $_POST["filter_gstr1"] : '';
// $sort_filter_frequancy = isset($_POST["sort_filter_frequancy"]) ? $_POST["sort_filter_frequancy"] : '';
// $sort_filter_frequancy1 = isset($_POST["sort_filter_frequancy1"]) ? $_POST["sort_filter_frequancy1"] : '';
// Get filter options for 3B Query and R1 Query
// $filter_3b_query = isset($_POST["filter_3b_query"]) ? $_POST["filter_3b_query"] : '';
// $filter_r1_query = isset($_POST["filter_r1_query"]) ? $_POST["filter_r1_query"] : '';
fetchDataAndCount($conn, $month, $year);

$query = "SELECT b.*, d.* 
          FROM gst_basic_info b 
          LEFT JOIN gst_details d 
          ON b.id = d.basic_info_id 
          AND d.month = '$month' 
          AND d.year = '$year' 
          WHERE b.return_filing_frequency != 'CANCELED'";

$conditions = [];

// 3B Query filter conditions
if ($filter_3b_query == 'with_value') {

    $conditions[] = "d.gstr3b_query != ''";
} elseif ($filter_3b_query == 'no_value') {
    if ($filter_group) {
        $conditions[] = "b.group_name='$filter_group'";
    }
    if ($sort_filter_frequancy1) {
        $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
    }
    if ($filter_gstr3b){
        $conditions[] = "d.gstr3b='$filter_gstr3b'";
    }   
    // if ($filter_3b_query_solved){
    // $conditions[] = "d.gstr3b_query_solved IS NULL OR d.gstr3b_query_solved='$filter_3b_query_solved'";

    // }
    $conditions[] = "d.gstr3b_query IS NULL OR d.gstr3b_query = ''";
}

// R1 Query filter conditions
if ($filter_r1_query == 'with_value') {
    $conditions[] = "d.gstr1_query != ''";
} elseif ($filter_r1_query == 'no_value') {
    if ($filter_group) {
        $conditions[] = "b.group_name='$filter_group'";
    }
    if ($sort_filter_frequancy1) {
        $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
    }
    if ($filter_gstr3b){
        $conditions[] = "d.gstr3b='$filter_gstr3b'";
    }
    $conditions[] = "d.gstr1_query IS NULL OR d.gstr1_query = ''";
}

// Other conditions
if ($filter_gstr3b == 'BLANK') {
    if ($sort_filter_frequancy1) {
        $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
    }
    if ($filter_group) {
        $conditions[] = "b.group_name='$filter_group'";
    }
    $conditions[] = "d.gstr3b IS NULL OR d.gstr3b = ''";
} elseif ($filter_gstr3b) {
    $conditions[] = "d.gstr3b='$filter_gstr3b'";
}

if ($filter_3b_query_solved == 'NA') {
    if ($sort_filter_frequancy1) {
        $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
    }
    if ($filter_group) {
        $conditions[] = "b.group_name='$filter_group'";
    }
    if ($filter_gstr3b) {
        $conditions[] = "d.gstr3b IS NULL OR d.gstr3b = ''";
    }
    if ($filter_3b_query){
        $conditions[] = "d.gstr3b_query IS NULL OR d.gstr3b_query = ''";
    }
    $conditions[] = "d.gstr3b_query_solved IS NULL OR d.gstr3b_query_solved='$filter_3b_query_solved'";
} elseif ($filter_3b_query_solved) {
    $conditions[] = "d.gstr3b_query_solved='$filter_3b_query_solved'";
}

if ($filter_gstr1 == 'BLANK') {
    if ($sort_filter_frequancy1) {
        $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
    }
    if ($filter_group) {
        $conditions[] = "b.group_name='$filter_group'";
    }
    $conditions[] = "d.gstr1 IS NULL OR d.gstr1 = ''";
} elseif ($filter_gstr1) {
    $conditions[] = "d.gstr1='$filter_gstr1'";
}

if ($filter_r1_query_solved == 'NA') {
    if ($sort_filter_frequancy1) {
        $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
    }
    if ($filter_group) {
        $conditions[] = "b.group_name='$filter_group'";
    }
    $conditions[] = "d.gstr1_query_solved IS NULL OR d.gstr1_query_solved='$filter_r1_query_solved'";
} elseif ($filter_r1_query_solved) {
    $conditions[] = "d.gstr1_query_solved='$filter_r1_query_solved'";
}

if ($filter_group) {
    $conditions[] = "b.group_name='$filter_group'";
}

if ($sort_filter_frequancy1) {
    $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
}

// Append conditions to the query
if (count($conditions) > 0) {
    $query .= " AND " . implode(' AND ', $conditions);
}

// Add sorting
if ($sort_filter_frequancy) {
    if ($sort_filter_frequancy == 'asc') {
        $query .= " ORDER BY b.return_filing_frequency ASC";
    } elseif ($sort_filter_frequancy == 'desc') {
        $query .= " ORDER BY b.return_filing_frequency DESC";
    }
}

// // Execute the query and fetch data
// $result = mysqli_query($conn, $query);

// if (!$result) {
//     echo "Error: " . mysqli_error($conn);
// } else {
//     // Fetch data and count
//     fetchDataAndCount($conn, $month, $year);
//     while ($row = mysqli_fetch_assoc($result)) {
//         // Display your data
//     }
// }

// $query = "SELECT b.*, d.* 
//           FROM gst_basic_info b 
//           LEFT JOIN gst_details d 
//           ON b.id = d.basic_info_id 
//           AND d.month = '$month' 
//           AND d.year = '$year' 
//           WHERE b.return_filing_frequency != 'CANCELLED'";
// $conditions = [];

// // 3B Query filter conditions
// if ($filter_3b_query == 'with_value') {
//     $conditions[] = "d.gstr3b_query != ''";
//     fetchDataAndCount($conn, $month, $year);

// } elseif ($filter_3b_query == 'no_value') {
//     $conditions[] = " d.gstr3b_query = ''";

//     // $conditions[] = "d.gstr3b_query IS NULL OR d.gstr3b_query = ''";
//     fetchDataAndCount($conn, $month, $year);
// }


// // R1 Query filter conditions
// if ($filter_r1_query == 'with_value') {
//     $conditions[] = "d.gstr1_query != ''";
//     fetchDataAndCount($conn, $month, $year);

// } elseif ($filter_r1_query == 'no_value') {
//     $conditions[] = "d.gstr1_query = ''";

//     // $conditions[] = "d.gstr1_query IS NULL OR d.gstr1_query = ''";
//     fetchDataAndCount($conn, $month, $year);
// }

// if ($filter_gstr3b == 'BLANK') {
//     $conditions[] = "d.gstr3b IS NULL OR d.gstr3b = ''";
//     fetchDataAndCount($conn, $month, $year);
// }elseif ( $filter_gstr3b) {
//         $conditions[] = "d.gstr3b='$filter_gstr3b'";
//         fetchDataAndCount($conn, $month, $year);
// }

// if ($filter_3b_query_solved == 'NA') {
//     $conditions[] = "d.gstr3b_query_solved IS NULL OR d.gstr3b_query_solved = ''";
//     fetchDataAndCount($conn, $month, $year);
// }elseif ($filter_3b_query_solved) {
//     $conditions[] = "d.gstr3b_query_solved='$filter_3b_query_solved'";
//     fetchDataAndCount($conn, $month, $year);
// }

// if ($filter_gstr1 == 'BLANK') {
//     $conditions[] = "d.gstr1 IS NULL OR d.gstr1 = ''";
//     fetchDataAndCount($conn, $month, $year);
// }elseif($filter_gstr1){
//     $conditions[] = "d.gstr1='$filter_gstr1'";
//     fetchDataAndCount($conn, $month, $year);
// }


// if ($filter_r1_query_solved == 'NA') {
//     $conditions[] = "d.gstr1_query_solved IS NULL OR d.gstr1_query_solved = ''";
//     fetchDataAndCount($conn, $month, $year);
// }elseif ($filter_r1_query_solved) {
//     $conditions[] = "d.gstr1_query_solved='$filter_r1_query_solved'";
//     fetchDataAndCount($conn, $month, $year);
// }


// if ($filter_group) {
//     $conditions[] = "b.group_name='$filter_group'";
//     fetchDataAndCount($conn, $month, $year);
// }

// if ($sort_filter_frequancy1) {
//     $conditions[] = "b.return_filing_frequency='$sort_filter_frequancy1'";
//     // Get the selected month and year from the form
//     // $selectedMonth = $_POST['month'];
//     // $selectedYear = $_POST['year'];
//     fetchDataAndCount($conn, $month, $year);
// }

// if (count($conditions) > 0) {
//     $query .= " WHERE " . implode(' AND ', $conditions);
// }

// // if ($sort_trade_name) {
// //     if ($sort_trade_name == 'asc') {
// //         $query .= " ORDER BY b.trade_name ASC";
// //     fetchDataAndCount($conn, $month, $year);

// //     } elseif ($sort_trade_name == 'desc') {
// //         $query .= " ORDER BY b.trade_name DESC";
// //         fetchDataAndCount($conn, $month, $year);
// //     }
// // }

// if ($sort_filter_frequancy) {
//     if ($sort_filter_frequancy == 'asc') {
//         $query .= " ORDER BY b.return_filing_frequency ASC";
//         fetchDataAndCount($conn, $month, $year);

//     } elseif ($sort_filter_frequancy == 'desc') {
//         $query .= " ORDER BY b.return_filing_frequency DESC";
//         fetchDataAndCount($conn, $month, $year);

//     }
// }

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['export_csv'])) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="gst_records.csv"');
    $output = fopen('php://output', 'w');

    // Query to fetch data from both tables
    $sql = "SELECT b.sr_no, b.gstin, b.trade_name, b.return_filing_frequency, b.group_name, 
                   d.gstr3b, d.gstr3b_query, d.gstr3b_query_solved, 
                   d.gstr1, d.gstr1_query, d.gstr1_query_solved,
                   d.month
            FROM gst_basic_info b
            JOIN gst_details d ON b.id = d.basic_info_id
            ORDER BY b.sr_no, FIELD(d.month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // Create an array to store data for each sr_no
    $data = [];
    $months = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $sr_no = $row['sr_no'];
        $month = $row['month'];

        if (!isset($data[$sr_no])) {
            $data[$sr_no] = [
                'SrNo' => $row['sr_no'],
                'GSTIN' => $row['gstin'],
                'Trade Name' => $row['trade_name'],
                'Return Filing Frequency' => $row['return_filing_frequency'],
                'Group' => $row['group_name']
            ];
        }

        // Add the monthly data to the corresponding row
        $data[$sr_no][$month . '_GSTR3B'] = $row['gstr3b'];
        $data[$sr_no][$month . '_GSTR3B Query'] = $row['gstr3b_query'];
        $data[$sr_no][$month . '_GSTR3B Query Solved'] = $row['gstr3b_query_solved'];
        $data[$sr_no][$month . '_GSTR1'] = $row['gstr1'];
        $data[$sr_no][$month . '_GSTR1 Query'] = $row['gstr1_query'];
        $data[$sr_no][$month . '_GSTR1 Query Solved'] = $row['gstr1_query_solved'];

        if (!in_array($month, $months)) {
            $months[] = $month;
        }
    }
    $headers_basic = [
        'SrNo',
        'GSTIN',
        'Trade Name',
        'Return Filing Frequency',
        'Group'
    ];

    // Write CSV headers for the second row (month-specific details)
    $headers_month = [];

    foreach ($months as $month) {
        $headers_month[] = $month;
        $headers_month[] = 'GSTR3B';
        $headers_month[] = 'GSTR3B Query';
        $headers_month[] = 'GSTR3B Query Solved';
        $headers_month[] = 'GSTR1';
        $headers_month[] = 'GSTR1 Query';
        $headers_month[] = 'GSTR1 Query Solved';
    }

    // Combine both headers for the CSV file
    $headers = array_merge($headers_basic, $headers_month);
    fputcsv($output, $headers);

    // Write data to CSV file
    foreach ($data as $sr_no => $row) {
        $csvRow = [];

        // Add basic details to the row
        foreach ($headers_basic as $header) {
            $csvRow[] = $row[$header] ?? '';
        }

        // Add month-specific details to the row
        foreach ($months as $month) {
            $csvRow[] = $month;
            $csvRow[] = $row[$month . '_GSTR3B'] ?? '';
            $csvRow[] = $row[$month . '_GSTR3B Query'] ?? '';
            $csvRow[] = $row[$month . '_GSTR3B Query Solved'] ?? '';
            $csvRow[] = $row[$month . '_GSTR1'] ?? '';
            $csvRow[] = $row[$month . '_GSTR1 Query'] ?? '';
            $csvRow[] = $row[$month . '_GSTR1 Query Solved'] ?? '';
        }

        fputcsv($output, $csvRow);
    }

    // Close file pointer
    fclose($output);
    exit();
}
$result = mysqli_query($conn, $query);
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
            background-color: #ffff;
            /* Light Greenish-Yellow */
            color: #05668d;
            /* Dark Blue */
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
/*Monthly table css */
table#monthlyTable, table#compositionTable {
    border-collapse: collapse;
    width: auto;
}

table#monthlyTable th, table#monthlyTable td, 
table#compositionTable th, table#compositionTable td {
    border: 1px solid #ddd;
    text-align: center;
    font-size: 12px; /* Smaller font size for compact design */
    padding: 4px; /* Less padding for compact design */
    margin: 0; /* Remove margin inside cells */
}

table#monthlyTable th, table#compositionTable th {
    background-color: #f2f2f2;
    font-weight: bold;
}

table#monthlyTable td, table#compositionTable td {
    background-color: #fff;
}

table#monthlyTable select, table#monthlyTable input[type="text"], 
table#compositionTable select, table#compositionTable input[type="text"] {
    width: 100%;
    box-sizing: border-box;
    font-size: 12px; /* Smaller font size for compact design */
    padding: 4px; /* Less padding for compact design */
    margin: 0; /* Remove margin inside form elements */
    border: none; /* Remove borders for a cleaner look */
}

        
        /* composition css*/
        table#compositionTable, table#compositionTable {
    border-collapse: collapse;
    width: auto;
}

table#compositionTable th, table#compositionTable td, 
table#compositionTable th, table#compositionTable td {
    border: 1px solid #ddd;
    text-align: center;
    font-size: 12px; /* Smaller font size for compact design */
    padding: 4px; /* Less padding for compact design */
    margin: 0; /* Remove margin inside cells */
}

table#compositionTable th, table#compositionTable th {
    background-color: #f2f2f2;
    font-weight: bold;
}

table#compositionTable td, table#compositionTable td {
    background-color: #fff;
}

table#compositionTable select, table#compositionTable input[type="text"], 
table#compositionTable select, table#compositionTable input[type="text"] {
    width: 100%;
    box-sizing: border-box;
    font-size: 12px; /* Smaller font size for compact design */
    padding: 4px; /* Less padding for compact design */
    margin: 0; /* Remove margin inside form elements */
    border: none; /* Remove borders for a cleaner look */
}

         /* quarterly css*/
table#quarterlyTable, table#quarterlyTable {
    border-collapse: collapse;
    width: auto;
}

table#quarterlyTable th, table#quarterlyTable td, 
table#quarterlyTable th, table#quarterlyTable td {
    border: 1px solid #ddd;
    text-align: center;
    font-size: 12px; /* Smaller font size for compact design */
    padding: 4px; /* Less padding for compact design */
    margin: 0; /* Remove margin inside cells */
}

table#quarterlyTable th, table#quarterlyTable th {
    background-color: #f2f2f2;
    font-weight: bold;
}

table#quarterlyTable td, table#quarterlyTable td {
    background-color: #fff;
}

table#quarterlyTable select, table#quarterlyTable input[type="text"], 
table#quarterlyTable select, table#quarterlyTable input[type="text"] {
    width: 100%;
    box-sizing: border-box;
    font-size: 12px; /* Smaller font size for compact design */
    padding: 4px; /* Less padding for compact design */
    margin: 0; /* Remove margin inside form elements */
    border: none; /* Remove borders for a cleaner look */
}

/*main table css*/

table.excel-table {
    width: 100%;
    table-layout: fixed;

    /* border-collapse: collapse; */
}

table.excel-table th, table.excel-table td {
    border: 1px solid #ddd;
    text-align: center;
    font-size: 10px; /* Smallest readable font size */
    padding: 0; /* Remove padding inside cells */
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
    font-size: 10px; /* Smallest readable font size */
    padding: 0; /* Remove padding inside form elements */
    margin: 0; /* Remove margin inside form elements */
    border: none; /* Remove borders for a cleaner look */
}

table.excel-table button {
    font-size: 10px; /* Smallest readable font size */
    padding: 0; /* Remove padding inside buttons */
    margin: 0; /* Remove margin inside buttons */
    border: none; /* Remove borders for a cleaner look */
    background: none; /* Remove background for a cleaner look */
    color: blue; /* Add color to indicate it is a button */
    cursor: pointer; /* Change cursor to pointer for button */
}
.srno{
    width: 20px;
}
.gstin-column {
    white-space: nowrap; /* Prevent extra space in GSTIN column */
    width: 100px;
}

.trade-name-column {
    /* width: 300; */
    white-space: wrap; /*Add extra space in Trade Name column */
}

.Return_Filing{
    width: 70px;
}
.compact-column {
    white-space: nowrap; /* Remove extra space in specified columns */
}
    </style>
</head>

<body>
    <!-- Horizontal Navbar -->
    <nav class="navbar navbar-expand-md" style="background: #00b4d8;">
    <a class="navbar-brand" href="#">
        <img src="images/userlogo.jpg" alt="Profile" class="img-fluid rounded-circle">
        <h6>Welcome <?php echo $_SESSION["username"]; ?></h6>
    </a>
    <h5>Dashboard</h5>
    <form action="" class="w-50 mt-2 ml-auto pl-2" method="post">
        <div class="form-row">
            <div class="form-group col-md-4">
                <select class="form-control" id="dynamic-dropdown-month" name="month" >
                    <?php
                    $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                    foreach ($months as $month) {
                        $selected = (isset($_COOKIE['Month']) && $_COOKIE['Month'] == $month) ? 'selected' : '';
                        echo "<option value='$month' $selected>$month</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group col-md-4">
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
            <div class="form-group col-md-4">
                <button type="submit" name="search_data" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="display.php">
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
        <form action="" method="post" class="ml-3"> 
            <button type="submit" name="export_csv" class="btn btn-secondary">ExportCSV</button>
        </form>
    </div>
</nav>
    
<script>
    document.getElementById('showTablesButton').addEventListener('click', function() {
        var monthlyTable = document.getElementById('monthlyTable');
        var compositionTable = document.getElementById('compositionTable');
        var quarterlyTable = document.getElementById('quarterlyTable');

        if (monthlyTable.style.display === 'none') {
            monthlyTable.style.display = 'table';
            compositionTable.style.display = 'table';
            quarterlyTable.style.display = 'table';
        } else {
            monthlyTable.style.display = 'none';
            compositionTable.style.display = 'none';
            quarterlyTable.style.display = 'none';
        }
    });
</script>
    <div id="excel-table1"> 
        <div class=" row text-center custom-table-container">
            <div class="col-3">
                <button id="showTablesButton" class="btn btn-primary mt-2 h-10" >Hide Tables</button>

            </div>
            <div class="col-3 ">
                <table id="monthlyTable" border="1">
                    <thead>
                        <tr>
                            <th>MONTHLY</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>GSTR3B</th>
                            <th>GSTR1</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>FILED</td>
                            <td><?php echo $filedCount; ?></td>
                            <td><?php echo $filedCountGSTR1; ?></td>
                        </tr>
                        <tr> 
                            <td>NIL</td>
                            <td><?php echo $nilCount; ?></td>
                            <td><?php echo $nilCountGSTR1; ?></td>
                        </tr>
                        <tr>
                            <td>TAX</td>
                            <td><?php echo $taxCount; ?></td>
                            <td><?php echo $taxCountGSTR1; ?></td>
                        </tr>
                        <tr>
                            <td>BLANK</td>
                            <td><?php echo $blankCount; ?></td>
                            <td><?php echo $blankCountGSTR1; ?></td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td><?php echo $totalCount; ?></td>
                            <td><?php echo $totalCountGSTR1; ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <div class="col-3">
                <table id="compositionTable" border="1">
                    <thead>
                        <tr>
                            <th>COMPOSITION</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>GSTR3B</th>
                            <th>GSTR1</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>FILED</td>
                            <td><?php echo $filedCountComp; ?></td>
                            <td><?php echo $filedCountGSTR1Comp; ?></td>
                        </tr>
                        <tr> 
                            <td>NIL</td>
                            <td><?php echo $nilCountComp; ?></td>
                            <td><?php echo $nilCountGSTR1Comp; ?></td>
                        </tr>
                        <tr>
                            <td>TAX</td>
                            <td><?php echo $taxCountComp; ?></td>
                            <td><?php echo $taxCountGSTR1Comp; ?></td>
                        </tr>
                        <tr>
                            <td>BLANK</td>
                            <td><?php echo $blankCountComp; ?></td>
                            <td><?php echo $blankCountGSTR1Comp; ?></td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td><?php echo $totalCountComp; ?></td>
                            <td><?php echo $totalCountGSTR1Comp; ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <div class="col-3">
                <table id="quarterlyTable" border="1">
                    <thead>
                        <tr>
                            <th>QUARTERLY</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>GSTR3B</th>
                            <th>GSTR1</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>FILED</td>
                            <td><?php echo $filedCountQtr; ?></td>
                            <td><?php echo $filedCountGSTR1Qtr; ?></td>
                        </tr>
                        <tr> 
                            <td>NIL</td>
                            <td><?php echo $nilCountQtr; ?></td>
                            <td><?php echo $nilCountGSTR1Qtr; ?></td>
                        </tr>
                        <tr>
                            <td>TAX</td>
                            <td><?php echo $taxCountQtr; ?></td>
                            <td><?php echo $taxCountGSTR1Qtr; ?></td>
                        </tr>
                        <tr>
                            <td>BLANK</td>
                            <td><?php echo $blankCountQtr; ?></td>
                            <td><?php echo $blankCountGSTR1Qtr; ?></td>
                        </tr>
                        <tr>
                            <td>TOTAL</td>
                            <td><?php echo $totalCountQtr; ?></td>
                            <td><?php echo $totalCountGSTR1Qtr; ?></td>
                        </tr>
                    </tbody>
                </table>
                </div>
        </div>
    </div>
       
    <script>
    document.getElementById('showTablesButton').addEventListener('click', function() {
        var monthlyTable = document.getElementById('monthlyTable');
        var compositionTable = document.getElementById('compositionTable');
        var quarterlyTable = document.getElementById('quarterlyTable');

        if (monthlyTable.style.display === 'none') {
            monthlyTable.style.display = 'table';
            compositionTable.style.display = 'table';
            quarterlyTable.style.display = 'table';
        } else {
            monthlyTable.style.display = 'none';
            compositionTable.style.display = 'none';
            quarterlyTable.style.display = 'none';
        }
    });
</script>

        <br>

    <div class="">
        <form action="" method="post" id="table-form">
        <table border="2" class="excel-table">
    <thead class="thead-dark">
        <tr class="text-center">
            <th class="srno">No</th>
            <th class="gstin-column">GSTIN</th>
            <th class="trade-name-column">Trade Name</th>
            <th class="Return_Filing">Return Filing Frequency</th>
            <th class="compact-column">Group</th>
            <th class="compact-column">GSTR 3B</th>
            <th width="240">3B Query</th>
            <th class="compact-column">3B Query Solved</th>
            <th class="compact-column">GSTR 1</th>
            <th width="240">R1 Query</th>
            <th class="compact-column">R1 Query Solved</th>
            <th>Action</th>
        </tr>
        <tr class="text-center">
            <th class="border-0 bg-white"></th>
            <th class="border-0 bg-white"></th>
            <th class="border-0 bg-white"></th>
            <th class="border-0 bg-white">
                <select class="" name="sort_filter_frequancy"
                    onchange="document.getElementById('table-form').submit();">
                    <option value="" <?php if ($sort_filter_frequancy == '') echo 'selected'; ?>>Default</option>
                    <option value="asc" <?php if ($sort_filter_frequancy == 'asc') echo 'selected'; ?>>A to Z</option>
                    <option value="desc" <?php if ($sort_filter_frequancy == 'desc') echo 'selected'; ?>>Z to A</option>
                </select>
                <select class="" id="sort_filter_frequancy1" name="sort_filter_frequancy1"
                    onchange="document.getElementById('table-form').submit();">
                    <option value="" <?php if ($filter_gstr3b == '') echo 'selected'; ?>>All</option>
                    <option value="COMPOSITION" <?php if ($sort_filter_frequancy1 == 'COMPOSITION') echo 'selected'; ?>>COMPOSITION</option>
                    <option value="MONTHLY" <?php if ($sort_filter_frequancy1 == 'MONTHLY') echo 'selected'; ?>>MONTHLY</option>
                    <option value="QUARTERLY" <?php if ($sort_filter_frequancy1 == 'QUARTERLY') echo 'selected'; ?>>QUARTERLY</option>
                </select>
            </th>
            <?php
            // Fetch data from gst_basic_info table
            $sqlGq = "SELECT * FROM gst_basic_info";
            $resultGq = mysqli_query($conn, $sqlGq);

            $rows = [];
            while ($row = mysqli_fetch_assoc($resultGq)) {
                $rows[] = $row;
            }

            // Fetch unique group values for filter
            $sqlGroups = "SELECT DISTINCT group_name FROM gst_basic_info";
            $resultGroups = mysqli_query($conn, $sqlGroups);

            $groups = [];
            while ($group = mysqli_fetch_assoc($resultGroups)) {
                $groups[] = $group['group_name'];
            }

            ?>
            <th class="border-0 bg-white">
                <select select class="" name="filter_group" onchange="document.getElementById('table-form').submit();">
                        <option value="" <?php if ($filter_group == '') echo 'selected'; ?>>All</option>
                        <?php foreach ($groups as $group): ?>
                            <option value="<?php echo $group; ?>" <?php if ($filter_group == $group) echo 'selected'; ?>><?php echo $group; ?></option>
                        <?php endforeach; ?>
                </select>
            </th>
            <th class="border-0 bg-white">
                <select class="" name="filter_gstr3b"
                    onchange="document.getElementById('table-form').submit();">
                    <option value="" <?php if ($filter_gstr3b == '') echo 'selected'; ?>>All</option>
                    <option value="BLANK" <?php if ($filter_gstr3b == 'BLANK') echo 'selected'; ?>>BLANK</option>
                    <option value="FILED" <?php if ($filter_gstr3b == 'FILED') echo 'selected'; ?>>FILED</option>
                    <option value="NIL" <?php if ($filter_gstr3b == 'NIL') echo 'selected'; ?>>NIL</option>
                    <option value="TAX" <?php if ($filter_gstr3b == 'TAX') echo 'selected'; ?>>TAX</option>
                </select>
            </th>
            <th class="border-0 bg-white">
                <select class="" name="filter_3b_query"
                    onchange="document.getElementById('table-form').submit();">
                    <option value="" <?php if ($filter_3b_query == '') echo 'selected'; ?>>All</option>
                    <option value="with_value" <?php if ($filter_3b_query == 'with_value') echo 'selected'; ?>>with_value</option>
                    <option value="no_value" <?php if ($filter_3b_query == 'no_value') echo 'selected'; ?>>no_value</option>
                </select>
            </th>
            <th class="border-0 bg-white">
                <select class="" name="filter_3b_query_solved"
                    onchange="document.getElementById('table-form').submit();">
                    <option value="" <?php if ($filter_3b_query_solved == '') echo 'selected'; ?>>All</option>
                    <option value="NA" <?php if ($filter_3b_query_solved == 'NA') echo 'selected'; ?>>NA</option>
                    <option value="YES" <?php if ($filter_3b_query_solved == 'YES') echo 'selected'; ?>>YES</option>
                    <option value="NO" <?php if ($filter_3b_query_solved == 'NO') echo 'selected'; ?>>NO</option>
                </select>
            </th>
            <th class="border-0 bg-white">
                <select class="" name="filter_gstr1"
                    onchange="document.getElementById('table-form').submit();">
                    <option value="" <?php if ($filter_gstr1 == '') echo 'selected'; ?>>All</option>
                    <option value="BLANK" <?php if ($filter_gstr1 == 'BLANK') echo 'selected'; ?>>BLANK</option>
                    <option value="FILED" <?php if ($filter_gstr1 == 'FILED') echo 'selected'; ?>>FILED</option>
                    <option value="NIL" <?php if ($filter_gstr1 == 'NIL') echo 'selected'; ?>>NIL</option>
                    <option value="TAX" <?php if ($filter_gstr1 == 'TAX') echo 'selected'; ?>>TAX</option>
                </select>
            </th>
            <th class="border-0 bg-white">
                <select class="" name="filter_r1_query"
                    onchange="document.getElementById('table-form').submit();">
                    <option value="" <?php if ($filter_r1_query == '') echo 'selected'; ?>>All</option>
                    <option value="with_value" <?php if ($filter_r1_query == 'with_value') echo 'selected'; ?>>with_value</option>
                    <option value="no_value" <?php if ($filter_r1_query == 'no_value') echo 'selected'; ?>>no_value</option>
                </select>
            </th>
            <th class="border-0 bg-white">
                <select class="" name="filter_r1_query_solved"
                        onchange="document.getElementById('table-form').submit();">
                        <option value="" <?php if ($filter_r1_query_solved == '') echo 'selected'; ?>>All</option>
                        <option value="NA" <?php if ($filter_r1_query_solved == 'NA') echo 'selected'; ?>>NA</option>
                        <option value="YES" <?php if ($filter_r1_query_solved == 'YES') echo 'selected'; ?>>YES</option>
                        <option value="NO" <?php if ($filter_r1_query_solved == 'NO') echo 'selected'; ?>>NO</option>
                </select>
            </th>
            <th class="border-0 bg-white"></th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $i = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $rowId = $row["id"]; // Unique identifier for each row
                echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="post">';
                echo "<input type='hidden' name='id' value='" . $rowId . "'>";
                echo "<input type='hidden' name='sr_no' value='" . $row["sr_no"] . "'>";
                echo "<input type='hidden' name='gstin' value='" . $row["gstin"] . "'>";
                echo "<input type='hidden' name='trade_name' value='" . $row["trade_name"] . "'>";
                echo "<input type='hidden' name='return_filing_frequency' value='" . $row["return_filing_frequency"] . "'>";
                echo "<input type='hidden' name='group_name' value='" . $row["group_name"] . "'>";
                echo "<tr>";
                echo "<td>" . $i . "</td>";
                echo "<td class='gstin-column'>" . $row["gstin"] . "</td>";
                echo "<td class='trade-name-column'>" . $row["trade_name"] . "</td>";
                echo "<td>" . $row["return_filing_frequency"] . "</td>";
                echo "<td class='compact-column'>" . $row["group_name"] . "</td>";
                echo "<td class='compact-column'>";
                echo "<select class='form-control' name='gstr3b' id='gstr3bFocus'>";
                echo "<option value='BLANK'" . ($row["gstr3b"] == 'BLANK' ? " selected" : "") . ">BLANK</option>";
                echo "<option value='FILED'" . ($row["gstr3b"] == 'FILED' ? " selected" : "") . ">FILED</option>";
                echo "<option value='NIL'" . ($row["gstr3b"] == 'NIL' ? " selected" : "") . ">NIL</option>";
                echo "<option value='TAX'" . ($row["gstr3b"] == 'TAX' ? " selected" : "") . ">TAX</option>";
                echo "</select>";
                echo "</td>";
                echo "<td><input type='text' class='form-control' name='gstr3b_query' value='" . $row["gstr3b_query"] . "' data-toggle='tooltip' data-placement='top' title='" . $row["gstr3b_query"] . "'></td>";
                echo "<td class='compact-column'>";
                echo "<select class='form-control' name='gstr3b_query_solved'>";
                echo "<option value='NA'" . ($row["gstr3b_query_solved"] == 'NA' ? " selected" : "") . ">NA</option>";
                echo "<option value='YES'" . ($row["gstr3b_query_solved"] == 'YES' ? " selected" : "") . ">YES</option>";
                echo "<option value='NO'" . ($row["gstr3b_query_solved"] == 'NO' ? " selected" : "") . ">NO</option>";
                echo "</select>";
                echo "</td>";
                echo "<td class='compact-column'>";
                echo "<select class='form-control' name='gstr1'>";
                echo "<option value='BLANK'" . ($row["gstr1"] == 'BLANK' ? " selected" : "") . ">BLANK</option>";
                echo "<option value='FILED'" . ($row["gstr1"] == 'FILED' ? " selected" : "") . ">FILED</option>";
                echo "<option value='NIL'" . ($row["gstr1"] == 'NIL' ? " selected" : "") . ">NIL</option>";
                echo "<option value='TAX'" . ($row["gstr1"] == 'TAX' ? " selected" : "") . ">TAX</option>";
                echo "</select>";
                echo "</td>";
                echo "<td><input type='text' class='form-control' name='gstr1_query' value='" . $row["gstr1_query"] . "' data-toggle='tooltip' data-placement='top' title='" . $row["gstr1_query"] . "'></td>";
                echo "<td class='compact-column'>";
                echo "<select class='form-control' name='gstr1_query_solved'>";
                echo "<option value='NA'" . ($row["gstr1_query_solved"] == 'NA' ? " selected" : "") . ">NA</option>";
                echo "<option value='YES'" . ($row["gstr1_query_solved"] == 'YES' ? " selected" : "") . ">YES</option>";
                echo "<option value='NO'" . ($row["gstr1_query_solved"] == 'NO' ? " selected" : "") . ">NO</option>";
                echo "</select>";
                echo "</td>";
                echo "<td><button type='submit' name='update_gst_data' id='updateButton' class='btn btn-primary'>Update</button></td>";
                echo "</tr>";
                echo "</form>";
                $i++;
            }
        } else {
            echo "<tr><td colspan='12' align='center'>0 results</td></tr>";
        }

        // Close database connection
        mysqli_close($conn);
        ?>
    </tbody>
    <script>
        document.getElementById('gstr3bFocus').addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault(); // Prevent the default action
                document.getElementById('updateButton').click(); // Trigger the button click
            }
        });
    </script>
</table>

        </form>
    </div>

    <script>
        document.addEventListener('keydown', function(event) {
            // Check if Ctrl and . are pressed together
            if (event.ctrlKey && event.key === 'q' || event.ctrlKey && event.key === 'Q') {
                // Prevent the default action
                event.preventDefault();

                // Set focus to the second dropdown
                document.getElementById('gstr3bFocus').focus();
            }
        });
    </script>

    <script>
        // Function to handle the change event of the dropdown list
        const handleDropdownChange = () => {
            const dropdown = document.getElementById("dynamic-dropdown-month");
            const selectedValueMonth = dropdown.value;
            document.cookie = "Month=" + encodeURIComponent(selectedValueMonth);
        };

        // Add event listener to the dropdown list
        document.getElementById("dynamic-dropdown-month").addEventListener("change", handleDropdownChange);
        const handleDropdownChange2 = () => {
            const dropdown = document.getElementById("dynamic-dropdown-year");
            const selectedValueYear = dropdown.value;
            document.cookie = "Year=" + encodeURIComponent(selectedValueYear);
        };
        document.getElementById("dynamic-dropdown-year").addEventListener("change", handleDropdownChange2);
    </script>

</body>

</html>