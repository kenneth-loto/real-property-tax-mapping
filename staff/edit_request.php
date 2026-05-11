<?php
include 'header.php';
$id = $_GET['id'];
$approved_request = $function->getStaffApprovedOrRejectedRequest($id);
?>
<html>
<body>
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card w-100 shadow-lg">
        <div class="card-body">
            <h3 class="text-center fw-bold mt-4 mb-4 text-dark">REQUEST FORM</h3>

            <?php
            $msg = Session::get("msg");
            if (isset($msg)) {
                echo $msg;
                Session::set("msg", NULL);
            }
            ?>

            <div class="border p-3 bg-light mb-4" style="border: 1px solid #ccc; border-radius: 8px;">

            <form method="post" action="../navigate.php" enctype="multipart/form-data">
                <div class="row text-dark">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="name"><strong>Name of Declarant <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($approved_request->name); ?>" placeholder="e.g. Mark D. Delacruz" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="email"><strong>Email <span class="text-danger">*</span></strong></label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($approved_request->email); ?>" placeholder="example@gmail.com" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="phone_number"><strong>Phone Number <span class="text-danger">*</span></strong></label>
                        <input type="number" class="form-control" name="phone_number" value="<?= htmlspecialchars($approved_request->phone_number); ?>" placeholder="e.g. 09123456789 (11 digits)" required>
                    </div>
                </div>

                <div class="row text-dark">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="td_number"><strong>TD Number <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="td_number" value="<?= htmlspecialchars($approved_request->td_number); ?>" placeholder="e.g. 08-022-00001" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="pin"><strong>PIN <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="pin" value="<?= htmlspecialchars($approved_request->pin); ?>" placeholder="e.g. 074-02-0001-015-31-2001" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="lot_number"><strong>Lot No. <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="lot_number" value="<?= htmlspecialchars($approved_request->lot_number); ?>" placeholder="e.g. 00-1" required>
                    </div>
                </div>

                <!-- Province, Municipality, and Barangay Dropdowns -->
                <div class="row text-dark">
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="province"><strong>Province <span class="text-danger">*</span></strong></label>
                        <select class="form-select" id="province" name="province" required>
                            <option value="">Select Province</option>
                        </select>
                        <input type="hidden" id="provinceName" name="provinceName">
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="form-label" for="municipality"><strong>Municipality <span class="text-danger">*</span></strong></label>
                        <select class="form-select" id="municipality" name="municipality" required>
                            <option value="">Select Municipality</option>
                        </select>
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
                        <label class="form-label" for="street"><strong>Street <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="street" value="<?= htmlspecialchars($approved_request->street); ?>" placeholder="e.g. St. Garcia" required>
                    </div>
                </div>

                <div class="row text-dark">
                    <div class="col-md-4 mb-4">
                        <label for="area"><strong>Area <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" name="area" value="<?= htmlspecialchars($approved_request->area); ?>" placeholder="e.g. 1518.56 sqm" required>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="market_value"><strong>Market Value <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="market_value" name="market_value" value="<?= htmlspecialchars($approved_request->market_value); ?>" placeholder="e.g. 400,899.00" required oninput="computeTax()">
                    </div>
                    <div class="col-md-4 mb-4">
                        <label for="class"><strong>Class <span class="text-danger">*</span></strong></label>
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
                    <div class="col-md-8 mb-4">
                        <label for="documents"><strong>Attach Documents <span class="text-danger">*</span></strong></label>
                        <input type="file" class="form-control" name="documents[]" accept="image/*" multiple required>
                        <small class="form-text">For faster transaction, use images that are landscape.</small>
                    </div>
                </div>

                <div class="row text-dark justify-content-center">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="assessed_value"><strong>Assessed Value <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="assessed_value" name="assessed_value" value="<?= htmlspecialchars($approved_request->assessed_value); ?>" placeholder="999.00" required oninput="computeTax()">
                    </div>
                </div>

                <div class="row text-dark">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="sef"><strong>SEF (1%) <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="sef" name="sef" value="<?= htmlspecialchars($approved_request->sef); ?>" placeholder="Auto generated" readonly>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="basic_tax"><strong>Basic Tax (1%) <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="basic_tax" name="basic_tax" value="<?= htmlspecialchars($approved_request->basic_tax); ?>" placeholder="Auto generated" readonly>
                    </div>
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="tax_due"><strong>Total Tax Due <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="tax_due" name="tax_due" value="<?= htmlspecialchars($approved_request->tax_due); ?>" placeholder="Auto generated" readonly>
                    </div>
                </div>

                <div class="row text-dark justify-content-center">
                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="staff_email"><strong>Staff's Email <span class="text-danger">*</span></strong></label>
                        <input type="text" class="form-control" id="staff_email" name="staff_email" value="<?= htmlspecialchars($approved_request->staff_email); ?>" placeholder="e.g. example@gmail.com" required oninput="computeTax()">
                    </div>
                </div>

                <!-- Centered Buttons -->
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary me-3 w-25 py-2 fs-4 mb-4 rounded-2" name="btn-staff-created-request" onclick="return confirm('Are you sure you want to submit? Please review your details before proceeding.');" 
                    title="Submit" style="justify-content: center !important;">Submit</button>
                    <a href="requests.php" class="btn btn-danger w-25 py-2 fs-4 mb-4 rounded-2" style="justify-content: center !important;" title="Cancel">Cancel</a>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<?php
// Clear form data after displaying the form
unset($_SESSION['form_data']);
?>

<script>
    // Function to compute SEF, Basic Tax, and Tax Due
    function computeTax() {
        let marketValue = parseFloat(document.getElementById('market_value').value) || 0;
        let assessedValue = parseFloat(document.getElementById('assessed_value').value) || 0;

        // Compute SEF and Basic Tax
        let sef = (marketValue * 0.01).toFixed(2);
        let basicTax = (assessedValue * 0.01).toFixed(2);

        // Compute Total Tax Due
        let taxDue = (parseFloat(sef) + parseFloat(basicTax)).toFixed(2);

        // Update the input fields
        document.getElementById('sef').value = sef;
        document.getElementById('basic_tax').value = basicTax;
        document.getElementById('tax_due').value = taxDue;
    }

    // Fetch and sort provinces from local JSON file
    fetch('../assets/json/provinces.json')
        .then(response => response.json())
        .then(data => {
            const provinceDropdown = document.getElementById('province');
            provinceDropdown.innerHTML = '<option value="">Select Province</option>';
            const sortedProvinces = data.sort((a, b) => a.name.localeCompare(b.name)); // Sort A-Z
            sortedProvinces.forEach(province => {
                provinceDropdown.innerHTML += `<option value="${province.code}">${province.name}</option>`;
            });
        })
        .catch(error => console.error('Error fetching provinces:', error));

    // Fetch municipalities based on selected province and sort A-Z
    document.getElementById('province').addEventListener('change', function() {
        const provinceCode = this.value; // Get the selected province code
        const provinceName = this.options[this.selectedIndex].text;
        document.getElementById('provinceName').value = provinceName;

        // Load all municipalities and filter by the selected province
        fetch('../assets/json/municipalities.json')
            .then(response => response.json())
            .then(data => {
                const municipalityDropdown = document.getElementById('municipality');
                municipalityDropdown.innerHTML = '<option value="">Select Municipality</option>';

                // Filter municipalities by provinceCode
                const filteredMunicipalities = data.filter(municipality => municipality.provinceCode === provinceCode);
                const sortedMunicipalities = filteredMunicipalities.sort((a, b) => a.name.localeCompare(b.name)); // Sort A-Z

                sortedMunicipalities.forEach(municipality => {
                    municipalityDropdown.innerHTML += `<option value="${municipality.code}">${municipality.name}</option>`;
                });

                // Reset barangay dropdown and hidden fields
                document.getElementById('barangay').innerHTML = '<option value="">Select Barangay</option>';
                document.getElementById('municipalityName').value = ''; // Reset hidden municipality name
                document.getElementById('barangayName').value = ''; // Reset hidden barangay name
            })
            .catch(error => console.error('Error fetching municipalities:', error));
    });

    // Fetch barangays based on selected municipality and sort A-Z
    document.getElementById('municipality').addEventListener('change', function() {
        const municipalityCode = this.value; // Get the selected municipality code
        const municipalityName = this.options[this.selectedIndex].text;
        document.getElementById('municipalityName').value = municipalityName;

        // Load all barangays and filter by the selected municipality
        fetch('../assets/json/barangays.json')
            .then(response => response.json())
            .then(data => {
                const barangayDropdown = document.getElementById('barangay');
                barangayDropdown.innerHTML = '<option value="">Select Barangay</option>';

                // Filter barangays by municipalityCode
                const filteredBarangays = data.filter(barangay => barangay.municipalityCode === municipalityCode);
                const sortedBarangays = filteredBarangays.sort((a, b) => a.name.localeCompare(b.name)); // Sort A-Z

                sortedBarangays.forEach(barangay => {
                    barangayDropdown.innerHTML += `<option value="${barangay.code}">${barangay.name}</option>`;
                });

                // Reset hidden barangay name
                document.getElementById('barangayName').value = ''; 
            })
            .catch(error => console.error('Error fetching barangays:', error));
    });

    // Set barangay name when selected
    document.getElementById('barangay').addEventListener('change', function() {
        const barangayName = this.options[this.selectedIndex].text;
        document.getElementById('barangayName').value = barangayName; // Save the selected barangay name
    });
</script>

</body>
</html>
