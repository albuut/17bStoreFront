<?php include('login_functions.php') ?>

<!DOCTYPE html>
<html>
<head>
	<title>Account Management</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="header">
	<h2>Account Management</h2>
            </div>
            <form method="post" action="account.php">
                <?php echo display_error(); ?>
                <?php
                if(isset($_SESSION['manage'])){
                    $user = $_SESSION['user'];
                    $username = $user['username'];
                    $email = $user['email'];
                    $new_pass1 = "";
                    $new_pass2 = "";
                    unset($_SESSION['manage']);
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
            </form>
</body>
</html>