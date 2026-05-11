<?php
include 'header.php';
$id = $_GET['id'];

// Fetch all request details using the new combined function
$requestDetails = $function->getApprovedRequestDetails($id);
?>

<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="d-flex justify-content-end mt-3">
                <a class="btn btn-primary ml-auto" href="payment_receipt.php?id=<?= htmlspecialchars($id); ?>">Go to Payment Receipt</a>
            </div>
            <div class="d-flex justify-content-center align-items-center mt-3 mb-2">
                <h2 class="text-dark">Approved Receipt</h2>
            </div>  
            <div class="row">
                <!-- Left Section: Request Details -->
                <div class="col-md-8">
                    <h4 class="card-title text-center fw-bold mt-4 mb-4 text-dark">Request Details</h4>
                    <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                        <div class="row text-dark">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Name of Declarant</label>
                                <input type="text" class="form-control text-dark" id="name" name="name" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['first_name'] . ' ' . $requestDetails['middle_name'] . ' ' . $requestDetails['last_name'] . ' ' . $requestDetails['suffix']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control text-dark" id="email" name="email" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['email']) : ''; ?>" 
                                       readonly>
                            </div>
                        </div>
                        <div class="row text-dark">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">TD Number</label>
                                <input type="text" class="form-control text-dark" id="td_number" name="td_number" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['td_number']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">PIN</label>
                                <input type="text" class="form-control text-dark" id="pin" name="pin" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['pin']) : ''; ?>" 
                                       readonly>
                            </div>
                        </div>
                        <div class="row text-dark">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Province</label>
                                <input type="text" class="form-control text-dark" id="province" name="province" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['province']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Municipality</label>
                                <input type="text" class="form-control text-dark" id="municipality" name="municipality" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['municipality']) : ''; ?>" 
                                       readonly>
                            </div>
                        </div>
                        <div class="row text-dark">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Barangay</label>
                                <input type="text" class="form-control text-dark" id="barangay" name="barangay" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['barangay']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Street</label>
                                <input type="text" class="form-control text-dark" id="street" name="street" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['street']) : ''; ?>" 
                                       readonly>
                            </div>
                        </div>
                        <div class="row text-dark">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Lot Number</label>
                                <input type="text" class="form-control text-dark" id="lot_number" name="lot_number" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['lot_number']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Area</label>
                                <input type="text" class="form-control text-dark" id="area" name="area" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['area']) : ''; ?>" 
                                       readonly>
                            </div>
                        </div>
                        <div class="row text-dark">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Market Value</label>
                                <input type="text" class="form-control text-dark" id="market_value" name="market_value" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['market_value']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Class</label>
                                <input type="text" class="form-control text-dark" id="class" name="class" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['class']) : ''; ?>" 
                                       readonly>
                            </div>
                        </div>
                    </div>
                    <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                        <h4 class="text-center text-dark">Uploaded Documents</h4>
                        <div class="row text-dark">
                            <div class="col-md-12 mb-4">
                                <ul>
                                    <!-- Assuming you have a way to fetch document paths from the database -->
                                    <?php if (!empty($requestDetails['documents'])): ?>
                                        <?php $documents = json_decode($requestDetails['documents']); ?>
                                        <?php foreach ($documents as $doc): ?>
                                            <li>
                                                <a href="<?= htmlspecialchars($doc); ?>" target="_blank">
                                                    <i class="fas fa-file"></i> <?= basename($doc); ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li>No documents uploaded.</li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right Section: Approval Request Form -->
                <div class="col-md-4 mt-4">
                    <h3 class="text-center fw-bold mb-4 text-dark">Approved Request</h3>
                    <div class="border p-3 bg-light" style="border: 1px solid #ccc; border-radius: 8px;">
                        <form action="../navigate.php" method="POST">
                            <input type="hidden" name="request_id" value="<?= htmlspecialchars($id); ?>">
                            <div class="mb-4">
                                <label for="market_value" class="form-label"><strong>Market Value</strong></label>
                                <input type="number" class="form-control text-dark" id="market_value" name="market_value" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['market_value']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="mb-4">
                                <label for="basic_tax" class="form-label"><strong>Basic Tax</strong></label>
                                <input type="number" class="form-control text-dark" id="basic_tax" name="basic_tax" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['basic_tax']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="mb-4">
                                <label for="assessed_value" class="form-label"><strong>Assessed Value</strong></label>
                                <input type="number" class="form-control text-dark" id="assessed_value" name="assessed_value" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['assessed_value']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="mb-4">
                                <label for="sef" class="form-label"><strong>SEF</strong></label>
                                <input type="number" class="form-control text-dark" id="sef" name="sef" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['sef']) : ''; ?>" 
                                       readonly>
                            </div>
                            <div class="mb-4">
                                <label for="tax_due" class="form-label"><strong>Tax Due</strong></label>
                                <input type="number" class="form-control text-dark" id="tax_due" name="tax_due" 
                                       value="<?= $requestDetails ? htmlspecialchars($requestDetails['tax_due']) : ''; ?>" 
                                       readonly>
                            </div>
                            <!-- Approved Icon -->
                            <div class="text-center mt-4">
                                <i class="fas fa-check-circle mb-3" style="font-size: 75px; color: green;"></i>
                                <h5 class="fw-bold text-dark">This request has been approved.</h5>
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
