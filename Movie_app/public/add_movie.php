<?php
include "../config/db.php";
include "../includes/functions.php";

if (!isset($_SESSION['user'])) {
    redirect("login.php");
}

include "../includes/header.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_year = date('Y');
    $title = isset($_POST["title"]) ? (string)$_POST["title"] : '';
    $year = isset($_POST["year"]) ? (int)$_POST["year"] : 0;
    $rating = isset($_POST["rating"]) ? (float)$_POST["rating"] : 0;
    $genre = isset($_POST["genre"]) ? (int)$_POST["genre"] : 0;

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
            "INSERT INTO movies (title, release_year, rating, genre_id)
             VALUES (?, ?, ?, ?)"
        );
        if ($stmt) {
            $stmt->bind_param("sidi", $title, $year, $rating, $genre);
            if ($stmt->execute()) {
                redirect("index.php");
            } else {
                $message = "Error adding movie. Please try again.";
            }
            $stmt->close();
        } else {
            $message = "Database error. Please try again.";
        }
    }
}

$genres = $conn->query("SELECT id, name FROM genres ORDER BY name");
if (!$genres) {
    die("Query failed: " . $conn->error);
}
?>

<h3>Add Movie</h3>
<?php if(!empty($message)) { echo "<p style='color:red;'>" . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . "</p>"; } ?>

<form method="post">
    <label for="title">Title:</label>
    <input type="text" id="title" name="title" required>

    <label for="year">Year (1900 - <?= date('Y') ?>):</label>
    <input type="number" id="year" name="year" min="1900" max="<?= date('Y') ?>">

    <label for="rating">Rating (less than 10):</label>
    <input type="number" id="rating" name="rating" step="0.1" min="0" max="9.9">

    <label for="genre">Genre:</label>
    <select id="genre" name="genre" required>
        <option value="">-- Select Genre --</option>
        <?php while ($g = $genres->fetch_assoc()) { ?>
            <option value="<?= htmlspecialchars($g['id'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($g['name'], ENT_QUOTES, 'UTF-8') ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Add Movie</button>
</form>

<?php include "../includes/footer.php"; ?>
