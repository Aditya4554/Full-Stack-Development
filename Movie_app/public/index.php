<?php

include "../config/db.php";
include "../includes/functions.php";

// Require login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include "../includes/header.php";

/* --- SEARCH LOGIC ADDED HERE --- */
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $keyword = "%" . $_GET['search'] . "%";
    $stmt = $conn->prepare(
        "SELECT movies.*, genres.name AS genre
         FROM movies
         LEFT JOIN genres ON movies.genre_id = genres.id
         WHERE movies.title LIKE ?"
    );
    if ($stmt) {
        $stmt->bind_param("s", $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        die("Query preparation failed: " . $conn->error);
    }
} 
else {
    // Default: Show all movies
    $sql = "SELECT movies.*, genres.name AS genre
            FROM movies
            LEFT JOIN genres ON movies.genre_id = genres.id";
    $result = $conn->query($sql);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}
?>

<!-- --- SEARCH FORM ADDED HERE --- -->
<form method="get" style="margin-bottom:15px;">
    <input type="text" name="search" placeholder="Search movie title..." value="<?= htmlspecialchars($_GET['search'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <button type="submit">Search</button>
</form>

<a href="add_movie.php">➕ Add Movie</a> |
<a href="search.php">🔍 Advanced Search</a>

<table border="1" cellpadding="8">
<tr>
    <th>Title</th>
    <th>Year</th>
    <th>Rating</th>
    <th>Genre</th>
    <th>Action</th>
   
</tr>

<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= htmlspecialchars($row['release_year'], ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= htmlspecialchars($row['rating'], ENT_QUOTES, 'UTF-8') ?></td>
    <td><?= htmlspecialchars($row['genre'], ENT_QUOTES, 'UTF-8') ?></td>
    <td>
        <a href="edit_movie.php?id=<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">Edit</a> |
        <form method="post" action="delete_movie.php" style="display:inline;">
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') ?>">
            <button type="submit" onclick="return confirm('Delete movie?')" style="background:none;border:none;color:blue;cursor:pointer;text-decoration:underline;">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>
</table>

<?php include "../includes/footer.php"; ?>
