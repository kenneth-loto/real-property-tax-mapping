<?php
include 'header.php';

// Get the request ID from the URL
$id = $_GET['id'] ?? null; // Use null coalescing operator for safety
$request = $function->getAllStaffApprovedRequestsById($id); // Fetch the approved request data based on the ID

$treasurer_email = $_SESSION['email'] ?? '';

// Check if the request data was retrieved successfully
if (!$request) {
    echo "<div class='alert alert-danger'>No request found for the provided ID.</div>";
    exit; // Exit if no request is found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment of Request</title>
    <!-- Add any additional CSS or JavaScript links here -->
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card w-100 shadow-lg">
            <div class="card-body">
                <div class="row justify-content-center">
                    <!-- Request Details Section -->
                    <div class="d-flex justify-content-start mt-3">
                        <a class="btn btn-primary" role="button" href="transaction_details.php?id=<?= htmlspecialchars($id); ?>">Go Back To Transactions</a>
                    </div>

                    <!-- Payment Submission Form Section -->
                    <div class="col-md-6 mt-4">
                        <h3 class="text-center fw-bold mb-4 text-dark">Official Receipt</h3>
                        
                        <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                            <form action="../navigate.php" method="POST">
                                <!-- Hidden input to pass the request ID -->

                                <input type="hidden" name="request_id" value="<?= htmlspecialchars($id); ?>">
                                <input type="hidden" name="treasurer_email" value="<?= htmlspecialchars($treasurer_email); ?>">


                                <div class="mb-4">
                                    <label for="or_number" class="form-label"><strong>OR Number <span class="text-danger">*</span></strong></label>
                                    <input type="text" class="form-control text-dark" id="or_number" name="or_number" placeholder="e.g. 00-001" required step="0.01" min="0">
                                </div>

                                <!-- Amount Paid Input -->
                                <div class="mb-4">
                                    <label for="amount_paid" class="form-label"><strong>Amount Paid <span class="text-danger">*</span></strong></label>
                                    <input type="number" class="form-control text-dark" id="amount_paid" name="amount_paid" placeholder="e.g. 99.00" required step="0.01" min="0">
                                </div>
                                
                                <!-- Payment Date Input -->
                                <div class="mb-4">
                                    <label for="payment_date" class="form-label"><strong>Date of Payment <span class="text-danger">*</span></strong></label>
                                    <input type="date" class="form-control text-dark" id="payment_date" name="payment_date" required>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary" name="btn-treasurer-paid-request" 
                                        onclick="return confirm('Are you sure you want to submit? Please review the details before proceeding.');" 
                                        title="Submit">Submit Payment</button>
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
