<?php
include 'header.php';
$id = $_GET['id'];
$request = $function->getARequest($id);
$approved_request = $function->getStaffApprovedRequests($id);
$staff_rejected_request = $function->getStaffRejectedRequests($id);
$admin_rejected_request = $function->getAdminRejectedRequests($id); // Fetch the admin-rejected request details
?>

<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="d-flex justify-content-start mt-3">
                    <a class="btn btn-primary" role="button" href="view_request.php?id=<?= $id; ?>">Go Back To Requests Details</a>
                </div>

                <div class="col-md-6 mt-4">
                    <?php if ($request->status === 'Staff Rejected' || $request->status === 'Admin Rejected'): ?>
                        <h3 class="text-center fw-bold text-danger mb-4">Rejected Request</h3>
                        <div class="border p-3 bg-light" style="border: 1px solid #ccc; border-radius: 8px;">
                            <form>
                                <?php if ($request->status === 'Staff Rejected'): ?>
                                    <!-- Staff Rejection Details -->
                                    <div class="mb-4">
                                        <label for="rejection_category" class="form-label"><strong>Rejection Category</strong></label>
                                        <p class="form-control text-dark" id="rejection_category" name="rejection_category" readonly><?= htmlspecialchars($staff_rejected_request['rejection_category'] ?? ''); ?></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="feedback" class="form-label"><strong>Feedback to Applicant</strong></label>
                                        <p class="form-control text-dark" id="feedback" name="feedback" readonly><?= htmlspecialchars($staff_rejected_request['feedback'] ?? ''); ?></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="document_status" class="form-label"><strong>Documents Reviewed</strong></label>
                                        <p class="form-control text-dark" id="document_status" name="document_status" readonly><?= htmlspecialchars($staff_rejected_request['document_status'] ?? ''); ?></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="staff_email" class="form-label"><strong>Staff's Email</strong></label>
                                        <p class="form-control text-dark" id="staff_email" name="staff_email" readonly><?= htmlspecialchars($staff_rejected_request['staff_email'] ?? ''); ?></p>
                                    </div>
                                <?php elseif ($request->status === 'Admin Rejected'): ?>
                                    <!-- Admin Rejection Details -->
                                    <div class="mb-4">
                                        <label for="rejection_category" class="form-label"><strong>Rejection Category</strong></label>
                                        <p class="form-control text-dark" id="rejection_category" name="rejection_category" readonly><?= htmlspecialchars($admin_rejected_request['rejection_category'] ?? ''); ?></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="feedback" class="form-label"><strong>Feedback to Applicant</strong></label>
                                        <p class="form-control text-dark" id="feedback" name="feedback" readonly><?= htmlspecialchars($admin_rejected_request['feedback'] ?? ''); ?></p>
                                    </div>
                                    <div class="mb-4">
                                        <label for="admin_email" class="form-label"><strong>Admin's Email</strong></label>
                                        <p class="form-control text-dark" id="admin_email" name="admin_email" readonly><?= htmlspecialchars($admin_rejected_request['admin_email'] ?? ''); ?></p>
                                    </div>
                                <?php endif; ?>
                            </form>
                        </div>

                        <div class="text-center mt-4">
                            <i class="fas fa-times-circle mb-3" style="font-size: 75px; color: red;"></i>
                            <h5 class="fw-bold text-dark">This request has been rejected.</h5>
                        </div>
                    <?php else: ?>
                        <!-- Approved Request Section (unchanged) -->
                        <h3 class="text-center fw-bold mb-4 text-dark">Approved Request</h3>
                        
                        <div class="border p-3 bg-light" style="border: 1px solid #ccc; border-radius: 8px;">
                            <form action="../navigate.php" method="POST">
                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($id); ?>">

                                <!-- Pre-fill the form fields if data exists -->
                                <div class="mb-4">
                                    <label for="market_value" class="form-label"><strong>Market Value</strong></label>
                                    <input type="number" class="form-control text-dark" id="market_value" name="market_value" value="<?= $approved_request ? htmlspecialchars($approved_request['market_value']) : ''; ?>" readonly>
                                </div>
                                <div class="mb-4">
                                    <label for="assessment_rate" class="form-label"><strong>Assessment rate %</label>
                                    <input type="number" class="form-control text-dark" id="assessment_rate" name="assessment_rate" value="<?= $approved_request ? htmlspecialchars($approved_request['assessment_rate']) : ''; ?>" readonly>
                                </div>
                                <div class="mb-4">
                                    <label for="basic_tax" class="form-label"><strong>Basic Tax</strong></label>
                                    <input type="number" class="form-control text-dark" id="basic_tax" name="basic_tax" value="<?= $approved_request ? htmlspecialchars($approved_request['basic_tax']) : ''; ?>" readonly>
                                </div>
                                <div class="mb-4">
                                    <label for="assessed_value" class="form-label"><strong>Assessed Value</strong></label>
                                    <input type="number" class="form-control text-dark" id="assessed_value" name="assessed_value" value="<?= $approved_request ? htmlspecialchars($approved_request['assessed_value']) : ''; ?>" readonly>
                                </div>
                                <div class="mb-4">
                                    <label for="sef" class="form-label"><strong>SEF</strong></label>
                                    <input type="number" class="form-control text-dark" id="sef" name="sef" value="<?= $approved_request ? htmlspecialchars($approved_request['sef']) : ''; ?>" readonly>
                                </div>
                                <div class="mb-4">
                                    <label for="tax_due" class="form-label"><strong>Tax Due</strong></label>
                                    <input type="number" class="form-control text-dark" id="tax_due" name="tax_due" value="<?= $approved_request ? htmlspecialchars($approved_request['tax_due']) : ''; ?>" readonly>
                                </div>
                            </form>
                        </div>

                        <div class="text-center mt-4">
                            <i class="fas fa-check-circle mb-3" style="font-size: 75px; color: green;"></i>
                            <h5 class="fw-bold text-dark">This request has been approved.</h5>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
