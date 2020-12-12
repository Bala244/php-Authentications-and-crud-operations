<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
// Check existence of id parameter before processing further
if(isset($_GET["id"])){
    // Include config file
    include 'config.php';
    
    // Prepare a select statement
    $sql = "SELECT * FROM employees
                        WHERE id=?";    
    if($stmt = mysqli_prepare($link, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_GET["id"]);
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                

   
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
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
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
        img{
            width: 50%;
            height: 50%;  
            margin-bottom: 3rem;      }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>

                        <img src="img/<?php echo $row["uploadfile"]; ?>" alt="">

                    <div class="form-group">
                        <label>Name</label>
                        <p class="form-control-static"><?php echo $row["name"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p class="form-control-static"><?php echo $row["address"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Salary</label>
                        <p class="form-control-static"><?php echo $row["salary"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Hobbies</label>
                        <p class="form-control-static"><?php echo $row["hobbies"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <p class="form-control-static"><?php echo $row["phoneno"]; ?></p>
                    </div>
                        <?php 
                                $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
                                $sqlll = "SELECT * FROM `multiple` WHERE insertid = $param_id";
                                $resultt = $link->query($sqlll);

                                if($resultt->num_rows > 0){
                                    while($roww = $resultt->fetch_assoc()){
                                        $imageURL = $roww["imgName"];

                              echo "<td><img src='img/". $imageURL ."'></td>";  

               }
                }
                 ?>                    
                    <p><a href="welcome.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>

</body>
</html>