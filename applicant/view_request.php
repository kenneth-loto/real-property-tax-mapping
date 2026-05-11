<?php
include 'header.php';
$id = $_GET['id'];
$request = $function->getARequest($id);
?>

<html>
<head>
    <link rel="stylesheet" href="../assets/css/view_request.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <!-- Centered Button -->
            <div class="d-flex justify-content-start mb-4 mt-3">
                <a class="btn btn-primary" role="button" href="requests.php">Back to Requests</a>
            </div>
            <h4 class="text-center fw-bold mt-4 mb-4 text-dark">SUBMITTED REQUEST DETAILS</h4>
            <div class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef;">

            <div class="row text-dark">
                <div class="col-md-3 mb-4">
                    <strong>Province</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->province); ?></p>
                </div>
                <div class="col-md-3 mb-4">
                    <strong>Municipality</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->municipality); ?></p>
                </div>
                <div class="col-md-3 mb-4">
                    <strong>Barangay</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->barangay); ?></p>
                </div>
                <div class="col-md-3 mb-4">
                    <strong>Street</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->street); ?></p>
                </div>
            </div>

            <div class="row text-dark justify-content-center">
                <div class="col-md-4 mb-4">
                    <strong>TD Number</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->td_number); ?></p>
                </div>
                <div class="col-md-4 mb-4">
                    <strong>PIN</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->pin); ?></p>
                </div>
                
            </div>

            <div class="row text-dark">    
                <div class="col-md-4 mb-4">
                    <strong>Lot No.</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->lot_number); ?></p>
                </div>
                <div class="col-md-4 mb-4">
                    <strong>Area</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->area); ?> sqm</p>
                </div>
                <div class="col-md-4 mb-4">
                    <strong>Class:</strong> 
                    <p class="form-control bg-light"><?= htmlspecialchars($request->class); ?></p>
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
</body>
</html>
