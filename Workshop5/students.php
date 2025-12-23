<?php include "header.php"; ?>

<h3>Student List</h3>

<?php
if (file_exists("students.txt")) {
    $lines = file("students.txt");

    foreach ($lines as $line) {
        $data = explode(",", trim($line));

        // Ensure all values exist
        $name   = $data[0] ?? "N/A";
        $email  = $data[1] ?? "N/A";
        $skills = $data[2] ?? "";

        $skillsArray = $skills ? explode("|", $skills) : [];

        echo "<p>";
        echo "<strong>Name:</strong> $name<br>";
        echo "<strong>Email:</strong> $email<br>";
        echo "<strong>Skills:</strong> " . implode(", ", $skillsArray);
        echo "</p><hr>";
    }
} else {
    echo "No students found.";
}
?>

<?php include "footer.php"; ?>
