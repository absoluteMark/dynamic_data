<?php
/**
 * Created by PhpStorm.
 * User: markslater
 * Date: 1/4/18
 * Time: 8:48 AM
 */

class db_functions
{

    public function uploadFile($post_array)
    {
        $_POST = $post_array;
        print_r($_POST);
        $uploadResponse = array();
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $uploadResponse['image_check'] = "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                $uploadResponse['image_check'] = "File is not an image.";
                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            $uploadResponse['image_exists'] = "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $uploadResponse['image_size'] = "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            $uploadResponse['image_type'] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            $uploadResponse['image_status'] = "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $uploadResponse['image_status'] = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                $uploadResponse['image_status'] = "Sorry, there was an error uploading your file.";
            }
        }

        return $uploadResponse;

    }


}