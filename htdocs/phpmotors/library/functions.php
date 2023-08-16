<?php
function checkEmail($clientEmail){
    $valEmail = filter_var($clientEmail, FILTER_VALIDATE_EMAIL);
 return $valEmail;
}

function checkPassword($clientPassword){
    $pattern = '/^(?=.*[[:digit:]])(?=.*[[:punct:]\s])(?=.*[A-Z])(?=.*[a-z])(?:.{8,})$/';
 return preg_match($pattern, $clientPassword);
}

function checkClassificationName($classificationName){
    $pattern = '/^(?:.{1,30})$/';
 return preg_match($pattern, $classificationName);
}

function navBuild($classifications){
    $navList = '<ul>';
    $navList .= "<li><a href='/phpmotors/index.php' title='View the PHP Motors home page'>Home</a></li>";
    foreach ($classifications as $classification) {
        $navList .= "<li><a href='/phpmotors/vehicles/?action=classification&classificationName=".urlencode($classification['classificationName'])."' 
        title='View our $classification[classificationName] product line'>$classification[classificationName]</a></li>";
    }
    $navList .= '</ul>';
    return $navList;
}

// Build the classifications select list 
function buildClassificationList($classifications){ 
    $classificationList = '<select name="classificationId" id="classificationList">'; 
    $classificationList .= "<option>Choose a Classification</option>"; 
    foreach ($classifications as $classification) { 
     $classificationList .= "<option value='$classification[classificationId]'>$classification[classificationName]</option>"; 
    } 
    $classificationList .= '</select>'; 
    return $classificationList; 
}

function buildVehiclesDisplay($vehicles){
    $dv = '<ul id="inv-display">';
    foreach ($vehicles as $vehicle) {
    $price = number_format($vehicle['invPrice']);
     $dv .= "<li class='display-info'><a href='/phpmotors/vehicles/?action=vehicleDetail&invId=$vehicle[invId]'>";
     $dv .= "<img class='car-img' src='$vehicle[imgPath]' alt='Image of $vehicle[invMake] $vehicle[invModel] on phpmotors.com'>";
     $dv .= '<hr>';
     $dv .= "<h2>$vehicle[invMake] $vehicle[invModel]</h2>";
     $dv .= "<span>$$price</span>";
     $dv .= '</a></li>';
    }
    $dv .= '</ul>';
    return $dv;
   }

//bud vehicle details wrapped up in HTML
function buildDetailDisplay($vehicleInfo, $thumbDisplay){

    // $dd = "<div class='imgColumn'><div class='image-details'><img src='$vehicleInfo[invImage]' alt= 'Image of $vehicleInfo[invMake] $vehicleInfo[invModel]'></div>";
    $price = number_format($vehicleInfo['invPrice']);
    $dd = "<div class='imgColumn'><div class='thumbImages-large'>$thumbDisplay</div><div class='image-details'><img src='$vehicleInfo[imgPath]' alt='Image of $vehicleInfo[invMake] $vehicleInfo[invModel]'></div>";
    // $dd .= "<h3 class='price'>Price: $$vehicleInfo[invPrice]</h3></div>";
    $dd .= "</div>";
    $dd .= "<div class='infoColumn'><h3 class='price'>$$price</h3>";  
    $dd .= "<p class='description'>$vehicleInfo[invDescription]</p>";
    $dd .= "<p class='color'>Color: $vehicleInfo[invColor]</p>"; 
    $dd .= "<p class='stock'># in Stock: $vehicleInfo[invStock]</p></div>"; 
    return $dd;
}

/* * ********************************
*  Functions for working with images
* ********************************* */

// Adds "-tn" designation to file name
function makeThumbnailName($image) {
    $i = strrpos($image, '.');
    $image_name = substr($image, 0, $i);
    $ext = substr($image, $i);
    $image = $image_name . '-tn' . $ext;
    return $image;
   }

// Build images display for image management view
function buildImageDisplay($imageArray) {
    $id = '<ul id="image-display">';
    foreach ($imageArray as $image) {
     $id .= '<li>';
     $id .= "<img class='images-upload' src='$image[imgPath]' title='$image[invMake] $image[invModel] image on PHP Motors.com' alt='$image[invMake] $image[invModel] image on PHP Motors.com'>";
     $id .= "<p><a href='/phpmotors/uploads?action=delete&imgId=$image[imgId]&filename=$image[imgName]' title='Delete the image'>Delete $image[imgName]</a></p>";
     $id .= '</li>';
   }
    $id .= '</ul>';
    return $id;
   }

// Build the vehicles select list
function buildVehiclesSelect($vehicles) {
    $prodList = '<select name="invId" id="invId">';
    $prodList .= "<option>Choose a Vehicle</option>";
    foreach ($vehicles as $vehicle) {
     $prodList .= "<option value='$vehicle[invId]'>$vehicle[invMake] $vehicle[invModel]</option>";
    }
    $prodList .= '</select>';
    return $prodList;
   }

// Handles the file upload process and returns the path
// The file path is stored into the database
function uploadFile($name) {
    // Gets the paths, full and local directory
    global $image_dir, $image_dir_path;
    if (isset($_FILES[$name])) {
     // Gets the actual file name
     $filename = $_FILES[$name]['name'];
     if (empty($filename)) {
      return;
     }
    // Get the file from the temp folder on the server
    $source = $_FILES[$name]['tmp_name'];
    // Sets the new path - images folder in this directory
    $target = $image_dir_path . '/' . $filename;
    // Moves the file to the target folder
    move_uploaded_file($source, $target);
    // Send file for further processing
    processImage($image_dir_path, $filename);
    // Sets the path for the image for Database storage
    $filepath = $image_dir . '/' . $filename;
    // Returns the path where the file is stored
    return $filepath;
    }
   }

// Processes images by getting paths and 
// creating smaller versions of the image
function processImage($dir, $filename) {
    // Set up the variables
    $dir = $dir . '/';
   
    // Set up the image path
    $image_path = $dir . $filename;
   
    // Set up the thumbnail image path
    $image_path_tn = $dir.makeThumbnailName($filename);
   
    // Create a thumbnail image that's a maximum of 200 pixels square
    resizeImage($image_path, $image_path_tn, 200, 200);
   
    // Resize original to a maximum of 500 pixels square
    resizeImage($image_path, $image_path, 500, 500);
   }

// Checks and Resizes image
function resizeImage($old_image_path, $new_image_path, $max_width, $max_height) {
     
    // Get image type
    $image_info = getimagesize($old_image_path);
    $image_type = $image_info[2];
   
    // Set up the function names
    switch ($image_type) {
    case IMAGETYPE_JPEG:
     $image_from_file = 'imagecreatefromjpeg';
     $image_to_file = 'imagejpeg';
    break;
    case IMAGETYPE_GIF:
     $image_from_file = 'imagecreatefromgif';
     $image_to_file = 'imagegif';
    break;
    case IMAGETYPE_PNG:
     $image_from_file = 'imagecreatefrompng';
     $image_to_file = 'imagepng';
    break;
    default:
     return;
   } // ends the swith
   
    // Get the old image and its height and width
    $old_image = $image_from_file($old_image_path);
    $old_width = imagesx($old_image);
    $old_height = imagesy($old_image);
   
    // Calculate height and width ratios
    $width_ratio = $old_width / $max_width;
    $height_ratio = $old_height / $max_height;
   
    // If image is larger than specified ratio, create the new image
    if ($width_ratio > 1 || $height_ratio > 1) {
   
     // Calculate height and width for the new image
     $ratio = max($width_ratio, $height_ratio);
     $new_height = round($old_height / $ratio);
     $new_width = round($old_width / $ratio);
   
     // Create the new image
     $new_image = imagecreatetruecolor($new_width, $new_height);
   
     // Set transparency according to image type
     if ($image_type == IMAGETYPE_GIF) {
      $alpha = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
      imagecolortransparent($new_image, $alpha);
     }
   
     if ($image_type == IMAGETYPE_PNG || $image_type == IMAGETYPE_GIF) {
      imagealphablending($new_image, false);
      imagesavealpha($new_image, true);
     }
   
     // Copy old image to new image - this resizes the image
     $new_x = 0;
     $new_y = 0;
     $old_x = 0;
     $old_y = 0;
     imagecopyresampled($new_image, $old_image, $new_x, $new_y, $old_x, $old_y, $new_width, $new_height, $old_width, $old_height);
   
     // Write the new image to a new file
     $image_to_file($new_image, $new_image_path);
     // Free any memory associated with the new image
     imagedestroy($new_image);
     } else {
     // Write the old image to a new file
     $image_to_file($old_image, $new_image_path);
     }
     // Free any memory associated with the old image
     imagedestroy($old_image);
   } // ends resizeImage function

   function buildThumbDisplay($thumbImages){
    $html = '<h3 class="thumbImages-title">Vehicle Thumbnails</h3>';
    $html .= '<ul class="thumbImages">';
    foreach ($thumbImages as $image) {
        $html .= '<li>';
        $html .= "<div class='thumbImages-div'><img src='{$image['imgPath']}' alt='Image of {$image['invMake']} {$image['invModel']}'></div>";
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

/* ------------ FINAL PROJECT ------------ */

function buildReviewDisplay($reviewList){
    $html = '<ul class="reviews">';
    foreach ($reviewList as $review) {
        $clientFirstName = $review['clientFirstname'];
        $clientLastName = $review['clientLastname'];
        $screenName = substr($clientFirstName, 0, 1) . str_replace(' ', '', $clientLastName);
        // $screenName = substr($review['clientFirstname'],0,1).$review['clientLastname'];
        // $screenName = getScreenName($review['clientFirstname'], $review['clientLastname']);

        $date = $review['reviewDate'];
        $date = strtotime($date);
        $date = date("j, F, Y ", $date);
        $html .= '<li>';
        $html .= "<div><span class='screenName'>$screenName</span> wrote on $date:</div><p>$review[reviewText]</p>";
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function buildClientReviewsDisplay($reviewList){
    $html = '<ul>';
    foreach ($reviewList as $review) {
        $date = $review['reviewDate'];
        $date = strtotime($date);
        $date = date("j, F, Y ", $date);
        $html .= '<li>';
        $html .= "$review[invMake] $review[invModel] (Reviewed on $date): <a href='/phpmotors/reviews?action=edit&reviewId=$review[reviewId]'>Edit</a> | <a href='/phpmotors/reviews?action=delete&reviewId=$review[reviewId]'>Delete</a>";
        $html .= '</li>';
    }
    $html .= '</ul>';
    return $html;
}

function buildEditView($review){ 
    $date= $review['reviewDate'];
    $date= strtotime($date);
    $date = date(" j F, Y", $date);
    $html = "<h1>$review[invMake] $review[invModel] Review</h1>";
    $html .= "<p>Reviewed on $date</p>";
    
    if (isset($_SESSION['message'])) {
        $html .= "<p>$_SESSION[message]</p>";
    }
        
    $html .= "<div>
        <form  class='update-review-form' method='post' action='/phpmotors/reviews/index.php'>
        <label>Review Text</label>
        <textarea class='reviewTextField' name='reviewText' required>$review[reviewText]</textarea>
        <input type='submit' name='submit' value='Update' class='blue-btn form-btn review-btn'>
        <input type='hidden' name='action' value='editReview'>        
        <input type='hidden' name='reviewId' value='$review[reviewId]'>
        </form> 
        </div>";
    return $html;
}

function buildDeleteView($review){
    $date = $review['reviewDate'];
    $date = strtotime($date);
    $date = date("j, F, Y ", $date);
    $html = "<h1>Delete $review[invMake] $review[invModel] Review</h1>";
    $html .= "<p>Reviewed on $date</p>";
    $html .= "<p class='notice-red'><em>Deletes cannot be undone. Are you sure you want to delete this review?</em></p>";
    $html .= "<div>
        <form class='delete-review-form' method='post' action='/phpmotors/reviews/index.php'>
        <span>Review Text</span><p class='reviews-delete-text'>$review[reviewText]</p>
        <input type='submit' name='submit' value='Delete' class='blue-btn form-btn review-btn'>
        <input type='hidden' name='action' value='deleteReview'>        
        <input type='hidden' name='reviewId' value='$review[reviewId]'>
        </form> 
        </div>";
    return $html;
}
?>