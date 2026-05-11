<?php
include 'function.php';
include_once 'session.php';
Session::init();

$function = new Functions();

//Admin

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-password-reset'])) {
    // Get the email from the form
    $email = $_POST['email'];

    // Check if the email exists in the database
    if ($function->checkEmailExists($email)) {
        // Generate a password reset token
        $token = $function->generatePasswordResetToken($email);

        // Send the password reset email
        if ($function->sendPasswordResetEmail($email, $token)) {
            // Success message
            Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Password reset email sent successfully! Check your inbox. </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 4000); // 4000 milliseconds = 4 seconds
            </script>");

            header("Location: applicant/applicant_login.php");
            exit;
        } else {
            // Handle email sending failure
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Failed to send the email! Please try again. </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
            
            header("Location: applicant/applicant_login.php");
            exit;
        }
    } else {
        // Email does not exist
        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Email does not exist! Please check your email. </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000);</script>");

        // Redirect back to the password reset form
        header("Location: applicant/forgot_password.php");
        exit;
    }

    // Redirect back to the password reset form
    header("Location: applicant/forgot_password.php");
    exit;
}













if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-create-admin'])) {
    // Call the createApplicant function and pass the form data
    $flag = $function->createAdmin($_POST);

    if ($flag == 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Account created successfully! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: admin/admin_login.php"); // Redirect to login page
        exit;
    } else {
        // Store form data in session for repopulation
        $_SESSION['form_data'] = $_POST;

        // Handle error cases
        if ($flag == -1) {
            // Email already exists
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Email already exists! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -2) {
            // Passwords do not match
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Passwords do not match! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -3) {
            // Password complexity issue
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Password does not meet complexity requirements! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } else {
            // General error
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        }

        // Redirect back to the applicant creation form
        header("Location: admin/admin_create_account.php");
        exit;
    }
}

//---AUTHENTICATION SECTION---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-login-admin'])) {
    $email = $_POST['email']; // Get email from POST
    $password = $_POST['password']; // Get password from POST
    
    $flag = $function->authenticateAdmin($email, $password);

    if ($flag === 1) {
        // Password is correct, set session variables or redirect
        $_SESSION['email'] = $email;
        header("Location: admin/index.php"); // Updated to a more specific dashboard page
        exit;
    } else {
        $_SESSION['form_data'] = $_POST;
        // Set error message based on login failure
        $msg = "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Invalid username or password! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>";
        Session::set("msg", $msg);

        // Redirect back to the login page
        header("Location: admin/admin_login.php"); // Change to your updated login page URL
        exit;
    }
}



// Staff

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-add-staff'])) {
    // Call the createApplicant function and pass the form data
    $flag = $function->addStaff($_POST);

    if ($flag == 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Account created successfully! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: admin/staff.php"); // Redirect to login page
        exit;
    } else {
        // Store form data in session for repopulation
        $_SESSION['form_data'] = $_POST;

        // Handle error cases
        if ($flag == -1) {
            // Email already exists
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Email already exists! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -2) {
            // Passwords do not match
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Passwords do not match! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -3) {
            // Password complexity issue
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Password does not meet complexity requirements! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } else {
            // General error
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        }

        // Redirect back to the applicant creation form
        header("Location: admin/add_staff.php");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-add-admin'])) {
    // Call the createApplicant function and pass the form data
    $flag = $function->addAdmin($_POST);

    if ($flag == 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Account created successfully! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: admin/admin.php"); // Redirect to login page
        exit;
    } else {
        // Store form data in session for repopulation
        $_SESSION['form_data'] = $_POST;

        // Handle error cases
        if ($flag == -1) {
            // Email already exists
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Email already exists! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -2) {
            // Passwords do not match
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Passwords do not match! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -3) {
            // Password complexity issue
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Password does not meet complexity requirements! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } else {
            // General error
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        }

        // Redirect back to the applicant creation form
        header("Location: admin/add_admin.php");
        exit;
    }
}

//---AUTHENTICATION SECTION---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-login-staff'])) {
    $email = $_POST['email']; // Get email from POST
    $password = $_POST['password']; // Get password from POST
    
    $flag = $function->authenticateStaff($email, $password);

    if ($flag === 1) {
        // Password is correct, set session variables or redirect
        $_SESSION['email'] = $email;
        header("Location: staff/index.php"); // Updated to a more specific dashboard page
        exit;
    } else {
        $_SESSION['form_data'] = $_POST;
        // Set error message based on login failure
        $msg = "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Invalid username or password! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>";
        Session::set("msg", $msg);

        // Redirect back to the login page
        header("Location: staff/staff_login.php"); // Change to your updated login page URL
        exit;
    }
}



// Treasurer

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-create-treasurer'])) {
    // Call the createApplicant function and pass the form data
    $flag = $function->createTreasurer($_POST);

    if ($flag == 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Account created successfully! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: treasurer/treasurer_login.php"); // Redirect to login page
        exit;
    } else {
        // Store form data in session for repopulation
        $_SESSION['form_data'] = $_POST;

        // Handle error cases
        if ($flag == -1) {
            // Email already exists
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Email already exists! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -2) {
            // Passwords do not match
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Passwords do not match! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -3) {
            // Password complexity issue
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Password does not meet complexity requirements! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } else {
            // General error
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        }

        // Redirect back to the applicant creation form
        header("Location: treasurer/treasurer_create_account.php");
        exit;
    }
}

//---AUTHENTICATION SECTION---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-login-treasurer'])) {
    $email = $_POST['email']; // Get email from POST
    $password = $_POST['password']; // Get password from POST
    
    $flag = $function->authenticateTreasurer($email, $password);

    if ($flag === 1) {
        // Password is correct, set session variables or redirect
        $_SESSION['email'] = $email;
        header("Location: treasurer/index.php"); // Updated to a more specific dashboard page
        exit;
    } else {
        $_SESSION['form_data'] = $_POST;
        // Set error message based on login failure
        $msg = "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Invalid username or password! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>";
        Session::set("msg", $msg);

        // Redirect back to the login page
        header("Location: treasurer/treasurer_login.php"); // Change to your updated login page URL
        exit;
    }
}



// Applicant

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-create-applicant'])) {
    // Call the createApplicant function and pass the form data
    $flag = $function->createApplicant($_POST);

    if ($flag == 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Registration Complete! <br> Please check your email for validation.</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: applicant/applicant_login.php"); // Redirect to login page
        exit;
    } else {
        // Store form data in session for repopulation
        $_SESSION['form_data'] = $_POST;

        // Handle error cases
        if ($flag == -1) {
            // Email already exists
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Email already exists! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -2) {
            // Passwords do not match
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Passwords do not match! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } elseif ($flag == -3) {
            // Password complexity issue
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Password does not meet complexity requirements! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        } else {
            // General error
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000);</script>");
        }

        // Redirect back to the applicant creation form
        header("Location: applicant/applicant_create_account.php");
        exit;
    }
}

//---AUTHENTICATION SECTION---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-login-applicant'])) {
    $email = $_POST['email']; // Get email from POST
    $password = $_POST['password']; // Get password from POST
    
    $flag = $function->authenticateApplicant($email, $password);

    if ($flag === 1) {
        // Password is correct and account is approved
        $_SESSION['email'] = $email;
        header("Location: applicant/index.php"); // Redirect to dashboard page
        exit;
    } else {
        $_SESSION['form_data'] = $_POST; // Save form data for repopulating the form
        // Set error message based on login failure
        if ($flag === 2) {
            // Incorrect email or password for security reasons
            $msg = "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center> Incorrect email or password ! </center></div><br>";
        } elseif ($flag === 4) {
            // Account is not approved
            $msg = "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center> Accounts not Approved ! </center></div><br>";
        } else {
            // User does not exist
            $msg = "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center> User does not exist ! </center></div><br>";
        }
        
        // Display the error message for a set duration
        $msg .= "<script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>";

        Session::set("msg", $msg);

        // Redirect back to the login page
        header("Location: applicant/applicant_login.php"); // Change to your updated login page URL
        exit;
    }
}




if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-applicant-add-requests'])) {
    // Ensure that the uploaded files are included in the data array
    $data = $_POST;

    // If you are uploading files, merge the files data with your POST data
    if (isset($_FILES['documents'])) {
        $data['documents'] = $_FILES['documents']; // Add uploaded files to the data array
    }

    $flag = $function->applicantAddRequests($data); // Make sure you're calling the correct function

    if ($flag == 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> A new request has been added! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 1000); // 1000 milliseconds = 1 second
        </script>");
        
        header("Location: applicant/requests.php"); // Redirect to the requests page
        exit;
    } elseif ($flag === 2) {
        // Specific message for duplicate TD Number and PIN
        $_SESSION['form_data'] = $_POST;

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> TD Number and PIN combination already exists! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 3000); // Show message for 3 seconds
        </script>");

        // Redirect back to the form page
        header("Location: applicant/add_requests.php"); // Redirect to your form page
        exit;
    } else {
        // Generic error handling
        $_SESSION['form_data'] = $_POST;

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 1000); // 1000 milliseconds = 1 second
        </script>");

        // Redirect back to the form page
        header("Location: applicant/add_requests.php"); // Redirect to your form page
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-applicant-edit-request'])) {
    $id = $_GET['id'];

    $flag = $function->applicantUpdateRequest($_POST, $id);
    if ($flag == 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Request has been edited! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 1000); // 1000 milliseconds = 1 second
        </script>");
        header("Location: applicant/requests.php");
        exit;
    } else {
        // Store form data in session for repopulation
        $_SESSION['form_data'] = $_POST;
        $_SESSION['edit_request_id'] = $id;

        // Set error message based on the flag returned
        if ($flag == 2) {
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Email already exists! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 1000); // 1000 milliseconds = 1 second
            </script>");
        } elseif ($flag == 3) {
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> TD Number and PIN already exist for another request! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 1000); // 1000 milliseconds = 1 second
            </script>");
        } else {
            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 1000); // 1000 milliseconds = 1 second
            </script>");
        }

        // Redirect back to the form page
        header("Location: applicant/edit_request.php?id=" . $_SESSION['edit_request_id']); // Redirect with the "id" parameter
        exit;
    }
}



if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['btn-delete-request'])){		
	
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $flag = $function->deleteRequest($id);
        if ($flag == 1) {
            $_SESSION["msg"] = "<div style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Request has been deleted! </center> </div><br><script>
            setTimeout(function() {
                window.location.href = 'requests.php';
            }, 1000); // 1000 milliseconds = 1 second
        </script>";
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center> </div><br>";
        }
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Invalid request! </center> </div><br>";
        }
    header("Location: applicant/requests.php");
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['btn-delete-applicant'])){		
	
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $flag = $function->deleteApplicant($id);
        if ($flag == 1) {
            $_SESSION["msg"] = "<div style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center> Applicant has been deleted </center> </div><br><script>
            setTimeout(function() {
                window.location.href = 'applicants.php';
            }, 1000); // 1000 milliseconds = 1 second
        </script>";
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Something went wrong! </center> </div><br>";
        }
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> Invalid request! </center> </div><br>";
        }
    header("Location: admin/applicants.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-update-status'])) {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        
        // Call the function to update the request status
        if ($function->updateRequestStatus($id, $status)) {
            $_SESSION["msg"] = "<div style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'>
                                    <center><i class='fa fa-check'></i> Request has been {$status}! </center>
                                </div><br>
                                <script>
                                    setTimeout(function() {
                                        window.location.href = 'requests.php';
                                    }, 1000); // 1000 milliseconds = 1 second
                                </script>";
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'>
                                    <center><i class='fa fa-warning'></i> Something went wrong while updating the request! </center>
                                </div><br>";
        }
    } else {
        $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'>
                                <center><i class='fa fa-warning'></i> Invalid request! </center>
                            </div><br>";
    }
    header("Location: admin/requests.php");
    exit(); // Stop further execution
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-update-status-staff'])) {
    if (isset($_POST['id']) && isset($_POST['status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        
        // Call the function to update the request status
        if ($function->updateRequestStatus($id, $status)) {
            $_SESSION["msg"] = "<div style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'>
                                    <center><i class='fa fa-check'></i> Request has been {$status}! </center>
                                </div><br>
                                <script>
                                    setTimeout(function() {
                                        window.location.href = 'requests.php';
                                    }, 1000); // 1000 milliseconds = 1 second
                                </script>";
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'>
                                    <center><i class='fa fa-warning'></i> Something went wrong while updating the request! </center>
                                </div><br>";
        }
    } else {
        $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'>
                                <center><i class='fa fa-warning'></i> Invalid request! </center>
                            </div><br>";
    }
    header("Location: staff/requests.php");
    exit(); // Stop further execution
}

///---APPROVED REQUEST HANDLING SECTION---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-approve-request'])) {
    // Gather data directly from the POST request
    $requestId = $_POST['request_id']; // Get request ID from POST
    $data = $_POST; // Get all POST data

    // Call the adminReviewedRequests function and store the result
    $result = $this->adminReviewedRequests($data); // Assuming adminReviewedRequests returns 1 on success, 0 on failure

    if ($result === 1) {
        // Success message and redirect
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Request approved and data inserted successfully!</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 1000); // 1000 milliseconds = 1 second
        </script>");
        
        header("Location: approved_requests.php"); // Redirect to the approved requests dashboard
        exit;
    } else {
        // Error occurred during the insertion
        $_SESSION['form_data'] = $_POST; // Preserve form data for the user

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error approving the request. Please try again.</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 3000); // Show message for 3 seconds
        </script>");
        
        // Redirect back to the approval form
        header("Location: approval_form.php?id=" . urlencode($requestId)); // Include the request ID to keep context
        exit;
    }
}







if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-admin-approve-applicant'])) {
    // Get request ID and form data
    $id = $_POST['id'];
    $data = $_POST;

    // Call the approveApplicant function and store the result
    $result = $function->approveApplicant($id, $data);

    if ($result === 1) {
        // Success message and redirect for approval
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Applicant approved successfully!</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // 1000 milliseconds = 1 second
            </script>");

        header("Location: admin/applicants.php"); // Redirect to the dashboard
        exit;
    } else {
        // Error message for approval
        $_SESSION['form_data'] = $_POST;

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error approving the request. Please try again.</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // Show message for 3 seconds
            </script>");

        // Redirect back to the review form
        header("Location: admin/approve_or_reject.php"); // Change to your review form URL
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-admin-reject-applicant'])) {
    // Get request ID and form data
    $id = $_POST['id'];
    $data = $_POST;

    // Call the rejectApplicant function and store the result
    $result = $function->rejectApplicant($id, $data);

    if ($result === 1) {
        // Success message and redirect for rejection
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Applicant rejected successfully!</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // 1000 milliseconds = 1 second
            </script>");

        header("Location: admin/applicants.php"); // Redirect to the dashboard
        exit;
    } else {
        // Error message for rejection
        $_SESSION['form_data'] = $_POST;

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error approving the request. Please try again.</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // Show message for 3 seconds
            </script>");

        // Redirect back to the review form
        header("Location: admin/approve_or_reject.php"); // Change to your review form URL
        exit;
    }
}





// Staff Things

//---REQUEST HANDLING SECTION FOR APPROVAL---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-staff-approve-request'])) {
        // Gather data directly from the POST request for approval
        $requestId = $_POST['request_id']; // Get request ID from POST
        $data = $_POST; // Get all POST data

        // Call the insertApprovedRequest function and store the result
        $result = $function->staffApprovedRequest($requestId, $data); // Ensure you call the correct function

        if ($result === 1) {
            // Success message and redirect for approval
            Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Request approved successfully!</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // 1000 milliseconds = 1 second
            </script>");

            header("Location: staff/requests.php"); // Redirect to the dashboard
            exit;
        } elseif ($result === 0) {
            // Error occurred during the approval process
            $_SESSION['form_data'] = $_POST; // Preserve form data for the user

            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error approving the request. Please try again.</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // Show message for 3 seconds
            </script>");

            // Redirect back to the review form
            header("Location: staff/review_request.php"); // Change to your review form URL
            exit;
        }
}

//---REQUEST HANDLING SECTION FOR REJECTION---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-staff-reject-request'])) {
        // Gather data directly from the POST request for rejection
        $requestId = $_POST['request_id']; // Get request ID from POST
        $data = $_POST; // Get all POST data

        // Call the insertRejectedRequest function with the rejection status and reason
        $result = $function->staffRejectedRequest($requestId, $data); // Ensure you call the correct function

        if ($result === 1) {
            // Success message and redirect for rejection
            Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Request rejected successfully!</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // 1000 milliseconds = 1 second
            </script>");

            header("Location: staff/requests.php"); // Redirect to the dashboard
            exit;
        } elseif ($result === 0) {
            // Error occurred during the rejection process
            $_SESSION['form_data'] = $_POST; // Preserve form data for the user

            Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error rejecting the request. Please try again.</center></div><br><script>
            setTimeout(function() {
                document.getElementById('error-msg').style.display = 'none';
            }, 2000); // Show message for 3 seconds
            </script>");

            // Redirect back to the review form
            header("Location: staff/review_request.php"); // Change to your review form URL
            exit;
        }
    }


    //---REQUEST HANDLING SECTION FOR APPROVAL---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-staff-created-request'])) {
    // Gather data directly from the POST request for approval
    $requestId = $_POST['request_id']; // Get request ID from POST
    $data = $_POST; // Get all POST data

    // Call the staffApprovedRequest function and store the result
    $result = $function->staffCreatedRequest($data); // Pass the POST data to the function

    if ($result === 1) {
        // Success message and redirect for approval
        Session::set("msg", "<div id='error-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Request approved successfully!</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // Hide the message after 2 seconds
        </script>");

        // Redirect to the requests page or dashboard after success
        header("Location: staff/requests.php");
        exit;
    } elseif ($result === "A request with this TD Number and PIN combination already exists.") {
        // Specific message for duplicate TD Number and PIN
        $_SESSION['form_data'] = $_POST;

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> TD Number and PIN combination already exists! </center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 3000); // Show message for 3 seconds
        </script>");

        // Redirect back to the form page
        header("Location: staff/add_requests.php"); // Redirect to your form page
        exit;
    } elseif ($result === 0) {
        // Error occurred during the approval process
        $_SESSION['form_data'] = $_POST; // Preserve form data for the user to review

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error approving the request. Please try again.</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // Hide the message after 2 seconds
        </script>");

        // Redirect back to the review form for corrections
        header("Location: staff/add_request.php"); // Make sure this URL points to the correct review page
        exit;
    }
}



// Treasurer Things

//---REQUEST HANDLING SECTION FOR TREASURER PAYMENT REQUESTS---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-staff-add-or'])) {
    // Gather data directly from the POST request for payment
    $requestId = $_POST['request_id']; // Get request ID from POST
    $data = $_POST; // Get all POST data

    // Call the insertTreasurerPaidRequest function and store the result
    $result = $function->updateAdminPaidRequest($data); // Ensure you call the correct function

    if ($result === 1) {
        // Success message and redirect for payment
        Session::set("msg", "<div id='success-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Payment recorded successfully!</center></div><br><script>
        setTimeout(function() {
            document.getElementById('success-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: staff/rp_records.php"); // Redirect to the treasurer's dashboard
        exit;
    } elseif ($result === 0) {
        // Error occurred during the payment process
        $_SESSION['form_data'] = $_POST; // Preserve form data for the user

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error recording the payment. Please try again.</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // Show message for 2 seconds
        </script>");

        // Redirect back to the payment form with the previous data
        header("Location: staff/rp_records.php?id=" . htmlspecialchars($requestId)); // Change to your payment form URL with request ID
        exit;
    }
}

//---REQUEST HANDLING SECTION FOR UPDATING TREASURER PAYMENT REQUESTS---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-update-treasurer-paid-request'])) {
    // Gather data directly from the POST request for the update
    $requestId = $_POST['request_id']; // Get request ID from POST
    $data = $_POST; // Get all POST data
    $data['payment_amount'] = $_POST['amount_paid']; // Get updated amount paid from the form
    $data['payment_date'] = $_POST['payment_date']; // Get updated payment date from the form
    $data['treasurer_email'] = $_POST['treasurer_email']; // Get treasurer's email from the form

    // Call the function to update the treasurer paid request and store the result
    $result = $function->updateTreasurerPaidRequest($data); // Ensure you call the correct function

    if ($result === 1) {
        // Success message and redirect for update
        Session::set("msg", "<div id='success-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Payment updated successfully!</center></div><br><script>
        setTimeout(function() {
            document.getElementById('success-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: treasurer/transactions.php"); // Redirect to the treasurer's transactions page
        exit;
    } elseif ($result === 0) {
        // Error occurred during the update process
        $_SESSION['form_data'] = $_POST; // Preserve form data for the user

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error updating the payment. Please try again.</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // Show message for 2 seconds
        </script>");

        // Redirect back to the payment update form with the previous data
        header("Location: treasurer/transaction_details.php?id=" . htmlspecialchars($requestId)); // Change to your update form URL with request ID
        exit;
    }
}




// Admin Thingss

//---REQUEST HANDLING SECTION FOR TREASURER PAYMENT REQUESTS---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-admin-approved-request'])) {
    // Gather data directly from the POST request for payment
    $requestId = $_POST['request_id']; // Get request ID from POST
    $data = $_POST;

    // Call the insertTreasurerPaidRequest function and store the result
    $result = $function->adminApprovedRequests($data); // Ensure you call the correct function

    if ($result === 1) {
        // Success message and redirect for payment
        Session::set("msg", "<div id='success-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Admin approved successfully!</center></div><br><script>
        setTimeout(function() {
            document.getElementById('success-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: admin/requests.php"); // Redirect to the treasurer's dashboard
        exit;
    } elseif ($result === 0) {
        // Error occurred during the payment process
        $_SESSION['form_data'] = $_POST; // Preserve form data for the user

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error approving the request. Please try again.</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // Show message for 2 seconds
        </script>");

        // Redirect back to the payment form with the previous data
        header("Location: admin/requests.php?id=" . htmlspecialchars($requestId)); // Change to your payment form URL with request ID
        exit;
    }
}

//---REQUEST HANDLING SECTION FOR TREASURER PAYMENT REQUESTS---//
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn-admin-rejected-request'])) {
    // Gather data directly from the POST request for payment
    $requestId = $_POST['request_id']; // Get request ID from POST
    $data = $_POST; // Get all POST data

    // Call the insertTreasurerPaidRequest function and store the result
    $result = $function->adminRejectedRequests($data); // Ensure you call the correct function

    if ($result === 1) {
        // Success message and redirect for payment
        Session::set("msg", "<div id='success-msg' style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-check'></i> Admin rejected successfully!</center></div><br><script>
        setTimeout(function() {
            document.getElementById('success-msg').style.display = 'none';
        }, 2000); // 2000 milliseconds = 2 seconds
        </script>");

        header("Location: admin/requests.php"); // Redirect to the treasurer's dashboard
        exit;
    } elseif ($result === 0) {
        // Error occurred during the payment process
        $_SESSION['form_data'] = $_POST; // Preserve form data for the user

        Session::set("msg", "<div id='error-msg' style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center><i class='fa fa-warning'></i> There was an error rejecting the request. Please try again.</center></div><br><script>
        setTimeout(function() {
            document.getElementById('error-msg').style.display = 'none';
        }, 2000); // Show message for 2 seconds
        </script>");

        // Redirect back to the payment form with the previous data
        header("Location: admin/approve_request_receipt.php?id=" . htmlspecialchars($requestId)); // Change to your payment form URL with request ID
        exit;
    }
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['btn-delete-staff'])){		
	
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $flag = $function->deleteStaff($id);
        if ($flag == 1) {
            $_SESSION["msg"] = "<div style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center></i> Staff has been deleted </center> </div><br><script>
            setTimeout(function() {
                window.location.href = 'staff.php';
            }, 1000); // 1000 milliseconds = 1 second
        </script>";
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center></i> Something went wrong </center> </div><br>";
        }
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center></i> Invalid request </center> </div><br>";
        }
    header("Location: admin/staff.php");
}

if($_SERVER['REQUEST_METHOD']=='POST' && isset($_POST['btn-delete-admin'])){		
	
    if (isset($_POST['id'])) {
        $id = $_POST['id'];
        $flag = $function->deleteAdmin($id);
        if ($flag == 1) {
            $_SESSION["msg"] = "<div style='background-color: #9fdf9f; color:black; border: solid #9fdf9f 1px; border-radius: 5px; padding: 10px;'><center></i> Admin has been deleted </center> </div><br><script>
            setTimeout(function() {
                window.location.href = 'admin.php';
            }, 1000); // 1000 milliseconds = 1 second
        </script>";
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center></i> Something went wrong </center> </div><br>";
        }
        } else {
            $_SESSION["msg"] = "<div style='background-color: #ED4337; color:white; border: solid #ED4337 1px; border-radius: 5px; padding: 10px;'><center></i> Invalid request </center> </div><br>";
        }
    header("Location: admin/admin.php");
}

?>
