<?php
if (!$_SESSION['loggedin']){
    header('Location: /phpmotors');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP MOTORS Website by Alma Benavides in CSE 340">
    <title>Review Update | PHP MOTORS</title>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main>
        <!-- <h1><?php echo "$review[invMake] $review[invModel]" ?> Review</h1>
        <p>Reviewed on <?php echo $date ?></p>        
        <div>
            <form method='post' action='/phpmotors/reviews/index.php'>
            <label>Review Text<textarea name='reviewText' required><?php echo $review['reviewText'] ?></textarea></label>
            <input type='submit' name='submit' value='Update' class='blue-btn form-btn'>
            <input type='hidden' name='action' value='editReview'>        
            <input type='hidden' name='reviewId' value='<?php if(isset($review['reviewId'])) {echo $review['reviewId'];}; ?>'>
            </form>
        </div> -->


        <div>
            <?php
            if (isset($message)) {
                echo $message;
            }elseif (isset($displayReview)){
                echo $displayReview;
            }
            unset($_SESSION['message'])
            ?>
        </div>
        
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>  
</body>
</html>