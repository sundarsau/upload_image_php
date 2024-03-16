<?php
$error = false;
$name_err = $photo_err = $err_msg = "";
$valid_ext = array('jpg','jpeg','png');

$target_dir = "uploads/";
$photo = "";

if (isset($_REQUEST['id']) && $_REQUEST['id'] != ""){
    // delete image
    $id = $_REQUEST['id'];

    $sql = "select photo from users where id=?";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows==1){
        $row = $result->fetch_assoc();
        $photo = $row['photo'];
    }

    if (file_exists($target_dir.$photo))
        unlink($target_dir.$photo);

    // delete from database
    $sql = "delete from users where id=?";
    $stmt->prepare($sql);
    $stmt->bind_param('i',$id);
    $stmt->execute();
    header("location:index.php");

}

if(isset($_POST['submit'])){
    $name = $_POST['name'];

    if ($name ==""){
        $name_err = "Please enter the name";
        $error = true;
    }

    if($_FILES['photo']['name'] ==""){
        $photo_err = "Please select a photo";
        $error = true;
    }

    if (!$error){
        // proceed with the validate image and upload

        $image_name = $_FILES['photo']['name'];
        $image_tmp = $_FILES['photo']['tmp_name'];
        $image_size = $_FILES['photo']['size'];

        // check for extension

        $ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));
        if(in_array($ext,$valid_ext)){
            // valid extension
            if (exif_imagetype($image_tmp) == IMAGETYPE_JPEG || exif_imagetype($image_tmp) == IMAGETYPE_PNG) {
                // valid image
                if ($image_size > 4000000){
                    // exceeds 4M
                    $err_msg = "image size exceeds 4M";
                }
                else{
                    // proceed with upload
                    $new_image = time()."-".basename($image_name);

                    try{
                        move_uploaded_file($image_tmp,$target_dir.$new_image);

                        // insert a row in the database
                        try{
                            $sql = "insert into users (name, photo) values (?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("ss",$name,$new_image);
                            $stmt->execute();
                            header("location:index.php");
                        }
                        catch(Exception $e){
                            $err_msg = $e->getMessage();
                        }

                    }
                    catch(Exception $e){
                        $err_msg = $e->getMessage();

                    }
                }
            }
            else{
                $err_msg = "Not a valid image file";
            }
        }
        else{
            $err_msg = "Not a valid extension";
        }

    }

}

