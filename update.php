<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}   
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary = $hobbies = $phoneno = "";
$name_err = $address_err = $salary_err = $hobbies_err = $phoneno_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address address
    $input_address = trim($_POST["address"]);
    if(empty($input_address)){
        $address_err = "Please enter an address.";     
    } else{
        $address = $input_address;
    }
    
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } else{
        $salary = $input_salary;
    }
    
    $b = $_POST["hobbies"];
    $input_hobbies = implode(',', $b);
    if(empty($input_hobbies)){
        $hobbies_err = "Please enter an hobbies.";     
    } else{
        $hobbies = $input_hobbies;
    }



    // Validate phoneno
    $input_phoneno = trim($_POST["phoneno"]);
    if(empty($input_phoneno)){
        $phoneno_err = "Please enter the Phone Number.";     
    } elseif(!ctype_digit($input_salary)){
        $phoneno_err = "Please enter a positive integer value.";
    } else{
        $phoneno = $input_phoneno;
    }
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err) && empty($hobbies_err) && empty($phoneno_err)){
        // Prepare an insert statement

        $sql = "UPDATE employees SET name=?, address=?, salary=?, hobbies=?, phoneno=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_name, $param_address, $param_salary, $param_hobbies, $param_phoneno, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;
            $param_hobbies = $hobbies;
            $param_phoneno = $phoneno;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: welcome.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
        
        // Close statement

    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM employees WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $address = $row["address"];
                    $salary = $row["salary"];
                    $a = $row["hobbies"];

                    $hobbies = explode(",", $a);

                    $phoneno = $row["phoneno"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                            <span class="help-block"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Address</label>
                            <textarea name="address" class="form-control"><?php echo $address; ?></textarea>
                            <span class="help-block"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control" value="<?php echo $salary; ?>">
                            <span class="help-block"><?php echo $salary_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Hobbies</label><br>
                                 <input type="checkbox" name="hobbies[]" value="books"/ 
                                 <?php
                                   if (in_array("books", $hobbies)) {
                                       echo"checked";
                                   }
                                  ?>
                                 >Books<br />
                            <input type="checkbox" name="hobbies[]" value="movies" 
                            /
                                <?php
                                   if (in_array("movies", $hobbies)) {
                                       echo"checked";
                                   }
                                ?>
                                  >Movies<br />
                           <input type="checkbox" name="hobbies[]" value="sports" 
                           /
                           <?php
                                if (in_array("sports", $hobbies)) {
                                     echo"checked";
                                }
                            ?>
                           >Sports<br />
                          <input type="checkbox" name="hobbies[]" value="games" 
                          /
                          <?php
                            if (in_array("games", $hobbies)) {
                                echo"checked";
                            }
                            ?>
                          >Games<br />
                          <input type="checkbox" name="hobbies[]" value="travelling" 
                          /
                          <?php
                                if (in_array("travelling", $hobbies)) {
                                   echo"checked";
                               }
                            ?>
                          >Travelling<br />
                            <span class="help-block"><?php echo $hobbies_err;?></span>
      
                        </div>
                        <div class="form-group <?php echo (!empty($phoneno_err)) ? 'has-error' : ''; ?>">
                            <label>Phone Number</label>
                            <input type="text" name="phoneno" class="form-control" value="<?php echo $phoneno; ?>">
                            <span class="help-block"><?php echo $phoneno_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="welcome.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>