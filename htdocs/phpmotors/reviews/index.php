<?php

/*
Reviews controller
*/

session_start();

require_once '../library/connections.php';
require_once '../library/functions.php';
require_once '../model/main-model.php';
require_once '../model/vehicles-model.php';
require_once '../model/uploads-model.php';
require_once '../model/reviews-model.php';

// Get the array of classifications
$classifications = getClassifications();
// Build a navigation bar using the $classifications array
$navList = navBuild($classifications);

$action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
if ($action == NULL) {
 $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}


switch ($action) {
    case 'addReview':
        $reviewText = trim(filter_input(INPUT_POST, 'reviewText', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        $invId = filter_input(INPUT_POST, 'invId', FILTER_SANITIZE_NUMBER_INT);
        $clientId = filter_input(INPUT_POST, 'clientId', FILTER_SANITIZE_NUMBER_INT);

        // Check for missing data
        if(empty($reviewText)){
            $message = '<p class="notice-red">Please provide information for all empty form fields.</p>';
            $_SESSION['message'] = $message;
            header("location: /phpmotors/vehicles?action=vehicleDetail&invId=$invId");
            exit; 
        };

        $reviewOutcome = regReview($reviewText, $invId, $clientId);

        if($reviewOutcome === 1){
            $message = '<p class="notice-green">The review was added succesfully.</p>';
            $_SESSION['message'] = $message;
            header("location: /phpmotors/vehicles?action=vehicleDetail&invId=$invId");
            unset($_SESSION['message']);
            exit; 
        } else {
            $message = '<p class="notice-red">Sorry, the added failed. Please try again.</p>';
            $_SESSION['message'] = $message;
            header("location: /phpmotors/vehicles?action=vehicleDetail&invId=$invId");
            exit;
        }
        break;

    case 'edit':
        $reviewId = filter_input(INPUT_GET, 'reviewId', FILTER_VALIDATE_INT);
        $review = getSpecificReview($reviewId);

        if(!($review)){
            $message = '<p class="notice-red">Sorry, no review could be found.</p>';
        } else {
            $displayReview = buildEditView($review);
        }
        include '../view/review-update.php';
        exit;
        break;

    case 'editReview':
        $reviewId = filter_input(INPUT_POST, 'reviewId', FILTER_VALIDATE_INT);
        $reviewText = trim(filter_input(INPUT_POST, 'reviewText', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        if(empty($reviewId) || empty($reviewText)){
            // $message = '<p class="notice-red">Please provide information for all empty form fields.</p>';
            // $_SESSION['message'] = $message;
            // header("location: /phpmotors/reviews?action=edit&reviewId=$reviewId");
            $message = '<p class="notice-red">Please provide information for all empty form fields.</p>';
            include '../view/review-update.php';
            exit; 
        };

        $updateResult = updateReview($reviewId, $reviewText);

        // if($updateResult === 1){
        //     $message = '<p class="notice-green">The review was updated succesfully.</p>';
        //     $_SESSION['message'] = $message;
        //     header("location: /phpmotors/accounts/");
        //     exit; 
        // } else {
        //     $message = '<p class="notice-red">No information have been updated.</p>';
        //     $_SESSION['message'] = $message;
        //     header("location: /phpmotors/reviews?action=edit&reviewId=$reviewId");
        //     exit;
        // }    
        if($updateResult){
            $message = '<p class="notice-green">The review was updated succesfully.</p>';
            $_SESSION['message'] = $message;
            header('location: /phpmotors/accounts/?action=admin');
            exit; 
        } else {
            $message = '<p class="notice-red">No information have been updated.</p>';
            $_SESSION['message'] = $message;
            header('location: /phpmotors/accounts/?action=admin');
            exit;
        }          
        break;

    case 'delete':
        $reviewId = filter_input(INPUT_GET, 'reviewId', FILTER_SANITIZE_NUMBER_INT);
        $review = getSpecificReview($reviewId);

        if(!($review)){
            $message = '<p class="notice-red">Sorry, no review could be found.</p>';
        } else {
            $displayReview = buildDeleteView($review);
        }
        include '../view/review-delete.php';
        exit;
        break;

    case 'deleteReview':
        $reviewId = filter_input(INPUT_POST, 'reviewId', FILTER_SANITIZE_NUMBER_INT);
        $updateResult = deleteReview($reviewId);

        if($updateResult){
            $message = '<p class="notice-green">The review was deleted succesfully.</p>';
            $_SESSION['message'] = $message;
            header('location: /phpmotors/accounts/?action=admin');
            exit; 
        } else {
            $message = '<p class="notice-red">Sorry, but review delet failed. Please try again.</p>';
            $_SESSION['message'] = $message;
            header('location: /phpmotors/accounts/?action=admin');
            exit;
        }            
        break;
        
    default:
        header("location: ../phpmotors/accounts/"); 
        exit;
        break;
};
?>