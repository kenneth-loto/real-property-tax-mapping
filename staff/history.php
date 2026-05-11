<?php
include 'header.php';

$functions = new Functions();

// Fetch the approved requests only once
$requestsHistory = $functions->fetchAllStaffApprovedRequests();
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
            <h3 class="fw-semibold mb-4 mt-2 text-dark">Staff Approved Requests History</h3>
            <table class="table table-bordered table-hover" id="table">
                <thead>
                    <tr>
                        <th class="header text-light">ID</th>
                        <th class="header text-light">Name of Declarant</th>
                        <th class="header text-light">Pin</th>
                        <th class="header text-light">Market Value</th>
                        <th class="header text-light">Tax Due</th>
                        <th class="header text-light">Approved At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0; // Counter for formal ID starting from 1
                    if (!empty($requestsHistory)) {
                        foreach ($requestsHistory as $row) :
                            $i++; // Increment the counter for each transaction
                            $id = $row['id']; // Original ID for operations like Edit/Delete
                            // Construct the full name from the applicant's details
                            $name = htmlspecialchars($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name'] . ' ' . $row['suffix']);
                            $pin = htmlspecialchars($row['pin']);
                            $market_value = htmlspecialchars($row['market_value']);
                            $tax_due = htmlspecialchars($row['tax_due']);
                            $approved_at = $row['approved_at']; // This should already be formatted
                    ?>
                            <tr class="text-align-left text-dark">
                                <td style="text-align: center !important"><?= $i; ?></td> <!-- Display formal ID -->
                                <td>
                                    <label><?= $name; ?></label>
                                </td>
                                <td>
                                    <?= $pin; ?></label>
                                </td>
                                <td style="text-align: center !important">
                                    <label><?= $market_value; ?></label>
                                </td>
                                <td style="text-align: center !important">
                                    <label><?= $tax_due; ?></label>
                                </td>
                                <td>
                                    <?= date('F j, Y, g:i a', strtotime($approved_at)); ?>
                                </td>
                            </tr>
                    <?php
                        endforeach;
                    } else {
                        // Handle case where no transactions exist
                        echo "<tr><td colspan='6' class='text-center text-dark'>No transactions found</td></tr>"; // Corrected colspan to 6
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
