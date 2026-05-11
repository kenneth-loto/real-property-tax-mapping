<?php
include 'header.php';

// Get the request ID from the URL
$id = $_GET['id'] ?? null; // Use null coalescing operator for safety
$request = $function->getAllStaffApprovedRequestsById($id);
$paidRequest = $function->getAllPaidRequests($id); // Fetch the approved request data based on the ID

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
                                <input type="hidden" name="name" value="<?= htmlspecialchars($request['name']); ?>">
                                <input type="hidden" name="email" value="<?= htmlspecialchars($request['email']); ?>">
                                <input type="hidden" name="phone_number" value="<?= htmlspecialchars($request['phone_number']); ?>">
                                <input type="hidden" name="staff_email" value="<?= htmlspecialchars($request['staff_email']); ?>">
                                <input type="hidden" name="td_number" value="<?= htmlspecialchars($request['td_number']); ?>">
                                <input type="hidden" name="pin" value="<?= htmlspecialchars($request['pin']); ?>">
                                <input type="hidden" name="province" value="<?= htmlspecialchars($request['province']); ?>">
                                <input type="hidden" name="municipality" value="<?= htmlspecialchars($request['municipality']); ?>">
                                <input type="hidden" name="barangay" value="<?= htmlspecialchars($request['barangay']); ?>">
                                <input type="hidden" name="street" value="<?= htmlspecialchars($request['street']); ?>">
                                <input type="hidden" name="lot_number" value="<?= htmlspecialchars($request['lot_number']); ?>">
                                <input type="hidden" name="area" value="<?= htmlspecialchars($request['area']); ?>">
                                <input type="hidden" name="market_value" value="<?= htmlspecialchars($request['market_value']); ?>">
                                <input type="hidden" name="class" value="<?= htmlspecialchars($request['class']); ?>">
                                <input type="hidden" name="documents" value="<?= htmlspecialchars($request['documents']); ?>"> <!-- Make sure this key is correct -->
                                <input type="hidden" name="assessed_value" value="<?= htmlspecialchars($request['assessed_value']); ?>">
                                <input type="hidden" name="basic_tax" value="<?= htmlspecialchars($request['basic_tax']); ?>">
                                <input type="hidden" name="sef" value="<?= htmlspecialchars($request['sef']); ?>">
                                <input type="hidden" name="tax_due" value="<?= htmlspecialchars($request['tax_due']); ?>">


                                <!-- Amount Paid Input -->
                                <div class="mb-4">
                                    <label for="amount_paid" class="form-label"><strong>Amount Paid <span class="text-danger">*</span></strong></label>
                                    <input type="number" class="form-control text-dark" id="amount_paid" name="amount_paid" value="<?= isset($paidRequest['payment_amount']) ? htmlspecialchars($paidRequest['payment_amount']) : 'N/A'; ?>" placeholder="e.g. 99.00" required step="0.01" min="0">
                                </div>
                                
                                <!-- Payment Date Input -->
                                <div class="mb-4">
                                    <label for="payment_date" class="form-label"><strong>Date of Payment <span class="text-danger">*</span></strong></label>
                                    <input type="date" class="form-control text-dark" id="payment_date" name="payment_date" 
                                        value="<?= isset($paidRequest['paid_at']) && strtotime($paidRequest['paid_at']) ? date('Y-m-d', strtotime($paidRequest['paid_at'])) : ''; ?>" required>
                                </div>

                                <!-- Treasurer Email Input -->
                                <div class="mb-4">
                                    <label for="treasurer_email" class="form-label"><strong>Treasurer's Email <span class="text-danger">*</span></strong></label>
                                    <input type="email" class="form-control text-dark" id="treasurer_email" name="treasurer_email" value="<?= isset($paidRequest['treasurer_email']) ? htmlspecialchars($paidRequest['treasurer_email']) : 'N/A'; ?>"placeholder="e.g. example@gmail.com" required>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary" name="btn-update-treasurer-paid-request" 
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
