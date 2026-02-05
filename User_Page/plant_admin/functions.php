<?php
function uploadImage($file){
    $allowed=['jpg','jpeg','png','gif'];
    $ext=strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
    if(!in_array($ext,$allowed)) return false;
    $name=uniqid().'.'.$ext;
    move_uploaded_file($file['tmp_name'],UPLOAD_PATH.$name);
    return $name;
}
function alert($type,$msg){ echo "<div class='alert alert-$type'>$msg</div>"; }
?>