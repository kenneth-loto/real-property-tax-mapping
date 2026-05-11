<?php
include 'header.php';

$functions = new Functions();

// Fetch all applicants with their approval/rejection status
$applicants = $functions->getAllApplicants();

// Custom sorting: Pending at the top, then Approved, then Rejected
usort($applicants, function ($a, $b) {
    $order = ['pending' => 0, 'approved' => 1, 'rejected' => 2];

    return $order[$a['account_status']] <=> $order[$b['account_status']];
});
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<link rel="stylesheet" href="../assets/css/requests.css">
<link rel="stylesheet" href="../assets/css/table.css">

<div class="container-fluid text-dark">
    <div class="card">
        <div class="card-body">
            <h3 class="fw-semibold mb-4 mt-2 text-dark">Applicants</h3>
            <?php
            $msg = Session::get("msg");
            if (isset($msg)) {
                echo $msg;
                Session::set("msg", NULL);
            }
            ?>
            <table class="table table-bordered table-hover" id="table">
                <thead>
                    <tr>
                        <th class="header text-light">ID</th>
                        <th class="header text-light">Name</th>
                        <th class="header text-light">Date Created</th>
                        <th class="header text-light">Status</th>
                        <th class="header text-light">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0; // Counter for formal ID starting from 1
                    if (!empty($applicants)) {
                        foreach ($applicants as $row) :
                            $i++; // Increment the counter for each transaction
                            $id = $row['id'];
                            $first_name = $row['first_name'];
                            $middle_name = $row['middle_name'];
                            $last_name = $row['last_name'];
                            $suffix = $row['suffix'];
                            $created_at = $row['created_at'];
                            $account_status = $row['account_status']; // Get the account status
                    ?>
                            <tr class="text-align-left text-dark">
                                <td class="text-center"><?= $i; ?></td> <!-- Display formal ID -->
                                <td>
                                    <label><?= htmlspecialchars($first_name); ?> <?= htmlspecialchars($middle_name); ?> <?= htmlspecialchars($last_name); ?> <?= htmlspecialchars($suffix); ?></label>
                                </td>
                                <td>
                                    <?= date('F j, Y, g:i a', strtotime($created_at)); ?>
                                </td>
                                <td class="text-center">
                                    <!-- Display the colored status bar -->
                                    <?php
                                    if ($account_status == 'pending') {
                                        echo '<div class="progress" style="height: 40px;">
                                                <div class="progress-bar bg-warning" style="width: 100%;">Pending</div>
                                            </div>';
                                    } elseif ($account_status == 'approved') {
                                        echo '<div class="progress" style="height: 40px;">
                                                <div class="progress-bar bg-success" style="width: 100%;">Approved</div>
                                            </div>';
                                    } elseif ($account_status == 'rejected') {
                                        echo '<div class="progress" style="height: 40px;">
                                                <div class="progress-bar bg-danger" style="width: 100%;">Rejected</div>
                                            </div>';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <!-- View Button -->
                                    <?php if ($account_status == 'approved' || $account_status == 'rejected'): ?>
                                        <a href="view_applicants.php?id=<?= $id; ?>" class="btn btn-secondary" title="View Account" aria-label="View">
                                            <i class="fas fa-eye"></i> <!-- View icon -->
                                        </a>

                                        <!-- Delete Button for Approved or Rejected Applicants -->
                                        <form action="../navigate.php" method="post" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this account?');">
                                            <input type="hidden" name="id" value="<?= $id; ?>">
                                            <button class="btn btn-danger" name="btn-delete-applicant" type="submit" title="Delete Account">
                                                <i class="fa fa-trash"></i> <!-- Trash icon -->
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <!-- Review Button for Pending Applicants -->
                                        <a href="view_applicants_data.php?id=<?= $id; ?>" class="btn btn-warning" title="Review Account" aria-label="Review">
                                            <i class="fas fa-file-signature"></i> <!-- Review icon -->
                                        </a>
                                    <?php endif; ?>
                                </td>
                    <?php
                        endforeach;
                    } else {
                        // Handle case where no transactions exist
                        echo "<tr><td colspan='4' class='text-center text-dark'>No transactions found</td></tr>";
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
