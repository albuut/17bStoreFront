<?php 
session_start();

// connect to database
$db = mysqli_connect('209.129.8.7', 'RCCCSCCIS17B', '4921449288', 'RCCCSCCIS17B');

// variable declaration
$username = "";
$email    = "";
$new_pass1 = "";
$new_pass2 = "";
$errors   = array(); 

// call the register() function if register_btn is clicked
if (isset($_POST['register_btn'])) {
    register();
}
if (isset($_POST['login_btn'])){
    header('location: login.php');
}
if (isset($_POST['enter_login_btn'])) {
    login();
}
if (isset($_POST['logout_btn'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: index.php");
}
if (isset($_POST['manage_account_btn'])) {
    $_SESSION['manage'] = "not set";
    $_SESSION['manage_self'] = "set";
    $user = $_SESSION['user'];
    $user_id = $user['id_user'];
    $_SESSION['edit_user'] = $user_id;
    header('location: account.php');
}
if (isset($_POST['manage_account_confirm_btn'])) {
    global $db, $username, $email, $new_pass1, $new_pass2, $errors;
    $user = $_SESSION['user'];
    $user_id = $_SESSION['edit_user'];
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_pass1 = $_POST['new_pass1'];
    $new_pass2 = $_POST['new_pass2'];
    $current = $_POST['password'];
    
    $query_account_info = "SELECT * FROM anguyen_store_entity_user WHERE id_user='$user_id'";
    $result_account = mysqli_query($db,$query_account_info);
    $account_data = mysqli_fetch_assoc($result_account);
    
    $user_type = "";
    
    if(isset($_SESSION['manage_self'])){
        $user_type = $user['user_type'];
    }else{
        $user_type = $_POST['user_type'];
    }    
    if($username == $account_data['username'] && $email == $account_data['email'] && empty($new_pass1) && empty($new_pass2) && $account_data['user_type'] == $user_type){
        if(isset($_SESSION['manage_self'])){
            $_SESSION['success'] = "No changes made to account";
            unset($_SESSION['manage_self']);
            header('location: index.php');
        }else{
            $_SESSION['success'] = "No changes made to account";
            header('location: manage_user.php');
        }
    }    
    if(strcasecmp($username,$account_data['username']) == 0){
        $query = "SELECT * FROM anguyen_store_entity_user WHERE username='$username'";
        $dupe = mysqli_query($db, $query);
        $dupe_user = mysqli_fetch_array($dupe);
        if($dupe_user['id_user'] != $account_data['id_user']){
            if(mysqli_num_rows($dupe) != 0){
                array_push($errors,"Username is already in use");
            }
        }
    }
    if(strcasecmp($email,$account_data['email']) == 0){
        $query = "SELECT * FROM anguyen_store_entity_user WHERE email='$email'";
        $dupe = mysqli_query($db, $query);
        $dupe_email = mysqli_fetch_array($dupe);
        if($dupe_email['id_user'] != $user_id){
            if(mysqli_num_rows($dupe) != 0){
                array_push($errors,"Email is already in use");
            }
        }
    }
    if(!empty($new_pass1) || !empty($new_pass2)){
        if (!preg_match('@[a-z]@', $new_pass1)) {
            array_push($errors, "The password is missing a lowercase letter");
        }
        if (!preg_match('@[A-Z]@', $new_pass1)) {
            array_push($errors, "The password is missing a uppercase letter");
        }
        if (!preg_match('@[0-9]@', $new_pass1)) {
            array_push($errors, "The password is missing a number");
        }
        if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $new_pass1)) {
            array_push($errors, "The password is missing a special character");
        }
        if (strlen($new_pass1) < 8) {
            array_push($errors, "The password needs to be at least 8 characters");
        }
        if ($new_pass1 != $new_pass2){
            array_push($errors, "The two passwords do not match");
        }
    }   
    $password = md5($current);        
    if($password != $user['password']){
        if(empty($current)){
            array_push($errors,"Please input current password");
        }else{
            array_push($errors,"Current Password is incorrect");
        }
    }
    
    if(count($errors) == 0){
        $update_username = "UPDATE anguyen_store_entity_user SET username='$username' WHERE id_user='$user_id'";
        mysqli_query($db,$update_username);
        $update_email = "UPDATE anguyen_store_entity_user SET email='$email' WHERE id_user='$user_id'";
        mysqli_query($db,$update_email);

        if(!empty($new_pass1)){
            $new_password = md5($new_pass1);
            $update_password = "UPDATE anguyen_store_entity_user SET password='$new_password' WHERE id_user='$user_id'";
            mysqli_query($db,$update_password);
        }
        
        if($account_data['user_type'] != $_POST['user_type']){
            $user_type = $_POST['user_type'];
            $update_type = "UPDATE anguyen_store_entity_user SET user_type='$user_type' WHERE id_user='$user_id'";
            mysqli_query($db,$update_type);
        }
        
        
        $query_user = "SELECT * FROM anguyen_store_entity_user WHERE id_user ='$user_id'";
        $result = mysqli_query($db,$query_user);
        $updated_user = mysqli_fetch_assoc($result);
        
        
        $_SESSION['success']  = "Account information updated";
        if($user['user_type'] == "user"){
            $_SESSION['user'] = $updated_user;
            unset($_SESSION['edit_user']);
            header('location: index.php');
        }else{
            if($user['id_user'] == $account_data['id_user']){
                $_SESSION['user'] = $updated_user;
            }
            unset($_SESSION['edit_user']);
             header('location: manage_user.php');
        }

    }
}
if (isset($_POST['manage_inventory_btn'])){
    header('location: manage_inventory.php');
}
if (isset($_POST['view_cart_btn'])){
    header('location: view_cart.php');
}
if (isset($_POST['view_order_btn'])){
    header('location: view_order.php');
}
if (isset($_POST['manage_user_order_btn'])){
    header('location: modify_order.php');
}



if (isset($_POST['browse_rocks_btn'])){
    header('location: browse_rocks.php');
}
if (isset($_POST['choose_btn'])) {
    header('location: choose_survey.php');
}
if (isset($_POST['back_manage_btn'])){
    header('location: manage_user.php');
}
if (isset($_POST['manage_users_btn'])){
    header('location: manage_user.php');
}
if (isset($_POST['create_user_btn'])){
    header('location: create_user.php');
}
if (isset($_POST['confirm_delete_btn'])){
    global $errors, $db;
    $confirm = $_POST['confirm'];
    if(strtolower($_POST['confirm']) == "confirm"){
        $user_id = $_SESSION['delete_user_id'];
        $query_delete_user = "DELETE FROM anguyen_store_entity_user WHERE id_user='$user_id'";
        $result_delete = mysqli_query($db,$query_delete_user);    
        unset($_SESSION['delete_user_id']);
        $_SESSION['success'] = "Succesfully deleted a user";
        header('location: manage_user.php');
    }else{
        array_push($errors,"Please input confirm properly down below to delete.");
    }
}
if(isset($_POST['delete_user_btn'])){
    global $db, $errors;
    $user = $_SESSION['user'];
    $current_id = $user['id_user'];
    $user_id = $_POST['username'];
    
    if($user_id == $current_id){
        array_push($errors,"You can't delete yourself");
    }else{
        $_SESSION['delete_user_id'] = $_POST['username'];
        header('location: delete_user.php');
    }
}
if (isset($_POST['edit_user_btn'])) {
    if(!empty($_POST['username'])){
        $_SESSION['edit_user'] = $_POST['username'];
        header('location: edit_user.php');
    }else{
        push_array($errors,"No users available to edit");
    }
}
if(isset($_POST['home_btn'])){
   header('location:index.php');
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;
	// receive all input values from the form. Call the e() function
        // defined below to escape form values
	$username    =  e($_POST['username']);
	$email       =  e($_POST['email']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}else{
            $query = "SELECT * FROM anguyen_store_entity_user WHERE username='$username'";
            $dupeUser = mysqli_query($db, $query);
            if (mysqli_num_rows($dupeUser) != 0) {
                array_push($errors, "Username is already in use");
            }
        }
	if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}else{
            $query = "SELECT * FROM anguyen_store_entity_user WHERE email='$email'";
            $dupeEmail = mysqli_query($db, $query);
            if(mysqli_num_rows($dupeEmail) != 0){
                array_push($errors,"Email is already in use");
            }
        }        
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}else{
            $pass_errors = 0;
            if(!preg_match('@[a-z]@', $password_1)){
                array_push($errors, "The password is missing a lowercase letter");
                $pass_errors++;
            }
            if(!preg_match('@[A-Z]@', $password_1)){
                array_push($errors, "The password is missing a uppercase letter");
                $pass_errors++;
            }
            if(!preg_match('@[0-9]@', $password_1)){
                array_push($errors, "The password is missing a number");
                $pass_errors++;
            }
            if(!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password_1)){
                array_push($errors, "The password is missing a special character");
                $pass_errors++;
            }
            if(strlen($password_1) < 8){
                array_push($errors, "The password needs to be at least 8 characters");
                $pass_errors++;
            }
            if ($password_1 != $password_2 && $pass_errors == 0) {
		array_push($errors, "The two passwords do not match");
            }            
        }

	// register user if there are no errors in the form
	if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database
		if (isset($_POST['user_type']) && isset($_SESSION['user'])) {
			$user_type = e($_POST['user_type']);
			$query = "INSERT INTO `anguyen_store_entity_user`(`username`, `email`, `user_type`, `password`) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			mysqli_query($db, $query);
			$_SESSION['success']  = "New user successfully created!!";
			header('location: manage_user.php');
		}else if(!isset($_SESSION['user'])){
			$query = "INSERT INTO `anguyen_store_entity_user`(`username`, `email`, `user_type`, `password`) 
					  VALUES('$username', '$email', 'user', '$password')";
			mysqli_query($db, $query);

			// get id of the created user
			$logged_in_user_id = mysqli_insert_id($db);
                        $query_user_info = "SELECT * FROM anguyen_store_entity_user WHERE id_user='$logged_in_user_id'";
                        $user_result = mysqli_query($db,$query_user_info);
                        
                        $_SESSION['user'] = mysqli_fetch_assoc($user_result);
			$_SESSION['success']  = "Successfully registered";
			header('location: index.php');				
		}
	}
}
function login(){
	global $db, $username, $errors;
	// grab form values
	$username = e($_POST['username']);
	$password = e($_POST['password']);
	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}
	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);
		$query = "SELECT * FROM anguyen_store_entity_user WHERE username='$username' AND password='$password' LIMIT 1";
		$results = mysqli_query($db, $query);
		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			$logged_in_user = mysqli_fetch_assoc($results);
			$_SESSION['user'] = $logged_in_user;
                        $_SESSION['success'] = "You are now logged in";
                        header('location: index.php');
                }else {
                    array_push($errors, "Wrong username/password combination");
		}
	}
}
// return user array from their id
function getUserById($id){
	global $db;
	$query = "SELECT * FROM users WHERE id=" . $id;
	$result = mysqli_query($db, $query);
	$user = mysqli_fetch_assoc($result);
	return $user;
}

// escape string
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

function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	}else{
		return false;
	}
}
function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		return true;
	}else{
		return false;
	}
}
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}