<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center;} 
        .wrapper{
            width: 100%;
            margin: 0 auto;
        }
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 15px;
        }
        .glyphicon-trash{
            color: red;
        }
        .id{
            display: none;
        }
        table {
          counter-reset: section;
        }

        .count:before {
          counter-increment: section;
          content: counter(section);
        }
        .b{
            text-transform: uppercase;
        }
        .pull-right{
            margin-left: 3rem;
        }
        img{
            width: 50px;
            height: 50px;
        }
    </style>
        <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b class="b"><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    <p>
         <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="pull-left">Employees Details</h2>
                        <a href="create.php" class="btn btn-success pull-right">Add New Employee</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "config.php";
                        $useremail = $_SESSION["email"];
                        $sqll = mysqli_query($link, "SELECT * FROM auth WHERE email='$useremail'");
                        $roww = mysqli_fetch_array($sqll);
                        $id = $roww["id"];
                        $sql = "SELECT * FROM employees WHERE userid='$id'";
                    // Attempt select query execution
                    
                    // $sql = "SELECT * FROM employees INNER JOIN multiple ON multiple.insertid=employees.id AND employees.userid = $id AND multiple.userid = $id";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>No</th>";
                                        echo "<th>profile</th>";
                                        echo "<th>Name</th>";
                                        echo "<th>Address</th>";
                                        echo "<th>Salary</th>";
                                        echo "<th>Hobbies</th>";
                                        echo "<th>Phoneno</th>";
                                        echo "<th>Action</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td class='id'>" . $row['id'] . "</td>";
                                        echo "<td class='count'>    </td>";
                                        echo "<td><img src='img/". $row['uploadfile'] ."'><a href='update-profile.php?id=". $row['id'] ."' title='update profile picture'><span class='glyphicon glyphicon-user'></span></td>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['address'] . "</td>";
                                        echo "<td>" . $row['salary'] . "</td>";
                                        echo "<td>" . $row['hobbies'] . "</td>";
                                        echo "<td>" . $row['phoneno'] . "</td>";
                                        echo "<td>";
                                            echo "<a href='update-post.php?id=". $row['id'] ."' title='Add Post' data-toggle='tooltip'><span class='glyphicon glyphicon-open'></span></a>";
                                            echo "<a href='read.php?id=". $row['id'] ."' title='View Record' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>";
                                            echo "<a href='update.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>";
                                            echo "<a href='delete.php?id=". $row['id'] ."' title='Update Record' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>