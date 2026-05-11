<?php
  include_once '../session.php';
  Session::init();
  include '../function.php';
  $function = new Functions();
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>RPTMTCS Sign Up</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/logo.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-5">
            <div class="card mb-0">
              <div class="card-body">
                <a href="#" class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../assets/images/logos/logo.png" width="200" alt="">
                </a>
                <h5 class="text-center">WELCOME TO <strong>RPTMTCS</strong></h5>
                <?php
                  $msg = Session::get("msg");
                  if (isset($msg)) {
                      echo $msg;
                      Session::set("msg", NULL);
                  }
                ?>
                <form method="post" action="../navigate.php" class="mt-4" enctype="multipart/form-data">
                  <!-- Row 1: Full Name -->
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">First Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="first_name" placeholder="e.g. Juan" value="<?php echo isset($_SESSION['form_data']['first_name']) ? htmlspecialchars($_SESSION['form_data']['first_name']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Last Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="last_name" placeholder="e.g. Cruz" value="<?php echo isset($_SESSION['form_data']['last_name']) ? htmlspecialchars($_SESSION['form_data']['last_name']) : ''; ?>" required>
                    </div>
                  </div>

                  <!-- Row 2: Middle Name (optional) and Suffix (optional) -->
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">Middle Name (Optional)</label>
                      <input type="text" class="form-control" name="middle_name" placeholder="e.g. Dela" value="<?php echo isset($_SESSION['form_data']['middle_name']) ? htmlspecialchars($_SESSION['form_data']['middle_name']) : ''; ?>">
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Suffix (Optional)</label>
                      <select class="form-select" name="suffix">
                        <option value="">Select Suffix</option>
                        <option value="Jr." <?php echo isset($_SESSION['form_data']['suffix']) && $_SESSION['form_data']['suffix'] == 'Jr.' ? 'selected' : ''; ?>>Jr.</option>
                        <option value="Jra." <?php echo isset($_SESSION['form_data']['suffix']) && $_SESSION['form_data']['suffix'] == 'Jra.' ? 'selected' : ''; ?>>Jra.</option>
                        <option value="Sr." <?php echo isset($_SESSION['form_data']['suffix']) && $_SESSION['form_data']['suffix'] == 'Sr.' ? 'selected' : ''; ?>>Sr.</option>
                      </select>
                    </div>
                  </div>

                  <!-- Row 3: Email and Contact Number -->
                  <div class="row mb-3">
                    <div class="col-md-6">
                      <label class="form-label">Email <span class="text-danger">*</span></label>
                      <input type="email" class="form-control" name="email" placeholder="e.g. example@gmail.com" value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Contact Number <span class="text-danger">*</span></label>
                      <input type="tel" class="form-control" name="contact_number" placeholder="e.g. 09123456789 (11 digits)" value="<?php echo isset($_SESSION['form_data']['contact_number']) ? htmlspecialchars($_SESSION['form_data']['contact_number']) : ''; ?>" required>
                    </div>
                  </div>

                  <!-- Row 4: Address -->
                  <div class="row mb-3">
                    <div class="col-md-12">
                      <label class="form-label">Address <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="address" placeholder="e.g. 123 Main St, Brgy. Bonifacio, Naval, Biliran" value="<?php echo isset($_SESSION['form_data']['address']) ? htmlspecialchars($_SESSION['form_data']['address']) : ''; ?>" required>
                    </div>
                  </div>

                  <!-- Row 5: Password and Confirm Password -->
                  <div class="row mb-3"> 
                    <div class="col-md-6">
                      <label class="form-label">Password <span class="text-danger">*</span></label>
                      <input type="password" class="form-control" name="password" placeholder="Minumun of 8 or more characters" value="<?php echo isset($_SESSION['form_data']['password']) ? htmlspecialchars($_SESSION['form_data']['password']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                      <input type="password" class="form-control" name="confirmpassword" placeholder="Re-enter your password" value="<?php echo isset($_SESSION['form_data']['confirmpassword']) ? htmlspecialchars($_SESSION['form_data']['confirmpassword']) : ''; ?>" required>
                    </div>
                  </div>

                  <!-- Row 6: Valid ID -->
                  <div class="row mb-4">
                    <div class="col-md-12">
                      <label class="form-label">Valid ID <span class="text-danger">*</span></label>
                      <input type="file" class="form-control" name="valid_id" accept="image/*" required>
                      <small class="form-text text-muted">Upload a clear image of your valid ID.</small>
                    </div>
                  </div>

                  <div class="d-flex justify-content-center">
                    <button name="btn-create-staff" type="submit" class="btn btn-dark w-50 py-2 fs-4 mb-4 rounded-2" 
                    onclick="return confirm('Are you sure you want to sign up? Please review your details before proceeding.');" 
                    title="Sign Up">Sign Up
                    </button>
                  </div>
                  
                  <div class="d-flex align-items-center justify-content-center">
                    <p class="fs-4 mb-0 fw-bold">Already have an account?</p>
                    <a class="text-primary fw-bold ms-2" href="staff_login.php">Sign In</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
