<?php
include 'header.php';

// Load the property coordinates from JSON file
$propertyCoordinates = json_decode(file_get_contents('../assets/json/properties_coordinates.json'), true);

// Fetch the email from the session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
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
            <h3 class="text-center fw-bold mt-4 mb-4 text-dark">Tax Declaration of Real Property</h3>

            <?php
            $msg = Session::get("msg");
            if (isset($msg)) {
                echo $msg;
                Session::set("msg", NULL);
            }
            ?>
            
            <div class="border p-3 mb-4" style="border: 1px solid #ccc; border-radius: 8px; background-color: #e9ecef;">

            <form method="post" action="../navigate.php" enctype="multipart/form-data">

            <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">

                <h3 class="text-dark mb-4 mt-3">Location of Property</h3>

                <!-- Province, Municipality, and Barangay Dropdowns -->
                <div class="row text-dark">
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="province">Province <span class="text-danger">*</span></label>
                        <!-- The input field for displaying the province name (e.g., Biliran) -->
                        <input type="text" class="form-control" id="province" name="province" value="Biliran" readonly>
                        <!-- Hidden input to store province code for internal usage -->
                        <input type="hidden" id="provinceCode" name="provinceCode" value="">
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="municipality"><strong>Municipality <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="municipality" name="municipality" value="Naval" readonly>
                        <input type="hidden" id="municipalityName" name="municipalityName">
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="barangay"><strong>Barangay <span class="text-danger">*</span></strong></label>
                        <select class="form-select" id="barangay" name="barangay" required>
                            <option value="">Select Barangay</option>
                        </select>
                        <input type="hidden" id="barangayName" name="barangayName">
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="street"><strong>Street</span></strong></label>
                        <input type="text" class="form-control" name="street" value="<?php echo isset($_SESSION['form_data']['street']) ? htmlspecialchars($_SESSION['form_data']['street']) : ''; ?>" placeholder="e.g. St. Garcia">
                    </div>
                </div>

                <div id="map"></div> <!-- Map Container -->

                <div class="row text-dark justify-content-center">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="selected_property"><strong>Select Property <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="selected_property" name="selected_property" placeholder="Select Sample Property From The Map" readonly required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="td_number"><strong>TD Number <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="td_number" value="<?php echo isset($_SESSION['form_data']['td_number']) ? htmlspecialchars($_SESSION['form_data']['td_number']) : ''; ?>" placeholder="e.g. 10-022-00001" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="pin"><strong>PIN <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="pin" value="<?php echo isset($_SESSION['form_data']['pin']) ? htmlspecialchars($_SESSION['form_data']['pin']) : ''; ?>" placeholder="e.g. 074-02-0001-015-31-2001" required>
                    </div>
                </div>

                <div class="row text-dark">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="lot_number"><strong>Lot No. <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="lot_number" value="<?php echo isset($_SESSION['form_data']['lot_number']) ? htmlspecialchars($_SESSION['form_data']['lot_number']) : ''; ?>" placeholder="e.g. 00-1" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="area"><strong>Area <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="area" value="<?php echo isset($_SESSION['form_data']['area']) ? htmlspecialchars($_SESSION['form_data']['area']) : ''; ?>" placeholder="e.g. 1518.56" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="class"><strong>Class <span class="text-danger">*</span></strong></label>
                        <select class="form-select" name="class" required>
                            <option value="" disabled selected>Select Class</option>
                            <option value="Commercial">Commercial</option>
                            <option value="Residential">Residential</option>
                            <option value="Industrial">Industrial</option>
                            <option value="Agricultural">Agricultural</option>
                            <option value="Mixed">Mixed</option>
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

    // Loop through properties to create polygons
    properties.forEach(function(property) {
        var coordinates = property.coordinates.map(function(coord) {
            return [coord[0], coord[1]]; // Convert to [lat, lng] format
        });

        // Create a polygon for each property
        var polygon = L.polygon(coordinates).addTo(map);
        polygon.bindPopup(property.name); // Show property name on click

        // Add click event to polygon
        polygon.on('click', function() {
            document.getElementById('selected_property').value = property.name; // Set selected property
        });
    });

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
                    img.style.height = '600px'; // Set fixed height

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
// Fetch and sort provinces from local JSON file
fetch('../assets/json/provinces.json')
  .then(response => response.json())
  .then(data => {
    const provinceDropdown = document.getElementById('province');
    const sortedProvinces = data.sort((a, b) => a.name.localeCompare(b.name));

    // Set province value and auto-select Biliran
    sortedProvinces.forEach(province => {
      if (province.name === 'Biliran') {
        provinceDropdown.value = province.name;
        document.getElementById('provinceCode').value = province.code; // Set hidden field with province code
      }
    });

    // Populate municipalities for Biliran
    const biliranProvinceCode = sortedProvinces.find(province => province.name === 'Biliran').code;
    populateMunicipalities(biliranProvinceCode); // Auto-populate municipalities for Biliran
  })
  .catch(error => console.error('Error fetching provinces:', error));

// Function to populate the municipality dropdown based on selected province
function populateMunicipalities(provinceCode) {
  fetch('../assets/json/municipalities.json')
    .then(response => response.json())
    .then(data => {
      const municipalityDropdown = document.getElementById('municipality');
      municipalityDropdown.innerHTML = '<option value="">Select Municipality</option>';

      // Sort municipalities for the selected province
      const sortedMunicipalities = data.filter(municipality => municipality.provinceCode === provinceCode)
                                        .sort((a, b) => a.name.localeCompare(b.name));

      // Populate municipalities dropdown with sorted values
      sortedMunicipalities.forEach(municipality => {
        municipalityDropdown.innerHTML += `<option value="${municipality.code}">${municipality.name}</option>`;
      });

      // Auto-select Naval if it exists in the municipalities for Biliran
      const biliranMunicipality = sortedMunicipalities.find(municipality => municipality.name === 'Naval');
      if (biliranMunicipality) {
        municipalityDropdown.value = biliranMunicipality.code; // Auto-select Naval by code
        document.getElementById('municipality').value = biliranMunicipality.name; // Display "Naval" as text
        document.getElementById('municipalityName').value = biliranMunicipality.name; // Set the hidden field with municipality name

        // Now populate barangays based on the selected municipality (Naval)
        populateBarangays(biliranMunicipality.code); // Pass the municipality code to filter barangays
      }

      // Reset barangay dropdown
      document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
    })
    .catch(error => console.error('Error fetching municipalities:', error));
}

// Function to populate barangay dropdown based on municipality code
function populateBarangays(municipalityCode) {
  fetch('../assets/json/barangays.json')
    .then(response => response.json())
    .then(data => {
      const barangayDropdown = document.getElementById('barangay');
      barangayDropdown.innerHTML = '<option value="">Select Barangay</option>';

      // Filter barangays for the selected municipality and sort them
      const filteredBarangays = data.filter(barangay => barangay.municipalityCode === municipalityCode)
                                    .sort((a, b) => {
                                      if (a.name === 'Naval') return -1; // Move Naval to the top (if applicable)
                                      if (b.name === 'Naval') return 1;
                                      return a.name.localeCompare(b.name); // Sort alphabetically otherwise
                                    });

      // Populate barangay dropdown with the filtered barangays
      filteredBarangays.forEach(barangay => {
        barangayDropdown.innerHTML += `<option value="${barangay.code}">${barangay.name}</option>`;
      });
    })
    .catch(error => console.error('Error fetching barangays:', error));
}

// Update hidden input when municipality is selected
document.getElementById('municipality').addEventListener('change', function () {
  const selectedOption = this.options[this.selectedIndex];
  const municipalityNameInput = document.getElementById('municipalityName');
  municipalityNameInput.value = selectedOption.text; // Set the name

  const municipalityCode = this.value;
  populateBarangays(municipalityCode); // Call to populate barangays based on selected municipality
});

// Update hidden input when barangay is selected
document.getElementById('barangay').addEventListener('change', function () {
  const selectedOption = this.options[this.selectedIndex];
  const barangayNameInput = document.getElementById('barangayName');
  barangayNameInput.value = selectedOption.text; // Set the name
});
</script>

</body>
</html>
