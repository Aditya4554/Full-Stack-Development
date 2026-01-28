<?php
include "../config/db.php";
include "../includes/functions.php";
include "../includes/header.php";
?>

<h3>Advanced Search</h3>

<div>
    Title (live):
    <input type="text" name="q" oninput="liveSearch(this.value)" placeholder="Type title...">
</div>

<div style="margin-top:10px;">
    Year (1900 - <?= date('Y') ?>):
    <input type="number" id="year" name="year" min="1900" max="<?= date('Y') ?>">

    Rating (less than 10):
    <input type="number" step="0.1" id="rating" name="rating" min="0" max="9.9">

    <button type="button" onclick="advancedSearch()">Search</button>
</div>

<!-- Results will be injected into the #result element from header -->

<?php include "../includes/footer.php"; ?>
