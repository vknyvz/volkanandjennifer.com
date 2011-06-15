<?php
/**
 * handles the picture upload in admin/pictures/edit
 */

mysql_connect('localhost', 'root', null);
mysql_select_db('vnj');

$error = false;

if (!isset($_FILES['Filedata']) || !is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
	$error = 'Invalid Upload';
}

if (!$error && !($size = @getimagesize($_FILES['Filedata']['tmp_name']) ) ) {
	$error = 'Please upload only images, no other files are supported.';
}

if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) ) {
	$error = 'Please upload only images of type JPEG, GIF or PNG.';
}

$location = 'tmp/'.$_FILES['Filedata']['name'];

move_uploaded_file($_FILES['Filedata']['tmp_name'], $location);

if ($error) {
	$return = array(
		'status' => '0',
		'error' => $error
	);
} else {
	$return = array(
		'status' => '1',		
		'name' => $_FILES['Filedata']['name']
	);

	$info = @getimagesize($location);
	
	$q = "INSERT INTO pictures SET pictureName = '" . $_FILES['Filedata']['name'] . "' ";
	mysql_query($q);
	
	if ($info) {
		$return['width'] = $info[0];
		$return['height'] = $info[1];
		$return['pictureId'] = mysql_insert_id();
		$return['mime'] = $info['mime'];
	}

}

echo json_encode($return);

?>