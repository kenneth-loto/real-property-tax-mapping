<?php
include 'header.php';
?>
<html>
<body>
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold mb-4">New Request</h5>
            <?php
            $msg = Session::get("msg");
            if (isset($msg)) {
                echo $msg;
                Session::set("msg", NULL);
            }
            ?>
            <form method="post" action="../navigate.php">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name of Declarant</label>
                            <input type="text" class="form-control" name="name" value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>" placeholder="e.g. Mark D. Delacruz" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" placeholder="example@gmail.com" required>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="td_number">TD Number</label>
                            <input type="text" class="form-control" name="td_number" value="<?php echo isset($_SESSION['form_data']['td_number']) ? htmlspecialchars($_SESSION['form_data']['td_number']) : ''; ?>" placeholder="e.g. 08-022-00001" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pin">PIN</label>
                            <input type="text" class="form-control" name="pin" value="<?php echo isset($_SESSION['form_data']['pin']) ? htmlspecialchars($_SESSION['form_data']['pin']) : ''; ?>" placeholder="e.g. 074-02-0001-015-31-2001" required>
                        </div>
                    </div>
                </div>

                <!-- Province and Municipality Dropdowns (Dynamic using API) -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="province">Province</label>
                            <select class="form-select" id="province" name="province" required>
                                <option value="">Select Province</option>
                            </select>
                            <input type="hidden" id="provinceName" name="provinceName">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="municipality">Municipality</label>
                            <select class="form-select" id="municipality" name="municipality" required>
                                <option value="">Select Municipality</option>
                            </select>
                            <input type="hidden" id="municipalityName" name="municipalityName">
                        </div>
                    </div>
                </div>

                <!-- Barangay and Street Inputs -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="barangay">Barangay</label>
                            <select class="form-select" id="barangay" name="barangay" required>
                                <option value="">Select Barangay</option>
                            </select>
                            <input type="hidden" id="barangayName" name="barangayName">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="street">Street</label>
                            <input type="text" class="form-control" name="street" placeholder="e.g. St. Garcia" required>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lot_number">Lot No.</label>
                            <input type="text" class="form-control" name="lot_number" value="<?php echo isset($_SESSION['form_data']['lot_number']) ? htmlspecialchars($_SESSION['form_data']['lot_number']) : ''; ?>" placeholder="e.g. 1024" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="area">Area</label>
                            <input class="form-control" name="area" rows="3" value="<?php echo isset($_SESSION['form_data']['area']) ? htmlspecialchars($_SESSION['form_data']['area']) : ''; ?>" placeholder="e.g. 1518.56 sq"></input>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="market_value">Market Value</label>
                            <input type="text" class="form-control" name="market_value" value="<?php echo isset($_SESSION['form_data']['market_value']) ? htmlspecialchars($_SESSION['form_data']['market_value']) : ''; ?>" placeholder="e.g. 400,899.00" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="class">Class</label>
                            <select class="form-select" name="class">
                                <option value="" disabled selected>Select Class</option>
                                <option value="Commercial">Commercial</option>
                                <option value="Residential">Residential</option>
                                <option value="Industrial">Industrial</option>
                                <option value="Agricultural">Agricultural</option>
                                <option value="Mixed">Mixed</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="documents">Attach Documents</label>
                            <input type="file" class="form-control" name="documents[]" multiple required>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="offset-sm-3 col-sm-3 d-grid">
                        <button name="btn-add-requests" type="submit" class="btn btn-primary" style="justify-content: center !important;">Submit</button>
                    </div>
                    <div class="col-sm-3 d-grid">
                        <a class="btn btn-danger" href="requests.php" role="button" style="justify-content: center !important;">Cancel</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
// Clear form data after displaying the form
unset($_SESSION['form_data']);
?>

<script>
// Fetch and sort provinces from PSGC API (alphabetical order)
fetch('https://psgc.gitlab.io/api/provinces/')
  .then(response => response.json())
  .then(data => {
    const provinceDropdown = document.getElementById('province');
    provinceDropdown.innerHTML = '<option value="">Select Province</option>';
    const sortedProvinces = data.sort((a, b) => a.name.localeCompare(b.name)); // Sort A-Z
    sortedProvinces.forEach(province => {
      provinceDropdown.innerHTML += `<option value="${province.code}">${province.name}</option>`;
    });
  });

// Fetch municipalities based on selected province and sort A-Z
document.getElementById('province').addEventListener('change', function() {
  const provinceCode = this.value;
  const provinceName = this.options[this.selectedIndex].text; // Get the province name
  document.getElementById('provinceName').value = provinceName; // Set the hidden input

  fetch(`https://psgc.gitlab.io/api/provinces/${provinceCode}/municipalities`)
    .then(response => response.json())
    .then(data => {
      const municipalityDropdown = document.getElementById('municipality');
      municipalityDropdown.innerHTML = '<option value="">Select Municipality</option>';
      const sortedMunicipalities = data.sort((a, b) => a.name.localeCompare(b.name)); // Sort A-Z
      sortedMunicipalities.forEach(municipality => {
        municipalityDropdown.innerHTML += `<option value="${municipality.code}">${municipality.name}</option>`;
      });
      document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>'; // Reset barangay dropdown
      document.getElementById('municipalityName').value = ''; // Reset hidden municipality name
      document.getElementById('barangayName').value = ''; // Reset hidden barangay name
    });
});

// Fetch barangays based on selected municipality and sort A-Z
document.getElementById('municipality').addEventListener('change', function() {
  const municipalityCode = this.value;
  const municipalityName = this.options[this.selectedIndex].text; // Get the municipality name
  document.getElementById('municipalityName').value = municipalityName; // Set the hidden input

  fetch(`https://psgc.gitlab.io/api/municipalities/${municipalityCode}/barangays`)
    .then(response => response.json())
    .then(data => {
      const barangayDropdown = document.getElementById('barangay');
      barangayDropdown.innerHTML = '<option value="">Select Barangay</option>';
      const sortedBarangays = data.sort((a, b) => a.name.localeCompare(b.name)); // Sort A-Z
      sortedBarangays.forEach(barangay => {
        barangayDropdown.innerHTML += `<option value="${barangay.code}">${barangay.name}</option>`;
      });
      document.getElementById('barangayName').value = ''; // Reset hidden barangay name
    });
});

// Set barangay name when selected
document.getElementById('barangay').addEventListener('change', function() {
  const barangayName = this.options[this.selectedIndex].text; // Get the barangay name
  document.getElementById('barangayName').value = barangayName; // Set the hidden input
});
</script>

</body>
</html>
