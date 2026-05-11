<?php
include 'header.php';
unset($_SESSION['form_data']);
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
            <h3 class="fw-semibold mb-4 mt-2 text-dark">Requests</h3>
            <a class="btn btn-secondary mb-4" role="button" href="add_requests.php">Add Request</a>
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
                                    <th class="border-bottom-0 header text-light">Name of Declarant</th>
                                    <th class="border-bottom-0 header text-light">Pin</th>
                                    <th class="border-bottom-0 header text-light">Date of Request</th>
                                    <th class="border-bottom-0 header text-light">Status</th>
                                    <th class="border-bottom-0 header text-light">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-dark">
                                <?php
                                $i = 0;
                                $requests = $function->getAllRequests(); // Fetch all requests

                                if ($requests) {
                                    // Define the status order and colors
                                    $statusOrder = [
                                        'Staff Rejected' => 5,
                                        'Admin Rejected' => 4,
                                        'Staff Approved' => 3,
                                        'Admin Approved' => 2,
                                        'Pending' => 1,
                                    ];

                                    $statusColorMap = [
                                        'Pending' => 'bg-warning',
                                        'Staff Approved' => 'bg-success',
                                        'Admin Approved' => 'bg-success',
                                        'Staff Rejected' => 'bg-danger',
                                        'Admin Rejected' => 'bg-danger',
                                    ];

                                    usort($requests, function ($a, $b) use ($statusOrder) {
                                        return ($statusOrder[$a['status']] ?? 99) - ($statusOrder[$b['status']] ?? 99);
                                    });

                                    foreach ($requests as $request) :
                                        $id = $request['id'];
                                        $name = $request['applicant_name'];
                                        $pin = $request['pin'];
                                        $created_at = $request['created_at'];
                                        $status = $request['status'];

                                        $statusColor = $statusColorMap[$status] ?? 'bg-warning';
                                        $i++;
                                ?>
                                        <tr class="text-align-left">
                                            <td class="text-center"><?= $i; ?></td>
                                            <td><?= htmlspecialchars($name) ?></td>
                                            <td class="text-center"><?= htmlspecialchars($pin) ?></td>
                                            <td><?= date('F j, Y, g:i a', strtotime($created_at)); ?></td>
                                            <td>
                                                <div class="progress" style="display: flex; height: 45px;">
                                                    <div class="progress-bar <?= $statusColor; ?>" role="progressbar" style="color: black; width: 100%; height: 45px;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                        <?= htmlspecialchars($status); ?>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                // Determine button actions based on status
                                                $buttons = [];
                                                if ($status === 'Pending') {
                                                    $buttons[] = '<a class="btn btn-warning" href="review_request.php?id=' . $id . '" title="Review Request"><i class="fas fa-file-signature" style="font-size: 1.1rem;"></i></a>';
                                                } else {
                                                    $buttons[] = '<a class="btn btn-secondary" href="view_request.php?id=' . $id . '" title="View Request"><i class="fa fa-eye" style="font-size: 1.1rem;"></i></a>';
                                                }

                                                if ($status === 'Staff Approved' || $status === 'Staff Rejected') {
                                                    $buttons[] = '<a class="btn btn-orange" href="edit_receipt.php?id=' . $id . '" title="Edit Request"><i class="fas fa-edit" style="font-size: 1.1rem;"></i></a>';
                                                }

                                                echo implode(' ', $buttons);
                                                ?>
                                            </td>
                                        </tr>
                                <?php
                                    endforeach;
                                } else {
                                    // Handle case where no requests exist
                                    echo "<tr><td colspan='6' class='text-center'>No requests found</td></tr>";
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
        <?php if (!empty($requests)): ?>
            $('#table').DataTable();
        <?php endif; ?>
    });
</script>

</body>
