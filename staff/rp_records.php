<?php
include 'header.php';

$functions = new Functions();

// Fetch the approved requests
$requestsHistory = $functions->fetchAllApprovedRequests();

$staff_email = $_SESSION['email'] ?? '';
?>

<!-- Include DataTables and Bootstrap CSS and JS -->
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
            <h3 class="fw-semibold mb-4 mt-2 text-dark">Approved Request Details</h3>
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
                        <th class="header text-light">Name of Declarant</th>
                        <th class="header text-light">Approved At</th>
                        <th class="header text-light">Payment Status</th>
                        <th class="header text-light">OR Input</th>
                        <th class="header text-light">More Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    if (!empty($requestsHistory)) {
                        foreach ($requestsHistory as $row) :
                            $i++;
                            $id = $row['request_id'];
                            $td_number = $row['td_number'];
                            $pin = $row['pin'];
                            $name = htmlspecialchars($row['name']);
                            $class = htmlspecialchars($row['class']);
                            $location = htmlspecialchars("{$row['barangay']}, {$row['municipality']}, {$row['province']}, {$row['street']}");
                            $area = htmlspecialchars($row['area']);
                            $market_value = htmlspecialchars($row['market_value']);
                            $assessed_value = htmlspecialchars($row['assessed_value']);
                            $basic_tax = htmlspecialchars($row['basic_tax']);
                            $sef = htmlspecialchars($row['sef']);
                            $tax_due = htmlspecialchars($row['tax_due']);
                            $approved_at = htmlspecialchars($row['approved_at']);
                            $payment_status = $row['payment_status'] ?? 'Not yet Paid';
                            $or_number = $row['or_number'] ?? '';
                            $amount = $row['amount'] ?? '';
                    ?>
                            <tr class="text-align-left text-dark">
                                <td class="text-center"><?= $i; ?></td>
                                <td><label><?= $name; ?></label></td>
                                <td><label><?= $approved_at; ?></label></td>
                                <td><label><?= $payment_status; ?></label></td>
                                <td class="text-center">
                                    <form method="post" action="../navigate.php" style="display:inline-block;">
                                        <input type="hidden" name="request_id" value="<?= $id; ?>">
                                        <input type="hidden" name="staff_email" value="<?= htmlspecialchars($staff_email); ?>">

                                        <button type="button" class="btn btn-secondary mb-3" 
                                                onclick="toggleORInputs(this)" 
                                                <?= ($payment_status === 'Paid') ? 'disabled' : ''; ?>>
                                            Show OR Input
                                        </button>

                                        <div class="or-inputs" style="display: none;">
                                            <label for="or_number" class="form-label">OR Number</label>
                                            <input class="form-control" type="text" id="or_number" name="or_number" value="<?= $or_number; ?>" placeholder="2024-11-17">

                                            <label for="amount" class="form-label mt-2">Amount</label>
                                            <input class="form-control mt-2" type="number" id="amount" name="amount" value="<?= $amount; ?>" placeholder="e.g. 50.00">

                                            <button type="submit" name="btn-staff-add-or" class="btn btn-secondary mt-3" title="Add OR" onclick="return confirm('Are you sure you want to add an OR? Please review the details first before proceeding.');">
                                                Add OR
                                            </button>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#detailsModal<?= $i; ?>">
                                        View Details
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="detailsModal<?= $i; ?>" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable">
                                    <div class="modal-content rounded-4 shadow">
                                        <!-- Modal Header -->
                                        <div class="modal-header border-bottom">
                                            <h3 class="text-dark" id="detailsModalLabel">Request Details</h3>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="modal-body p-4">
                                            <div class="row gy-3">
                                                <!-- Card for Name -->
                                                <div class="col-12">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-user-circle fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Name:</strong> <?= $name; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for TD Number -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-check fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>TD Number:</strong> <?= $td_number; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for PIN -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-key fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>PIN:</strong> <?= $pin; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for Class -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-home fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Class:</strong> <?= $class; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for Location -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-map-marker-alt fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Location:</strong> <?= $location; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for Area -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-ruler fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Area (sq):</strong> <?= $area; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for Market Value -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-dollar-sign fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Market Value:</strong> <?= $market_value; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for Assessed Value -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-chart-line fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Assessed Value:</strong> <?= $assessed_value; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for Basic Tax -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-coins fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Basic Tax:</strong> <?= $basic_tax; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for SEF -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-chart-bar fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>SEF:</strong> <?= $sef; ?></p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Card for Tax Due -->
                                                <div class="col-12 col-md-6">
                                                    <div class="card shadow-sm border-0 rounded-3" style="background-color: #f8f9fa; min-height: 120px;">
                                                        <div class="card-body d-flex align-items-center">
                                                            <i class="fas fa-file-invoice-dollar fs-4 me-3 text-dark"></i>
                                                            <p class="mb-0"><strong>Tax Due:</strong> <?= $tax_due; ?></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- End Modal Body -->
                                    </div>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    } else {
                        echo "<tr><td colspan='15' class='text-center text-dark'>No transactions found</td></tr>";
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
        $('#table').DataTable({
        });
    });
</script>

<script>
function toggleORInputs(button) {
    const orInputs = button.nextElementSibling;
    if (orInputs.style.display === "none") {
        orInputs.style.display = "block";
        button.textContent = "Hide OR Input";
    } else {
        orInputs.style.display = "none";
        button.textContent = "Show OR Input";
    }
}
</script>

</body>
</html>

