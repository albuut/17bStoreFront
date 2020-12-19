<?php
session_start();
$db = mysqli_connect('209.129.8.7', 'RCCCSCCIS17B', '4921449288', 'RCCCSCCIS17B');
$errors   = array(); 

$ship_name = "";
$ship_ad1 = "";
$ship_ad2 = "";
$ship_city = "";
$ship_state = "";
$ship_zip = "";

$payment_name = "";
$payment_card = "";
$sec_num = "";
$exp_date = "";

$bill_name = "";
$bill_ad1 = "";
$bill_ad2 = "";
$bill_city = "";
$bill_state = "";
$bill_zip = "";

$delivery = "";

if(isset($_POST['view_total_btn'])){
    view_total();
}
if(isset($_POST['checkout_btn'])){
    checkout();
}
if(isset($_POST['home_btn'])){
    if(isset($_SESSION['view_total'])){
        unset($_SESSION['view_total']);
    }
    header('location: index.php');
}
function view_total(){
    global $db, $errors, 
            $ship_name, $ship_ad1, $ship_ad2, $ship_city, $ship_state, $ship_zip, 
            $payment_name, $payment_card, $sec_num, $exp_date,$delivery,
            $bill_name, $bill_ad1, $bill_ad2, $bill_city, $bill_state, $bill_zip;
    
    $ship_name = $_POST['ship_name'];
    $ship_ad1 = $_POST['ship_ad1'];
    $ship_ad2 = $_POST['ship_ad2'];
    $ship_city = $_POST['ship_city'];
    $ship_state = $_POST['ship_state'];
    $ship_zip = $_POST['ship_zip'];
    
    $payment_name = $_POST['payment_name'];
    $payment_card = $_POST['payment_card'];
    $sec_num = $_POST['sec_num'];
    $exp_date = $_POST['exp'];
    
    $bill_name = $_POST['bill_name'];
    $bill_ad1 = $_POST['bill_ad1'];
    $bill_ad2 = $_POST['bill_ad2'];
    $bill_city = $_POST['bill_city'];
    $bill_state = $_POST['bill_state'];
    $bill_zip = $_POST['bill_zip'];
    
    $delivery = $_POST['delivery'];
    if(empty($ship_name)){
        array_push($errors,'Shipping name is missing');
    }
    if(empty($ship_ad1)){
        array_push($errors,'Shipping Address Line 1 is missing');
    }
    if(empty($ship_city)){
        array_push($errors,'Shipping City is missing');
    }
    if(empty($ship_zip)){
        array_push($errors,'Shipping Zip is missing');        
    }else if(!preg_match('#[0-9]{5}#',$ship_zip)){
        array_push($errors, "Invalid Zip Code, use a 5 digit zip.");
    }
    
    if(empty($payment_name)){
        array_push($errors,'Name for payment is missing');
    }
    if(empty($payment_card)){
        array_push($errors,'Card number for payment is missing');
    }else if(!preg_match('/^\d*$/', $payment_card)){
        array_push($errors, 'Invalid credit card information');
    }
    if(empty($sec_num)){
        array_push($errors,'Security number for payment is missing');
    }else if(!preg_match('/^\d*$/', $sec_num)){
        array_push($errors, 'Invalid credit card information');
    }
    if(empty($exp_date)){
        array_push($errors,'Expiration date for payment is missing');
    }else{
        if(preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{2})$/",$exp_date)){
            $test_date = explode('-', $exp_date);
            if(!checkdate($test_date[0], $test_date[1], $test_date[2])) {
                array_push($errors, "Invalid Date or Format");
            }else{
                $date = date('m-d-Y', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
                $compare = explode('-',$date);
                if($compare[2] < $test_date[2]){
                    array_push($errors, "Card is expired");
                }else if($compare[2] == $test_date[2]){
                    if($compare[0] < $test_date[0]){
                        array_push($errors, "Card is expired");
                    }elseif($compare[0] == $test_date[0]){
                        if($compare[1]<$test_date[1]){
                           array_push($errors, "Card is expired");
                        }
                    }
                }
            }
            
        }else{
            array_push($errors, "Invalid Date or Format");
        }        
    }
    if(empty($bill_name)){
        array_push($errors,'Billing name is missing');
    }
    if(empty($bill_ad1)){
        array_push($errors,'Billing Address Line 1 is missing');
    }
    if(empty($bill_city)){
        array_push($errors,'Billing city is missing');
    }
    if(empty($bill_zip)){
        array_push($errors,'Billing zip is missing');
    }elseif(!preg_match('#[0-9]{5}#',$bill_zip)){
        array_push($errors, 'Invalid Zip Code, use a 5 digit zip');
    }
    
    if(count($errors) == 0){
        $_SESSION['view_total'] = "true";
    }
    
}
function checkout(){
    global $db, $errors,
    $ship_name, $ship_ad1, $ship_ad2, $ship_city, $ship_state, $ship_zip,
    $payment_name, $payment_card, $sec_num, $exp_date, $delivery,
    $bill_name, $bill_ad1, $bill_ad2, $bill_city, $bill_state, $bill_zip;
    $user = $_SESSION['user'];
    $user_id = $user['id_user'];
    $arrival_date = "";
    
    $ship_name = $_POST['ship_name'];
    $ship_ad1 = $_POST['ship_ad1'];
    $ship_ad2 = $_POST['ship_ad2'];
    $ship_city = $_POST['ship_city'];
    $ship_state = $_POST['ship_state'];
    $ship_zip = $_POST['ship_zip'];
    
    $payment_name = $_POST['payment_name'];
    $payment_card = $_POST['payment_card'];
    $sec_num = $_POST['sec_num'];
    $exp_date = $_POST['exp'];
    
    $bill_name = $_POST['bill_name'];
    $bill_ad1 = $_POST['bill_ad1'];
    $bill_ad2 = $_POST['bill_ad2'];
    $bill_city = $_POST['bill_city'];
    $bill_state = $_POST['bill_state'];
    $bill_zip = $_POST['bill_zip'];
    
    $delivery = $_POST['delivery'];
    
    $query_cart = "SELECT * FROM anguyen_store_cart WHERE user_id='$user_id'";
    $result_cart = mysqli_query($db,$query_cart);
    
    if (mysqli_num_rows($result_cart) > 0) {
        $query_ship = "INSERT  INTO `anguyen_store_entity_ship` (`user_id`,`name`,`ad1`,`ad2`,`city`,`state`,`zipcode`) VALUES ('$user_id','$ship_name','$ship_ad1','$ship_ad2','$ship_city','$ship_state','$ship_zip')";
        mysqli_query($db, $query_ship);
        $ship_id = $db->insert_id;

        $query_bill = "INSERT  INTO `anguyen_store_entity_bill` (`user_id`,`name`,`ad1`,`ad2`,`city`,`state`,`zipcode`) VALUES ('$user_id','$bill_name','$bill_ad1','$bill_ad2','$bill_city','$bill_state','$bill_zip')";
        mysqli_query($db, $query_bill);
        $bill_id = $db->insert_id;

        $query_pay = "INSERT INTO `anguyen_store_entity_pay` (`name`,`card_num`,`secure_num`,`exp_date`,`user_id`) VALUES ('$payment_name','$payment_card','$sec_num','$exp_date','$user_id')";
        mysqli_query($db, $query_pay) or die(mysqli_error($db));
        $pay_id = $db->insert_id;

        $cost = $_SESSION['total_cost'];

        switch ($delivery) {
            case 25:
                $arrival_date = date('m-d-Y', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')));
                break;
            case 15:
                $arrival_date = date('m-d-Y', mktime(0, 0, 0, date('m'), date('d') + 5, date('Y')));
                break;
            case 8:
                $arrival_date = date('m-d-Y', mktime(0, 0, 0, date('m'), date('d') + 7, date('Y')));
                break;
        }
        $order_date = date('m-d-Y', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
        echo $exp_date;

        $query_order = "INSERT INTO `anguyen_store_entity_order` (`user_id`,`status`,`ordered`,`arrive`,`total`,`pay_id`,`ship_id`,`bill_id`) VALUES ('$user_id','Proccessing','$order_date','$arrival_date','$cost','$pay_id','$ship_id','$bill_id')";
        mysqli_query($db, $query_order) or die(mysqli_error($db));
        $order_id = $db->insert_id;


        while ($row = mysqli_fetch_array($result_cart)) {
            $item_id = $row['item_id'];
            $num_item = $row['num_item'];
            $query_xref = "INSERT INTO `anguyen_store_xref_order_item` (`user_id`,`order_id`,`item_id`,`num_item`) VALUES ('$user_id','$order_id','$item_id', '$num_item')";
            mysqli_query($db, $query_xref);

            $query_item = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id='$item_id'";
            $result_item = mysqli_query($db, $query_item);
            $item_data = mysqli_fetch_assoc($result_item);
            $new_stock = $item_data['stock'] - $num_item;

            $query_update = "UPDATE anguyen_store_entity_inventory SET stock='$new_stock' WHERE item_id='$item_id'";
            $query_purchase = "UPDATE anguyen_store_entity_inventory SET purchased='$num_item' WHERE item_id='$item_id'";

            mysqli_query($db, $query_update);
            mysqli_query($db, $query_purchase);
        }

        $query_clear = "DELETE FROM anguyen_store_cart WHERE user_id='$user_id'";
        mysqli_query($db, $query_clear);
        unset($_SESSION['view_total']);
        $_SESSION['success'] = "Succesfully submitted order";
        header('location: index.php');
    }else{
        array_push($errors,"No item's in cart to purchase");
    }
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