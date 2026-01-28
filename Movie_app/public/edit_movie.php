<?php
include "../config/db.php";
include "../includes/functions.php";

if (!isset($_SESSION['user'])) {
    redirect("login.php");
}

include "../includes/header.php";

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$message = "";

if ($id <= 0) {
    redirect("index.php");
}

$stmt = $conn->prepare("SELECT * FROM movies WHERE id=?");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $movie = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    
    if (!$movie) {
        redirect("index.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_year = date('Y');
    $title = isset($_POST["title"]) ? (string)$_POST["title"] : '';
    $year = isset($_POST["year"]) ? (int)$_POST["year"] : 0;
    $rating = isset($_POST["rating"]) ? (float)$_POST["rating"] : 0;

    // Validate year (1900 to current year)
    if ($year < 1900 || $year > $current_year) {
        $message = "Year must be between 1900 and " . $current_year . ".";
    }
    // Validate rating (less than 10)
    elseif ($rating >= 10) {
        $message = "Rating must be less than 10.";
    }
    else {
        $stmt = $conn->prepare(
            "UPDATE movies SET title=?, release_year=?, rating=? WHERE id=?"
        );
        if ($stmt) {
            $stmt->bind_param("sidi", $title, $year, $rating, $id);
            if ($stmt->execute()) {
                redirect("index.php");
            } else {
                $message = "Error updating movie. Please try again.";
            }
            $stmt->close();
        } else {
            $message = "Database error. Please try again.";
        }
    }
}
?>

<h3>Edit Movie</h3>
<?php if(!empty($message)) { echo "<p style='color:red;'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>"; } ?>

<form method="post" enctype="multipart/form-data">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($movie['title'], ENT_QUOTES, 'UTF-8') ?>" required>

    <label for="year">Year (1900 - <?= date('Y') ?>):</label>
    <input type="number" id="year" name="year" min="1900" max="<?= date('Y') ?>" value="<?= htmlspecialchars($movie['release_year'], ENT_QUOTES, 'UTF-8') ?>">

    <label for="rating">Rating (less than 10):</label>
    <input type="number" id="rating" name="rating" step="0.1" min="0" max="9.9" value="<?= htmlspecialchars($movie['rating'], ENT_QUOTES, 'UTF-8') ?>">

    <button type="submit">Update Movie</button>
</form>

<?php include "../includes/footer.php"; ?>
