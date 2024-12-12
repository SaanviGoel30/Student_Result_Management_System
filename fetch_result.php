<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Result Management System</title>
    <!-- Link to the external CSS file -->
    <link rel="stylesheet" href="stylesheet.css">
    <!-- Optional Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php
// Start session
session_start();
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "student_result_ms";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rollno = $_POST['rollno'];
    $classname = $_POST['classname'];
    $dob = $_POST['dob'];

    if (empty($rollno) || empty($classname) || empty($dob)) {
        echo "<div class='container mt-5'><p class='text-danger'>Invalid input provided.</p></div>";
        exit;
    }
     // Store in session variables
     $_SESSION['rollno'] = $rollno;
     $_SESSION['classname'] = $classname;
     $_SESSION['dob'] = $dob;
 }

 // Get roll ID, class ID, and DOB from session
 if (isset($_SESSION['rollno'], $_SESSION['classname'], $_SESSION['dob'])) {
    $rollno = $_SESSION['rollno'];
    $classname = $_SESSION['classname'];
    $dob = $_SESSION['dob'];
 
    

    // Prepare SQL query to fetch student details and results
    $sql = "SELECT s.StudentId, s.StudentName, c.ClassName, c.Section, sub.SubjectName, r.Marks
            FROM tblstudents s
            JOIN tblclasses c ON s.ClassId = c.ClassId
            JOIN tblresult r ON s.StudentId = r.StudentId
            JOIN tblsubjects sub ON r.SubjectId = sub.SubjectId
            WHERE s.RollId = ? AND c.ClassId = ? AND s.DOB = ? AND s.Status = 1 AND r.Status = 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $rollno, $classname, $dob);
    $stmt->execute();
    $result = $stmt->get_result();

    // Initialize variables for total marks and subject count
    $totalMarks = 0;
    $subjectCount = 0;

    // Check if results are found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $studentId = $row['StudentId'];
        $studentName = $row['StudentName'];
        $className = $row['ClassName'];
        $section = $row['Section'];

        echo "<div class='container mt-5'>";
        echo "<h2 class='text-center'>Student Result</h2>";
        echo "<div class='card p-4 mt-4'>";
        echo "<h5>Student ID: $studentId</h5>";
        echo "<h5>Name: $studentName</h5>";
        echo "<h5>Class: $className Section: $section</h5>";
        echo "<table class='table table-bordered mt-4'>";
        echo "<thead><tr><th>Subject</th><th>Marks</th></tr></thead>";
        echo "<tbody>";

        // Output each subject and marks
        do {
            echo "<tr>
                    <td>{$row['SubjectName']}</td>
                    <td>{$row['Marks']}</td>
                  </tr>";
            $totalMarks += $row['Marks'];
            $subjectCount++;
        } while ($row = $result->fetch_assoc());

        echo "</tbody></table>";

        // Calculate percentage
        $percentage = ($totalMarks / ($subjectCount * 100)) * 100;
        echo "<h5 class='mt-4'>Total Marks: $totalMarks</h5>";
        echo "<h5>Percentage: " . number_format($percentage, 2) . "%</h5>";
        echo "</div></div>";
    } else {
        echo "<div class='container mt-5'><p class='text-danger'>No results found for the given roll number and class.</p></div>";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>


	
