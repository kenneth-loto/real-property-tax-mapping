<?php
include 'header.php';

// Get the request ID from the URL
$id = $_GET['id'] ?? null; // Use null coalescing operator for safety
$request = $function->getAllStaffApprovedRequestsById($id); // Fetch the approved request data based on the ID

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
                    <div class="d-flex justify-content-between mt-3">
                        <a class="btn btn-primary" role="button" href="transactions.php">Go Back To Transactions</a>
                        <a class="btn btn-primary" role="button" href="transaction_details_receipt.php?id=<?= htmlspecialchars($id); ?>">Go To Receipt Form</a>
                    </div>
                    <div class="col-md-8">
                        <h4 class="card-title text-center fw-bold mt-4 mb-4 text-dark">Payment of Request</h4>
                        
                        <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                            <div class="row text-dark">
                                <!-- Display Requestor Name -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Name of Declarant</label> 
                                    <p class="form-control"><?= htmlspecialchars("{$request['first_name']} {$request['middle_name']} {$request['last_name']} {$request['suffix']}"); ?></p>
                                </div>
                                <!-- Display Approved At Date -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Approved At</label> 
                                    <p class="form-control"><?= date('F j, Y, g:i a', strtotime($request['approved_at'])); ?></p>
                                </div>
                            </div>
                            
                            <div class="row text-dark">
                                <!-- Display TD Number -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">TD Number</label> 
                                    <p class="form-control"><?= htmlspecialchars($request['td_number']); ?></p>
                                </div>
                                <!-- Display Class -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Class</label> 
                                    <p class="form-control"><?= htmlspecialchars($request['class']); ?></p>
                                </div>
                            </div>
                            
                            <div class="row justify-content-center text-dark">
                                <!-- Display Tax Due -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tax Due</label> 
                                    <p class="form-control"><?= htmlspecialchars($request['tax_due']); ?></p>
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
