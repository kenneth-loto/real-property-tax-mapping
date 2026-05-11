<?php
include 'header.php';

$admin_email = $_SESSION['email'] ?? '';

?>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
            <h5 class="card-title fw-semibold mb-4 text-dark">Requests</h5>
            <a class="btn btn-primary" role="button" href="add_requests.php" style="margin-bottom: 20px;">Add Request</a>
            <?php
            $msg = Session::get("msg");
            if (isset($msg)) {
                echo $msg;
                Session::set("msg", NULL);
            }
            ?>
            <div class="col-lg-12 d-flex align-items-stretch text-dark">
                <div class="card w-100">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="table">
                            <thead class="text-dark">
                                <tr>
                                    <th class="border-bottom-0 header text-light">ID</th>
                                    <th class="border-bottom-0 header text-light">Pending Requests</th>
                                    <th class="border-bottom-0 header text-light">Approved At</th>
                                    <th class="border-bottom-0 header text-light">Status</th>
                                    <th class="border-bottom-0 header text-light">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark">
                                <?php
                                $i = 0;
                                $requests = $function->getAllStaffApprovedRequestsForAdmin(); // Assuming you have this function to fetch requests

                                // Sort requests based on custom order
                                usort($requests, function ($a, $b) {
                                    $order = ['Staff Approved', 'Admin Approved', 'Admin Rejected'];
                                    return array_search($a['status'], $order) - array_search($b['status'], $order);
                                });

                                if ($requests) {
                                    foreach ($requests as $request) :
                                        $i++;
                                        $id = $request['request_id'];
                                        $applicant_name = $request['applicant_name'];
                                        $approved_at = $request['approved_at'];

                                        // Check the request status and set progress bar parameters
                                        $status = $request['status']; // Get the status of the request
                                        $submissionColor = 'bg-success';
                                        $submissionText = 'Submitted';
                                        $staffReviewColor = 'bg-warning';
                                        $staffText = 'Staff on Review';
                                        $adminReviewColor = 'bg-warning';
                                        $adminText = 'Waiting Approval';
                                        $submissionWidth = '28%'; // Static width for submission
                                        $staffWidth = '36%'; // Default width for staff review
                                        $adminWidth = '36%'; // Default width for admin review

                                        // Adjust colors and texts based on request status
                                        switch ($status) {
                                            case 'Pending':
                                                $staffReviewColor = 'bg-warning';
                                                $staffText = 'Staff on Review';
                                                $adminReviewColor = 'bg-warning';
                                                $adminText = 'Waiting Approval';
                                                break;

                                            case 'Staff Approved':
                                                $staffReviewColor = 'bg-success';
                                                $staffText = 'Staff Reviewed';
                                                $adminReviewColor = 'bg-warning'; // Admin not approved yet
                                                $adminText = 'Waiting Approval';
                                                break;

                                            case 'Admin Approved':
                                                $staffReviewColor = 'bg-success'; // If staff approved, keep it green
                                                $staffText = 'Staff Reviewed';
                                                $adminReviewColor = 'bg-success';
                                                $adminText = 'Admin Approved';
                                                break;

                                            case 'Admin Rejected':
                                                $submissionColor = 'bg-success';
                                                $submissionText = 'Submitted';
                                                $staffReviewColor = 'bg-success';
                                                $staffText = 'Staff Reviewed';
                                                $adminReviewColor = 'bg-danger';
                                                $adminText = 'Admin Rejected';
                                                break;
                                        }
                                ?>
                                        <tr class="text-align-left">
                                            <td class="text-center"><?= $i; ?></td>
                                            <td>
                                                <?= $applicant_name; ?><br>
                                            </td>
                                            <td><?= date('F j, Y, g:i a', strtotime($approved_at)); ?></td>
                                            <td>
                                                <div class="progress" style="display: flex; height: 50px;">
                                                    <div class="progress-bar <?= $submissionColor; ?>" role="progressbar" style="color: black; width: <?= $submissionWidth; ?>; height: 50px; border-right: 1px solid white;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $submissionText; ?>
                                                    </div>
                                                    <div class="progress-bar <?= $staffReviewColor; ?>" role="progressbar" style="color: black; width: <?= $staffWidth; ?>; height: 50px; border-right: 1px solid white;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $staffText; ?>
                                                    </div>
                                                    <div class="progress-bar <?= $adminReviewColor; ?>" role="progressbar" style="color: black; width: <?= $adminWidth; ?>; height: 50px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        <?= $adminText; ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <!-- View Button (always visible) -->
                                                <a class="btn btn-secondary" href="view_request.php?id=<?= $id; ?>" title="Review Request">
                                                    <i class="fa fa-eye" style="font-size: 1.1rem;"></i>
                                                </a>

                                                <?php if ($status !== 'Admin Approved' && $status !== 'Admin Rejected') { ?>
                                                    <!-- Approve Button Form (only visible if not Admin Approved or Rejected) -->
                                                    <form action="../navigate.php" method="POST" style="display:inline-block;">
                                                        <input type="hidden" name="request_id" value="<?= $id; ?>">
                                                        <input type="hidden" name="admin_email" value="<?= htmlspecialchars($admin_email); ?>">
                                                        <button type="submit" name="btn-admin-approved-request" class="btn btn-success" title="Approve Request" onclick="return confirm('Are you sure you want to approve this request? Please review the details first before proceeding.');">
                                                            <i class="fa fa-check" style="font-size: 1.1rem;"></i>
                                                        </button>
                                                    </form>

                                                    <a class="btn btn-danger" href="reject_request.php?id=<?= $id; ?>" title="Reject Request">
                                                        <i class="fa fa-times" style="font-size: 1.1rem;"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php
                                        $i++;
                                    endforeach;
                                } else {
                                    // Handle case where no requests exist
                                    echo "<tr><td colspan='5' class='text-center text-light'>No requests found</td></tr>";
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
<script src="../assets/js/app.js"></script>
<script>
    $(document).ready(function() {
        $('#table').DataTable();
    });
</script>
