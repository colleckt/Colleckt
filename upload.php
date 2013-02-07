<?php

require_once("phpFlickr-3.1/phpFlickr.php");
// Create new phpFlickr object

// Find the NSID of the authenticated user
$nsid = $token['user']['nsid'];
//Сheck that we have a file
if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
  //Check if the file is JPEG image and it's size is less than 350Kb
  $filename = basename($_FILES['uploaded_file']['name']);
  $ext = substr($filename, strrpos($filename, '.') + 1);
  if (($ext == "jpg") && ($_FILES["uploaded_file"]["type"] == "image/jpeg") && 
    ($_FILES["uploaded_file"]["size"] < 3500000)) {
    //Determine the path to which we want to save this file
      $newname = dirname(__FILE__).'/upload/'.$filename;
      //Check if the file with the same name is already exists on the server
      if (!file_exists($newname)) {
        //Attempt to move the uploaded file to it's new place
        if ((move_uploaded_file($_FILES['uploaded_file']['tmp_name'],$newname))) {
        	$f = new phpFlickr("36c90e9ddbcbe8061e224196a331a1be","fe8300bbb10ad9fe",true);
        	$f->auth("write");
			$token = $f->auth_checkToken();
 
        	 $upload = $f->sync_upload("Upload/".$filename);
			
        	echo "It's done! The file has been saved as: ".$newname;
		   echo "<br/>";
		   echo "Filename = ".$filename;
		   
			echo "<br/>";
			echo "Upload status = ",$upload;
				echo "<br/>";
				// Get the friendly URL of the user's photos
$photos_url = $f->urls_getUserPhotos($nsid);
 
// Get the user's first 36 public photos
$photos = $f->photos_search(array("user_id" => $nsid, "per_page" => 36));
 
// Loop through the photos and output the html
 $i=0;
foreach ((array)$photos['photo'] as $photo) {
	echo "<a href=$photos_url$photo[id]>";
	echo "<img border='0' alt='$photo[title]' ".
		"src=" . $f->buildPhotoURL($photo, "Square") . ">";
	echo "</a>";
	$i++;
	// If it reaches the sixth photo, insert a line break
	if ($i % 6 == 0) {
		echo "<br>\n";
	}
}
        } else {
           echo "Error: A problem occurred during file upload!";
        }
      } else {
         echo "Error: File ".$_FILES["uploaded_file"]["name"]." already exists";
      }
  } else {
     echo "Error: Only .jpg images under 350Kb are accepted for upload";
  }
} else {
 echo "Error: No file uploaded";
}

?>