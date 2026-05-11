<?php
include 'header.php';

// Get disease ID from the query string
$id = $_GET['id'];

// Fetch the disease data (assuming getADisease returns an associative array)
$staff = $function->getAAdmin($id);
?>
<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
        <a class="btn btn-secondary mt-3" role="button" href="admin.php">Go Back To Admin</a>
            <h3 class="text-center mt-4 mb-4 text-dark">Update Admin</h3>

            <?php
                  $msg = Session::get("msg");
                  if (isset($msg)) {
                      echo $msg;
                      Session::set("msg", NULL);
                  }
                ?>
                <form method="post" action="../navigate.php" class="mt-4 text-dark" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?= htmlspecialchars($staff->id); ?>">

                <h4 class="mb-3 mt-5">Name of Admin</h4>

                <!-- Row 1: Full Name -->
                <div class="row">
                <div class="col-md-3 mb-4">
                    <label class="form-label" for="first_name">First Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="first_name" placeholder="e.g. Juan" value="<?= isset($_SESSION['form_data']['first_name']) ? htmlspecialchars($_SESSION['form_data']['first_name']) : htmlspecialchars($staff->first_name); ?>" >
                </div>
                
                <div class="col-md-3 mb-4">
                    <label class="form-label">Middle Name (Optional)</label>
                    <input type="text" class="form-control" name="middle_name" placeholder="e.g. Dela" value="<?= isset($_SESSION['form_data']['middle_name']) ? htmlspecialchars($_SESSION['form_data']['middle_name']) : htmlspecialchars($staff->middle_name); ?>">
                </div>

                <div class="col-md-3 mb-4">
                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="last_name" placeholder="e.g. Cruz" value="<?= isset($_SESSION['form_data']['last_name']) ? htmlspecialchars($_SESSION['form_data']['last_name']) : htmlspecialchars($staff->middle_name); ?>" required>
                </div>
                <div class="col-md-3 mb-4">
                    <label class="form-label">Suffix (Optional)</label>
                    <select class="form-select" name="suffix">
                    <option value="">Select Suffix</option>
                    <option value="Jr." <?= isset($_SESSION['form_data']['suffix']) && $_SESSION['form_data']['suffix'] == 'Jr.' ? 'selected' : ''; ?>>Jr.</option>
                    <option value="Jra." <?= isset($_SESSION['form_data']['suffix']) && $_SESSION['form_data']['suffix'] == 'Jra.' ? 'selected' : ''; ?>>Jra.</option>
                    <option value="Sr." <?= isset($_SESSION['form_data']['suffix']) && $_SESSION['form_data']['suffix'] == 'Sr.' ? 'selected' : ''; ?>>Sr.</option>
                    </select>
                </div>
                </div>

                <h4 class="mb-3 mt-3">Address</h4>

                <!-- Province, Municipality, and Barangay Dropdowns -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="province">Province <span class="text-danger">*</span></label>
                        <!-- Make the input field readonly and set the value to the name of the province (Biliran) -->
                        <input type="text" class="form-control" id="province" name="province" value="Biliran" readonly>
                        <input type="hidden" id="provinceCode" name="provinceCode" value="Biliran"> <!-- Hidden field to store the province code -->
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="municipality">Municipality <span class="text-danger">*</span></label>
                        <!-- Make the input field readonly and set the value to the name of the municipality (Naval) -->
                        <input type="text" class="form-control" id="municipality" name="municipality" value="Naval" readonly>
                        <input type="hidden" id="municipalityName" name="municipalityName" value="Naval"> <!-- Hidden field to store the municipality name -->
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="barangay">Barangay <span class="text-danger">*</span></label>
                        <select class="form-select" id="barangay" name="barangay" required>
                            <option value="">Select Barangay</option>
                        </select>
                        <input type="hidden" id="barangayName" name="barangayName">
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="street">Street (Optional)</label>
                        <input type="text" class="form-control" name="street" value="<?= isset($staff->street) ? htmlspecialchars($staff->street) : ''; ?>" placeholder="e.g. St. Garcia">
                    </div>
                </div>

                <div class="row mt-3">
                    <!-- Contact Information Section -->
                    <div class="col-md-6 mb-4">
                        <h4 class="mb-3">Contact Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" placeholder="e.g. example@gmail.com" value="<?= isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : htmlspecialchars($staff->email); ?>" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="contact_number"><strong>Contact Number <span class="text-danger">*</span></strong></label>
                                <div class="input-group">
                                    <span class="input-group-text">+63</span>
                                    <input type="tel" class="form-control" id="contact_number" name="contact_number" maxlength="10" pattern="9[0-9]{9}" 
                                    value="<?= isset($_SESSION['form_data']['contact_number']) ? htmlspecialchars(substr($_SESSION['form_data']['contact_number'], 3)) : htmlspecialchars($staff->contact_number); ?>"
                                    placeholder="9123456789" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="col-md-6 mb-4">
                        <h4 class="mb-3">Password</h4>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password must be at least 8"
                                            value="<?php echo isset($_SESSION['form_data']['password']) ? htmlspecialchars($_SESSION['form_data']['password']) : ''; ?>" required>
                                    <span class="input-group-text" onclick="togglePasswordVisibility('password', this)">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Re-enter your password"
                                            value="<?php echo isset($_SESSION['form_data']['confirmpassword']) ? htmlspecialchars($_SESSION['form_data']['confirmpassword']) : ''; ?>" required>
                                    <span class="input-group-text" onclick="togglePasswordVisibility('confirmpassword', this)">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h4 class="mb-3 offset-md-2">Validation</h4>

                  <div class="row justify-content-center">
                    <div class="col-md-8 mb-4">
                        <label for="valid_id">Valid ID <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="valid_id" name="valid_id" accept="image/*" required>
                        <small class="form-text text-muted">Upload a clear image of your valid ID. Have it Landscape if possible.</small>

                        <!-- Image Carousel Container -->
                        <div id="imageCarousel" class="carousel slide mt-3" data-bs-ride="carousel" style="display: none;">
                            <div class="carousel-inner" id="carousel-inner"></div>
                        </div>
                    </div>

                <div class="d-flex justify-content-center">
                    <button name="btn-create-admin" type="submit" class="btn btn-dark w-25 py-2 fs-4 mb-4 rounded-2" style="justify-content: center !important;"
                    onclick="return confirm('Are you sure you want to submit? Please review the details before proceeding.');" 
                    title="Submit">Submit
                    </button>
                </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('valid_id').addEventListener('change', function(event) {
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
  function togglePasswordVisibility(inputId, icon) {
    const input = document.getElementById(inputId);
    const iconElement = icon.querySelector('i');
    
    if (input.type === "password") {
      input.type = "text";
      iconElement.classList.remove('fa-eye');
      iconElement.classList.add('fa-eye-slash');
    } else {
      input.type = "password";
      iconElement.classList.remove('fa-eye-slash');
      iconElement.classList.add('fa-eye');
    }
  }
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

