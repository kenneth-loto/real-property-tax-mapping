<?php
include 'header.php';
include_once '../conn.php';

// Create an instance of the connection class
$dbConnection = new conn();
$pdo = $dbConnection->conn; // Access the PDO connection

// Create an instance of the Functions class
$functions = new Functions();

// Fetch totals and transaction history
$totalRequests = $functions->fetchTotalRequests();
$totalApproved = $functions->fetchTotalApprovedRequests();
$totalRejected = $functions->fetchTotalRejectedRequests();
$transactionHistoryResult = $functions->fetchTransactionHistory();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real Property Tax Mapping with Tax Collection System</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
    <script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
    <link rel="stylesheet" href="../assets/css/styles.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
    <link rel="stylesheet" href="../assets/css/external.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/requests.css">
    <link rel="stylesheet" href="../assets/css/table.css">
</head>
<body>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4 text-black">Dashboard</h5>
            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-light text-dark">
                        <div class="card-body">
                            <h5 class="card-title">Total Number of Properties</h5>
                            <p class="card-text"><?php echo htmlspecialchars($totalApproved); ?></p> <!-- Total approved properties -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light text-dark">
                        <div class="card-body">
                            <h5 class="card-title">Total Number of Requests</h5>
                            <p class="card-text"><?php echo htmlspecialchars($totalRequests); ?></p> <!-- Total requests by user -->
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light text-dark">
                        <div class="card-body">
                            <h5 class="card-title">Total Number of Rejected Requests</h5>
                            <p class="card-text"><?php echo htmlspecialchars($totalRejected); ?></p> <!-- Total rejected requests by user -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row mb-3">
                <div class="col-lg-12 text-dark">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Request Overview</h5>
                            <div id="chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Info -->
            <h3 class="fw-semibold text-black mb-4">Submitted Requests History</h3>
            <div class="text-dark"> 
            <table class="table table-bordered table-dark" id="table">
                <thead>
                    <tr>
                        <th class="header text-light">ID</th>
                        <th class="header text-light">PIN</th>
                        <th class="header text-light">Location</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0; // Counter for formal ID starting from 1
                    $approvedRequest = $functions->fetchTransactionHistory(); // Call the function to fetch transaction history
                    if ($approvedRequest) {
                        foreach ($approvedRequest as $row) :
                            $i++; // Increment the counter for each transaction
                            $id = $row['id']; // Original ID for operations like Edit/Delete
                            $pin = $row['pin'];
                            $street = $row['street'];
                            $barangay = $row['barangay'];
                            $municipality = $row['municipality'];
                            $province = $row['province'];
                    ?>
                            <tr class="text-align-left text-dark">
                                <td style="text-align: center !important;"><?= $i; ?></td> <!-- Display formal ID -->
                                <td class="text-center">
                                    <?= $pin; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($street); ?> <?= htmlspecialchars($barangay); ?> <?= htmlspecialchars($municipality); ?> <?= htmlspecialchars($province); ?>
                                </td>
                            </tr>
                    <?php
                        endforeach;
                    } else {
                        // Handle case where no transactions exist
                        echo "<tr><td colspan='3' class='text-center text-black'>No transactions found</td></tr>"; // Adjusted colspan to match the number of columns
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
<script>
    const options = {
        chart: {
            type: 'bar',
            height: 300
        },
        series: [{
            name: 'Requests',
            data: [<?php echo $totalApproved; ?>, <?php echo $totalRequests; ?>, <?php echo $totalRejected; ?>] // Total properties, total requests, rejected requests
        }],
        xaxis: {
            categories: ['Total Properties', 'Total Requests', 'Rejected Requests'] // Updated categories
        },
        colors: ['#34495e'] // Set chart color to gray
    };

    const chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

<script src="../assets/js/sidebarmenu.js"></script>

<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script>

</body>
</html>
