<?php
/*
* Vehicles Controller
*/

// Create or access a Session
session_start();

// Get the database connection file
  require_once '../library/connections.php';
// Get the functions library
  require_once '../library/functions.php';
// Get the PHP Motors model for use as needed
  require_once '../model/main-model.php';
// Get the vehicles model
  require_once '../model/vehicles-model.php';
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

// $classificationList = "<option value=''>Choose car classification</option>";
// foreach ($classifications as $classification) {
//   $classificationList.="<option value='" . urlencode($classification['classificationId']) . "'>$classification[classificationName]</option>";
// };

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
  if ($action == NULL){
    $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
  }


switch ($action){
  // case 'regVehicles':
  //   include '../add-vehicle.php';
  //   exit;
  //   break;

  // case 'regClassification':
  //   include '../add-classification.php';
  //   exit;
  //   break;

  case 'regClassification':
      // Filter and store the data
      $classificationName = trim(filter_input(INPUT_POST, 'classificationName', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
      $checkClassificationName = checkClassificationName($classificationName);

      // Check for missing data
      if(empty($checkClassificationName)){
        $message = '<p>Please provide information for empty form field.</p>';
        include '../view/add-classification.php';
        exit; 
      };

      // Send the data to the model
      $addOutcome = regClassification($classificationName);

      // Check and report the result
      if($addOutcome === 1){
        header("location:../vehicles/index.php");
        exit;
      } else {
        $message = "<p>Sorry, but the adding of the classification failed. Please try again.</p>";
        include '../view/add-classification.php';
        exit;
      }
    break;

  case 'regVehicles':
    // Filter and store the data
    $classificationId = trim(filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_FLOAT));
    $invMake = trim(filter_input(INPUT_POST, 'invMake', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $invModel = trim(filter_input(INPUT_POST, 'invModel', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $invDescription = trim(filter_input(INPUT_POST, 'invDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $invImage = trim(filter_input(INPUT_POST, 'invImage', FILTER_SANITIZE_URL));
    $invThumbnail = trim(filter_input(INPUT_POST, 'invThumbnail', FILTER_SANITIZE_URL));
    $invPrice = trim(filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    $invStock = trim(filter_input(INPUT_POST, 'invStock', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    $invColor = trim(filter_input(INPUT_POST, 'invColor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    // Check for missing data
    if(empty($classificationId) || empty($invMake) || empty($invModel) || 
      empty($invDescription) || empty($invImage) || empty($invThumbnail) || 
      empty($invPrice) || empty($invStock) || empty($invColor)){
      $message = '<p class="notice-red">Please provide information for all empty form fields.</p>';
      include '../view/add-vehicle.php';
      exit; 
    };

    // Send the data to the model
    $vehicleOutcome = regVehicle($classificationId, $invMake, $invModel,
    $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invColor);

    // Check and report the result
    if($vehicleOutcome === 1){
      $message = "<p>The $invMake $invModel was added succesfully.</p>";

      $invImage = "";
      $invModel = "";
      $invMake = "";
      $classificationId = "";
      $invDescription = "";
      $invThumbnail = "";
      $invPrice = "";
      $invStock = "";
      $invColor = "";

      include '../view/add-vehicle.php';
      exit;
    } else {
      $message = "<p>Sorry, but the adding of the vehicle failed. Please try again.</p>";
      include '../view/add-vehicle.php';
      exit;
    }
    break;

  case 'getInventoryItems': 
    // Get the classificationId 
    $classificationId = filter_input(INPUT_GET, 'classificationId', FILTER_SANITIZE_NUMBER_INT); 
    // Fetch the vehicles by classificationId from the DB 
    $inventoryArray = getInventoryByClassification($classificationId); 
    // Convert the array to a JSON object and send it back 
    echo json_encode($inventoryArray); 
    break;

  case 'mod':
    $invId = filter_input(INPUT_GET, 'invId', FILTER_VALIDATE_INT);
    $invInfo = getInvItemInfo($invId);
    if(count($invInfo)<1){
      $message = 'Sorry, no vehicle information could be found.';
    }
    include '../view/vehicle-update.php';
    exit;
    break;

  case 'updateVehicle':
    $classificationId = trim(filter_input(INPUT_POST, 'classificationId', FILTER_SANITIZE_NUMBER_FLOAT));
    $invMake = trim(filter_input(INPUT_POST, 'invMake', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $invModel = trim(filter_input(INPUT_POST, 'invModel', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);
    $invDescription = trim(filter_input(INPUT_POST, 'invDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $invImage = trim(filter_input(INPUT_POST, 'invImage', FILTER_SANITIZE_URL));
    $invThumbnail = trim(filter_input(INPUT_POST, 'invThumbnail', FILTER_SANITIZE_URL));
    $invPrice = trim(filter_input(INPUT_POST, 'invPrice', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    $invStock = trim(filter_input(INPUT_POST, 'invStock', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    $invColor = trim(filter_input(INPUT_POST, 'invColor', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

    // Check for missing data
    if (empty($classificationId) || empty($invMake) || empty($invModel) 
    || empty($invDescription) || empty($invImage) || empty($invThumbnail)
    || empty($invPrice) || empty($invStock) || empty($invColor)) {
    $message = '<p>Please complete all information for the item! Double check the classification of the item.</p>';
    include '../view/vehicle-update.php';
    exit;
    }

    $updateResult = updateVehicle($classificationId, $invMake, $invModel, $invId, $invDescription, $invImage, $invThumbnail, $invPrice, $invStock, $invColor);
    if ($updateResult) {
      $message = "<p class='notice-green'>Congratulations, the $invMake $invModel was successfully updated.</p>";
      $_SESSION['message'] = $message;
      header('location: /phpmotors/vehicles/');
      exit;
    } else {
      $message = "<p class='notice-red'>Error. the $invMake $invModel was not updated.</p>";
      include '../view/vehicle-update.php';
      exit;
    }
    break;

  case 'del':
    $invId = filter_input(INPUT_GET, 'invId', FILTER_VALIDATE_INT);
    $invInfo = getInvItemInfo($invId);
    if (count($invInfo) < 1) {
        $message = 'Sorry, no vehicle information could be found.';
      }
      include '../view/vehicle-delete.php';
      exit;

    break;  

  case 'deleteVehicle':
    $invMake = filter_input(INPUT_POST, 'invMake', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $invModel = filter_input(INPUT_POST, 'invModel', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);
      
    $deleteResult = deleteVehicle($invId);
    if ($deleteResult) {
      $message = "<p class='notice-green'>Congratulations the, $invMake $invModel was	successfully deleted.</p>";
      $_SESSION['message'] = $message;
      header('location: /phpmotors/vehicles/');
      exit;
    } else {
      $message = "<p class='notice-red'>Error: $invMake $invModel was not
      deleted.</p>";
      $_SESSION['message'] = $message;
      header('location: /phpmotors/vehicles/');
      exit;
    }
    break;

  case 'classification':
    $classificationName = filter_input(INPUT_GET, 'classificationName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $vehicles = getVehiclesByClassification($classificationName);
    if(!count($vehicles)){
      $message = "<p class='notice-red'>Sorry, no $classificationName vehicles could be found.</p>";
    } else {
      $vehicleDisplay = buildVehiclesDisplay($vehicles);
    }
    // echo $vehicleDisplay;
    // exit;
    include '../view/classification.php';
    break;

  case 'vehicleDetail':
    $invId = filter_input(INPUT_GET, 'invId', FILTER_SANITIZE_NUMBER_INT);
    $vehicleInfo = getInvItemInfo($invId);
    $thumbImages = getThumbnailPath($invId);
    $reviewList = getReviewsByItem($invId);
    // $vehicleMake = $vehicleInfo['invMake'] ?? '';
    // $vehicleModel = $vehicleInfo['invModel'] ?? '';
    $vehicleMake = isset($vehicleInfo['invMake']) ? $vehicleInfo['invMake'] : '';
    $vehicleModel = isset($vehicleInfo['invModel']) ? $vehicleInfo['invModel'] : '';

    if(!count($vehicleInfo)){
      $message = "<p class='notice-red'>Sorry, no vehicle could be found.</p>";
    } else {
      $thumbDisplay = buildThumbDisplay($thumbImages);
      $vehicleDetailDisplay = buildDetailDisplay($vehicleInfo, $thumbDisplay);
    }

    // if(!count($reviewList)){
    //   $message = "<p class='notice'>Sorry, no reviews could be found.</p>";
    // } else {
    //     $vehicleReviewDisplay = buildReviewDisplay($reviewList);
    // }  
    if (count($reviewList)) {
      $vehicleReviewDisplay = buildReviewDisplay($reviewList);
    }
    include '../view/vehicle-detail.php';
    break;

  default:
    $classificationList = buildClassificationList($classifications);
    include '../view/vehicle-management.php';
    exit;
    break;
};
?>