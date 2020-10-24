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

//fetch user data
     $useremail = $_SESSION["email"];
    $sqll = mysqli_query($link, "SELECT * FROM auth WHERE email='$useremail'");
    $roww = mysqli_fetch_array($sqll);
    $id = $roww["id"];
// Define variables and initialize with empty values
$name = $address = $salary = $hobbies = $phoneno =  "";
$name_err = $address_err = $salary_err = $hobbies_err = $phoneno_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
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

    $a = $_POST["hobbies"];
    $input_hobbies = implode(',', $a);
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
        $sql="select * from employees where (phoneno='$phoneno');";

              ($stmtt=mysqli_query($link,$sql));

              if (mysqli_num_rows($stmtt) > 0) {
                
                $row = mysqli_fetch_assoc($stmtt);
                if($phoneno==$row['phoneno'])
                {
                    echo "<div class='alert alert-danger' role'alert'>Phone Number already Taken</div>";
                }
        }

        else{
            
            $sql = "INSERT INTO employees (name, address, salary, hobbies, phoneno, userid) VALUES (?, ?, ?, ?, ?, ?)";
                 
            if($stmt = mysqli_prepare($link, $sql)){
                    // Bind variables to the prepared statement as parameters
                    mysqli_stmt_bind_param($stmt, "ssssss", $name, $address, $salary, $hobbies, $phoneno, $id);
                        
                    // Attempt to execute the prepared statement
                    if(mysqli_stmt_execute($stmt)){
                        // Records created successfully. Redirect to landing page
                        header("location: welcome.php");
                        exit();
                    } else{
                        echo "Something Wrong try Again later";
                    }
                }
                    
        }
          
        // Close statement

        
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                        <div class="form-group <?php echo (!empty($hobbies_err)) ? 'has-error' : ''; ?>">
                            <label>Hobbies</label><br>
                                 <input type="checkbox" name="hobbies[]" value="books" />Books<br />
                            <input type="checkbox" name="hobbies[]" value="movies" />Movies<br />
                           <input type="checkbox" name="hobbies[]" value="sports" />Sports<br />
                          <input type="checkbox" name="hobbies[]" value="games" />Games<br />
                          <input type="checkbox" name="hobbies[]" value="travelling" />Travelling<br />
                            <span class="help-block"><?php echo $hobbies_err;?></span>
      
                        </div>
                        <div class="form-group <?php echo (!empty($phoneno_err)) ? 'has-error' : ''; ?>">
                            <label>Phone Number</label>
                            <input type="text" name="phoneno" class="form-control" value="<?php echo $phoneno; ?>">
                            <span class="help-block"><?php echo $phoneno_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="welcome.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>