<!DOCTYPE html>
<html lang="lt">
<head>
    <meta charset="UTF-8">
    <title>Pagrinidinis langas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
        h1, h2{
            text-align: center;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
	
    <div class="wrapper">
        <div class="container-fluid">
		
            <div class="row">
                <div class="col-md-12">
                <h1>Studentų sužaidybinimo programa</h1>
                    <div class="mt- mb-1 clearfix">
                        <a href="create.php" class="btn btn-success ml-2 pull-right"><i class="fa fa-plus"></i> Pridėti studentą</a>
                        <a href="instructions.php" class="btn btn-warning ml-2 pull-right"> Redaguoti vertinimus</a>
                        
                    </div>
                    <h3>Paieška</h3>
					<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
						<div class="form-group">
							<div class="pull-right">
								<button type="submit" class="btn btn-secondary"><span class="fa fa-search"></span></button>
							</div>
						
							<input type="text" name="search" class="form-control" style="width:90%">
						
						</div>
					</form>
					
                    <?php
                    // Include config file
                    require_once "config.php";
                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM students ORDER BY points DESC";
					
					if(isset($_POST['search']) && !empty($_POST["search"])) {
						$sql = "SELECT * FROM students WHERE name LIKE '%" . trim($_POST["search"]) . "%' OR surename LIKE '%" . trim($_POST["search"]) . "%' ORDER BY points DESC";
					}
					
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>Vardas</th>";
                                        echo "<th>Pavardė</th>";
                                        echo "<th>Taškų kiekis</th>";
                                        echo "<th>Veiksmas</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['name'] . "</td>";
                                        echo "<td>" . $row['surename'] . "</td>";
                                        echo "<td>" . $row['points'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="update.php?id='. $row['id'] .'" class="mr-3" title="Redaguoti taškus" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                            echo '<a href="delete.php?id='. $row['id'] .'" title="Pašalinti studentą" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>Nėra studentų</em></div>';
                        }
                    } else{
                        echo "Klaida";
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