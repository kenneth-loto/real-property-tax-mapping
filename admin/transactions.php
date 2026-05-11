<?php
include 'header.php';

$functions = new Functions();

// Fetch the approved requests only once
$transactionHistoryResult = $functions->fetchAdminTransactionHistory();
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="../assets/css/requests.css">
<link rel="stylesheet" href="../assets/css/table.css">

<div class="container-fluid text-dark">
    <div class="card">
        <div class="card-body">
            <h3 class="fw-semibold mb-4 mt-2 text-dark">Admin Reviewed Request</h3>
            <table class="table table-bordered table-hover" id="table">
                    <thead>
                        <tr class="text-black">
                            <th class="header text-light">ID</th>
                            <th class="header text-light">Name</th>
                            <th class="header text-light">Pin</th>
                            <th class="header text-light">Location</th>
                            <th class="header text-light">Status</th>
                            <th class="header text-light">Approved At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0; // Counter for formal ID starting from 1
                        $approvedRequest = $transactionHistoryResult; // Use the fetched transaction history
                        if ($approvedRequest) {
                            foreach ($approvedRequest as $row) :
                                $i++; // Increment the counter for each transaction
                                $request_id = $row['request_id']; // Unique transaction ID
                                $full_name = $row['full_name']; // Full name of the applicant
                                $pin = $row['pin'];
                                $street = $row['street'];
                                $barangay = $row['barangay'];
                                $municipality = $row['municipality'];
                                $province = $row['province'];
                                $status = $row['status'];
                                $transaction_date = $row['transaction_date'];
                        ?>
                            <tr class="text-align-left">
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

<script src="../assets/js/sidebarmenu.js"></script>

<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script>

</body>
</html>
