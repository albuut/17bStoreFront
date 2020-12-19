<?php include('login_functions.php') ?>
<html>
    <head>
        <title>User Registration</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>Survey User Registration</h2> 
        </div>
        <!--Registration Form-->
        <form method="post" action="register.php">
            <?php echo display_error(); ?>
            <div class ="input-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo $username; ?>">                
            </div>
            <div class ="input-group">
                <label>Email</label>
                <input type="email" name="email" value="<?php echo $email; ?>">             
            </div>
            <div class ="input-group">
                <label>Password</label>
                <input type="password" name="password_1" value="">                
            </div>
            <div class ="input-group">
                <label>Confirm Password</label>
                <input type="password" name="password_2" value="">                
            </div>
            <div class ="input-group">
                <button type="submit" class="btn" name="register_btn">Register</button>            
            </div>
            <p>
                Already a member? <a href="login.php">Sign in</a>
            </p>          
        </form>
    </body>
</html>

