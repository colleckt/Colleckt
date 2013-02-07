<html> 
<body>
  <form enctype="multipart/form-data" action="upload.php" method="post">
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    Choose a file to upload: <input name="uploaded_file" type="file" />
    <input type="submit" value="Upload" />
  </form> 

<?php
require_once("phpFlickr-3.1/phpFlickr.php");
// Create new phpFlickr object
$f = new phpFlickr("36c90e9ddbcbe8061e224196a331a1be","fe8300bbb10ad9fe");

$f->auth("write");
$token = $f->auth_checkToken();
 
// Find the NSID of the authenticated user
$nsid = $token['user']['nsid'];
 
// Get the friendly URL of the user's photos
$photos_url = $f->urls_getUserPhotos($nsid);
 
// Get the user's first 36 public photos
$photos = $f->photos_search(array("user_id" => $nsid, "per_page" => 36));
 $i=0;
// Loop through the photos and output the html
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
 
?>
</body>
</html>