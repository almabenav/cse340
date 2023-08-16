<?php 
//check logged in and clientLevel higher than 1, if not redirect to home view
if ($_SESSION['clientData']['clientLevel'] < 2) {
 header('location: /phpmotors/');
 exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP MOTORS Website by Alma Benavides in CSE 340">
    <title>Add Classification | PHP MOTORS</title>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main>
        <h1>Add Classification</h1>
        <?php
            if (isset($message)) {
                echo $message;
            }
            ?>
        <form method="post" action="/phpmotors/vehicles/index.php">
            <span>*Classification Name must be 30 characters max.</span>
            <label>Classification Name <input type="text" name="classificationName" pattern="^(?:.{1,30})$" required></label>
            <button class="blue-btn form-btn" type="submit" name="submit" value="regClassification">Add Vehicle</button>
            <input type="hidden" name="action" value="regClassification">
        </form>
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>  
</body>
</html>