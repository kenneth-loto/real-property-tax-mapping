<?php
include 'header.php';

// Ensure the ID is provided and is a valid number
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$admin_email = $_SESSION['email'] ?? '';
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
                    <a class="btn btn-secondary" role="button" href="view_applicants_data.php?id=<?= $id; ?>">Go To Applicant's Details</a>
                    <button id="toggleButton" class="btn btn-secondary" onclick="toggleForms()">Reject Request Form</button>
                </div>
                <div class="col-md-6 mt-4"> 
                    <h3 class="text-center fw-bold mb-4 text-dark">Validation</h3>

                    <!-- Approval Form -->
                    <div id="approveForm" class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef;">
                        <h3 class="text-center text-dark mb-4">Approve Request Form</h3>
                        <form action="../navigate.php" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id); ?>">
                            <input type="hidden" name="admin_email" value="<?= htmlspecialchars($admin_email); ?>">

                            <div class="mb-4">
                                <label for="feedback" class="form-label"><strong>Feedback <span class="text-danger">*</span></strong></label>
                                <textarea type="text" class="form-control text-dark" id="feedback" name="feedback" required></textarea>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary" name="btn-admin-approve-applicant" value="approve" onclick="return confirm('Are you sure you want to approve this request?');" 
                                title="Approve Request">Approve Request</button>
                            </div>
                        </form>
                    </div>

                    <!-- Rejection Form -->
                    <div id="rejectForm" class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef; display: none;">
                        <h3 class="text-center text-dark mb-4">Reject Request Form</h3>
                        <form action="../navigate.php" method="POST">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id); ?>">
                            <input type="hidden" name="admin_email" value="<?= htmlspecialchars($admin_email); ?>">

                            <div class="mb-4">
                                <label for="rejection_category" class="form-label"><strong>Rejection Reason <span class="text-danger">*</span></strong></label>
                                <select class="form-control text-dark" id="rejection_category" name="rejection_category" required>
                                    <option value="Invalid Data">Invalid Data</option>
                                    <option value="Blurred Image">Blurred ID Image</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="feedback" class="form-label"><strong>Feedback to Applicant <span class="text-danger">*</span></strong></label>
                                <textarea class="form-control text-dark" id="feedback" name="feedback" rows="3" placeholder="Provide specific details for rejection..." required></textarea>
                                <small class="form-text text-muted">Specify if the applicant's details are lacking or invalid.</small>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-secondary" name="btn-admin-reject-applicant" value="reject" onclick="return confirm('Are you sure you want to reject this request?');" 
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
