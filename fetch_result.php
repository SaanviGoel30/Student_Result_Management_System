<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>Fetch Student Result</title>
	<!-- Bootstrap CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container mt-5">
		<h2 class="text-center">Fetch Student Result</h2>
		<!-- Form for fetching results -->
		<form action="" method="POST" class="mt-4">
			<div class="mb-3">
				<label for="rollno" class="form-label">Roll Number</label>
				<input type="number" name="rollno" id="rollno" class="form-control" required>
			</div>
			<div class="mb-3">
				<label for="classname" class="form-label">Class Name</label>
				<input type="text" name="classname" id="classname" class="form-control" required>
			</div>
			<button type="submit" class="btn btn-primary">Fetch Result</button>
		</form>
	</div>

	<?php
	// Database connection
	$servername = "localhost";
	$username = "root";
	$password = "";
	$database = "student_result_ms"; // Updated database name

	$conn = new mysqli($servername, $username, $password, $database);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Handle form submission
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$rollno = $_POST['rollno'];
		$classname = $_POST['classname'];

		// Prepare SQL query to fetch results
		$sql = "SELECT StudentId, Subject_Name, Marks FROM students WHERE Roll_ID = ? AND Class_Name = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("is", $rollno, $classname);
		$stmt->execute();
		$result = $stmt->get_result();

		// Check if results found
		if ($result->num_rows > 0) {
			echo "<div class='container mt-5'>";
			echo "<h3>Results for Roll No: $rollno, Class: $classname</h3>";
			echo "<table class='table table-bordered mt-3'>";
			echo "<thead><tr><th>Student ID</th><th>Subject</th><th>Marks</th></tr></thead>";
			echo "<tbody>";

			// Output each row
			while ($row = $result->fetch_assoc()) {
				echo "<tr>
						<td>{$row['StudentId']}</td>
						<td>{$row['Subject_Name']}</td>
						<td>{$row['Marks']}</td>
					  </tr>";
			}

			echo "</tbody></table>";
			echo "</div>";
		} else {
			echo "<div class='container mt-5'><p class='text-danger'>No results found for the given roll number and class.</p></div>";
		}

		// Close statement
		$stmt->close();
	}

	// Close connection
	$conn->close();
	?>
</body>
</html>

