<?php
/*
* Accounts Controller
*/

// Create or access a Session
session_start();

// Get the database connection file
  require_once '../library/connections.php';
// Get the functions library
  require_once '../library/functions.php';
// Get the PHP Motors model for use as needed
  require_once '../model/main-model.php';
// Get the accounts model
  require_once '../model/accounts-model.php';
// Get the vehicles model
  require_once '../model/uploads-model.php';
// Get the reviews model
  require_once '../model/reviews-model.php';

// Get the array of classifications
$classifications = getClassifications();
// var_dump($classifications);
// 	exit;

// Call navBuild from functions.php
$navList = navBuild($classifications);

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
 if ($action == NULL){
  $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
 }

switch ($action){
  case 'register-page':
    include '../view/registration.php';
    exit;
    break;

  case 'login-page':
    include '../view/login.php';
    exit;
    break;

  case 'register':
    // include '../view/registration.php';

    // Filter and store the data
    $clientFirstname = trim(filter_input(INPUT_POST, 'clientFirstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $clientLastname = trim(filter_input(INPUT_POST, 'clientLastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $clientEmail = checkEmail($clientEmail);
    $checkPassword = checkPassword($clientPassword);


    //checking for an existing email address
    $existingEmail = checkExistingEmail($clientEmail);

    // Check for existing email address in the table
    if($existingEmail){
      $_SESSION['message'] = "That email address already exists. Do you want to login instead?";
      include '../view/login.php';
      unset($_SESSION['message']);
      exit;
    }

    // Check for missing data
    if(empty($clientFirstname) || empty($clientLastname) || empty ($clientEmail) || empty($checkPassword)){
      $message = '<p>Please provide information for all empty form fields.</p>';
      include '../view/registration.php';
      exit;
    }

    // Hash the checked password
    $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);

    // Send the data to the model
    $regOutcome = regClient($clientFirstname, $clientLastname,
    $clientEmail, $hashedPassword);

    // Check and report the result
    // if($regOutcome === 1){
    //   $message = "<p>Thanks for registering $clientFirstname. Please use your email and password to login.</p>";
    //   include '../view/login.php';
    //   exit;
    // } else {

    // Check and report the result
    if ($regOutcome === 1) {
      setcookie('firstname', $clientFirstname, strtotime('+1 year'), '/');
      $_SESSION['message'] = "Thanks for registering $clientFirstname. Please use your email and password to login.";
      header('Location: /phpmotors/accounts/?action=login');
      exit;
    } else {
      $message = "<p>Sorry $clientFirstname, but the registration failed. Please try again.</p>";
      include '../view/registration.php';
      exit;
    }
  break;


  case 'login':
    // Filter and store the data
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientEmail = checkEmail($clientEmail);
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $checkPassword = checkPassword($clientPassword);

    // Check for missing data
    if(empty($clientEmail) || empty($checkPassword)){
      $_SESSION['message'] = "Please provide a valid email address and password.";
      include '../view/login.php';
      unset($_SESSION['message']);
      exit;
      // $message = '<p>Please provide information for all empty form fields.</p>';
      // include $_SERVER['DOCUMENT_ROOT'] . '/phpmotors/view/login.php';
      // exit;
    }

    // A valid password exists, proceed with the login process
    // Query the client data based on the email address
    $clientData = getClient($clientEmail);

    // Compare the password just submitted against
    // the hashed password for the matching client
    // Check if $clientData is not an array
    if (!is_array($clientData)) {
      $_SESSION['message'] = "<p class='notice-red'>No client found with the provided email address.</p>";
      include '../view/login.php';
      unset($_SESSION['message']);
      exit;
    }
    $hashCheck = password_verify($clientPassword, $clientData['clientPassword']);
    
    // If the hashes don't match create an error
    // and return to the login view
    if(!$hashCheck) {
      $_SESSION['message'] = "<p>Please check your password and try again.</p>";
      include '../view/login.php';
      unset($_SESSION['message']);
      exit;
    }
    // A valid user exists, log them in
    $_SESSION['loggedin'] = TRUE;
    $_SESSION['firstname'] = $clientData['clientFirstname'];

    // Remove the password from the array
    // the array_pop function removes the last
    // element from an array
    array_pop($clientData);
    // Store the array into the session
    $_SESSION['clientData'] = $clientData;
    
    // $reviewList = getReviewsByClient($clientId);
    // $clientInfo = $_SESSION['clientData'];
    // $reviewList = getReviewsByClient($clientInfo['clientId']);
    $clientInfo = $_SESSION['clientData'];

    $reviewList = getReviewsByClient($_SESSION['clientData']['clientId']);
    if(!count($reviewList)){
      $message = "<p class='notice'>No reviews.</p>";
    } else {
      $vehicleReviewDisplay = buildClientReviewsDisplay($reviewList);
    } 

    include '../view/admin.php';
  break;

  // case 'admin';
  // $clientData = $_SESSION ['clienData'];
  // include '../view/admin.php';

  case 'admin':

    $clientInfo = $_SESSION['clientData'];

    $reviewList = getReviewsByClient($_SESSION['clientData']['clientId']);
    if(!count($reviewList)){
      $message = "<p class='notice'>No reviews.</p>";
    } else {
      $vehicleReviewDisplay = buildClientReviewsDisplay($reviewList);
    } 

    include '../view/admin.php';
    break;

  case 'update-client-page':
    $clientInfo = $_SESSION['clientData'];
    include '../view/client-update.php';
    exit;
    break;

  case 'updateclient':
    $clientFirstname = trim(filter_input(INPUT_POST, 'clientFirstname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $clientLastname = trim(filter_input(INPUT_POST, 'clientLastname', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $clientEmail = trim(filter_input(INPUT_POST, 'clientEmail', FILTER_SANITIZE_EMAIL));
    $clientEmail = checkEmail($clientEmail);
    $clientId = trim(filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_FLOAT));

    if ($clientEmail!=$_SESSION['clientData']['clientEmail']){
      $existingEmail = checkExistingEmail($clientEmail);
      if($existingEmail){
        $message = '<p>That email already exist. Try again.</p>';
        include '../view/client-update.php';
        exit;
      }
    }

    // Check for missing data
    if(empty($clientFirstname) || empty($clientLastname) || empty($clientEmail)){
      $message = "<p>Please provide information for all empty fields.</p>";
      include '../view/client-update.php';
      exit;
    }

    // Sed the data to the model
    $updateResult = updateclient($clientId, $clientFirstname, $clientLastname, $clientEmail);

    //Check and report the result
    if($updateResult===1){
      $message = "<p>The Account was updated successfully.</p>";
      $_SESSION['message'] = $message;
      $clientInfo = getClientById($clientId);
      $_SESSION['clientData'] = $clientInfo;
      header('location: /phpmotors/accounts/?action=admin');
      unset($_SESSION['message']);
      exit;
    } else {
      $message = '<p class="notice-red">The account updating failed. Please try again.</p>';
      $_SESSION['message'] = $message;
      header('location: /phpmotors/accounts/?action=admin');
      unset($_SESSION['message']);
      exit;
    }
  break;

  case 'updatepassword':
    $clientId = trim(filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_FLOAT));
    $clientPassword = trim(filter_input(INPUT_POST, 'clientPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $clientInfo = $_SESSION['clientData'];

    $checkPassword = checkPassword($clientPassword);
    if(empty($checkPassword)){
        $messagePassword = '<p class="notice-red">Please check your password and try again.</p>';
        include '../view/client-update.php';
        exit;
    }

    // Hash the checked password
    $hashedPassword = password_hash($clientPassword, PASSWORD_DEFAULT);
    
    $updateResult = updatepassword($clientId, $hashedPassword);

    // Check and report the result
    if($updateResult){
        $message = "<p>Password was updated successfully.</p>";
        $_SESSION['message'] = $message;
        header('location: /phpmotors/accounts/?action=admin');
        unset($_SESSION['message']);
        exit;
    } else {
        $message = "<p>Password update failed. Please try again.</p>";
        $_SESSION['message'] = $message;
        header('location: /phpmotors/accounts/?action=admin');
        unset($_SESSION['message']);
        exit;
    }
    break;

  case 'Logout':
    //remove session
    session_unset();
    //destroy session
    session_destroy();
    header('Location: /phpmotors/accounts/?action=login');
    exit;
  break;

 default:
 include '../view/admin.php';
};
?>