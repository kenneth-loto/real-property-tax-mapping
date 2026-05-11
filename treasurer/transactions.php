<?php
include 'header.php';
unset($_SESSION['form_data']);

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit; // Stop further execution
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
<script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="../assets/css/requests.css">
<link rel="stylesheet" href="../assets/css/table.css">

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4 text-dark">Payment of Requests</h5>
            <?php
            $msg = Session::get("msg");
            if (isset($msg)) {
                echo $msg;
                Session::set("msg", NULL);
            }
            ?>
            <div class="col-lg-12 d-flex align-items-stretch text-dark">
                <div class="w-100">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="table">
                            <thead class="text-dark">
                                <tr>
                                    <th class="border-bottom-0 header text-light">ID</th>
                                    <th class="border-bottom-0 header text-light">Reviewed At</th>
                                    <th class="border-bottom-0 header text-light">TD Number</th>
                                    <th class="border-bottom-0 header text-light">Status</th>
                                    <th class="border-bottom-0 header text-light">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark fw-bold">
                                <?php
                                $i = 0;
                                $approvedRequests = $function->getAllStaffApprovedRequests(); // Fetch approved requests
                                $pendingRequests = [];
                                $paidRequests = [];

                                // Separate requests into pending and paid
                                if ($approvedRequests) {
                                    foreach ($approvedRequests as $request) {
                                        $id = $request['request_id'];
                                        $updated_at = $request['approved_at'];
                                        $td_number = $request['td_number'];

                                        // Fetch the payment status for this request
                                        $requestStatus = $function->getTreasurerStatus($id); // Fetch status from treasurer_paid_requests
                                        $status = $requestStatus ? $requestStatus['payment_status'] : 'Pending'; // If not found, default to 'Pending'

                                        // Store requests in respective arrays
                                        if ($status === 'Paid') {
                                            $paidRequests[] = [
                                                'id' => $id,
                                                'updated_at' => $updated_at,
                                                'td_number' => $td_number,
                                                'status' => $status
                                            ];
                                        } else {
                                            $pendingRequests[] = [
                                                'id' => $id,
                                                'updated_at' => $updated_at,
                                                'td_number' => $td_number,
                                                'status' => $status
                                            ];
                                        }
                                    }

                                    // Display pending requests first
                                    foreach ($pendingRequests as $request) {
                                        $i++;
                                        $id = $request['id'];
                                        $updated_at = $request['updated_at'];
                                        $td_number = $request['td_number'];
                                        $status = $request['status'];

                                        $displayStatus = 'Pending';
                                        $statusColor = 'bg-warning';
                                        $actionButtons = '
                                            <a class="btn btn-warning" href="transaction_details.php?id=' . $id . '" title="Review Request">
                                                <i class="fas fa-file-signature" style="font-size: 1.1rem;"></i>
                                            </a>
                                            <a class="btn btn-primary" href="view_transaction_details.php?id=' . $id . '" title="View Request">
                                                <i class="fa fa-eye" style="font-size: 1.1rem;"></i>
                                            </a>';
                                ?>
                                        <tr class="text-center">
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= date('F j, Y, g:i a', strtotime($updated_at)); ?></td>
                                            <td><?= $td_number; ?></td>
                                            <td>
                                                <div class="progress" style="display: flex; height: 40px;">
                                                    <div class="progress-bar <?= $statusColor; ?>" role ="progressbar" style="width: 100%; height: 40px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $displayStatus; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?= $actionButtons; ?>
                                            </td>
                                        </tr>
                                <?php
                                    }

                                    // Display paid requests
                                    foreach ($paidRequests as $request) {
                                        $i++;
                                        $id = $request['id'];
                                        $updated_at = $request['updated_at'];
                                        $td_number = $request['td_number'];
                                        $status = $request['status'];

                                        $displayStatus = 'Paid';
                                        $statusColor = 'bg-success';
                                        $actionButtons = '
                                            <a class="btn btn-primary" href="view_transaction_details.php?id=' . $id . '" title="View Request">
                                                <i class="fa fa-eye" style="font-size: 1.1rem;"></i>
                                            </a>
                                            <a class="btn btn-warning" href="edit_transaction.php?id=' . $id . '" title="Edit Request">
                                                <i class="fas fa-edit" style="font-size: 1.1rem;"></i>
                                            </a>';
                                ?>
                                        <tr class="text-center">
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= date('F j, Y, g:i a', strtotime($updated_at)); ?></td>
                                            <td><?= $td_number; ?></td>
                                            <td>
                                                <div class="progress" style="display: flex; height: 40px;">
                                                    <div class="progress-bar <?= $statusColor; ?>" role="progressbar" style="width: 100%; height: 40px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $displayStatus; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?= $actionButtons; ?>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    // Handle case where no approved requests exist
                                    echo "<tr><td colspan='5' class='text-center'>No approved requests found</td></tr>";
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/libs/jquery/dist/jquery.min.js"></script>
<script src="../assets/js/sidebarmenu.js"></script>

<script>
    $(document).ready(function() {
        <?php if (!empty($approvedRequests)): ?>
            $('#table').DataTable();
        <?php endif; ?>
    });
</script>

</body>
</html>