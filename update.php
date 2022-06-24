<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $surename = $points = "";
$name_err = $surename_err = $points_err = "";
$add_points = "";
 
if(isset($_POST['Add'])) {
    $add_points = trim($_POST["Add"]);
}

if(isset($_POST['add5'])) {
    $add_points = 5;
}
if(isset($_POST['sub5'])) {
    $add_points = -5;
}

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];
    
    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Įveskite vardą";
    } else{
        $name = $input_name;
    }
    
    // Validate surename surename
    $input_surename = trim($_POST["surename"]);
    if(empty($input_surename)){
        $surename_err = "Įveskite pavardę";     
    } else{
        $surename = $input_surename;
    }
    
    // Validate points
    $input_points = trim($_POST["points"]);   
    if(!is_numeric($input_points)){
        $points_err = "Įveskite taškų kiekį";
    } else{
        $points = $input_points;
    }
	
	if(is_numeric($add_points)){
		$points = $points + $add_points;
	}
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($surename_err) && empty($points_err)){
        // Prepare an update statement
        $sql = "UPDATE students SET name=?, surename=?, points=? WHERE id=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_name, $param_surename, $param_points, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_surename = $surename;
            $param_points = $points;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Klaida.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM students WHERE id = ?";
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
                    $surename = $row["surename"];
                    $points = $row["points"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Klaida";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        //mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Redaguoti taškus</title>
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
                    <h2 class="mt-5">Redaguoti taškus</h2>
                    <p>Pasirinkite taškų vertes arba įveskite konkrečią taškų vertę</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Studento vardas</label>
                            <input readonly type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Studento pavardė</label>
                            <input readonly type="text" name="surename" class="form-control <?php echo (!empty($surename_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $surename; ?>">
                            <span class="invalid-feedback"><?php echo $surename_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Studento taškų kiekis</label>
                            <input readonly type="text" name="points" class="form-control <?php echo (!empty($points_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $points; ?>">
                            <span class="invalid-feedback"><?php echo $points_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
						
						
						<?php
						// Include config file
						require_once "config.php";
                    
						// Attempt select query execution
						$sql = "SELECT * FROM evaluations ORDER BY value DESC";
					
						if($result = mysqli_query($link, $sql)){
							if(mysqli_num_rows($result) > 0){
								echo '<div class="form-group">';
									echo '<label for="exampleFormControlSelect1">Pasirinkite įvertinimą</label>';
									echo '<select class="form-control" id="exampleFormControlSelect1" name="Add">';
										echo '<option selected></option>';
										while($row = mysqli_fetch_array($result)){
											echo "<option value=" . $row['value'] . ">" . $row['name'] . "  ( " . $row['value'] .  " )</option>";
										}
									echo "</select>";                            
								echo "</div>";
								// Free result set
								mysqli_free_result($result);
							} else{
								echo '<div class="alert alert-danger"><em>Nėra vertinimų</em></div>';
							}
						} else{
							echo "Klaida";
						}
 
						// Close connection
						mysqli_close($link);
						?>
						
						<!--
						<div class="form-group">
							<label for="exampleFormControlSelect1">Option</label>
							<select class="form-control" id="exampleFormControlSelect1" name="Add">
								<option selected>Pasirinkite priežastį</option>
								<option value=5>Įsijungęs kamerą +5</option>
								<option value=-5>Neįsijungęs kameros -5</option>
								<option value=10>Atsakytas klausimas +10</option>
								<option value=-5>Neatsakytas klausimas -5</option>
								<option value=15>Akyvumas paskaitoje +15</option>
							</select>
						</div> -->
						
                        <input type="submit" class="btn btn-primary" value="Patvirtinti">
                        <a href="index.php" class="btn btn-secondary ml-2">Atšaukti</a>
						
						<!--
						<input type="submit" name="add5"
							class="btn btn-secondary" value="+5" />
          
						<input type="submit" name="sub5"
							class="btn btn-secondary" value="-5" />
							
						-->

						
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>