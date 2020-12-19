<?php include('login_functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Create User</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Admin - Create User</h2>
	</div>
	
	<form method="post" action="create_user.php">
            <div class="input-group">
		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Username</label>
			<input type="text" name="username" value="<?php echo $username; ?>">
		</div>
		<div class="input-group">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email; ?>">
		</div>
		<div class="input-group">
			<label>User type</label>
			<select name="user_type" id="user_type" >
				<option value="user">User</option>
				<option value="admin">Admin</option>
			</select>
		</div>
		<div class="input-group">
			<label>Password</label>
			<input type="password" name="password_1">
		</div>
		<div class="input-group">
			<label>Confirm password</label>
			<input type="password" name="password_2">
		</div>
		<div class="input-group">
                        <button type="submit" class="btn" name="back_manage_btn">Back</button>
			<button type="submit" class="btn" name="register_btn">Create User</button>
		</div>
            </div>
	</form>
</body>
</html>