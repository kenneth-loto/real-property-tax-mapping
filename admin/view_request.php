<?php
include 'header.php';
$id = $_GET['id'];
$request_receipt = $function->getARequestsReceipt($id);

// Decode the selected_property JSON
$selectedProperty = json_decode($request_receipt->selected_property, true);

// Load the property coordinates from JSON file
$propertyCoordinates = json_decode(file_get_contents('../assets/json/properties_coordinates.json'), true);

$staff_email = $_SESSION['email'] ?? '';

?>

<html>
<head>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

    <link rel="stylesheet" href="../assets/css/leaflet/leaflet.css" />
    <style>
        #map {
            height: 400px; /* Set the height of the map */
            margin-bottom: 20px; /* Space between the map and the form */
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <div class="row justify-content-center">
                <div class="d-flex justify-content-start mt-3">
                    <a class="btn btn-secondary" href="requests.php">Back To Requests</a>
                </div>
                
                <div class="col-md-12">
                    <h3 class="text-center fw-bold mt-4 mb-4 text-dark">Applicant's Request Details</h3>
                    <div class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef;">

                    <h3 class="text-dark mb-4 mt-3">Applicant's Info</h3>

                        <!-- Applicant Details Section -->
                        <div class="row text-dark">
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>Name of Declarant</strong></label>
                                <p class="form-control"><?= htmlspecialchars("{$request_receipt->first_name} {$request_receipt->middle_name} {$request_receipt->last_name} {$request_receipt->suffix}"); ?></p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>Email</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->applicant_email); ?></p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>Phone Number</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->contact_number); ?></p>
                            </div>
                        </div>

                        <h3 class="text-dark mb-4 mt-3">Location of Property</h3>

                        <!-- request_receipt Details Section -->
                        <div class="row text-dark">
                            <div class="col-md-3 mb-4">
                                <label class="form-label"><strong>Province</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->province); ?></p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label"><strong>Municipality</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->municipality); ?></p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label"><strong>Barangay</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->barangay); ?></p>
                            </div>
                            <div class="col-md-3 mb-4">
                                <label class="form-label"><strong>Street</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->street); ?></p>
                            </div>
                        </div>

                        <div id="map"></div> <!-- Map Container -->

                        <h3 class="text-dark mb-4 mt-5">Property's Info</h3>

                        <div class="row text-dark justify-content-center">
                            <div class="col-md-4 mb-4">
                                <label class="form-label" for="selected_property"><strong>Property Name</strong></label>
                                <input type="text" class="form-control" name="selected_property" 
                                    value="<?= isset($selectedProperty['name']) ? htmlspecialchars($selectedProperty['name']) : ''; ?>"  
                                    placeholder="Selected Property" readonly required>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>TD Number</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->td_number); ?></p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>Pin</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->pin); ?></p>
                            </div>
                        </div>
                        
                        <div class="row text-dark">
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>Lot Number</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->lot_number); ?></p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>Area</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->area); ?> sqm</p>
                            </div>
                            <div class="col-md-4 mb-4">
                                <label class="form-label"><strong>Class</strong></label>
                                <p class="form-control"><?= htmlspecialchars($request_receipt->class); ?></p>
                            </div>
                        </div>

                        <h3 class="text-dark mb-4 mt-3">Property's Documents</h3>

                        <!-- Documents Carousel Section -->
                        <div class="row text-dark mb-5">
                            <div class="col-md-12 mb-5">
                                <label class="form-label"><strong>Attached Documents</strong></label>
                                <div id="documentCarousel" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php 
                                            $documentsArray = json_decode($request_receipt->documents, true); 
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

                        <div class="container mt-5">

                            <!-- Approve request_receipt Form (Visible by default) -->
                            <div id="approveForm" class="approval-section">
                                <h3 class="text-center text-dark mb-4">Approve Request Form</h3>
                                <form action="../navigate.php" method="POST">

                                    <div class="row mb-4">
                                        <div class="col-6 mx-auto">
                                            <label for="market_value" class="form-label"><strong>Market Value <span class="text-danger">*</span></strong></label>
                                            <input type="number" class="form-control text-dark" id="market_value" name="market_value" 
                                                value="<?= isset($request_receipt->market_value) ? htmlspecialchars($request_receipt->market_value) : ''; ?>" 
                                                oninput="computeTax()" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6 mx-auto">
                                            <label for="assessment_rate" class="form-label"><strong>Assessment Rate % <span class="text-danger">*</span></strong></label>
                                            <input type="number" class="form-control text-dark" id="assessment_rate" name="assessment_rate" 
                                                value="<?= isset($request_receipt->assessment_rate) ? htmlspecialchars($request_receipt->assessment_rate) : ''; ?>" 
                                                oninput="computeTax()" required readonly>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6 mx-auto">
                                            <label for="assessed_value" class="form-label"><strong>Assessed Value <span class="text-danger">*</span></strong></label>
                                            <input type="number" class="form-control text-dark" id="assessed_value" name="assessed_value" 
                                                placeholder="Auto generated" readonly value="<?= isset($request_receipt->assessed_value) ? htmlspecialchars($request_receipt->assessed_value) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6 mx-auto">
                                            <label for="basic_tax" class="form-label"><strong>Basic Tax (1%) <span class="text-danger">*</span></strong></label>
                                            <input type="number" class="form-control text-dark" id="basic_tax" name="basic_tax" 
                                                placeholder="Auto generated" readonly value="<?= isset($request_receipt->basic_tax) ? htmlspecialchars($request_receipt->basic_tax) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6 mx-auto">
                                            <label for="sef" class="form-label"><strong>SEF (1%) <span class="text-danger">*</span></strong></label>
                                            <input type="number" class="form-control text-dark" id="sef" name="sef" 
                                                placeholder="Auto generated" readonly value="<?= isset($request_receipt->sef) ? htmlspecialchars($request_receipt->sef) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-6 mx-auto">
                                            <label for="tax_due" class="form-label"><strong>Total Tax Due <span class="text-danger">*</span></strong></label>
                                            <input type="number" class="form-control text-dark" id="tax_due" name="tax_due" 
                                                placeholder="Auto generated" readonly value="<?= isset($request_receipt->tax_due) ? htmlspecialchars($request_receipt->tax_due) : ''; ?>">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../assets/css/leaflet/leaflet.js"></script>
<script>
    // Initialize the map
    var map = L.map('map').setView([11.560358021250153, 124.39303069766652], 18); // Center the map based on your properties

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(map);

    // Property coordinates from PHP
    var properties = <?php echo json_encode($propertyCoordinates); ?>;

    // Decode the selected_property JSON (replace with actual PHP echo)
    var selectedProperty = <?php echo json_encode($selectedProperty); ?>;

    // Create a Set to keep track of coordinates already added to avoid duplicates
    var addedCoordinates = new Set();

    // Loop through properties to create polygons
    properties.forEach(function(property) {
        var coordinates = property.coordinates.map(function(coord) {
            return coord.join(','); // Create a unique string for each coordinate pair
        });

        // Check for duplicates and only add unique properties
        if (coordinates.some(coord => addedCoordinates.has(coord))) {
            // If there's a duplicate, skip this property
            console.warn('Duplicate coordinates found for property:', property.name);
            return;
        } else {
            // Add coordinates to the Set
            coordinates.forEach(coord => addedCoordinates.add(coord));
        }

        // Define color based on property type
        var color = property.type === "Owned" ? "red" : "blue"; // Use "red" for owned properties and "blue" for defaults

        // Create a polygon for each property
        var polygon = L.polygon(property.coordinates, { color: color }).addTo(map);
        polygon.bindPopup(property.name); // Show property name on click

        // Add click event to polygon
        polygon.on('click', function() {
            document.getElementById('selected_property').value = JSON.stringify(property); // Set selected property JSON
        });
    });

    // If there is a selected property, map its coordinates as well
    if (selectedProperty && selectedProperty.coordinates) {
        var selectedCoordinates = selectedProperty.coordinates.map(function(coord) {
            return coord; // Ensure the coordinates are in [lat, lng] format
        });

        // Create a polygon for the selected property
        var selectedPolygon = L.polygon(selectedCoordinates, { color: "red" }).addTo(map);
        selectedPolygon.bindPopup(selectedProperty.name); // Show selected property name on click

        // Optional: Center the map on the selected property
        map.fitBounds(selectedPolygon.getBounds());
    }
</script>

</body>
</html>
