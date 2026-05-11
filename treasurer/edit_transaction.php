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
                <div class="d-flex justify-content-between mt-3">
                    <a class="btn btn-primary" role="button" href="transactions.php">Go Back To Transactions</a>
                    <a class="btn btn-primary" role="button" href="edit_transaction_receipt.php?id=<?= htmlspecialchars($id); ?>">Go To Transaction Receipt</a>
                </div>
                <!-- Request Details Section -->
                <div class="col-md-8">
                    <h4 class="card-title text-center fw-bold mt-4 mb-4 text-dark">Payment of Request</h4>
                    
                    <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                        <div class="row text-dark">
                            <!-- Display Requestor Name -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Name of Declarant</label> 
                                <input type="text" class="form-control" value="<?= isset($paidRequest['name']) ? htmlspecialchars($paidRequest['name']) : 'N/A'; ?>" readonly>
                            </div>
                            <!-- Display Approved At Date -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Approved At</label> 
                                <input type="text" class="form-control" value="<?= isset($paidRequest['paid_at']) ? date('F j, Y, g:i a', strtotime($paidRequest['paid_at'])) : 'N/A'; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row text-dark">
                            <!-- Display TD Number -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">TD Number</label> 
                                <input type="text" class="form-control" value="<?= isset($paidRequest['td_number']) ? htmlspecialchars($paidRequest['td_number']) : 'N/A'; ?>" readonly>
                            </div>
                            <!-- Display Class -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Class</label> 
                                <input type="text" class="form-control" value="<?= isset($paidRequest['class']) ? htmlspecialchars($paidRequest['class']) : 'N/A'; ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="row justify-content-center text-dark">
                            <!-- Display Tax Due -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Tax Due</label> 
                                <input type="text" class="form-control" value="<?= isset($paidRequest['tax_due']) ? htmlspecialchars($paidRequest['tax_due']) : 'N/A'; ?>" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
