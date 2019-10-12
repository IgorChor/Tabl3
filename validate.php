<?php
// Include config file
require_once "conn.php";
 
// Define variables and initialize with empty values
$firstname = $lastname = $address = "";
$firstname_err = $lastname_err = $address_err = "";
 //string check
function clean($value = "") {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);
    
    return $value;
}
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_firstname = trim($_POST["firstname"]);
    if(empty($input_firstname)){
        $firstname_err = "Please enter a First name.";
    } elseif(!filter_var($input_firstname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $firstname_err = "Please enter a valid First name.";
    } else{
        $firstname = $input_firstname;
    }
    
    // Validate Secomd name
    $input_lastname = trim($_POST["lastname"]);
    if(empty($input_lastname)){
        $lastname_err = "Please enter a Second name.";     
    } elseif(!filter_var($input_lastname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $lastname_err = "Please enter a valid Second name.";
    } else{
        $lastname = $input_lastname;
    }
    
    // Validate Email
    $input_address = trim($_POST["email"]);
    if(empty($input_address)){
        $address_err = "Please enter a Email.";     
    } else
        if(!filter_var($input_address, FILTER_VALIDATE_EMAIL)){
        $address_err = "Please enter a valid Email.";
    } else{
        $address = $input_address;
    }
    
    
    
    
    // Check input errors before inserting in database
    if(empty($firstname_err) && empty($lastname_err) && empty($address_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO employees (firstname, lastmane, address) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_fistname, $param_lastname, $param_address);
            
            // Set parameters
            $param_firstname = $firstname;
            $param_lastname = $lastmane;
            $param_address = $address;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>