<?php if (isset($_SESSION['message'])) {
 $message = $_SESSION['message'];
}?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP MOTORS Website by Alma Benavides in CSE 340">
    <title>Image Management | PHP MOTORS</title>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main>
        <h1>Image Managment</h1>
        <p>Choose one of the options below:</p>

        <h2>Add New Vehicle Image</h2>
        <?php
        if (isset($message)) {
        echo $message;
        } ?>

        <form class="upload-form" action="/phpmotors/uploads/" method="post" enctype="multipart/form-data">
            <label for="invItem">Vehicle</label><br>
            <div><?php echo $prodSelect; ?></div>
            <fieldset>
                <legend>Is this the main image for the vehicle?</legend>
                <div class="radio-buttons">
                    <label for="priYes" class="pImage">Yes<input type="radio" name="imgPrimary" id="priYes" class="pImage" value="1"></label>
                    <label for="priNo" class="pImage">No<input type="radio" name="imgPrimary" id="priNo" class="pImage" checked value="0"></label>
                </div>
            </fieldset>
            <label>Upload Image:</label>
            <input id="invItem" type="file" name="file1">
            <input type="submit" class="regbtn blue-btn" value="Upload">
            <input type="hidden" name="action" value="upload">
        </form>
       
        <hr>

        <h2>Existing Images</h2>
        <p class="notice-red">If deleting an image, delete the thumbnail too and vice versa.</p>
        <?php
        if (isset($imageDisplay)) {
        echo $imageDisplay;
        } ?>
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>  
</body>
</html>
<?php unset($_SESSION['message']); ?>