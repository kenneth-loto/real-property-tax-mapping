<?php
include 'header.php';
$id = $_GET['id'];
$request = $function->getARequest($id);
$approved_requests = $function->getAllApprovedRequests($id);
$rejected_requests = $function->getAllRejectedRequests($id);
?>

<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="row justify-content-center">
                <!-- Centered Back to Requests button inside the first 2 columns -->
                <div class="d-flex justify-content-between mt-3">
                    <a class="btn btn-primary" role="button" href="review_request.php?id=<?= htmlspecialchars($id); ?>">Go Back To Requests</a>
                    <button id="toggleButton" class="btn btn-primary" onclick="toggleForms()">Form To Reject Request</button>
                </div>
                <!-- Right Section: Approval and Rejection Forms -->
                <div class="col-md-6 mt-4"> 
                    <h3 class="text-center fw-bold mb-4 text-dark">MANAGE REQUEST</h3>

                    <!-- Approval Form -->
                    <div id="approveForm" class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                        <h3 class="text-center text-dark mb-4">Approve Request Form</h3>
                        <form action="../navigate.php" method="POST">

                            <div class="mb-4">
                                <label for="market_value" class="form-label"><strong>Market Value <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="market_value" value="<?= htmlspecialchars($approved_requests->market_value); ?>" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="assessed_value" class="form-label"><strong>Assessed Value <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="assessed_value" name="assessed_value" placeholder="e.g. 999.00" oninput="computeTax()" required>
                            </div>
                            <div class="mb-4">
                                <label for="basic_tax" class="form-label"><strong>Basic Tax (1%) <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="basic_tax" name="basic_tax" placeholder="Auto generated" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="sef" class="form-label"><strong>SEF (1%) <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="sef" name="sef" placeholder="Auto generated" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="tax_due" class="form-label"><strong>Total Tax Due <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="tax_due" name="tax_due" placeholder="Auto generated" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="staff_email" class="form-label"><strong>Staff's Email <span class="text-danger">*</span></strong></label>
                                <input type="email" class="form-control text-dark" id="staff_email" name="staff_email" placeholder="e.g. example@gmail.com">
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-primary" name="btn-staff-approve-request" value="approve" onclick="return confirm('Are you sure you want to approve this request?');" 
                                title="Approve Request">Approve Request</button>
                            </div>
                        </form>
                    </div>

                    <!-- Rejection Form -->
                    <div id="rejectForm" class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px; display: none;">
                        <h3 class="text-center text-dark mb-4">Reject Request Form</h3>
                        <form action="../navigate.php" method="POST">

                            <div class="mb-4">
                                <label for="rejection_category" class="form-label"><strong>Rejection Category <span class="text-danger">*</span></strong></label>
                                <select class="form-control text-dark" id="rejection_category" name="rejection_category" required>
                                    <option value="Incomplete Documents">Incomplete Documents</option>
                                    <option value="Invalid Data">Invalid Data</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Feedback to Applicant -->
                            <div class="mb-4">
                                <label for="feedback" class="form-label"><strong>Feedback to Applicant <span class="text-danger">*</span></strong></label>
                                <textarea class="form-control text-dark" id="feedback" name="feedback" rows="3" placeholder="Provide specific details for rejection..." required></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="document_status" class="form-label"><strong>Documents Reviewed</strong></label>
                                <select class="form-control text-dark" id="document_status" name="document_status" required>
                                    <option value="Valid">All documents are valid</option>
                                    <option value="Invalid">Some documents are invalid</option>
                                    <option value="Missing">Some documents are missing</option>
                                </select>
                            </div>

                            <!-- Staff's Email -->
                            <div class="mb-4">
                                <label for="staff_email" class="form-label"><strong>Staff's Email <span class="text-danger">*</span></strong></label>
                                <input type="text" class="form-control text-dark" id="staff_email" name="staff_email" placeholder="e.g. example@gmail.com" required>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-danger" name="btn-staff-reject-request" value="reject" onclick="return confirm('Are you sure you want to reject this request?');" 
                                title="Reject Request">Reject Request</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function computeTax() {
    const assessedValue = parseFloat(document.getElementById('assessed_value').value) || 0;

    document.getElementById('basic_tax').value = (assessedValue * 0.01).toFixed(2);
    document.getElementById('sef').value = (assessedValue * 0.01).toFixed(2);
    document.getElementById('tax_due').value = (parseFloat(document.getElementById('basic_tax').value) + parseFloat(document.getElementById('sef').value)).toFixed(2);
}

function toggleForms() {
    const approveForm = document.getElementById('approveForm');
    const rejectForm = document.getElementById('rejectForm');
    const toggleButton = document.getElementById('toggleButton');

    if (approveForm.style.display === "none") {
        approveForm.style.display = "block";
        rejectForm.style.display = "none";
        toggleButton.innerText = "Form To Reject Request";
    } else {
        approveForm.style.display = "none";
        rejectForm.style.display = "block";
        toggleButton.innerText = "Form To Approve Request";
    }
}
</script>
</body>
</html>
