<?php
// Build the select list
$classificationList = "<option value=''>Choose car classification</option>";
foreach ($classifications as $classification) {
  $classificationList .= "<option value='($classification[classificationId])'";
  
  if(isset($classificationId)){
    if($classification['classificationId'] === $classificationId){
        $classificationList .= ' selected ';
    }
  }

  $classificationList .= ">$classification[classificationName]</option>";
};

// //check logged in and clientLevel higher than 1, if not redirect to home view
if ($_SESSION['clientData']['clientLevel'] < 2) {
 header('location: /phpmotors/');
 exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP MOTORS Website by Alma Benavides in CSE 340">
    <title>Add Vehicle | PHP MOTORS</title>

    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/normalize.css">
</head>
<body>
    <header id="page_header"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/header.php'; ?> </header>
    <nav><?php echo $navList; ?></nav>
    <main>
        <h1>Add Vehicle</h1>
        <?php
        if (isset($message)) {
            echo $message;
        }
        ?>
        <form class="add-vehicle-form" method="post" action="/phpmotors/vehicles/index.php">
            <select name="classificationId"><?php echo $classificationList; ?></select><br>
            <label>Make<input type="text" name="invMake" <?php if(isset($invMake)){echo "value='$invMake'";}  ?> required></label>
            <label>Model<input type="text" name="invModel" <?php if(isset($invModel)){echo "value='$invModel'";}  ?> required></label>
            <label>Description<input type="text" name="invDescription" <?php if(isset($invDescription)){echo "value='$invDescription'";}  ?> required></label>
            <label>Image<input type="text" name="invImage" <?php if(isset($invImage)){echo "value='$invImage'";}  ?> required></label>
            <label>Thumbnail<input type="text" name="invThumbnail" <?php if(isset($invThumbnail)){echo "value='$invThumbnail'";}  ?> required></label>
            <label>Price<input type="number" name="invPrice" <?php if(isset($invPrice)){echo "value='$invPrice'";}  ?> required></label>
            <label>Stock<input type="number" name="invStock" <?php if(isset($invStock)){echo "value='$invStock'";}  ?> required></label> 
            <label>Color<input type="text" name="invColor" <?php if(isset($invColor)){echo "value='$invColor'";}  ?> required></label>

            <button class="blue-btn form-btn" type="submit" name="submit" value="regVehicles">Add Vehicle</button>
            <input type="hidden" name="action" value="regVehicles">
        </form>
    </main>
    <footer id="page_footer"><?php require_once $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/snippets/footer.php'; ?> </footer>  
</body>
</html>