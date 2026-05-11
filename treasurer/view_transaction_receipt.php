<?php
include 'header.php';
$id = $_GET['id']; // Get the request ID from the URL
$paidRequest = $function->getAllPaidRequests($id); // Fetch the payment details for the request

?>

<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="row justify-content-center">
                <!-- Request Details Section -->
                <div class="d-flex justify-content-start mt-3">
                    <a class="btn btn-primary" role="button" href="view_transaction_details.php?id=<?= htmlspecialchars($id); ?>">Go Back To Transaction Details</a>
                </div>

                <!-- Payment Details Section -->
                <div class="col-md-6 mt-4">
                    <h3 class="text-center fw-bold mb-4 text-dark">Official Receipt</h3>
                    
                    <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                        <form>
                            <!-- Hidden input to pass the request ID -->
                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($id); ?>">

                            <!-- Amount Paid Input -->
                            <div class="mb-4">
                                <label for="payment_amount" class="form-label"><strong>Amount Paid</strong></label>
                                <input type="text" class="form-control text-dark" value="<?= isset($paidRequest['payment_amount']) ? htmlspecialchars($paidRequest['payment_amount']) : 'N/A'; ?>" readonly>
                            </div>
                            
                            <!-- Payment Date Input -->
                            <div class="mb-4">
                                <label for="payment_date" class="form-label"><strong>Date of Payment</strong></label>
                                <input type="text" class="form-control text-dark" value="<?= isset($paidRequest['payment_date']) ? date('F j, Y, g:i a', strtotime($paidRequest['payment_date'])) : 'N/A'; ?>" readonly>
                            </div>

                            <!-- Treasurer Email Input -->
                            <div class="mb-4">
                                <label for="treasurer_email" class="form-label"><strong>Treasurer Email</strong></label>
                                <input type="text" class="form-control text-dark" value="<?= isset($paidRequest['treasurer_email']) ? htmlspecialchars($paidRequest['treasurer_email']) : 'N/A'; ?>" readonly>
                            </div>

                            <!-- Approved Icon -->
                            <div class="text-center mt-4">
                                <i class="fas fa-check-circle mb-3" style="font-size: 75px; color: green;"></i>
                                <h5 class="fw-bold text-dark">This request has been paid.</h5>
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
