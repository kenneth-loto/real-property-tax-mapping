<?php
include 'header.php';
include_once '../conn.php';

// Create an instance of the connection class
$dbConnection = new conn();
$pdo = $dbConnection->conn; // Access the PDO connection

// Create an instance of the Functions class
$functions = new Functions();

// Fetch totals and transaction history
$totalRequests = $functions->fetchTotalRequestsOfApplicants();
$totalApproved = $functions->fetchTotalStaffApprovedRequests();
$totalRejected = $functions->fetchTotalStaffRejectedRequests();
$transactionHistoryResult = $functions->fetchStaffTransactionHistory();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                            <h5 class="card-title">Staff Approved Requests</h5>
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
                            <h5 class="card-title">Staff Rejected Requests</h5>
                            <p class="card-text"><?php echo htmlspecialchars($totalRejected); ?></p> <!-- Total rejected requests by user -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold text-black">Request Overview</h5>
                            <div id="chart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Info -->
            <div class="text-dark">
                <h3 class="fw-semibold text-dark mb-4">Staff Reviewed Requests</h3>
                <table class="table table-bordered table-hover" id="table">
                    <thead>
                        <tr class="text-black">
                            <th class="header text-light">ID</th>
                            <th class="header text-light">Name</th>
                            <th class="header text-light">Pin</th>
                            <th class="header text-light">Location</th>
                            <th class="header text-light">Status</th>
                            <th class="header text-light">Reviewed At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0; // Counter for formal ID starting from 1
                        $approvedRequest = $transactionHistoryResult; // Use the fetched transaction history
                        if ($approvedRequest) {
                            foreach ($approvedRequest as $row) :
                                $i++; // Increment the counter for each transaction
                                $transaction_id = $row['transaction_id']; // Unique transaction ID
                                $full_name = $row['full_name']; // Full name of the applicant
                                $pin = $row['pin'];
                                $street = $row['street'];
                                $barangay = $row['barangay'];
                                $municipality = $row['municipality'];
                                $province = $row['province'];
                                $status = $row['status'];
                                $transaction_date = $row['transaction_date'];
                        ?>
                            <tr class="text-align-left text-black">
                                <td class="text-center"><?= $i; ?></td> <!-- Display formal ID -->
                                <td><?= htmlspecialchars($full_name); ?></td>
                                <td class="text-center">
                                    <?= htmlspecialchars($pin); ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($street); ?>, <?= htmlspecialchars($barangay); ?>, <?= htmlspecialchars($municipality); ?>, <?= htmlspecialchars($province); ?>
                                </td>
                                <td><?= htmlspecialchars($status); ?></td>
                                <td><?= date('F j, Y, g:i a', strtotime($transaction_date)); ?></td>
                            </tr>
                        <?php
                            endforeach;
                        } else {
                            // Handle case where no transactions exist
                            echo "<tr><td colspan='6' class='text-center text-black'>No transactions found</td></tr>"; // Adjusted colspan to match the number of columns
                        }
                        ?>
                    </tbody>
                </table>
            </div>
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
            categories: ['Total Staff Approved Requests', 'Total Number of Requests', 'Total Staff Rejected Requests'] // Updated categories
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
