<?php
include 'header.php';

// Check if 'id' exists in the URL
if (!isset($_GET['id'])) {
    echo "Error: Request ID is missing.";
    exit;
}

$id = $_GET['id'];

$paid_requests = $function->getAllPaidRequests($id);

?>

<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="row">
                <div class="d-flex justify-content-between mt-3">
                    <a class="btn btn-primary" href="request_receipt.php?id=<?= htmlspecialchars($id); ?>">Go back Request Receipt</a>
                    <a class="btn btn-primary" href="approve_request_receipt.php?id=<?= htmlspecialchars($id); ?>">Approve Request</a>
                </div>
                <div class="d-flex justify-content-center align-items-center mt-3 mb-2">
                    <h2 class="text-dark">Payment Receipt</h2>
                </div> 
                <div class="col-md-8">
                    <h4 class="card-title text-center fw-bold mt-4 mb-4 text-dark">Payment of Request</h4>
                    
                    <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                        <div class="row text-dark">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Paid At</label> 
                                <input type="text" class="form-control" value="<?= isset($paid_requests['paid_at']) ? date('F j, Y, g:i a', strtotime($paid_requests['paid_at'])) : 'N/A'; ?>" readonly>
                            </div>
                            <!-- Display Requestor Name -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Name</label> 
                                <input type="text" class="form-control" value="<?= isset($paid_requests['first_name'], $paid_requests['last_name']) ? htmlspecialchars($paid_requests['first_name'] . ' ' . $paid_requests['last_name'] . ' ' . ($paid_requests['middle_name'] ?? '') . ' ' . ($paid_requests['suffix'] ?? '')) : 'N/A'; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row text-dark">
                            <!-- Display TD Number -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">TD Number</label> 
                                <input type="text" class="form-control" value="<?= isset($paid_requests['td_number']) ? htmlspecialchars($paid_requests['td_number']) : 'N/A'; ?>" readonly>
                            </div>
                            <!-- Display Class -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Class</label> 
                                <input type="text" class="form-control" value="<?= isset($paid_requests['class']) ? htmlspecialchars($paid_requests['class']) : 'N/A'; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row justify-content-center text-dark">
                            <!-- Display Tax Due -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Tax Due</label> 
                                <input type="text" class="form-control" value="<?= isset($paid_requests['tax_due']) ? htmlspecialchars($paid_requests['tax_due']) : 'N/A'; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mt-4">
                    <h3 class="text-center fw-bold mb-4 text-dark">Official Receipt</h3>
                    
                    <div class="border p-3 bg-light" style="border: 1px solid #ccc; border-radius: 8px;">
                        <div class="mb-4">
                            <label for="payment_amount" class="form-label"><strong>Amount Paid</strong></label>
                            <input type="text" class="form-control text-dark" value="<?= htmlspecialchars($paid_requests['payment_amount'] ?? ''); ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="payment_date" class="form-label"><strong>Date of Payment</strong></label>
                            <input type="text" class="form-control text-dark" value="<?= date('F j, Y, g:i a', strtotime($paid_requests['payment_date'] ?? '')); ?>" readonly>
                        </div>
                        <div class="mb-4">
                            <label for="treasurer_email" class="form-label"><strong>Treasurer Email</strong></label>
                            <input type="text" class="form-control text-dark" value="<?= htmlspecialchars($paid_requests['treasurer_email'] ?? ''); ?>" readonly>
                        </div>
                        <div class="text-center mt-4">
                            <i class="fas fa-check-circle mb-3" style="font-size: 75px; color: green;"></i>
                            <h5 class="fw-bold text-dark">This request has been paid.</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
