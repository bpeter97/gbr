<!DOCTYPE html>
<html>
<head>
	<title>Search Button Functions</title>
</head>
<body>

	<form action="view/search_results.php" method="get">
		<div class="form-group">
			<select class="form-control" id="sel1">
				<option>Choose One</option>
		    	<option>Containers</option>
		    	<option>Customers</option>
		    	<option>Quotes</option>
		    	<option>Orders</option>
			</select>
			<input type="text" class="form-control" id="usr">
		</div>
	</form>

	<?php

		include('../cfg/database.php')

		$db = new Database;
		$db->connect();

		// Check to see if a search was submitted.
		if(isset($_POST['submit'])){
			if(isset($_GET['category'])){
				$category = $_GET['category'];
				// We are going to check if the category was left as the default or selected.
				if($category = "Choose One"){
					// If not, go back with alert.
					header('Location: ' . $_SERVER['HTTP_REFERER'] . '?action=dcat');
				} else {
					// If so, then check if query is empty.
					if(isset($_GET['query'])){
						$query = $_GET['query'];
					} else {
						// If it is then go back with alert.
						header('Location: ' . $_SERVER['HTTP_REFERER'] . '?action=equery');
					}
				}
			}
			// If category and query is good to go, we need to figure out what category (table) we are checking and then search for the query.

			// We will use a switch case for this. This will reduce code clutter and let us block out each section better.

			switch ($category) {

				case 'containers':
				
					// This one needs to be done first.
					$sql="SELECT * FROM containers WHERE 
						release_number LIKE '%". $query ."%' OR
						container_size LIKE '%". $query ."%' OR
						container_serial_number LIKE '%". $query ."%' OR
						container_number LIKE '%". $query ."%' OR
						container_shelves LIKE '%". $query ."%' OR
						container_paint LIKE '%". $query ."%' OR
						container_onbox_numbers LIKE '%". $query ."%' OR
						container_signs LIKE '%". $query ."%' OR
						rental_resale LIKE '%". $query ."%' OR
						container_address LIKE '%". $query ."%' OR
						type LIKE '%". $query ."%' OR
						";
					$db->sql($sql);

					// Grab the results from the database object.
					$conres = $db->getResult();

					// Get the number of rows in the results and display that to the user.
					$connumres = $db->numRows();
					echo "There were ". $connumres ." results found.</br></br>";

					// Let's see what we get!
					echo '<pre>';
					var_dump($conres);
					echo '</pre>';

					break;
				
				case 'customers':
					// This one needs to be done first.
					break;

				case 'orders':
					// This one needs to be done first.
					break;

				case 'purchases':
					// code...
					break;

				case 'vendors':
					// code...
					break;

				case 'modifications':
					// code...
					break;

				case 'drivers':
					// code...
					break;

				case 'product_orders':
					// code...
					break;

				case 'quotes':
					// This one needs to be done first.
					break;

				case 'sales':
					// code...
					break;

				case 'taxrates':
					// code...
					break;

				case 'users':
					// code...
					break;

				case 'rentals':
					// code...
					break;

				case 'modifications':
					// code...
					break;

				default:
					echo 'There is an issue with getting the category.';
					break;
			}

		}

	?>

</body>
</html>