<?php
//Connect to a database
session_start();
$db = mysqli_connect('209.129.8.7', 'RCCCSCCIS17B', '4921449288', 'RCCCSCCIS17B');

//Variable Declaration
$errors = array();
$item_name = "";
$item_desc = "";
$item_stock = 0;
$item_price = 0;

if(isset($_POST['input_shipping_btn'])){
    header('location: pay.php');
}
if(isset($_POST['view_btn'])){
    $_SESSION['view'] = $_POST['display'];
}
if(isset($_POST['buy_item_btn'])){
    global $db, $errors;
    $item_id = $_SESSION['view'];
    $user = $_SESSION['user'];
    $user_id = $user['id_user'];
    $num_item = $_POST['buy'];
    
    $query_item = "SELECT * FROM anguyen_store_cart WHERE item_id='$item_id' AND user_id='$user_id'";
    $result_item = mysqli_query($db,$query_item);
    
    if($num_item > 0){
        if (mysqli_num_rows($result_item) == 0) {
            $query_insert = "INSERT INTO `anguyen_store_cart`(`item_id`,`num_item`,`user_id`) VALUES ('$item_id','$num_item','$user_id')";
            mysqli_query($db, $query_insert);
        } else {
            $cart_data = mysqli_fetch_assoc($result_item);
            $in_cart = $cart_data['num_item'];
            $total = $in_cart + $num_item;
            $query_update = "UPDATE anguyen_store_cart SET num_item='$total' WHERE item_id='$item_id' AND user_id='$user_id'";
            mysqli_query($db, $query_update);
            
        }
        $query_item_info = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id='$item_id'";
        $result_item_info = mysqli_query($db,$query_item_info);
        $item_data = mysqli_fetch_assoc($result_item_info);
        $item_name = $item_data['name'];
        $_SESSION['success'] = "You have added ".$num_item." ".$item_name." to cart!";
    }else{
        $_SESSION['success'] = "You already have the whole stock in your inventory!";
    }   
}
if(isset($_POST['modify_cart_btn'])){
    global $db, $errors;
    $list_item_id = $_SESSION['list_item_id'];
    $user = $_SESSION['user'];
    $user_id = $user['id_user'];
    $num_item = $_POST['modify'];
    
    for($x = 0; $x < count($list_item_id); $x++){
       if($num_item[$x] == 0){
           $item_id = $list_item_id[$x];           
           $query_delete = "DELETE FROM anguyen_store_cart WHERE item_id='$item_id' AND user_id='$user_id'";
           mysqli_query($db, $query_delete);
       }else{
            $query_update = "UPDATE anguyen_store_cart SET num_item='$num_item[$x]' WHERE item_id='$list_item_id[$x]' AND user_id='$user_id'";
            mysqli_query($db, $query_update);
       }
    }
}
if(isset($_POST['home_btn'])){
    unset($_SESSION['order_id']);
    unset($_SESSION['view_order']);
    header('location: index.php');    
}
if(isset($_POST['modify_order_btn'])){
    $_SESSION['view_order'] = $_POST['order'];
}
if(isset($_POST['modify_view_order_btn'])){
    $status = $_POST['status'];
    $order_id = $_SESSION['view_order'];
    $query_status = "UPDATE anguyen_store_entity_order SET status='$status' WHERE order_id='$order_id'";
    mysqli_query($db, $query_status);
}
if (isset($_POST['manage_inventory_btn'])){
    header('location: manage_inventory.php');
}
if(isset($_POST['add_item_btn'])){
    header('location: add_item.php');
}
if(isset($_POST['delete_item_btn'])){
    $_SESSION['delete_item_id'] = $_POST['item'];
    header('location: delete_item.php');
}
if(isset($_POST['confirm_delete_item_btn'])){
    global $db, $errors;
    $confirm = $_POST['confirm'];
    if(strtolower($confirm) == "confirm"){
        $item_id = $_SESSION['delete_item_id'];
        $data_item = $_SESSION['delete_data'];
        
        $fileext = $data_item['image'];
        
        $query_item_entity = "DELETE FROM anguyen_store_entity_inventory WHERE item_id='$item_id'";
        $result = mysqli_query($db,$query_item_entity);
        if($result){
            unset($_SESSION['delete_item_id']);
            $_SESSION['succes'] = "Successfully deleted item";
            $newfilename = $data_item['item_id'].$data_item['image'];
            unlink('images/'.$newfilename);
            header('location: manage_inventory.php');
        }else{
            echo mysqli_error($db);
        }
    }else{
        array_push($errors,'Please input "confirm" to delete');
    }
}
if(isset($_POST['confirm_add_item_btn'])){
    global $db, $errors;
    $filename = $_FILES["file"]["name"];
    $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
    $file_ext = substr($filename, strripos($filename, '.')); // get file name
    $filesize = $_FILES["file"]["size"];
    $allowed_file_types = array('.jpg', '.jpeg', 'png');
    $newfilename = "";
    
    $item_price = $_POST['item_price'];
    
    if($item_price <= 0){
        push_array($errors,"Price cannot go below $0");
    }
    if(empty($item_price)){
        push_array($errors,"There must be a price");
    }
    
    if(empty($_POST['item_name'])){
        array_push($errors,"Item requires a name");
    }else{
        $item_name = $_POST['item_name'];
        $query_dupe = "SELECT * FROM anguyen_store_entity_inventory WHERE name='$item_name'";
        $result_dupe = mysqli_query($db, $query_dupe);
        if (mysqli_num_rows($result_dupe) != 0) {
            array_push($errors, "Item name is already taken");
        }
    }
    if(empty($_POST['item_desc'])){
        array_push($errors,"Item requires a description");
    }else{
        $item_desc = $_POST['item_desc'];
    }
    if(preg_match("/^[0-9]+$/", $_POST['item_stock'])){
        $item_stock = $_POST['item_stock'];
    }else{
        array_push($errors,"Enter a valid stock value");;
    }

    if (empty($file_basename)) {
        // file selection error
        array_push($errors, "File required to upload");
    }
    elseif ($filesize > 200000) {
        array_push($errors, "Image is too large");
    } elseif(!in_array($file_ext,$allowed_file_types)) {
        // file type error
        array_push($errors, "Only these file types are allowed for upload: " . implode(', ', $allowed_file_types));
        unlink($_FILES["file"]["tmp_name"]);
    }

    if (count($errors) == 0) {
        $query_add_item = "INSERT INTO `anguyen_store_entity_inventory` (`name`,`description`,`stock`,`purchased`,`image`,`price`)"
                . "VALUES('$item_name','$item_desc',$item_stock,0,'$file_ext','$item_price')";
        $result = mysqli_query($db, $query_add_item);
        if (!$result) {
            echo mysqli_error($db);
        }else{
            $item_id = mysqli_insert_id($db);
            $newfilename = $item_id . $file_ext;
            move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $newfilename);
            
            $_SESSION['success'] = "Item successfully added";
            header('location: manage_inventory.php');
        }
    }
}
if(isset($_POST['modify_item_btn'])){
    $_SESSION['modify_item_id'] = $_POST['item'];
    $_SESSION['first'] = "first";
    header('location: modify_item.php');
}
if(isset($_POST['confirm_modify_item_btn'])){
    global $db, $errors;
    $filename = $_FILES["file"]["name"];
    $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
    $file_ext = substr($filename, strripos($filename, '.')); // get file name
    $filesize = $_FILES["file"]["size"];
    $allowed_file_types = array('.jpg', '.jpeg', 'png');
    $newfilename = "";
    
    $data_item = $_SESSION['item_data'];
    $item_id = $data_item['item_id'];
    
    $item_price = $_POST['item_price'];
    
    if($item_price <= 0){
        push_array($errors,"Price cannot go below $0");
    }
    if(empty($item_price)){
        push_array($errors,"There must be a price");
    }
    
    
    if(($_POST['item_name'] == $data_item['name']) && ($_POST['item_desc'] == $data_item['description']) && ($_POST['item_stock'] == $data_item['stock']) && empty($file_basename)){
        $_SESSION['success'] = "No changes made";
        unset($_SESSION['item_data']);
        unset($_SESSION['modify_item_id']);
        header('location: manage_inventory.php');
    }
       
    
    if(empty($_POST['item_name'])){
        array_push($errors,"Item requires a name");
    }else{
        $item_name = $_POST['item_name'];
        $old_name = $data_item['name'];        
        $query_dupe = "SELECT * FROM anguyen_store_entity_inventory WHERE name='$item_name'";
        $result_dupe = mysqli_query($db, $query_dupe);
        if (mysqli_num_rows($result_dupe) != 0) {
            if(strtolower($item_name) != strtolower($old_name)){
                array_push($errors, "Item name is already taken");
            }
        }
    }
    if(empty($_POST['item_desc'])){
        array_push($errors,"Item requires a description");
    }else{
        $item_desc = $_POST['item_desc'];
    }
    if(preg_match("/^[0-9]+$/", $_POST['item_stock'])){
        $item_stock = $_POST['item_stock'];
    }else{
        array_push($errors,"Enter a valid stock value");;
    }
    
    if(!empty($file_basename)){
        if (in_array($file_ext, $allowed_file_types) && ($filesize < 200000)) {
            // Rename file
            $newfilename = $data_item['item_id'] . $file_ext;
            if ($filesize > 200000) {
                array_push($errors, "Image is too large");
            } elseif(!in_array($file_ext,$allowed_file_types)){
                // file type error
                array_push($errors, "Only these file types are allowed for upload: " . implode(', ', $allowed_file_types));
                unlink($_FILES["file"]["tmp_name"]);
            }
        }
    }
    if (count($errors) == 0) {
        if(!empty($file_basename)){
            unlink('images/'.$newfilename);
            move_uploaded_file($_FILES["file"]["tmp_name"], "images/" . $newfilename);
            $query_image = "UPDATE anguyen_store_entity_inventory SET image='$file_ext' WHERE item_id='$item_id'";
            mysqli_query($db,$query_image);
        }
        
        $query_name = "UPDATE anguyen_store_entity_inventory SET name='$item_name' WHERE item_id='$item_id'";
        $query_desc = "UPDATE anguyen_store_entity_inventory SET description='$item_desc' WHERE item_id='$item_id'";
        $query_stock = "UPDATE anguyen_store_entity_inventory SET stock='$item_stock' WHERE item_id='$item_id'";
        $query_price = "UPDATE anguyen_store_entity_inventory SET price='$item_price' WHERE item_id='$item_id'";
        mysqli_query($db,$query_price);
        mysqli_query($db,$query_name);
        mysqli_query($db,$query_desc);
        mysqli_query($db,$query_stock);        
        
        $_SESSION['success'] = "Item successfully modified";
        unset($_SESSION['item_data']);
        unset($_SESSION['modify_item_id']);
        header('location: manage_inventory.php');
    }
}
if(isset($_POST['view_order_btn'])){
    $order_id = $_POST['order'];
    $_SESSION['view_order'] = $order_id;
}
function e($val){
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
    global $errors;
    if (count($errors) > 0){
	echo '<div class="error">';
        foreach ($errors as $error){
            echo $error .'<br>';
	}
	echo '</div>';
    }
}