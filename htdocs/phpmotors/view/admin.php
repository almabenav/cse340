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
    <title>Admin | PHP MOTORS</title>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main>
        <section>
            <?php if(isset($_SESSION['clientData'])){
                $clientFirstname = $_SESSION['clientData']['clientFirstname'];
                $clientLastname = $_SESSION['clientData']['clientLastname'];
                $clientEmail = $_SESSION['clientData']['clientEmail'];
                $clientLevel = $_SESSION['clientData']['clientLevel'];
        
            echo "<h1>$clientFirstname $clientLastname</h1>";

            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
            }

            echo "<p><em>You are logged in.</em></p>";
            echo "<ul>
                    <li>First name: $clientFirstname</li>
                    <li>Last name: $clientLastname</li>
                    <li>Email: $clientEmail</li>
                </ul>";
                
                echo "<h2>Account Information</h2>";
                echo "<p>Use this link to update account information.</p>";
                echo "<a href='/phpmotors/accounts/?action=update-client-page'>Update Account Information</a>";

                if(($clientLevel)>1){
                    echo "<h2>Inventory Management</h2>";
                    echo "<p>Use this link to manage the inventory.</p>";
                    echo "<a href='/phpmotors/vehicles/index.php'>Vehicle Managment</a>";
                }
            }?>
        </section>
        <section>
            <h2>Manage Your Product Reviews</h2>
            <?php
                if(isset($vehicleReviewDisplay)){
                    echo $vehicleReviewDisplay;
                } else {
                    echo "<p>No reviews yet</p>";
                }
            ?>
        </section>
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>  
</body>
</html>