<?php
include 'header.php';

// Check if 'id' exists in the URL
if (!isset($_GET['id'])) {
    echo "Error: Request ID is missing.";
    exit;
}

$id = $_GET['id'];

// Fetch the reviewed request details using the same ID
$requests = $function->getAllStaffApprovedRequestsForAdmin($id);

$admin_email = $_SESSION['email'] ?? '';
?>

<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="d-flex justify-content-between mt-3">
                    <a class="btn btn-primary" href="requests.php">Go back To Requests</a>
                </div>

                <div class="col-md-6 mt-4 mb-4"> <!-- Initially hidden -->
                    <h3 class="text-center fw-bold mb-4 text-dark">Reject Request</h3>
                    <div class="border p-3 bg-light" style="border: 1px solid #ccc; border-radius: 8px;">

                        <!-- Rejection Form -->
                        <form action="../navigate.php" method="POST">

                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($id); ?>">
                                <input type="hidden" name="admin_email" value="<?= htmlspecialchars($admin_email); ?>">

                            <!-- Rejection Category -->
                            <div class="mb-4">
                                <label for="rejection_category" class="form-label"><strong>Rejection Category <span class="text-danger">*</span></strong></label>
                                <select class="form-control text-dark" id="rejection_category" name="rejection_category" required>
                                    <option value="">Select Here</option>
                                    <option value="Incomplete Documents">Incomplete Documents</option>
                                    <option value="Invalid Data">Invalid Data</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <!-- Feedback to Applicant -->
                            <div class="mb-4">
                                <label for="feedback" class="form-label"><strong>Feedback to Applicant</strong></label>
                                <textarea class="form-control text-dark" id="feedback" name="feedback" rows="3" placeholder="Provide specific details for rejection..." required></textarea>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-danger" name="btn-admin-rejected-request" onclick="return confirm('Are you sure you want to reject this request?');" 
                                title="Reject Request">Reject Request</button>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>
</div>
</body>
</html>
