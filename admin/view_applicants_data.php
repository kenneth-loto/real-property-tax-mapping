<?php
include 'header.php';
$id = $_GET['id'];
$applicants_validation = $function->getApplicantById($id);

if (!$applicants_validation) {
    echo "<p class='text-danger'>Applicant not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicant Details</title>
    <link rel="stylesheet" href="../assets/css/view_request.css">
    <link rel="stylesheet" href="../assets/css/form.css">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card card-custom p-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
            <a href="applicants.php" class="btn btn-secondary back-button">Go Back</a>
            <a href="approve_or_reject.php?id=<?= $id; ?>" class="btn btn-secondary back-button">Approve/Reject</a>
            </div>
            
            <h3 class="text-center mb-4 text-dark">Applicant Details</h3>
            
            <div class="section-title">Name of Declarant</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['first_name'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Middle Name</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['middle_name'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['last_name'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Suffix</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['suffix'] ?? ''); ?>">
                </div>
            </div>

            <div class="section-title">Address</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Province</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['province'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Municipality</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['municipality'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Barangay</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['barangay'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Street</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['street'] ?? ''); ?>">
                </div>
            </div>

            <div class="section-title">Contact Information</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['email'] ?? ''); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" class="form-control" readonly value="<?= htmlspecialchars($applicants_validation['contact_number'] ?? ''); ?>">
                </div>
            </div>

            <div class="section-title">Validation</div>
            <div class="mb-3">
                <label class="form-label">Valid ID</label>
                <?php 
                $validIdArray = json_decode($applicants_validation['valid_id'] ?? '[]', true); 
                if (!empty($validIdArray)): 
                    foreach ($validIdArray as $validIdPath): ?>
                        <img src="../<?= htmlspecialchars($validIdPath); ?>" alt="Valid ID" class="img-preview">
                    <?php endforeach;
                else: ?>
                    <p class="text-muted">No valid ID attached.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
