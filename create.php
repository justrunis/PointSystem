<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $surename = $points = "";
$name_err = $surename_err = $points_err = "";
 
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
    
    // Validate surename
    $input_surename = trim($_POST["surename"]);
    if(empty($input_surename)){
        $surename_err = "Please enter an surename.";     
    }
    elseif(!filter_var($input_surename, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $surename_err = "Please enter a valid surename.";
    }else{
        $surename = $input_surename;
    }
    
    // Validate points
    $input_points = trim($_POST["points"]);
    if(empty($input_points)){
        $points_err = "Please enter the points amount.";     
    } elseif(!ctype_digit($input_points)){
        $points_err = "Please enter a positive integer value.";
    } else{
        $points = $input_points;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($surename_err) && empty($points_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO students (name, surename, points) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $param_name, $param_surename, $param_points);
            
            // Set parameters
            $param_name = $name;
            $param_surename = $surename;
            $param_points = $points;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add student</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Add student</h2>
                    <p>Please fill this form and submit to add student to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Surename</label>
                            <input type="text" name="surename" class="form-control <?php echo (!empty($surename_err)) ? 'is-invalid' : ''; ?>"><?php echo $surename; ?></input>
                            <span class="invalid-feedback"><?php echo $surename_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Points</label>
                            <input type="text" name="points" class="form-control <?php echo (!empty($points_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $points; ?>">
                            <span class="invalid-feedback"><?php echo $points_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>