<?php
include "header.php";
include "functions.php";

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $fileName = uploadPortfolioFile($_FILES['portfolio']);
        $msg = "File uploaded successfully: $fileName";
    } catch (Exception $e) {
        $msg = $e->getMessage();
    }
}
?>

<form method="post" enctype="multipart/form-data">
    Select Portfolio File:
    <input type="file" name="portfolio" required><br><br>
    <button type="submit">Upload</button>
</form>

<p><?php echo $msg; ?></p>

<?php include "footer.php"; ?>
