<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $surename = $value = "";
$name_err = $value_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Įveskite pavadinimą";
    } else{
        $name = $input_name;
    }
    
    // Validate value
    $input_value = trim($_POST["value"]);
    if(empty($input_value)){
        $value_err = "Įveskite vertę";     
    } elseif(!is_numeric($input_value)){
        $value_err = "Įveskite skaičių";
    } else{
        $value = $input_value;
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($value_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO evaluations (name, value) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_name, $param_value);
            
            // Set parameters
            $param_name = $name;
            $param_value = $value;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: instructions.php");
                exit();
            } else{
                echo "Klaida";
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
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Pridėti kriterijų</title>
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
                    <h2 class="mt-5">Naujo kriterijaus pridėjimas</h2>
                    <p>Įveskite naują vertinimo kriterijų</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Pavadinimas</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Taškų kiekis</label>
                            <input type="text" name="value" class="form-control <?php echo (!empty($value_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $value; ?>">
                            <span class="invalid-feedback"><?php echo $value_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Pridėti">
                        <a href="instructions.php" class="btn btn-secondary ml-2">Atšaukti</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>