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
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="../assets/css/requests.css">
<link rel="stylesheet" href="../assets/css/table.css">

<div class="container-fluid text-dark">
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
            <table class="table table-bordered table-hover" id="table">
                <thead class="text-dark">
                    <tr>
                        <th class="text-light header">ID</th>
                        <th class="text-light header">Pending Requests</th>
                        <th class="text-light header">Date Requested</th>
                        <th class="text-light header">Status</th>
                        <th class="text-light header">Action</th>
                    </tr>
                </thead>
                <tbody class="text-dark">
                    <?php
                    $i = 1;
                    $requests = $function->applicantGetRequests(); // Fetch requests
                    
                    // Separate requests by status category
                    $pending = [];
                    $staffApproved = [];
                    $adminApproved = [];
                    $adminRejected = [];
                    $staffRejected = [];

                    if ($requests) {
                        foreach ($requests as $request) {
                            $id = $request['id'];

                            // Fetch request statuses for the current request
                            $status = $function->fetchRequestStatus($id);

                            // Sort requests based on the status
                            if (empty($status['staff_approved_requests']) && empty($status['staff_rejected_requests']) && empty($status['admin_approved_requests']) &&
                                empty($status['admin_rejected_requests'])) {
                                $pending[] = $request;
                            } elseif (!empty($status['staff_approved_requests'])) {
                                $staffApproved[] = $request;
                            } elseif (!empty($status['admin_approved_requests'])) {
                                $adminApproved[] = $request;
                            } elseif (!empty($status['admin_rejected_requests'])) {
                                $adminRejected[] = $request;
                            } elseif (!empty($status['staff_rejected_requests'])) {
                                $staffRejected[] = $request;
                            }
                        }
                    }

                    // Function to display each request row with dynamic buttons based on status
                    function displayRequestRows($requests, $i, $function) {
                        foreach ($requests as $request) {
                            $id = $request['id'];
                            $created_at = $request['created_at'];
                            $pin = $request['pin'];
                            $progressBars = $function->getProgressBarStatus($id); // Get progress bar statuses
                            
                            // Determine button display based on status
                            $buttons = '';
                            $status = $function->fetchRequestStatus($id); // Ensure status data is available

                            // Pending - Edit, View, Delete buttons
                            if (empty($status['staff_approved_requests']) && empty($status['staff_rejected_requests']) && empty($status['admin_approved_requests']) &&
                                empty($status['admin_rejected_requests'])) {
                                $buttons .= "<a class='btn btn-secondary' href='view_request.php?id={$id}' title='View'><i class='fa fa-eye' style='font-size: 1.1rem;'></i></a>
                                            <a class='btn btn-warning' href='edit_request.php?id={$id}' title='Edit'><i class='fa fa-edit' style='font-size: 1.1rem;'></i></a>
                                            <form action='../navigate.php' method='post' style='display: inline;'>
                                                <input type='hidden' name='id' value='{$request['id']}'>
                                                <button class='btn btn-danger' name='btn-delete-request' type='submit' onclick=\"return confirm('Are you sure you want to delete this request?');\" title='Delete Request'>
                                                    <i class='fa fa-trash' style='font-size: 1.1rem;'></i>
                                                </button>
                                            </form>";
                            
                            // Fully approved by all - show View only
                            } elseif (!empty($status['admin_rejected_requests']) || !empty($status['staff_rejected_requests'])) {
                                // Buttons for Rejected Requests
                                $buttons .= "<a class='btn btn-secondary' href='view_request.php?id={$id}' title='View'><i class='fa fa-eye' style='font-size: 1.1rem;'></i></a>
                                            <a class='btn btn-warning' href='edit_request.php?id={$id}' title='Edit'><i class='fa fa-edit' style='font-size: 1.1rem;'></i></a>";
                            } else {
                                // Other statuses
                                $buttons .= "<a class='btn btn-secondary' href='view_request.php?id={$id}' title='View'><i class='fa fa-eye' style='font-size: 1.1rem;'></i></a>";
                            }
                            
                            // Render row
                            echo "<tr class='text-align-left'>
                                    <td styly='text-align: center !important;'>{$i}</td>
                                    <td>{$pin}</td>
                                    <td>" . date('F j, Y, g:i a', strtotime($created_at)) . "</td>
                                    <td>
                                        <div class='progress' style='display: flex; height: 50px;'>
                                            <div class='progress-bar {$progressBars['bar1']['color']}' role='progressbar' style='color: black; width: 28%; height: 50px; border-right: 2px solid #fff; padding: 10px 0;'>{$progressBars['bar1']['text']}</div>
                                            <div class='progress-bar {$progressBars['bar2']['color']}' role='progressbar' style='color: black; width: 36%; height: 50px; border-right: 2px solid #fff; padding: 10px 0;'>{$progressBars['bar2']['text']}</div>
                                            <div class='progress-bar {$progressBars['bar4']['color']}' role='progressbar' style='color: black; width: 36%; height: 50px;'>{$progressBars['bar4']['text']}</div>
                                        </div>
                                    </td>
                                    <td style='text-align: center !important;'>
                                        $buttons
                                    </td>
                                </tr>";
                            $i++;
                        }
                        return $i;
                    }

                    // Display sorted requests
                    $i = displayRequestRows($pending, $i, $function);
                    $i = displayRequestRows($staffApproved, $i, $function);
                    $i = displayRequestRows($adminApproved, $i, $function);
                    $i = displayRequestRows($adminRejected, $i, $function);
                    displayRequestRows($staffRejected, $i, $function);

                    // Display message if no requests are found
                    if (empty($requests)) {
                        echo "<tr><td colspan='5' class='text-center text-dark'>No requests found</td></tr>";
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
