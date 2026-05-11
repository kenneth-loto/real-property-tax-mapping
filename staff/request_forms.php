<?php
include 'header.php';

// Ensure the ID is provided and is a valid number
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$request = $function->getARequest($id);
$staff_email = $_SESSION['email'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Request</title>
    <!-- Include your CSS files here -->
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="d-flex justify-content-between mt-3">
                    <a class="btn btn-secondary" role="button" href="review_request.php?id=<?= htmlspecialchars($id); ?>">Back To Request Details</a>
                    <button id="toggleButton" class="btn btn-secondary" onclick="toggleForms()">Reject Request Form</button>
                </div>
                <div class="col-md-6 mt-4"> 
                    <h3 class="text-center fw-bold mb-4 text-dark">Manage Request</h3>

                    <!-- Approval Form -->
                    <div id="approveForm" class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef;">
                        <h3 class="text-center text-dark mb-4">Approve Request Form</h3>
                        <form action="../navigate.php" method="POST">
                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($id); ?>">
                            <input type="hidden" name="staff_email" value="<?= htmlspecialchars($staff_email); ?>">

                            <div class="mb-4">
                                <label for="market_value" class="form-label"><strong>Market Value <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="market_value" name="market_value" oninput="computeTax()" required>
                            </div>
                            <div class="mb-4">
                                <label for="assessment_rate" class="form-label"><strong>Assessment Rate % <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="assessment_rate" name="assessment_rate" oninput="computeTax()" required>
                            </div>
                            <div class="mb-4">
                                <label for="assessed_value" class="form-label"><strong>Assessed Value <span class="text-danger">*</span></strong></label>
                                <input type="number" class="form-control text-dark" id="assessed_value" name="assessed_value" placeholder="Auto generated" readonly>
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
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary" name="btn-staff-approve-request" value="approve" onclick="return confirm('Are you sure you want to approve this request?');" 
                                title="Approve Request">Approve Request</button>
                            </div>
                        </form>
                    </div>

                    <!-- Rejection Form -->
                    <div id="rejectForm" class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef; display: none;">
                        <h3 class="text-center text-dark mb-4">Reject Request Form</h3>
                        <form action="../navigate.php" method="POST">
                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($id); ?>">
                            <input type="hidden" name="staff_email" value="<?= htmlspecialchars($staff_email); ?>">

                            <div class="mb-4">
                                <label for="rejection_category" class="form-label"><strong>Rejection Category <span class="text-danger">*</span></strong></label>
                                <select class="form-control text-dark" id="rejection_category" name="rejection_category" required>
                                    <option value="Incomplete Documents">Incomplete Documents</option>
                                    <option value="Invalid Data">Invalid Data</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

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

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary" name="btn-staff-reject-request" value="reject" onclick="return confirm('Are you sure you want to reject this request?');" 
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
    const marketValue = parseFloat(document.getElementById('market_value').value) || 0;
    const assessmentRate = parseFloat(document.getElementById('assessment_rate').value) / 100 || 0; // Convert percentage

    const assessedValue = marketValue * assessmentRate;
    const basicTax = assessedValue * 0.01; // 1% of assessed value
    const sef = assessedValue * 0.01; // 1% SEF

    document.getElementById('assessed_value').value = assessedValue.toFixed(2);
    document.getElementById('basic_tax').value = basicTax.toFixed(2);
    document.getElementById('sef').value = sef.toFixed(2);
    document.getElementById('tax_due').value = (basicTax + sef).toFixed(2);
}

function toggleForms() {
    const approveForm = document.getElementById('approveForm');
    const rejectForm = document.getElementById('rejectForm');
    const isApproveVisible = approveForm.style.display !== 'none';

    approveForm.style.display = isApproveVisible ? 'none' : 'block';
    rejectForm.style.display = isApproveVisible ? 'block' : 'none';
    document.getElementById('toggleButton').innerText = isApproveVisible ? 'Approve Request Form' : 'Reject Request Form';
}
</script>

</body>
</html>
