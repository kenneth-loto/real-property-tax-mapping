<?php
include 'header.php';
$id = $_GET['id'];
$request = $function->getARequest($id); // Fetch the reviewed request details
?>

<html>
<head>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg"> <!-- Set width to 100% to accommodate 3 columns -->
        <div class="card-body">
            <div class="row justify-content-center">
                <!-- Centered Back to Requests button inside the first 2 columns -->
                <div class="d-flex justify-content-between mt-3">
                    <a class="btn btn-primary" role="button" href="requests.php">Go Back To Requests</a>
                    <a class="btn btn-primary" role="button" href="view_request_receipt.php?id=<?= $id; ?>">Go To Requests Receipt</a>
                </div>
                <!-- Left Section: Request Details (spans 2 columns) -->
                <div class="col-md-12">
                    <h4 class="card-title text-center fw-bold mt-4 mb-4 text-dark">Request Details</h4>

                    <!-- Request Details Section -->
                    <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">
                        <div class="row text-dark">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Name of Declarant</label>
                                <p class="form-control">
                                    <?= htmlspecialchars(trim($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name)); ?>
                                </p>

                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Email</label>
                                <p class="form-control"><?= htmlspecialchars($request->applicant_email); ?></p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Phone Number</label>
                                <p class="form-control"><?= htmlspecialchars($request->contact_number); ?></p>
                            </div>
                        </div>

                        <div class="row text-dark">
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Province</label>
                                <p class="form-control"><?= htmlspecialchars($request->province); ?></p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Municipality</label>
                                <p class="form-control"><?= htmlspecialchars($request->municipality); ?></p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Barangay</label>
                                <p class="form-control"><?= htmlspecialchars($request->barangay); ?></p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label">Street</label>
                                <p class="form-control"><?= htmlspecialchars($request->street); ?></p>
                            </div>
                        </div>

                        <div class="row text-dark justify-content-center">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">TD Number</label>
                                <p class="form-control"><?= htmlspecialchars($request->td_number); ?></p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">PIN</label>
                                <p class="form-control"><?= htmlspecialchars($request->pin); ?></p>
                            </div>
                        </div>

                        <div class="row text-dark">
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Lot Number</label>
                                <p class="form-control"><?= htmlspecialchars($request->lot_number); ?></p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Area</label>
                                <p class="form-control"><?= htmlspecialchars($request->area); ?> sqm</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label">Class</label>
                                <p class="form-control"><?= htmlspecialchars($request->class); ?></p>
                            </div>
                        </div>

                        <div class="row text-dark">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Documents Attached</label>
                                <div id="documentCarousel" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php 
                                            $documentsArray = json_decode($request->documents, true); 
                                            if (!empty($documentsArray)): 
                                                foreach ($documentsArray as $index => $document): ?>
                                                    <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                                                        <img src="../<?= htmlspecialchars($document); ?>" alt="Document" class="d-block w-100" style="max-height: 500px; object-fit: cover;">
                                                    </div>
                                        <?php endforeach; 
                                            else: ?>
                                            <p class="text-dark">No documents attached.</p>
                                        <?php endif; ?>
                                    </div>
                                    <a class="carousel-control-prev" href="#documentCarousel" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#documentCarousel" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div>
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
