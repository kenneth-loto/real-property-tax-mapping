<?php

require 'PHPMailer-6.9.2/src/Exception.php';
require 'PHPMailer-6.9.2/src/PHPMailer.php';
require 'PHPMailer-6.9.2/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'conn.php';

class Functions
{
    private $db;

    public function __construct() {
        $this->db = new conn(); 
    }


    // Session

    // Function to start a session and check login status
    public function checkSession() {
        if (!isset($_SESSION['email'])) {
            header("Location: ../login.php");
            exit; // Stop further execution
        }
    }


    // FORGOT PASSWORD FUNCTIONS

    // Method to check if an email exists in the users table
    public function checkEmailExists($email) {
        $sql = 'SELECT * FROM applicants WHERE email = :email';
        $stmt = $this->db->conn->prepare($sql); // Removed $this->db->conn
        $stmt->execute([':email' => $email]);
        return $stmt->rowCount() > 0; // Returns true if the email exists
    }

    public function savePasswordResetToken($email, $token) {
        // Set the timezone to Philippines
        date_default_timezone_set('Asia/Manila');
    
        // Set expiration time to 5 minutes from the current time
        $expiresAt = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    
        // Prepare and execute the SQL statement to insert the token
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)";
        $stmt = $this->db->conn->prepare($sql);
    
        // Check for execution errors
        if (!$stmt->execute([':email' => $email, ':token' => $token, ':expires_at' => $expiresAt])) {
            // Output error information
            print_r($stmt->errorInfo());
        }
    }

    public function validateToken($token) {
        $sql = 'SELECT email FROM password_resets WHERE token = :token AND expires_at > NOW()';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':token' => $token]);
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_OBJ)->email;
        }
        return false;
    }
    

    // Method to update the password for a user
    public function updatePassword($email, $newPassword) {
        // Check password complexity
        if (!preg_match('/[A-Z]/', $newPassword) || 
            !preg_match('/[a-z]/', $newPassword) || 
            !preg_match('/[0-9]/', $newPassword) || 
            !preg_match('/[\W_]/', $newPassword) || 
            strlen($newPassword) < 8) {
            return 0; // Password does not meet complexity requirements
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = 'UPDATE applicants SET password = :password WHERE email = :email';
        
        // Ensure that $this->db->conn is included as you mentioned it was removed
        $stmt = $this->db->conn->prepare($sql);
        
        $stmt->execute([
            ':password' => $hashedPassword,
            ':email' => $email
        ]);
    }

    // Function to retrieve the current user's email
    public function getCurrentUserEmail() {
        return $_SESSION['email'] ?? null; // Return the email or null if not set
    }

    public function generatePasswordResetToken($email) {
        $token = bin2hex(random_bytes(50));
        $this->savePasswordResetToken($email, $token);
        return $token;
    }

    public function sendPasswordResetEmail($email, $token) {
        $resetLink = "localhost/Queipo/applicant/reset_password.php?token=" . $token;
        
        // Create a new PHPMailer instance
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Set your SMTP server (Gmail)
        $mail->SMTPAuth = true;
        $mail->Username = 'rptmtcs@gmail.com'; // Your Gmail address
        $mail->Password = 'rkzw dszx oqap skyk'; // Your Gmail password or App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587; // TCP port to connect to

        // Recipients
        $mail->setFrom('rptmtcs@gmail.com', 'RPTMTCS'); // Sender's email and name
        $mail->addAddress($email); // Recipient's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Click this link to reset your password: <a href='$resetLink'>$resetLink</a>";
        $mail->AltBody = "Click this link to reset your password: $resetLink"; // Plain text for non-HTML clients

        // Send the email
        return $mail->send();
    }




    //Admin

    public function createAdmin($data) {
        // Check if email already exists
        $sqlCheckEmail = "SELECT * FROM admin WHERE email = :email";
        $stmtCheckEmail = $this->db->conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->execute([':email' => $data['email']]);
    
        if ($stmtCheckEmail->rowCount() > 0) {
            return -1; // Email already exists
        }
    
        // Check if password and confirm password match
        if ($data['password'] !== $data['confirmpassword']) {
            return -2; // Passwords do not match
        }
    
        // Check password complexity
        if (!preg_match('/[A-Z]/', $data['password']) || 
            !preg_match('/[a-z]/', $data['password']) || 
            !preg_match('/[0-9]/', $data['password']) || 
            !preg_match('/[\W_]/', $data['password']) || 
            strlen($data['password']) < 8) {
            return -3; // Password does not meet complexity requirements
        }

        // Handling valid ID upload
        $valid_id = [];
        if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "assets/images/uploads/valid_id/"; // Directory to store uploaded images
            $fileName = basename($_FILES["valid_id"]["name"]);
            $targetFile = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["valid_id"]["tmp_name"], $targetFile)) {
                $valid_id[] = $targetFile; // Add file path to the valid_id array
            } else {
                echo "Sorry, there was an error uploading the file: " . $fileName;
            }
        }

        // Store file paths as a JSON array in the 'valid_id' field
        $valid_idJSON = json_encode($valid_id);
    
        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
        // Insert new user data into the database
        $sql = "INSERT INTO admin (first_name, middle_name, last_name, suffix, address, email, contact_number, valid_id, password) 
                VALUES (:first_name, :middle_name, :last_name, :suffix, :address, :email, :contact_number, :valid_id, :password)";
        
        $stmt = $this->db->conn->prepare($sql);
        $r = $stmt->execute([
            ':first_name' => $data['first_name'],
            ':middle_name' => isset($data['middle_name']) ? $data['middle_name'] : NULL,
            ':last_name' => $data['last_name'],
            ':suffix' => isset($data['suffix']) ? $data['suffix'] : NULL,
            ':address' => $data['address'],
            ':email' => $data['email'],
            ':contact_number' => $data['contact_number'],
            ':valid_id' => $valid_idJSON,
            ':password' => $hashedPassword
        ]);
    
        if ($r) {
            return 1; // Success
        } else {
            return 0; // Failure
        }
    }

    public function authenticateAdmin($email, $password) {
        // Query to retrieve the hashed password associated with the provided email
        $sql = "SELECT * FROM admin WHERE email = :email";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        // Check if the user exists
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                return 1; // Password is correct
            } else {
                return 2; // Incorrect password
            }
        } else {
            return 3; // User does not exist
        }
    }



    // Staff

    public function addStaff($data) {
        // Check if email already exists
        $sqlCheckEmail = "SELECT * FROM staff WHERE email = :email";
        $stmtCheckEmail = $this->db->conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->execute([':email' => $data['email']]);
    
        if ($stmtCheckEmail->rowCount() > 0) {
            return -1; // Email already exists
        }
    
        // Check if password and confirm password match
        if ($data['password'] !== $data['confirmpassword']) {
            return -2; // Passwords do not match
        }
    
        // Check password complexity
        if (!preg_match('/[A-Z]/', $data['password']) || 
            !preg_match('/[a-z]/', $data['password']) || 
            !preg_match('/[0-9]/', $data['password']) || 
            !preg_match('/[\W_]/', $data['password']) || 
            strlen($data['password']) < 8) {
            return -3; // Password does not meet complexity requirements
        }

         // Handling valid ID upload
        $valid_id = [];
        if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "assets/images/uploads/valid_id/staff/"; // Directory to store uploaded images
            $fileName = basename($_FILES["valid_id"]["name"]);
            $targetFile = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["valid_id"]["tmp_name"], $targetFile)) {
                $valid_id[] = $targetFile; // Add file path to the valid_id array
            } else {
                echo "Sorry, there was an error uploading the file: " . $fileName;
            }
        }

        // Store file paths as a JSON array in the 'valid_id' field
        $valid_idJSON = json_encode($valid_id);

    
        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
        // Insert new user data into the database
        $sql = "INSERT INTO staff (first_name, middle_name, last_name, suffix, province, municipality, barangay, street, email, contact_number, valid_id, password) 
                VALUES (:first_name, :middle_name, :last_name, :suffix, :province, :municipalityName, :barangayName, :street, :email, :contact_number, :valid_id, :password)";
        
        $stmt = $this->db->conn->prepare($sql);
        $r = $stmt->execute([
            ':first_name' => $data['first_name'],
            ':middle_name' => $data['middle_name'],
            ':last_name' => $data['last_name'],
            ':suffix' => $data['suffix'],
            ':province' => $data['province'],
            ':municipalityName' => $data['municipalityName'],
            ':barangayName' => $data['barangayName'],
            ':street' => $data['street'],
            ':email' => $data['email'],
            ':contact_number' => $data['contact_number'],
            ':valid_id' => $valid_idJSON,
            ':password' => $hashedPassword
        ]);
    
        if ($r) {
            return 1; // Success
        } else {
            return 0; // Failure
        }
    }

    public function addAdmin($data) {
        // Check if email already exists
        $sqlCheckEmail = "SELECT * FROM admin WHERE email = :email";
        $stmtCheckEmail = $this->db->conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->execute([':email' => $data['email']]);
    
        if ($stmtCheckEmail->rowCount() > 0) {
            return -1; // Email already exists
        }
    
        // Check if password and confirm password match
        if ($data['password'] !== $data['confirmpassword']) {
            return -2; // Passwords do not match
        }
    
        // Check password complexity
        if (!preg_match('/[A-Z]/', $data['password']) || 
            !preg_match('/[a-z]/', $data['password']) || 
            !preg_match('/[0-9]/', $data['password']) || 
            !preg_match('/[\W_]/', $data['password']) || 
            strlen($data['password']) < 8) {
            return -3; // Password does not meet complexity requirements
        }

         // Handling valid ID upload
        $valid_id = [];
        if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "assets/images/uploads/valid_id/admin/"; // Directory to store uploaded images
            $fileName = basename($_FILES["valid_id"]["name"]);
            $targetFile = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["valid_id"]["tmp_name"], $targetFile)) {
                $valid_id[] = $targetFile; // Add file path to the valid_id array
            } else {
                echo "Sorry, there was an error uploading the file: " . $fileName;
            }
        }

        // Store file paths as a JSON array in the 'valid_id' field
        $valid_idJSON = json_encode($valid_id);

    
        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
        // Insert new user data into the database
        $sql = "INSERT INTO admin (first_name, middle_name, last_name, suffix, province, municipality, barangay, street, email, contact_number, valid_id, password) 
                VALUES (:first_name, :middle_name, :last_name, :suffix, :province, :municipalityName, :barangayName, :street, :email, :contact_number, :valid_id, :password)";
        
        $stmt = $this->db->conn->prepare($sql);
        $r = $stmt->execute([
            ':first_name' => $data['first_name'],
            ':middle_name' => $data['middle_name'],
            ':last_name' => $data['last_name'],
            ':suffix' => $data['suffix'],
            ':province' => $data['province'],
            ':municipalityName' => $data['municipalityName'],
            ':barangayName' => $data['barangayName'],
            ':street' => $data['street'],
            ':email' => $data['email'],
            ':contact_number' => $data['contact_number'],
            ':valid_id' => $valid_idJSON,
            ':password' => $hashedPassword
        ]);
    
        if ($r) {
            return 1; // Success
        } else {
            return 0; // Failure
        }
    }

    public function authenticateStaff($email, $password) {
        // Query to retrieve the hashed password associated with the provided email
        $sql = "SELECT * FROM staff WHERE email = :email";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        // Check if the user exists
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                return 1; // Password is correct
            } else {
                return 2; // Incorrect password
            }
        } else {
            return 3; // User does not exist
        }
    }

    
    public function getApplicantData() {
        // Retrieve the current user's email from the session
        $user_email = $_SESSION['email'] ?? null;
    
        // Prepare the SQL query to retrieve the applicant's data based on the email
        $sql = 'SELECT name, phone_number, address, email FROM applicants WHERE email = :email';
        $stmt = $this->db->conn->prepare($sql);
        
        // Bind the email parameter to the prepared statement
        $stmt->bindParam(':email', $user_email);
        
        // Execute the query
        $stmt->execute(); 
        
        // Fetch the applicant's data as an associative array
        $data = $stmt->fetch(PDO::FETCH_ASSOC); 
        
        return $data;
    }
    
    
    

    public function getAllRequests() {
        // Prepare the SQL query to retrieve all requests along with the concatenated applicant name
        $sql = 'SELECT requests.*, 
                       CONCAT(applicants.first_name, " ", applicants.middle_name, " ", applicants.last_name, " ", applicants.suffix) AS applicant_name 
                FROM requests 
                LEFT JOIN applicants ON requests.applicant_email = applicants.email 
                ORDER BY requests.id DESC';
        
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(); // No need for parameters since we are fetching all records
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative array
        return $data;
    }
    
    public function getAllApplicants() {
        // Prepare the SQL query to retrieve all applicants with their approval/rejection status
        $sql = 'SELECT a.*, 
                       CASE 
                           WHEN b.applicant_email IS NOT NULL THEN "approved"
                           WHEN c.applicant_email IS NOT NULL THEN "rejected"
                           ELSE "pending"
                       END AS account_status
                FROM applicants a
                LEFT JOIN approved_applicants b ON a.email = b.applicant_email
                LEFT JOIN rejected_applicants c ON a.email = c.applicant_email
                ORDER BY a.created_at DESC';
        
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(); // No need for parameters since we are fetching all records
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative array
        return $data;
    }    
    

    public function getApplicantById($id) {
        // Prepare the SQL query to retrieve a single applicant by ID
        $sql = 'SELECT * FROM applicants WHERE id = :id LIMIT 1';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind the ID parameter
        $stmt->execute(); // Execute the query
    
        // Fetch the row as an associative array
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the data (or null if no applicant is found)
        return $data ? $data : null;
    }
    

    public function getAllApprovedRequests() {
        // Prepare the SQL query to retrieve all requests
        $sql = 'SELECT * FROM staff_approved_requests ORDER BY id DESC';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(); // No need for parameters since we are fetching all records
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative array
        return $data;
    } 

    public function getAllRejectedRequests() {
        // Prepare the SQL query to retrieve all requests
        $sql = 'SELECT * FROM staff_rejected_requests ORDER BY id DESC';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(); // No need for parameters since we are fetching all records
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative array
        return $data;
    } 



    // Treasurer

    public function createTreasurer($data) {
        // Check if email already exists
        $sqlCheckEmail = "SELECT * FROM treasurer WHERE email = :email";
        $stmtCheckEmail = $this->db->conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->execute([':email' => $data['email']]);
        
        if ($stmtCheckEmail->rowCount() > 0) {
            return -1; // Email already exists
        }
    
        // Check if password and confirm password match
        if ($data['password'] !== $data['confirmpassword']) {
            return -2; // Passwords do not match
        }
    
        // Check password complexity
        if (!preg_match('/[A-Z]/', $data['password']) || 
            !preg_match('/[a-z]/', $data['password']) || 
            !preg_match('/[0-9]/', $data['password']) || 
            !preg_match('/[\W_]/', $data['password']) || 
            strlen($data['password']) < 8) {
            return -3; // Password does not meet complexity requirements
        }
    
        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
         // Handling valid ID upload
         $valid_id = [];
         if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === UPLOAD_ERR_OK) {
             $targetDir = "assets/images/uploads/valid_id/"; // Directory to store uploaded images
             $fileName = basename($_FILES["valid_id"]["name"]);
             $targetFile = $targetDir . $fileName;
 
             // Move the uploaded file to the target directory
             if (move_uploaded_file($_FILES["valid_id"]["tmp_name"], $targetFile)) {
                 $valid_id[] = $targetFile; // Add file path to the valid_id array
             } else {
                 echo "Sorry, there was an error uploading the file: " . $fileName;
             }
         }
 
         // Store file paths as a JSON array in the 'valid_id' field
         $valid_idJSON = json_encode($valid_id);
    
        // Insert new user data into the database
        $sql = "INSERT INTO treasurer (first_name, middle_name, last_name, suffix, address, email, contact_number, valid_id, password) 
                VALUES (:first_name, :middle_name, :last_name, :suffix, :address, :email, :contact_number, :valid_id, :password)";
        
        $stmt = $this->db->conn->prepare($sql);
        $r = $stmt->execute([
            ':first_name' => $data['first_name'],
            ':middle_name' => isset($data['middle_name']) ? $data['middle_name'] : NULL,
            ':last_name' => $data['last_name'],
            ':suffix' => isset($data['suffix']) ? $data['suffix'] : NULL,
            ':address' => $data['address'],
            ':email' => $data['email'],
            ':contact_number' => $data['contact_number'],
            ':valid_id' => $valid_idJSON, // Store the file path
            ':password' => $hashedPassword
        ]);
    
        if ($r) {
            return 1; // Success
        } else {
            return 0; // Failure
        }
    }    

    public function authenticateTreasurer($email, $password) {
        // Query to retrieve the hashed password associated with the provided email
        $sql = "SELECT * FROM treasurer WHERE email = :email";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':email' => $email]);

        // Check if the user exists
        if ($stmt->rowCount() == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                return 1; // Password is correct
            } else {
                return 2; // Incorrect password
            }
        } else {
            return 3; // User does not exist
        }
    }

    

    //Applicants

    public function createApplicant($data) {
        // Check if email already exists
        $sqlCheckEmail = "SELECT * FROM applicants WHERE email = :email";
        $stmtCheckEmail = $this->db->conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->execute([':email' => $data['email']]);
    
        if ($stmtCheckEmail->rowCount() > 0) {
            return -1; // Email already exists
        }
    
        // Check if password and confirm password match
        if ($data['password'] !== $data['confirmpassword']) {
            return -2; // Passwords do not match
        }
    
        // Check password complexity
        if (!preg_match('/[A-Z]/', $data['password']) || 
            !preg_match('/[a-z]/', $data['password']) || 
            !preg_match('/[0-9]/', $data['password']) || 
            !preg_match('/[\W_]/', $data['password']) || 
            strlen($data['password']) < 8) {
            return -3; // Password does not meet complexity requirements
        }

         // Handling valid ID upload
        $valid_id = [];
        if (isset($_FILES['valid_id']) && $_FILES['valid_id']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "assets/images/uploads/valid_id/"; // Directory to store uploaded images
            $fileName = basename($_FILES["valid_id"]["name"]);
            $targetFile = $targetDir . $fileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["valid_id"]["tmp_name"], $targetFile)) {
                $valid_id[] = $targetFile; // Add file path to the valid_id array
            } else {
                echo "Sorry, there was an error uploading the file: " . $fileName;
            }
        }

        // Store file paths as a JSON array in the 'valid_id' field
        $valid_idJSON = json_encode($valid_id);

    
        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    
        // Insert new user data into the database
        $sql = "INSERT INTO applicants (first_name, middle_name, last_name, suffix, province, municipality, barangay, street, email, contact_number, valid_id, password) 
                VALUES (:first_name, :middle_name, :last_name, :suffix, :province, :municipalityName, :barangayName, :street, :email, :contact_number, :valid_id, :password)";
        
        $stmt = $this->db->conn->prepare($sql);
        $r = $stmt->execute([
            ':first_name' => $data['first_name'],
            ':middle_name' => $data['middle_name'],
            ':last_name' => $data['last_name'],
            ':suffix' => $data['suffix'],
            ':province' => $data['province'],
            ':municipalityName' => $data['municipalityName'],
            ':barangayName' => $data['barangayName'],
            ':street' => $data['street'],
            ':email' => $data['email'],
            ':contact_number' => $data['contact_number'],
            ':valid_id' => $valid_idJSON,
            ':password' => $hashedPassword
        ]);
    
        if ($r) {
            return 1; // Success
        } else {
            return 0; // Failure
        }
    }

    public function authenticateApplicant($email, $password) {
        // Query to check if the email exists in the approved_applicants table
        $sqlCheckApproved = "SELECT * FROM approved_applicants WHERE applicant_email = :email";
        $stmtCheckApproved = $this->db->conn->prepare($sqlCheckApproved);
        $stmtCheckApproved->execute([':email' => $email]);
    
        // Check if the account is approved
        if ($stmtCheckApproved->rowCount() === 0) {
            return 4; // Account is not approved
        }
    
        // Query to retrieve the hashed password associated with the provided email in the applicants table
        $sql = "SELECT * FROM applicants WHERE email = :email";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
    
        // Check if the user exists
        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password'];
    
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                return 1; // Password is correct
            } else {
                return 2; // Incorrect password
            }
        } else {
            return 3; // User does not exist
        }
    }    

    public function isAccountApproved($email) {
        $sql = "SELECT * FROM approved_applicants WHERE applicant_email = :email";
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute([':email' => $email]);
    
        return $stmt->rowCount() > 0; // Returns true if account is approved, false otherwise
    }
    

    
    
    public function deleteApplicant($id){
		$sql = 'DELETE FROM applicants WHERE id=:id';
		$stmt = $this->db->conn->prepare($sql);
		$r = $stmt->execute([':id' => $id]);
		if($r){
			return 1;
		}else{
			return 0;
		}
	}
    
    public function deleteAdmin($id){
		$sql = 'DELETE FROM admin WHERE id=:id';
		$stmt = $this->db->conn->prepare($sql);
		$r = $stmt->execute([':id' => $id]);
		if($r){
			return 1;
		}else{
			return 0;
		}
	}
    

    public function deleteRequest($id){
		$sql = 'DELETE FROM requests WHERE id=:id';
		$stmt = $this->db->conn->prepare($sql);
		$r = $stmt->execute([':id' => $id]);
		if($r){
			return 1;
		}else{
			return 0;
		}
	}


    
    


    
 
    
    
    public function updateRequestStatus($id, $status) {
        // Prepare the SQL query to update the request status
        $sql = 'UPDATE requests SET status = :status WHERE id = :id';
        $stmt = $this->db->conn->prepare($sql);
        
        // Execute the statement with parameters
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }    

    public function fetchTotalRequestsOfApplicants() {
        // Prepare the SQL query to to retrieve all requests
        $query = "SELECT COUNT(*) AS total_requests FROM requests ORDER BY id DESC";
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total_requests'];
    }
    
    public function fetchApprovedRequests() {
        // Get the current user's email
        $email = $this->getCurrentUserEmail();
        
        // Prepare the SQL query to fetch all approved requests by the user's email
        $query = "SELECT * FROM requests WHERE status = 'approved' AND email = :email";
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute([':email' => $email]);
        
        // Fetch all rows that match the query
        $approvedRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $approvedRequests;
    }

    public function fetchApprovedTransactionHistory() {
        // Get the current user's email
        $email = $this->getCurrentUserEmail();
    
        // Prepare the SQL query to retrieve requests by the user's email
        $query = "SELECT * FROM approved_requests WHERE email = :email ORDER BY created_at DESC"; // Assuming you have a created_at column
        $stmt = $this->db->conn->prepare($query);
        $stmt->execute([':email' => $email]);
    
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the results as an associative array
    }

    


// APPLICANT THINGS

public function applicantAddRequests($data) {
    // Check if combination of TD Number and PIN already exists
    $sqlCheckUnique = "SELECT * FROM requests WHERE td_number = :td_number AND pin = :pin";
    $stmtCheckUnique = $this->db->conn->prepare($sqlCheckUnique);
    $stmtCheckUnique->execute([':td_number' => $data['td_number'], ':pin' => $data['pin']]);
    
    if ($stmtCheckUnique->rowCount() > 0) {
        return 2;
    }

    // Load property coordinates from JSON file
    $propertyCoordinates = json_decode(file_get_contents('assets/json/properties_coordinates.json'), true);
    
    // Find selected property coordinates based on the property name
    $coordinates = null;
    foreach ($propertyCoordinates as $property) {
        if ($property['name'] === $data['selected_property']) {
            $coordinates = $property['coordinates'];
            break;
        }
    }

    if (!$coordinates) {
        return "Error: Property coordinates not found.";
    }

    // Construct JSON data for selected property with user-defined name
    $selected_property = json_encode([
        'name' => $data['selected_property'],  // Use user's input for the property name
        'type' => $data['class'],  // Use class as type
        'coordinates' => $coordinates
    ]);

    // Handle multiple image uploads
    $documents = [];
    if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
        $targetDir = "assets/images/uploads/documents/";

        foreach ($_FILES['documents']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['documents']['error'][$key] === UPLOAD_ERR_OK) {
                $fileName = basename($_FILES["documents"]["name"][$key]);
                $targetFile = $targetDir . $fileName;

                if (move_uploaded_file($tmpName, $targetFile)) {
                    $documents[] = $targetFile;
                } else {
                    echo "Sorry, there was an error uploading file: " . $fileName;
                }
            }
        }
    }
    
    // Store file paths as JSON array in 'documents'
    $documentsJSON = json_encode($documents);

    // Insert new request, now including applicant_email instead of name, email, and phone_number
    $sql = "INSERT INTO requests (applicant_email, td_number, pin, province, municipality, barangay, street, selected_property, lot_number, area, class, documents, updated_at) 
            VALUES (:email, :td_number, :pin, :province, :municipalityName, :barangayName, :street, :selected_property, :lot_number, :area, :class, :documents, NOW())";

    $stmt = $this->db->conn->prepare($sql);
    $r = $stmt->execute([
        ':email' => $data['email'],  // Applicant's ID
        ':td_number' => $data['td_number'],
        ':pin' => $data['pin'],
        ':province' => $data['province'],
        ':municipalityName' => $data['municipalityName'],
        ':barangayName' => $data['barangayName'],
        ':street' => $data['street'],
        ':selected_property' => $selected_property,  // Save as JSON
        ':lot_number' => $data['lot_number'],
        ':area' => $data['area'],
        ':class' => $data['class'],
        ':documents' => $documentsJSON
    ]);
    
    // Return specific codes for success or failure
    if ($r) {
        return 1; // Success
    } else {
        return 0; // Failure
    }
}

public function applicantGetRequests() {
    // Get the current user's email
    $email = $this->getCurrentUserEmail();

    // Prepare the SQL query to retrieve requests by the user's email, sorted by status
    $sql = '
        SELECT * FROM requests 
        WHERE applicant_email = :applicant_email 
        ORDER BY 
            CASE 
                WHEN status = "Pending" THEN 1 
                WHEN status = "Staff Approved" THEN 2
                WHEN status = "Treasurer Approved" THEN 3
                WHEN status = "Admin Approved" THEN 4
                WHEN status = "Staff Rejected" THEN 5
                WHEN status = "Admin Rejected" THEN 6
                ELSE 7 -- Any unknown statuses will be placed last
            END, id DESC'; // Maintain order by ID for consistency
    
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':applicant_email' => $email]);
    $data = $stmt->fetchAll();
    
    return $data;
}





public function applicantUpdateRequest($data, $id) {
    // Fetch the current user's email, td_number, pin, status, and other necessary fields
    $sqlGetCurrentData = "SELECT email, td_number, pin, province, municipality, barangay, documents, status FROM requests WHERE id = :id";
    $stmtGetCurrentData = $this->db->conn->prepare($sqlGetCurrentData);
    $stmtGetCurrentData->execute([':id' => $id]);
    $currentData = $stmtGetCurrentData->fetch(PDO::FETCH_ASSOC);

    // Check and delete from specific tables if the status is rejected
    if ($currentData['status'] === 'Admin Rejected') {
        // Delete from admin_rejected_requests table
        $sqlDeleteAdminRejected = "DELETE FROM admin_rejected_requests WHERE request_id = :id";
        $stmtDeleteAdminRejected = $this->db->conn->prepare($sqlDeleteAdminRejected);
        $stmtDeleteAdminRejected->execute([':id' => $id]);
        
        // Delete from treasurer_approved_requests table
        $sqlDeleteTreasurerApproved = "DELETE FROM treasurer_paid_requests WHERE request_id = :id";
        $stmtDeleteTreasurerApproved = $this->db->conn->prepare($sqlDeleteTreasurerApproved);
        $stmtDeleteTreasurerApproved->execute([':id' => $id]);

        // Delete from staff_approved_requests table
        $sqlDeleteStaffApproved = "DELETE FROM staff_approved_requests WHERE request_id = :id";
        $stmtDeleteStaffApproved = $this->db->conn->prepare($sqlDeleteStaffApproved);
        $stmtDeleteStaffApproved->execute([':id' => $id]);
    } elseif ($currentData['status'] === 'Staff Rejected') {
        $sqlDeleteStaffRejected = "DELETE FROM staff_rejected_requests WHERE request_id = :id";
        $stmtDeleteStaffRejected = $this->db->conn->prepare($sqlDeleteStaffRejected);
        $stmtDeleteStaffRejected->execute([':id' => $id]);
    }

    // Check if the provided email is different from the current email
    if ($data['email'] !== $currentData['email']) {
        $sqlCheckEmail = "SELECT * FROM requests WHERE email = :email";
        $stmtCheckEmail = $this->db->conn->prepare($sqlCheckEmail);
        $stmtCheckEmail->execute([':email' => $data['email']]);
    
        if ($stmtCheckEmail->rowCount() > 0) {
            return 2; // Duplicate email found
        }
    }

    // Check if the TD Number and PIN combination is unique, excluding the current record
    if ($data['td_number'] !== $currentData['td_number'] || $data['pin'] !== $currentData['pin']) {
        $sqlCheckUniqueTDandPIN = "SELECT * FROM requests WHERE td_number = :td_number AND pin = :pin AND id != :id";
        $stmtCheckUniqueTDandPIN = $this->db->conn->prepare($sqlCheckUniqueTDandPIN);
        $stmtCheckUniqueTDandPIN->execute([
            ':td_number' => $data['td_number'],
            ':pin' => $data['pin'],
            ':id' => $id
        ]);

        if ($stmtCheckUniqueTDandPIN->rowCount() > 0) {
            return 3; // Duplicate TD Number and PIN found
        }
    }

    // Prepare the update SQL query including status and updated_at
    $sql = 'UPDATE requests SET 
                applicant_email = :email, 
                phone_number = :phone_number,
                td_number = :td_number, 
                pin = :pin, 
                province = :provinceName, 
                municipality = :municipalityName, 
                barangay = :barangayName, 
                street = :street, 
                lot_number = :lot_number, 
                area = :area,  
                class = :class,
                status = \'Pending\',  -- Set status to "Pending"
                updated_at = NOW()     -- Set updated_at to the current timestamp
            WHERE id = :id';

    // Set province, municipality, and barangay values conditionally
    $provinceName = !empty($data['provinceName']) ? $data['provinceName'] : $currentData['province'];
    $municipalityName = !empty($data['municipalityName']) ? $data['municipalityName'] : $currentData['municipality'];
    $barangayName = !empty($data['barangayName']) ? $data['barangayName'] : $currentData['barangay'];

    // Prepare and execute the statement
    $stmt = $this->db->conn->prepare($sql);
    $r = $stmt->execute([
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':phone_number' => $data['phone_number'],
        ':td_number' => $data['td_number'],
        ':pin' => $data['pin'],
        ':provinceName' => $provinceName,
        ':municipalityName' => $municipalityName,
        ':barangayName' => $barangayName,
        ':street' => $data['street'],
        ':lot_number' => $data['lot_number'],
        ':area' => $data['area'],
        ':class' => $data['class'],
        ':id' => $id
    ]);

    // Return specific codes for success or failure
    if ($r) {
        return 1; // Success
    } else {
        return 0; // Failure
    }
}

public function fetchAllApprovedRequests() {
    // Prepare the SQL query to retrieve the required fields from the joined tables
    $query = "
        SELECT 
            ar. payment_status,
            ar.request_id AS request_id,  -- Assuming this is the ID from the admin_approved_requests table
            r.td_number, 
            r.pin, 
            CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name, ' ', a.suffix) AS name, 
            r.class, 
            r.province, 
            r.municipality, 
            r.barangay, 
            r.street, 
            r.area,
            s.market_value, 
            s.assessed_value, 
            s.basic_tax, 
            s.sef, 
            s.tax_due,
            ar.approved_at  -- Assuming this field exists to track the approval date
        FROM 
            admin_approved_requests ar
        INNER JOIN 
            requests r ON ar.request_id = r.id  -- Assuming request_id links to the requests table
        INNER JOIN 
            applicants a ON r.applicant_email = a.email  -- Assuming applicant_email links to the applicants table
        INNER JOIN 
            staff_approved_requests s ON r.id = s.request_id  -- Assuming request_id links to the staff_approved_requests table
        WHERE 
            s.status = 'Approved'  -- Filter to include only approved requests
        ORDER BY 
            ar.approved_at DESC";  // Sort by the approval date

    $stmt = $this->db->conn->prepare($query); // Prepare the query using the database connection
    $stmt->execute(); // Execute the query

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array
}





public function fetchAdminApprovedRequests() {
    // Get the current user's email
    $email = $this->getCurrentUserEmail();

    // Prepare the SQL query to retrieve admin approved requests linked through the normalized structure
    $query = "
        SELECT 
            a.id,
            a.approved_at,  -- Approved date from admin_approved_requests
            r.pin,  -- PIN from requests
            r.area,  -- Area from requests
            sa.tax_due,  -- Tax due from staff_approved_requests
            sa.market_value,  -- Market value from staff_approved_requests
            a.payment_status  -- Payment status from admin_approved_requests
        FROM admin_approved_requests a
        JOIN staff_approved_requests sa ON a.request_id = sa.request_id
        JOIN requests r ON sa.request_id = r.id
        JOIN applicants app ON r.applicant_email = app.email  -- Joining applicants to filter by email
        WHERE app.email = :email
        ORDER BY a.approved_at DESC";  // Order by approved_at in descending order

    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':email' => $email]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




public function fetchRequestStatus($requestId) {
    $statuses = [
        'requests' => null, // Updated key
        'staff_approved_requests' => null,   
        'staff_rejected_requests' => null,
        'admin_rejected_requests' => null,
        'admin_approved_requests' => null,
    ];

    // Requests
    $query = "SELECT status FROM requests WHERE id = :requestId";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':requestId' => $requestId]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $statuses['requests'] = $row['status'];
    }

    // Staff Approved Requests
    $query = "SELECT status FROM staff_approved_requests WHERE request_id = :requestId";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':requestId' => $requestId]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $statuses['staff_approved_requests'] = $row['status'];
    }

    // Admin Approved Requests
    $query = "SELECT status FROM admin_approved_requests WHERE request_id = :requestId";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':requestId' => $requestId]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $statuses['admin_approved_requests'] = $row['status'];
    }

    // Admin Rejected Requests
    $query = "SELECT status FROM admin_rejected_requests WHERE request_id = :requestId";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':requestId' => $requestId]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $statuses['admin_rejected_requests'] = $row['status'];
    }

    // Staff Rejected Requests
    $query = "SELECT status FROM staff_rejected_requests WHERE request_id = :requestId";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':requestId' => $requestId]);
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $statuses['staff_rejected_requests'] = $row['status'];
    }

    return $statuses;
}

public function getProgressBarStatus($requestId) {
    $statuses = [
        'bar1' => ['color' => 'bg-warning', 'text' => 'Pending'],
        'bar2' => ['color' => 'bg-warning', 'text' => 'Staff On Review'],
        'bar4' => ['color' => 'bg-warning', 'text' => 'Waiting Approval'],
    ];

    // Check request status
    $query = "SELECT * FROM requests WHERE id = :requestId";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':requestId' => $requestId]);
    $request = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($request) {
        $statuses['bar1'] = ['color' => 'bg-success', 'text' => 'Submitted'];

        // Staff approval or rejection
        $stmt = $this->db->conn->prepare("SELECT * FROM staff_approved_requests WHERE request_id = :requestId");
        $stmt->execute([':requestId' => $requestId]);
        if ($stmt->fetch()) {
            $statuses['bar2'] = ['color' => 'bg-success', 'text' => 'Staff Reviewed'];
        } else {
            $stmt = $this->db->conn->prepare("SELECT * FROM staff_rejected_requests WHERE request_id = :requestId");
            $stmt->execute([':requestId' => $requestId]);
            if ($stmt->fetch()) {
                $statuses['bar2'] = ['color' => 'bg-danger', 'text' => 'Staff Rejected'];
                $statuses['bar3'] = ['color' => 'bg-danger', 'text' => 'Rejected'];
                $statuses['bar4'] = ['color' => 'bg-danger', 'text' => 'Rejected'];
                return $statuses; // Exit early on rejection
            }
        }

        // Admin approval or rejection
        $stmt = $this->db->conn->prepare("SELECT * FROM admin_approved_requests WHERE request_id = :requestId");
        $stmt->execute([':requestId' => $requestId]);
        if ($stmt->fetch()) {
            $statuses['bar4'] = ['color' => 'bg-success', 'text' => 'Admin Approved'];
        } else {
            $stmt = $this->db->conn->prepare("SELECT * FROM admin_rejected_requests WHERE request_id = :requestId");
            $stmt->execute([':requestId' => $requestId]);
            if ($stmt->fetch()) {
                $statuses['bar4'] = ['color' => 'bg-danger', 'text' => 'Admin Rejected'];
            }
        }
    }

    return $statuses;
}

public function fetchTotalRequests() {
    // Get the current user's email
    $email = $this->getCurrentUserEmail();
    
    // Prepare the SQL query to count requests by the user's email
    $query = "SELECT COUNT(*) AS total_requests FROM requests WHERE applicant_email = :email"; // Ensure the correct column is used
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total_requests'] ?? 0; // Return 0 if no requests found
}


public function fetchTotalApprovedRequests() {
    // Get the current user's email
    $email = $this->getCurrentUserEmail();
    
    // Prepare the SQL query to count approved requests linked through the normalized structure
    $query = "
        SELECT COUNT(*) AS total_approved
        FROM admin_approved_requests a
        JOIN requests r ON a.request_id = r.id
        JOIN applicants app ON r.applicant_email = app.email
        WHERE a.status = 'Approved' AND app.email = :email";

    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total_approved'] ?? 0; // Return 0 if no approved requests found
}


public function fetchTotalRejectedRequests() {
    // Get the current user's email
    $email = $this->getCurrentUserEmail();
    
    // Prepare the SQL query to count rejected requests by the user's email
    $query = "SELECT COUNT(*) AS total_rejected FROM requests WHERE status IN ('Staff Rejected', 'Admin Rejected') AND applicant_email = :email";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total_rejected'] ?? 0; // Return 0 if no rejected requests found
}


public function fetchTransactionHistory() {
    // Get the current user's email
    $email = $this->getCurrentUserEmail();

    // Prepare the SQL query to retrieve requests by the user's email
    $query = "SELECT * FROM requests WHERE applicant_email = :email ORDER BY created_at DESC"; // Assuming you have a created_at column
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute([':email' => $email]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the results as an associative array
}



// Staff Things

public function getARequest($id) {
    // Prepare the SQL query to retrieve the request along with applicant's name, email, and contact number
    $sql = 'SELECT requests.*, 
                   applicants.first_name, 
                   applicants.middle_name, 
                   applicants.last_name, 
                   applicants.suffix,
                   applicants.email AS applicant_email, 
                   applicants.contact_number 
            FROM requests 
            LEFT JOIN applicants ON requests.applicant_email = applicants.email 
            WHERE requests.id = :id';
    
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_OBJ); // Fetch a single row as an object
    return $data;
}

public function getARequestsReceipt($id) {
    // Prepare the SQL query to retrieve the request along with applicant's name, email, contact number, 
    // and additional data from staff_approved_requests
    $sql = 'SELECT requests.*, 
                   applicants.first_name, 
                   applicants.middle_name, 
                   applicants.last_name, 
                   applicants.suffix,
                   applicants.email AS applicant_email, 
                   applicants.contact_number,
                   staff_approved_requests.request_id,
                   staff_approved_requests.market_value,
                   staff_approved_requests.assessment_rate,
                   staff_approved_requests.assessed_value,
                   staff_approved_requests.basic_tax,
                   staff_approved_requests.sef,
                   staff_approved_requests.tax_due
            FROM requests 
            LEFT JOIN applicants ON requests.applicant_email = applicants.email
            LEFT JOIN staff_approved_requests ON staff_approved_requests.request_id = requests.id
            WHERE requests.id = :id';

    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_OBJ); // Fetch a single row as an object
    return $data;
}



public function staffApprovedRequest($requestId, $data) {
    // Insert into staff_approved_requests table
    $sqlInsertApproved = "INSERT INTO staff_approved_requests 
                          (request_id, market_value, assessment_rate, assessed_value, basic_tax, sef, tax_due, staff_email, status, approved_at) 
                          VALUES 
                          (:request_id, :market_value, :assessment_rate, :assessed_value, :basic_tax, :sef, :tax_due, :staff_email, 'Approved', NOW())";
    
    $stmtApproved = $this->db->conn->prepare($sqlInsertApproved);
    $rApproved = $stmtApproved->execute([
        ':request_id' => $requestId,
        ':market_value' => $data['market_value'],
        ':assessment_rate' => $data['assessment_rate'],
        ':assessed_value' => $data['assessed_value'],
        ':basic_tax' => $data['basic_tax'],
        ':sef' => $data['sef'],
        ':tax_due' => $data['tax_due'],
        ':staff_email' => $data['staff_email']
    ]);

    if ($rApproved) {
        // Update the status in the requests table
        $sqlUpdateRequest = "UPDATE requests SET status = 'Staff Approved' WHERE id = :id";
        $stmtUpdateRequest = $this->db->conn->prepare($sqlUpdateRequest);
        $stmtUpdateRequest->execute([':id' => $requestId]);

        // Retrieve applicant's name and selected_property JSON from requests table
        $sqlSelectRequest = "SELECT a.first_name, a.middle_name, a.last_name, a.suffix, r.class, r.selected_property, r.lot_number 
                             FROM requests r
                             JOIN applicants a ON r.applicant_email = a.email
                             WHERE r.id = :id";
        $stmtSelectRequest = $this->db->conn->prepare($sqlSelectRequest);
        $stmtSelectRequest->execute([':id' => $requestId]);
        $requestDetails = $stmtSelectRequest->fetch();

        // Concatenate full name from applicant's details
        $fullName = trim($requestDetails['first_name'] . ' ' . $requestDetails['middle_name'] . ' ' . $requestDetails['last_name'] . ' ' . $requestDetails['suffix']);

        // Decode selected_property JSON to extract coordinates
        $selectedProperty = json_decode($requestDetails['selected_property'], true);
        $propertyType = $requestDetails['class'];
        $propertyCoordinates = json_encode($selectedProperty['coordinates'] ?? []);

        $lotNumber = $requestDetails['lot_number'];

        // Insert or update the properties table with client’s name as property name
        $sqlInsertProperty = "INSERT INTO properties (request_id, name, type, lot_number, coordinates, status) 
                              VALUES (:request_id, :name, :type, :lot_number, :coordinates, 'Occupied')
                              ON DUPLICATE KEY UPDATE 
                              name = :name, coordinates = :coordinates, status = 'Occupied'";
        
        $stmtInsertProperty = $this->db->conn->prepare($sqlInsertProperty);
        $stmtInsertProperty->execute([
            ':request_id' => $requestId,
            ':name' => $fullName,  // Set name to client’s full name
            ':type' => $propertyType,
            ':lot_number' => $lotNumber, // Ensure this is set and added
            ':coordinates' => $propertyCoordinates
        ]);

        return 1; // Success
    } else {
        error_log("Error in staffApprovedRequest for request ID: $requestId");
        return 0; // Error
    }
}


public function getProperties($municipality) {
    // Prepare the SQL query to retrieve specific fields from the requests table
    $sql = 'SELECT id, request_id, name, type, coordinates, status, payment_status FROM requests WHERE municipality = :municipality';
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':municipality' => $municipality]); // Use the municipality parameter to fetch the specific properties
    $properties = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative arrays
    return $properties;
}


public function approveApplicant($id, $data) {
    // Fetch the applicant's email based on the request ID
    $sqlFetchEmail = "SELECT email FROM applicants WHERE id = :id";
    $stmtFetchEmail = $this->db->conn->prepare($sqlFetchEmail);
    $stmtFetchEmail->execute([':id' => $id]);
    $applicant = $stmtFetchEmail->fetch();

    if (!$applicant) {
        error_log("Applicant not found for request ID: $id");
        return 0; // Error if applicant email not found
    }

    // Insert into approved_applicants table
    $sqlInsertApproved = "INSERT INTO approved_applicants 
                          (applicant_email, feedback, admin_email, approved_at) 
                          VALUES 
                          (:applicant_email, :feedback, :admin_email, NOW())";

    $stmtApproved = $this->db->conn->prepare($sqlInsertApproved);
    $stmtApproved->execute([
        ':applicant_email' => $applicant['email'],
        ':feedback' => $data['feedback'],
        ':admin_email' => $data['admin_email']
    ]);

    // Send an approval notification email using PHPMailer
    $this->sendApprovalEmail($applicant['email']);
    return 1; // Success
}

public function sendApprovalEmail($email) {

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rptmtcs@gmail.com';
        $mail->Password   = 'rkzw dszx oqap skyk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('rptmtcs@gmail.com', 'RPTMTCS');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Approved';
        $mail->Body    = "<p>Dear Applicant,</p>
                          <p>Congratulations! Your account has been approved.</p>
                          <p>Best regards,<br>RPTMTCS</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}");
    }
}



public function rejectApplicant($id, $data) {
    // Fetch the applicant's email based on the request ID
    $sqlFetchEmail = "SELECT email FROM applicants WHERE id = :id";
    $stmtFetchEmail = $this->db->conn->prepare($sqlFetchEmail);
    $stmtFetchEmail->execute([':id' => $id]);
    $applicant = $stmtFetchEmail->fetch();

    if (!$applicant) {
        error_log("Applicant not found for request ID: $id");
        return 0; // Error if applicant email not found
    }

    // Insert into rejected_applicants table
    $sqlInsertRejected = "INSERT INTO rejected_applicants 
                          (applicant_email, rejection_reason, feedback, admin_email, rejected_at) 
                          VALUES 
                          (:email, :rejection_reason, :feedback, :admin_email, NOW())";

    $stmtRejected = $this->db->conn->prepare($sqlInsertRejected);
    $stmtRejected->execute([
        ':email' => $applicant['email'],
        ':rejection_reason' => $data['rejection_category'],
        ':feedback' => $data['feedback'],
        ':admin_email' => $data['admin_email']
    ]);

    // Send an approval notification email using PHPMailer
    $this->sendRejectionEmail($applicant['email']);
    return 1; // Success
}

public function sendRejectionEmail($email) {

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rptmtcs@gmail.com';
        $mail->Password   = 'rkzw dszx oqap skyk';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('rptmtcs@gmail.com', 'RPTMTCS');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Account Rejected';
        $mail->Body    = "<p>Dear Applicant,</p>
                            <p>We regret to inform you that your account application has been rejected. Please contact us for further information.</p>
                            <p>Best regards,<br>RPTMTCS</p>";

        $mail->send();
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}");
    }
}




public function staffRejectedRequest($requestId, $data) {
    // Insert into staff_rejected_requests
    $sqlInsertRejected = "INSERT INTO staff_rejected_requests 
                          (request_id, rejection_category, feedback, document_status, staff_email, rejected_at) 
                          VALUES 
                          (:request_id, :rejection_category, :feedback, :document_status, :staff_email, NOW())";

    // Prepare the statement
    $stmtRejected = $this->db->conn->prepare($sqlInsertRejected);

    // Execute the insert statement
    $rRejected = $stmtRejected->execute([
        ':request_id' => $requestId,
        ':rejection_category' => $data['rejection_category'],
        ':feedback' => $data['feedback'],
        ':document_status' => $data['document_status'],
        ':staff_email' => $data['staff_email']
    ]);

    if ($rRejected) {
        // Optionally update the status in the requests table
        $sqlUpdateRequest = "UPDATE requests SET status = 'Staff Rejected' WHERE id = :id";
        $stmtUpdateRequest = $this->db->conn->prepare($sqlUpdateRequest);
        $stmtUpdateRequest->execute([':id' => $requestId]);

        return 1; // Success
    } else {
        error_log("Error inserting into staff_rejected_requests for request ID: $requestId");
        return 0; // Error
    }
}


public function staffCreatedRequest($data) {

    // Check if combination of TD Number and PIN already exists
    $sqlCheckUnique = "SELECT * FROM requests WHERE td_number = :td_number AND pin = :pin";
    $stmtCheckUnique = $this->db->conn->prepare($sqlCheckUnique);
    $stmtCheckUnique->execute([':td_number' => $data['td_number'], ':pin' => $data['pin']]);
    
    if ($stmtCheckUnique->rowCount() > 0) {
        return "A request with this TD Number and PIN combination already exists.";
    }

    // Handling multiple image uploads
    $newDocuments = [];
    if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
        $targetDir = "assets/images/uploads/documents/"; // Directory to store uploaded images

        foreach ($_FILES['documents']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['documents']['error'][$key] === UPLOAD_ERR_OK) {
                // Sanitize file name
                $fileName = basename($_FILES["documents"]["name"][$key]);
                $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $fileName); // Sanitize file name
                $targetFile = $targetDir . $fileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $newDocuments[] = $targetFile; // Add new file path to the array
                } else {
                    error_log("Error uploading file: " . $fileName);
                }
            }
        }
    }

    // Store file paths as a JSON array in the 'documents' field
    $documentsJSON = json_encode($newDocuments);

    // Insert data into the requests table
    $sqlInsertRequest = "INSERT INTO requests (name, email, phone_number, td_number, pin, province, municipality, barangay, street, lot_number, area, market_value, class, documents, status, created_at) 
                         VALUES (:name, :email, :phone_number, :td_number, :pin, :province, :municipality, :barangay, :street, :lot_number, :area, :market_value, :class, :documents, 'Staff Approved', NOW())";

    $stmtRequest = $this->db->conn->prepare($sqlInsertRequest);
    $rRequest = $stmtRequest->execute([
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':phone_number' => $data['phone_number'],
        ':td_number' => $data['td_number'],
        ':pin' => $data['pin'],
        ':province' => $data['province'],
        ':municipality' => $data['municipality'],
        ':barangay' => $data['barangay'],
        ':street' => $data['street'],
        ':lot_number' => $data['lot_number'],
        ':area' => $data['area'],
        ':market_value' => $data['market_value'],
        ':class' => $data['class'],
        ':documents' => $documentsJSON
    ]);

    if ($rRequest) {
        // Get the last inserted request ID
        $requestId = $this->db->conn->lastInsertId();

        // Insert data into staff_approved_requests using the request ID
        $sqlInsertApproved = "INSERT INTO staff_approved_requests (request_id, market_value, assessed_value, basic_tax, sef, tax_due, staff_email, name, email, phone_number, td_number, pin, province, municipality, barangay, street, lot_number, area, class, documents, approved_at) 
                              VALUES (:request_id, :market_value, :assessed_value, :basic_tax, :sef, :tax_due, :staff_email, :name, :email, :phone_number, :td_number, :pin, :province, :municipality, :barangay, :street, :lot_number, :area, :class, :documents, NOW())";

        $stmtApproved = $this->db->conn->prepare($sqlInsertApproved);
        $rApproved = $stmtApproved->execute([
            ':request_id' => $requestId,
            ':market_value' => $data['market_value'],
            ':assessed_value' => $data['assessed_value'],
            ':basic_tax' => $data['basic_tax'],
            ':sef' => $data['sef'],
            ':tax_due' => $data['tax_due'],
            ':staff_email' => $data['staff_email'],
            ':name' => $data['name'],
            ':email' => $data['email'],
            ':phone_number' => $data['phone_number'],
            ':td_number' => $data['td_number'],
            ':pin' => $data['pin'],
            ':province' => $data['province'],
            ':municipality' => $data['municipality'],
            ':barangay' => $data['barangay'],
            ':street' => $data['street'],
            ':lot_number' => $data['lot_number'],
            ':area' => $data['area'],
            ':class' => $data['class'],
            ':documents' => $documentsJSON
        ]);

        if ($rApproved) {
            return 1; // Success
        } else {
            error_log("Error in staffApprovedRequest for request ID: $requestId");
            return 0; // Error
        }
    } else {
        error_log("Error inserting into requests table.");
        return 0; // Error
    }
}


public function getStaffApprovedRequests($id) {
    // Prepare the SQL query to retrieve the reviewed request by ID
    $sql = 'SELECT * FROM staff_approved_requests WHERE request_id = :id LIMIT 1'; 
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]); // Use the ID parameter to fetch the specific reviewed request
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row as an associative array
    return $data;
}

public function getStaffRejectedRequests($id) {
    // Prepare the SQL query to retrieve the reviewed request by ID
    $sql = 'SELECT * FROM staff_rejected_requests WHERE request_id = :id LIMIT 1'; 
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]); // Use the ID parameter to fetch the specific reviewed request
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row as an associative array
    return $data;
}

public function fetchTotalStaffApprovedRequests() {
    // Prepare the SQL query to to retrieve all requests
    $query = "SELECT COUNT(*) AS total_requests FROM staff_approved_requests ORDER BY id DESC";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total_requests'];
}

public function fetchTotalStaffRejectedRequests() {
    // Prepare the SQL query to to retrieve all requests
    $query = "SELECT COUNT(*) AS total_requests FROM staff_rejected_requests ORDER BY id DESC";
    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row['total_requests'];
}

public function fetchStaffTransactionHistory() {
    $query = "
        (SELECT sr.id AS transaction_id, sr.request_id, 
            CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name, ' ', a.suffix) AS full_name, 
            r.td_number, r.pin, r.province, r.municipality, r.barangay, r.street, sr.status, sr.approved_at AS transaction_date 
         FROM staff_approved_requests sr
         JOIN requests r ON sr.request_id = r.id
         JOIN applicants a ON r.applicant_email = a.email)  -- Assuming applicant_id links requests to applicants
        UNION ALL
        (SELECT sr.id AS transaction_id, sr.request_id, 
            CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name, ' ', a.suffix) AS full_name, 
            r.td_number, r.pin, r.province, r.municipality, r.barangay, r.street, sr.status, sr.rejected_at AS transaction_date 
         FROM staff_rejected_requests sr
         JOIN requests r ON sr.request_id = r.id
         JOIN applicants a ON r.applicant_email = a.email)  -- Assuming applicant_id links requests to applicants
        ORDER BY transaction_date DESC
    ";

    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the results as an associative array
}

public function fetchAdminTransactionHistory() {
    $query = "
        (SELECT ar.id AS transaction_id, ar.request_id, 
            CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name, ' ', a.suffix) AS full_name, 
            r.td_number, r.pin, r.province, r.municipality, r.barangay, r.street, ar.status, ar.approved_at AS transaction_date 
         FROM admin_approved_requests ar
         JOIN requests r ON ar.request_id = r.id
         JOIN applicants a ON r.applicant_email = a.email)  -- Assuming applicant_id links requests to applicants
        UNION ALL
        (SELECT ar.id AS transaction_id, ar.request_id, 
            CONCAT(a.first_name, ' ', a.middle_name, ' ', a.last_name, ' ', a.suffix) AS full_name, 
            r.td_number, r.pin, r.province, r.municipality, r.barangay, r.street, ar.status, ar.rejected_at AS transaction_date 
         FROM admin_rejected_requests ar
         JOIN requests r ON ar.request_id = r.id
         JOIN applicants a ON r.applicant_email = a.email)  -- Assuming applicant_id links requests to applicants
        ORDER BY transaction_date DESC
    ";

    $stmt = $this->db->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Return the results as an associative array
}



public function fetchAllStaffApprovedRequests() {
    // Prepare the SQL query to retrieve all approved requests with applicant names
    $sql = 'SELECT 
                a.first_name, a.middle_name, a.last_name, a.suffix, r.pin, sr.market_value, sr.tax_due, sr.approved_at, sr.id     
            FROM 
                staff_approved_requests sr
            JOIN 
                requests r ON sr.request_id = r.id 
            JOIN 
                applicants a ON r.applicant_email = a.email 
            ORDER BY 
                sr.id DESC';

    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute(); // Execute the statement
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative array
    return $data; // Return the data
}


public function getStaffApprovedOrRejectedRequest($id) {
    $sql = 'SELECT * FROM requests WHERE id = :id AND (status = "Staff Approved" OR status = "Staff Rejected")';
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]);
    $data = $stmt->fetch(PDO::FETCH_OBJ);
    return $data;
}

public function getAdminRejectedRequests($id) {
    // Prepare the SQL query to retrieve the reviewed request by ID for admin-rejected cases
    $sql = 'SELECT rejection_category, feedback, admin_email FROM admin_rejected_requests WHERE request_id = :id LIMIT 1';
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]); // Use the ID parameter to fetch the specific reviewed request
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row as an associative array
    return $data;
}





// Treasurer Things

public function updateAdminPaidRequest($data) {
    // Update the existing record to add missing fields and change the payment status
    $sqlUpdateAdminRequest = "UPDATE admin_approved_requests 
                              SET or_number = :or_number, 
                                  amount = :amount, 
                                  staff_email = :staff_email, 
                                  payment_status = 'Paid'
                              WHERE request_id = :request_id"; // Ensure this column exists

    $stmtUpdate = $this->db->conn->prepare($sqlUpdateAdminRequest);
    $rAdminRequest = $stmtUpdate->execute([
        ':or_number' => $data['or_number'],
        ':amount' => $data['amount'],
        ':staff_email' => $data['staff_email'],
        ':request_id' => $data['request_id'] // Ensure this key exists in $data
    ]);

    if ($rAdminRequest) {
        // Optional: Update the status in the requests table if needed
        $sqlUpdateRequest = "UPDATE requests SET status = 'Admin Approved' WHERE id = :id";
        $stmtUpdateRequest = $this->db->conn->prepare($sqlUpdateRequest);
        $stmtUpdateRequest->execute([':id' => $data['request_id']]);

        // Optional: Update the payment status in the properties table if applicable
        $sqlUpdateProperty = "UPDATE properties SET payment_status = 'Paid' WHERE request_id = :request_id";
        $stmtUpdateProperty = $this->db->conn->prepare($sqlUpdateProperty);
        $stmtUpdateProperty->execute([':request_id' => $data['request_id']]);

        return 1; // Success
    } else {
        return 0; // Error
    }
}






public function updateTreasurerPaidRequest($data) {
    // Fetch existing documents from treasurer_paid_requests
    $sqlFetchRequest = "SELECT documents FROM treasurer_paid_requests WHERE request_id = :request_id";
    $stmtFetch = $this->db->conn->prepare($sqlFetchRequest);
    $stmtFetch->execute([':request_id' => $data['request_id']]);
    $existingDocuments = $stmtFetch->fetchColumn();

    // Decode existing documents JSON to an array
    $existingDocuments = $existingDocuments ? json_decode($existingDocuments, true) : [];

    // Handling multiple new image uploads
    $newDocuments = [];
    if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
        $targetDir = "assets/images/uploads/documents/"; // Directory to store uploaded images

        foreach ($_FILES['documents']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['documents']['error'][$key] === UPLOAD_ERR_OK) {
                // Sanitize file name
                $fileName = basename($_FILES["documents"]["name"][$key]);
                $fileName = preg_replace('/[^a-zA-Z0-9-_\.]/', '', $fileName);
                $targetFile = $targetDir . $fileName;

                // Move the uploaded file to the target directory
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $newDocuments[] = $targetFile; // Add new file path to the array
                } else {
                    error_log("Error uploading file: " . $fileName);
                }
            }
        }
    }

    // Merge new documents with existing ones
    $allDocuments = array_merge($existingDocuments, $newDocuments);

    // Store file paths as a JSON array in the 'documents' field
    $documentsJSON = json_encode($allDocuments);

    // Update treasurer_paid_requests
    $sqlUpdatePaidRequest = "UPDATE treasurer_paid_requests SET 
                              name = :name, email = :email, phone_number = :phone_number, 
                              staff_email = :staff_email, td_number = :td_number, pin = :pin, 
                              province = :province, municipality = :municipality, barangay = :barangay, 
                              street = :street, lot_number = :lot_number, area = :area, 
                              market_value = :market_value, class = :class, documents = :documents, 
                              assessed_value = :assessed_value, basic_tax = :basic_tax, 
                              sef = :sef, tax_due = :tax_due, payment_amount = :payment_amount, 
                              payment_date = :payment_date, treasurer_email = :treasurer_email, 
                              payment_status = 'Paid' 
                              WHERE request_id = :request_id";

    $stmtUpdatePaidRequest = $this->db->conn->prepare($sqlUpdatePaidRequest);
    $resultUpdate = $stmtUpdatePaidRequest->execute([
        ':name' => $data['name'],
        ':email' => $data['email'],
        ':phone_number' => $data['phone_number'],
        ':staff_email' => $data['staff_email'],
        ':td_number' => $data['td_number'],
        ':pin' => $data['pin'],
        ':province' => $data['province'],
        ':municipality' => $data['municipality'],
        ':barangay' => $data['barangay'],
        ':street' => $data['street'],
        ':lot_number' => $data['lot_number'],
        ':area' => $data['area'],
        ':market_value' => $data['market_value'],
        ':class' => $data['class'],
        ':documents' => $documentsJSON, // Updated documents JSON
        ':assessed_value' => $data['assessed_value'],
        ':basic_tax' => $data['basic_tax'],
        ':sef' => $data['sef'],
        ':tax_due' => $data['tax_due'],
        ':payment_amount' => $data['payment_amount'],
        ':payment_date' => $data['payment_date'],
        ':treasurer_email' => $data['treasurer_email'],
        ':request_id' => $data['request_id']
    ]);

    if ($resultUpdate) {
        // Optionally update the status in the requests table
        $sqlUpdateRequest = "UPDATE requests SET status = 'Treasurer Approved' WHERE id = :id";
        $stmtUpdateRequest = $this->db->conn->prepare($sqlUpdateRequest);
        $stmtUpdateRequest->execute([':id' => $data['request_id']]);

        return 1; // Success
    } else {
        return 0; // Error
    }
}


public function getAllStaffApprovedRequests() {
    // Prepare the SQL query to retrieve all approved requests with additional data from the requests table
    $sql = '
        SELECT 
            sap.*,
            r.td_number
        FROM 
            staff_approved_requests sap
        LEFT JOIN 
            requests r ON sap.request_id = r.id
        ORDER BY 
            sap.id DESC
    ';
    
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute(); // Execute the query
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative array
    return $data;
}



public function getAllStaffApprovedRequestsById($id) {
    // Prepare the SQL query to retrieve the reviewed request by ID
    $sql = '
        SELECT 
            sap.*,
            a.first_name,
            a.middle_name,
            a.last_name,
            a.suffix,
            r.td_number,
            r.class
        FROM 
            staff_approved_requests sap
        LEFT JOIN 
            requests r ON sap.request_id = r.id    -- Join requests using request_id
        LEFT JOIN 
            applicants a ON r.applicant_email = a.email      -- Join applicants using email
        WHERE 
            sap.request_id = :id 
        LIMIT 1
    '; 
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]);

    if ($stmt->rowCount() > 0) { // Check if any row was returned
        return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row as an associative array
    } else {
        return null; // Return null if no request found
    }
}

public function getApprovedRequestDetails($id) {
    // Prepare the SQL query to retrieve all necessary details in one go
    $sql = 'SELECT 
                a.first_name,
                a.middle_name,
                a.last_name,
                a.suffix,
                a.email,
                r.td_number,
                r.pin,
                r.province,
                r.municipality,
                r.barangay,
                r.street,
                r.lot_number,
                r.area,
                r.class,
                r.documents,
                sa.market_value,
                sa.basic_tax,
                sa.sef,
                sa.assessed_value,
                sa.tax_due,
                sa.staff_email
            FROM 
                staff_approved_requests sa
            JOIN 
                requests r ON sa.request_id = r.id
            JOIN 
                applicants a ON r.applicant_email = a.email
            WHERE 
                r.id = :id';
    
    $stmt = $this->db->conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind the request ID to the query
    $stmt->execute(); 
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the result as an associative array
    
    return $data;
}




public function getAllPaidRequests($id) {
    // Prepare the SQL query to retrieve data from multiple tables
    $sql = 'SELECT 
                a.first_name, 
                a.last_name, 
                a.middle_name, 
                a.suffix, 
                r.td_number, 
                r.class, 
                sar.tax_due,
                tpr.paid_at,
                tpr.payment_amount,
                tpr.treasurer_email
            FROM 
                treasurer_paid_requests AS tpr
            JOIN 
                requests AS r ON tpr.request_id = r.id
            JOIN 
                applicants AS a ON r.applicant_email = a.email
            JOIN 
                staff_approved_requests AS sar ON r.id = sar.request_id
            WHERE 
                tpr.request_id = :id 
            LIMIT 1'; 

    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute([':id' => $id]); // Use the ID parameter to fetch the specific reviewed request
    $data = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single row as an associative array
    return $data;
}



public function getTreasurerStatus($requestId) {
    $query = "SELECT payment_status FROM treasurer_paid_requests WHERE request_id = :id";
    $stmt = $this->db->conn->prepare($query);
    $stmt->bindParam(':id', $requestId); // Use $requestId instead of $id
    $stmt->execute();
    
    return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch the status as an associative array
}



// Admin Thingss

public function adminApprovedRequests($data) {
    // Insert into admin_approved_requests
    $sqlInsertApprovedRequest = "INSERT INTO admin_approved_requests 
                              (request_id, admin_email, due_date, approved_at) 
                              VALUES 
                              (:request_id, :admin_email, NOW(), NOW())";

    $stmtApprovedRequest = $this->db->conn->prepare($sqlInsertApprovedRequest);
    $rApprovedRequest = $stmtApprovedRequest->execute([
        ':request_id' => $data['request_id'],
        ':admin_email' => $data['admin_email']
    ]);

    if ($rApprovedRequest) { // Check the result of the execution
        // Optionally, update the status in the requests table
        $sqlUpdateRequest = "UPDATE requests SET status = 'Admin Approved' WHERE id = :id";
        $stmtUpdateRequest = $this->db->conn->prepare($sqlUpdateRequest);
        $stmtUpdateRequest->execute([':id' => $data['request_id']]); // Use the correct request ID

        return 1; // Success
    } else {
        return 0; // Error
    }
}

public function adminRejectedRequests($data) {
    // Insert into admin_approved_requests
    $sqlInsertApprovedRequest = "INSERT INTO admin_rejected_requests 
                              (request_id, admin_email, rejection_category, feedback, rejected_at) 
                              VALUES 
                              (:request_id, :admin_email, :rejection_category, :feedback, NOW())";

    $stmtApprovedRequest = $this->db->conn->prepare($sqlInsertApprovedRequest);
    $rApprovedRequest = $stmtApprovedRequest->execute([
        ':request_id' => $data['request_id'],
        ':admin_email' => $data['admin_email'],
        ':rejection_category' => $data['rejection_category'],
        ':feedback' => $data['feedback']
    ]);

    if ($rApprovedRequest) { // Check the result of the execution
        // Optionally, update the status in the requests table
        $sqlUpdateRequest = "UPDATE requests SET status = 'Admin Rejected' WHERE id = :id";
        $stmtUpdateRequest = $this->db->conn->prepare($sqlUpdateRequest);
        $stmtUpdateRequest->execute([':id' => $data['request_id']]); // Use the correct request ID

        return 1; // Success
    } else {
        return 0; // Error
    }
}

public function getAllStaffApprovedRequestsForAdmin() {
    $sql = 'SELECT sar.request_id, sar.approved_at, req.status, 
                   CONCAT(applicants.first_name, " ", applicants.middle_name, " ", 
                          applicants.last_name, " ", applicants.suffix) AS applicant_name
            FROM staff_approved_requests sar
            LEFT JOIN requests req ON sar.request_id = req.id
            LEFT JOIN applicants ON req.applicant_email = applicants.email
            ORDER BY sar.id DESC';
    
    // Prepare the SQL statement
    $stmt = $this->db->conn->prepare($sql);
    
    // Execute the query
    $stmt->execute();
    
    // Fetch all rows as associative array
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    return $data;
}




public function fetchBarangaysReport($municipality) {
    // Prepare the SQL query to fetch barangay report data for the specified municipality
    $sql = 'SELECT r.barangay,
                   SUM(s.assessed_value) AS total_assessed_value,
                   SUM(CASE WHEN a.payment_status = "Paid" THEN a.amount ELSE 0 END) AS current_collection,
                   SUM(s.tax_due) AS target_collection
            FROM staff_approved_requests s
            JOIN requests r ON s.request_id = r.id
            LEFT JOIN admin_approved_requests a ON s.request_id = a.request_id
            WHERE r.municipality = :municipality
            GROUP BY r.barangay';
    
    $stmt = $this->db->conn->prepare($sql);
    
    // Execute the statement with the municipality parameter
    $stmt->execute([
        ':municipality' => $municipality
    ]);
    
    // Fetch all results
    $barangaysReport = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate collection efficiency for each barangay
    foreach ($barangaysReport as &$row) {
        $row['collection_efficiency'] = 0;
        if ($row['target_collection'] > 0) {
            $row['collection_efficiency'] = ($row['current_collection'] / $row['target_collection']) * 100;
        }
    }
    
    return $barangaysReport;
}

public function getAllTreasurerApprovedRequests() {
    // Prepare the SQL query to retrieve all requests
    $sql = 'SELECT * FROM treasurer_paid_requests ORDER BY id DESC';
    $stmt = $this->db->conn->prepare($sql);
    $stmt->execute(); // No need for parameters since we are fetching all records
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as associative array
    return $data;
} 

public function checkAdminRequestStatus(int $requestId): array {
    // Prepare SQL statements to check both tables
    $approvedQuery = "SELECT COUNT(*) FROM admin_approved_requests WHERE request_id = :requestId";
    $rejectedQuery = "SELECT COUNT(*) FROM admin_rejected_requests WHERE request_id = :requestId";

    // Initialize counts
    $approvedCount = 0;
    $rejectedCount = 0;

    // Check in the approved requests table
    $stmt = $this->db->conn->prepare($approvedQuery);
    $stmt->execute([':requestId' => $requestId]);
    $approvedCount = $stmt->fetchColumn();

    // Check in the rejected requests table
    $stmt = $this->db->conn->prepare($rejectedQuery);
    $stmt->execute([':requestId' => $requestId]);
    $rejectedCount = $stmt->fetchColumn();

    // Determine status based on counts
    if ($approvedCount > 0) {
        return ['color' => 'bg-success', 'text' => 'Admin Approved'];
    } elseif ($rejectedCount > 0) {
        return ['color' => 'bg-danger', 'text' => 'Admin Rejected'];
    }

    // Default status is pending
    return ['color' => 'bg-warning', 'text' => 'Pending'];
}


    public function getNameByEmail($email) {
        // Prepare the SQL query to retrieve the first, middle, last names, and suffix based on the email
        $sql = 'SELECT first_name, middle_name, last_name, suffix FROM applicants WHERE email = :email';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);  // Bind the email parameter
        $stmt->execute(); // Execute the query
        
        // Fetch the result as an associative array
        $name = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return the fetched data, or an empty string if not found
        return $name ? $name : '';
    }

    public function getAllStaff() {
        // Prepare the SQL query to retrieve all diseases
        $sql = 'SELECT * FROM staff ORDER BY first_name ASC';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(); // Execute the query
        
        // Fetch all rows as objects
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Return the fetched data
        return $data;
    }

    public function getAllAdmin() {
        // Prepare the SQL query to retrieve all diseases
        $sql = 'SELECT * FROM admin ORDER BY first_name ASC';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->execute(); // Execute the query
        
        // Fetch all rows as objects
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Return the fetched data
        return $data;
    }

    public function getAStaff($id) {
        // Prepare the SQL query to retrieve a single applicant by ID
        $sql = 'SELECT * FROM staff WHERE id = :id LIMIT 1';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind the ID parameter
        $stmt->execute(); // Execute the query

        $data = $stmt->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function getAAdmin($id) {
        // Prepare the SQL query to retrieve a single applicant by ID
        $sql = 'SELECT * FROM admin WHERE id = :id LIMIT 1';
        $stmt = $this->db->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Bind the ID parameter
        $stmt->execute(); // Execute the query

        $data = $stmt->fetch(PDO::FETCH_OBJ);

        return $data;
    }

    public function deleteStaff($id){
		$sql = 'DELETE FROM staff WHERE id=:id';
		$stmt = $this->db->conn->prepare($sql);
		$r = $stmt->execute([':id' => $id]);
		if($r){
			return 1;
		}else{
			return 0;
		}
	}
}
?>
