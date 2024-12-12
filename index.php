<?php
// Start session
session_start();
?>

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
		<form action="fetch_result.php" method="POST" class="mt-4">
			<div class="mb-3">
				<label for="rollno" class="form-label">Roll Number</label>
				<input type="number" name="rollno" id="rollno" class="form-control" required>
			</div>
			<div class="mb-3">
				<label for="classname" class="form-label">Class Name</label>
				<select name="classname" id="classname" class="form-control" required>
					<option value="">Select Class</option>
					<?php
					// Database connection
					$servername = "localhost";
					$username = "root";
					$password = "";
					$database = "student_result_ms";
					
					$conn = new mysqli($servername, $username, $password, $database);
					
					// Fetching classes
					$sql = "SELECT ClassId, ClassName, Section FROM tblclasses WHERE Status = 1";
					$result = $conn->query($sql);
					
					// Populate the dropdown
					if ($result->num_rows > 0) {
						while($row = $result->fetch_assoc()) {
							echo "<option value='" . $row['ClassId'] . "'>" . $row['ClassName'] . " Section " . $row['Section'] . "</option>";
						}
					}
					$conn->close();
					?>
				</select>
			</div>
			<div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" name="dob" id="dob" class="form-control" required>
      </div>
			<button type="submit" class="btn btn-primary">Fetch Result</button>
		</form>
	</div>
</body>
</html>
