<?php include('login_functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Edit User</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Admin - Edit User</h2>
	</div>
	
	<form method="post" action="edit_user.php">
            <div class="input-group">
		<?php echo display_error(); ?>
                <?php
                    if(isset($_SESSION['edit_user'])){
                        $user_id = $_SESSION['edit_user'];
                        $query_user = "SELECT * FROM anguyen_store_entity_user WHERE id_user='$user_id'";
                        $result_user = mysqli_query($db,$query_user);
                        $user_data = mysqli_fetch_assoc($result_user);
                        
                        $username = $user_data['username'];
                        $email = $user_data['email'];
                        $new_pass1 = "";
                        $new_pass2 = "";
                    }
                
                ?>
		    <div class="input-group">
                        <label>Username</label>
                            <input type="text" name="username" value="<?php echo $username;?>" required>
                    </div>
                    <div class="input-group">
                        <label>Email</label>
                            <input type="text" name="email" value="<?php echo $email;?>" required>
                    </div>
                    <div class="input-group">
			<label>User type</label>
			<select name="user_type" id="user_type" >
				<?php
                                    if($user_data['user_type'] == "user"){
                                        echo '<option value="user" selected="selected">User</option>';
                                        echo '<option value="admin">Admin</option>';
                                    }else{
                                        echo '<option value="user">User</option>';
                                        echo '<option value="admin" selected="selected">Admin</option>';
                                    }
                                
                                
                                ?>
			</select>
                    </div>
                    <div class="input-group">
			<label>New Password</label>
                        <input type="password" name="new_pass1" value="<?php echo $new_pass1?>">
                    </div>
                    <div class="input-group">
			<label>Confirm New Password</label>
                        <input type="password" name="new_pass2" value="<?php echo $new_pass2?>">
                    </div>
                    <div class="input-group">
			<label>Current Password</label>
			<input type="password" name="password">
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn" name="manage_account_confirm_btn">Confirm</button>
                    </div>
            </div>
	</form>
</body>
</html>