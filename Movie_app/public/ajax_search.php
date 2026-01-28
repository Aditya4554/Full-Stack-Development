<?php
include "../config/db.php";

header('Content-Type: text/html; charset=utf-8');

// Live title search
if (isset($_GET['q'])) {
    $q = "%" . $_GET['q'] . "%";
    $stmt = $conn->prepare(
        "SELECT movies.*, genres.name AS genre
         FROM movies
         LEFT JOIN genres ON movies.genre_id = genres.id
         WHERE movies.title LIKE ? LIMIT 50"
    );
    $stmt->bind_param("s", $q);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 0) {
        echo "No results.";
        exit();
    }

    echo '<table border="1" cellpadding="6"><tr><th>Title</th><th>Year</th><th>Rating</th><th>Genre</th></tr>';
    while ($row = $res->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($row['release_year'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($row['rating'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($row['genre'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    exit();
}

// Advanced search by year and/or rating
$current_year = date('Y');
$year = isset($_GET['year']) && $_GET['year'] !== '' ? intval($_GET['year']) : null;
$rating = isset($_GET['rating']) && $_GET['rating'] !== '' ? floatval($_GET['rating']) : null;

// Validate year range (1900 to current year)
if ($year !== null && ($year < 1900 || $year > $current_year)) {
    echo 'Year must be between 1900 and ' . $current_year . '.';
    exit();
}

// Validate rating (less than 10)
if ($rating !== null && $rating >= 10) {
    echo 'Rating must be less than 10.';
    exit();
}

if ($year === null && $rating === null) {
    echo 'No search parameters provided.';
    exit();
}

if ($year !== null && $rating !== null) {
    $stmt = $conn->prepare(
        "SELECT movies.*, genres.name AS genre
         FROM movies
         LEFT JOIN genres ON movies.genre_id = genres.id
         WHERE movies.release_year > 1900 AND movies.release_year < ? AND movies.rating < ?"
    );
    $stmt->bind_param("id", $current_year, $rating);
} elseif ($year !== null) {
    $stmt = $conn->prepare(
        "SELECT movies.*, genres.name AS genre
         FROM movies
         LEFT JOIN genres ON movies.genre_id = genres.id
         WHERE movies.release_year = ? AND movies.release_year > 1900 AND movies.release_year < ?"
    );
    $stmt->bind_param("ii", $year, $current_year);
} else {
    $stmt = $conn->prepare(
        "SELECT movies.*, genres.name AS genre
         FROM movies
         LEFT JOIN genres ON movies.genre_id = genres.id
         WHERE movies.rating < ? AND movies.release_year > 1900 AND movies.release_year < ?"
    );
    $stmt->bind_param("di", $rating, $current_year);
}

$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows == 0) {
    echo "No results.";
    exit();
}

echo '<table border="1" cellpadding="6"><tr><th>Title</th><th>Year</th><th>Rating</th><th>Genre</th></tr>';
while ($row = $res->fetch_assoc()) {
    echo '<tr>';
    echo '<td>' . htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($row['release_year'], ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($row['rating'], ENT_QUOTES, 'UTF-8') . '</td>';
    echo '<td>' . htmlspecialchars($row['genre'], ENT_QUOTES, 'UTF-8') . '</td>';
    echo '</tr>';
}
echo '</table>';
?>
