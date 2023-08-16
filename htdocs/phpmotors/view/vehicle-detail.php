<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP MOTORS Website by Alma Benavides in CSE 340">
    <title>Vehicle Detail | PHP MOTORS</title>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main>
        <h1><?php echo "$vehicleMake $vehicleModel";?></h1>
        <?php if(isset($message)){
            echo $message; }
        ?>
        <div class="details-grid">
            <?php if(isset($vehicleDetailDisplay)){
            echo $vehicleDetailDisplay; }
            ?>
            <div class="thumbImages-small">
                <?php if(isset($thumbDisplay)){
                echo $thumbDisplay; }
                ?>
            </div>
        </div>

        <!-- FINAL PROJECT -->

        <section>
            <h2>Customer reviews</h2>
            <?php
                if (!isset($_SESSION['loggedin'])) {
                    echo '<p>You must <a href= "/phpmotors/accounts/?action=login">login</a> to write a review.</p>';
                             
                }else{
                    $clientFirstName = $_SESSION['clientData']['clientFirstname'];
                    $clientLastName = $_SESSION['clientData']['clientLastname'];
                    $clientId = $_SESSION['clientData']['clientId'];
                    $screenName = substr($clientFirstName, 0, 1) . $clientLastName;
                    echo "<h3>Review the $vehicleMake $vehicleModel</h3>";
                    echo "<div>
                        <form  class='review-form' method='post' action='/phpmotors/reviews/index.php'>
                            <label class='screenNameLabel'>Screen Name:<br><input class='screenNameField' type='text' name='screenName' readonly value='$screenName'></label>
                            <label>Review: <textarea class='reviewText' name='reviewText' required></textarea></label>
                            <input type='submit' name='submit' value='Submit Review' class='blue-btn form-btn review-btn'>
                            <input type='hidden' name='invId' value='$invId'>
                            <input type='hidden' name='clientId' value='$clientId'>
                            <input type='hidden' name='action' value='addReview'>
                        </form>
                        </div>";   
                }    
            ?>
            <div>
                <?php 
                    if(isset($vehicleReviewDisplay)){
                    echo $vehicleReviewDisplay;
                    }else{
                        echo "<p><em>Be the first to write a review.</em></p>";
                    }
                ?>
            </div>
        </section>
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>  
</body>
</html>