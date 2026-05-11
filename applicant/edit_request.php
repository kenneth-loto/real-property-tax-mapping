<?php
include 'header.php';

$id = $_GET['id'];
$request = $function->getARequest($id);

// Decode the selected_property JSON
$selectedProperty = json_decode($request->selected_property, true);

// Load the property coordinates from JSON file
$propertyCoordinates = json_decode(file_get_contents('../assets/json/properties_coordinates.json'), true);

// Assuming you have a function to fetch provinces, municipalities, and barangays
$provinces = json_decode(file_get_contents('../assets/json/provinces.json'), true);
usort($provinces, function($a, $b) {
    return strcmp($a['name'], $b['name']);
});

$municipalities = json_decode(file_get_contents('../assets/json/municipalities.json'), true);
$barangays = json_decode(file_get_contents('../assets/json/barangays.json'), true);

?>
<html>
<head>
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
            <h3 class="text-center fw-bold mt-4 mb-4 text-dark">EDIT YOUR REQUEST</h3>

            <?php
            $msg = Session::get("msg");
            if (isset($msg)) {
                echo $msg;
                Session::set("msg", NULL);
            }
            ?>
            
            <div class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef;">

            <form method="post" action="../navigate.php" enctype="multipart/form-data">
                <!-- Province, Municipality, and Barangay Dropdowns -->
                <div class="row text-dark">

                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="province"><strong>Province <span class="text-danger">*</span></strong></label>
                        <select class="form-select" id="province" name="province">
                            <option value="">Select Province</option>
                            <?php foreach ($provinces as $province): ?>
                                <option value="<?= $province['code']; ?>" <?= $request->province == $province['name'] ? 'selected' : ''; ?>>
                                    <?= $province['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="municipality"><strong>Municipality <span class="text-danger">*</span></strong></label>
                        <select class="form-select" id="municipality" name="municipality">
                            <option value="">Select Municipality</option>
                            <?php foreach ($municipalities as $municipality): ?>
                                <option value="<?= $municipality['code']; ?>" <?= $request->municipality == $municipality['name'] ? 'selected' : ''; ?>>
                                    <?= $municipality['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="barangay"><strong>Barangay <span class="text-danger">*</span></strong></label>
                        <select class="form-select" id="barangay" name="barangay">
                            <option value="">Select Barangay</option>
                            <?php foreach ($barangays as $barangay): ?>
                                <option value="<?= $barangay['code']; ?>" <?= $request->barangay == $barangay['name'] ? 'selected' : ''; ?>>
                                    <?= $barangay['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="street"><strong>Street <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="street" value="<?= htmlspecialchars($request->street); ?>" placeholder="e.g. St. Garcia" required>
                    </div>

                </div>

                <div id="map"></div> <!-- Map Container -->

                <div class="row text-dark justify-content-center">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="selected_property"><strong>Select Property <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="selected_property" 
                            value="<?= isset($selectedProperty['name']) ? htmlspecialchars($selectedProperty['name']) : ''; ?>"  
                            placeholder="Selected Property" readonly required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="td_number"><strong>TD Number <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="td_number" value="<?= htmlspecialchars($request->td_number); ?>" placeholder="e.g. 08-022-00001" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="pin"><strong>PIN <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="pin" value="<?= htmlspecialchars($request->pin); ?>" placeholder="e.g. 074-02-0001-015-31-2001" required>
                    </div>
                </div>

                <div class="row text-dark">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="lot_number"><strong>Lot No. <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="lot_number" value="<?= htmlspecialchars($request->lot_number); ?>" placeholder="e.g. 00-1" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="area"><strong>Area <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="area" value="<?= htmlspecialchars($request->area); ?>" placeholder="e.g. 1518.56 sqm" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="class"><strong>Class <span class="text-danger">*</span></strong></label>
                        <select class="form-select" name="class" required>
                        <option value="" disabled selected>Select Class</option>
                            <option value="Commercial" <?= $request->class == 'Commercial' ? 'selected' : ''; ?>>Commercial</option>
                            <option value="Residential" <?= $request->class == 'Residential' ? 'selected' : ''; ?>>Residential</option>
                            <option value="Industrial" <?= $request->class == 'Industrial' ? 'selected' : ''; ?>>Industrial</option>
                            <option value="Agricultural" <?= $request->class == 'Agricultural' ? 'selected' : ''; ?>>Agricultural</option>
                            <option value="Mixed" <?= $request->class == 'Mixed' ? 'selected' : ''; ?>>Mixed</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-4 text-dark justify-content-center">
                    <div class="col-md-12 mb-4">
                        <label for="documents"><strong>Attach Documents <span class="text-danger">*</span></strong></label>
                        <input type="file" class="form-control" id="documents" name="documents[]" accept="image/*" multiple required>
                        <small class="form-text">For faster transaction, use images that are landscape.</small>

                        <!-- Image Carousel Container -->
                        <div id="imageCarousel" class="carousel slide mt-3" data-bs-ride="carousel" style="display: none;">
                            <div class="carousel-inner" id="carousel-inner"></div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Centered Buttons -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary me-3 w-25 py-2 fs-4 mb-4 rounded-2" name="btn-applicant-add-requests" onclick="return confirm('Are you sure you want to submit? Please review your details before proceeding.');" 
                    title="Submit" style="justify-content: center !important;">Submit</button>
                    <a href="requests.php" class="btn btn-danger w-25 py-2 fs-4 mb-4 rounded-2" style="justify-content: center !important;" title="Cancel">Cancel</a>
                </div>
            </form>
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

    document.getElementById('documents').addEventListener('change', function(event) {
        const carouselInner = document.getElementById('carousel-inner');
        const carouselContainer = document.getElementById('imageCarousel');
        carouselInner.innerHTML = ''; // Clear previous previews
        carouselContainer.style.display = 'none'; // Hide carousel initially

        Array.from(event.target.files).forEach((file, index) => {
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'carousel-item' + (index === 0 ? ' active' : '');

                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'd-block w-100';
                    img.style.width = '100%'; // Set fixed width
                    img.style.height = '500px'; // Set fixed height

                    itemDiv.appendChild(img);
                    carouselInner.appendChild(itemDiv);
                };
                reader.readAsDataURL(file);
            }
        });

        if (event.target.files.length > 0) {
            carouselContainer.style.display = 'block'; // Show carousel when there are images
        }
    });
</script>

<script>
// Fetch municipalities and sort them based on selected province
document.getElementById('province').addEventListener('change', function() {
    // Clear and reset dependent dropdowns
    document.getElementById('municipality').innerHTML = '<option value="">Select Municipality</option>';
    document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
    
    const provinceCode = this.value;
    if (provinceCode) {
        fetch('../assets/json/municipalities.json')
        .then(response => response.json())
        .then(municipalities => {
            // Filter municipalities based on the selected province
            const filteredMunicipalities = municipalities.filter(municipality => municipality.provinceCode === provinceCode);
            filteredMunicipalities.sort((a, b) => a.name.localeCompare(b.name));
            
            filteredMunicipalities.forEach(municipality => {
                const option = document.createElement('option');
                option.value = municipality.code;
                option.textContent = municipality.name;

                // Check if this municipality matches the previously selected one
                if (municipality.name === '<?= htmlspecialchars($request->municipality); ?>') {
                    option.selected = true;
                }

                document.getElementById('municipality').appendChild(option);
            });

            // Trigger change event for municipalities to load barangays if there's a previously selected municipality
            const selectedMunicipality = document.getElementById('municipality').value;
            if (selectedMunicipality) {
                document.getElementById('municipality').dispatchEvent(new Event('change'));
            }
        });
    }
});

// Fetch barangays based on selected municipality
document.getElementById('municipality').addEventListener('change', function() {
    // Clear barangay dropdown
    document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
    
    const municipalityCode = this.value;
    if (municipalityCode) {
        fetch('../assets/json/barangays.json')
        .then(response => response.json())
        .then(barangays => {
            // Filter barangays based on the selected municipality
            const filteredBarangays = barangays.filter(barangay => barangay.municipalityCode === municipalityCode);
            filteredBarangays.sort((a, b) => a.name.localeCompare(b.name));

            filteredBarangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = barangay.code;
                option.textContent = barangay.name;

                // Check if this barangay matches the previously selected one
                if (barangay.name === '<?= htmlspecialchars($request->barangay); ?>') {
                    option.selected = true;
                }

                document.getElementById('barangay').appendChild(option);
            });
        });
    }
});

// Trigger the change event on page load to auto-select based on initial province
document.addEventListener('DOMContentLoaded', function() {
    const provinceSelect = document.getElementById('province');
    if (provinceSelect.value) {
        provinceSelect.dispatchEvent(new Event('change'));
    }
});
</script>


</body>
</html>
