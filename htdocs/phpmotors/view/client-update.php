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
    <title>Account Management | PHP MOTORS</title>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main>
    <h1>Account Update</h1>
        <?php
        if (isset($message)) {
            echo $message;
        }
        ?>
            
        <form class="Manage-account-form" method="post" action="/phpmotors/accounts/index.php">
            <label class="top" for="fname">First name<input id="fname" type="text" name="clientFirstname" <?php if(isset($clientInfo['clientFirstname'])){ echo "value='$clientInfo[clientFirstname]'"; } elseif(isset($clientFirstname)){ echo "value='$clientFirstname'";} ?> required></label>
            <label class="top" for="lname">Last name<input id="lname" type="text" name="clientLastname" <?php if(isset($clientInfo['clientLastname'])){ echo "value='$clientInfo[clientLastname]'"; } elseif(isset($clientLastname)){ echo "value='$clientLastname'";} ?> required></label>
            <label class="top" for="email">Email<input id="email" type="email" name="clientEmail" <?php if(isset($clientInfo['clientEmail'])){ echo "value='$clientInfo[clientEmail]'"; } elseif(isset($clientEmail)){ echo "value='$clientEmail'";} ?> required></label><br>
            <a href=""></a>
            <button class="blue-btn form-btn" type="submit" name="submit" value="Update Client">Update Info</button>
            <input type="hidden" name="action" value="updateclient">
            <input type="hidden" name="clientId" value="
            <?php if(isset($clientInfo['clientId'])){
            echo $clientInfo['clientId'];} ?>">
        </form>

        <h3>Update Password</h3>
        <?php
            if (isset($messagePassword)) {
                echo $messagePassword;
            }
        ?>
        <p>Password must be at least 8 characters and contain at least 1 number, 1 capital letter and 1 special character</p>
        <form method="post" action="/phpmotors/accounts/index.php">
            <label class="top" for="password">Password<input id="password" type="password" name="clientPassword" pattern="(?=^.{8,}$)(?=.*\d)(?=.*\W+)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$"></label>

            <button class="blue-btn form-btn" type="submit" name="submit" value="updatepassword">Update Password</button>
            <input type="hidden" name="action" value="updatepassword">
            <input type="hidden" name="clientId" value="
            <?php if(isset($clientInfo['clientId'])){
            echo $clientInfo['clientId'];} ?>">
        </form>
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>  
</body>
</html>