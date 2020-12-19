<?php
include('login_functions.php');
if (!isLoggedIn()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage User</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
	<h2>Delete</h2>
    </div>
    <form method="post" action="delete_user.php">
        <div class="input-group">
            <?php echo display_error(); 
                $user_id = $_SESSION['delete_user_id'];
                $query_name = "SELECT * FROM anguyen_store_entity_user WHERE id_user='$user_id'";
                $result_name = mysqli_query($db, $query_name);
                $name_data = mysqli_fetch_array($result_name);
            
            ?> 
            <label><strong>Enter confirm to delete user "<?php echo $name_data['username'];?>"</strong></label>
            <input type="text" name ="confirm">
        </div>
        <div class="input-group">
            <button type="submit" class="btn" name="back_manage_btn">Exit</button>
            <button type="submit" class="btn" name="confirm_delete_btn">Delete</button>
        </div>
    </form>
</body>
</html>