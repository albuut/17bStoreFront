<?php include('login_functions.php');?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="header">
            <h2>Rock Shop - Home Page</h2>
        </div>
            <!-- notification message -->
            <form method="post" action="index.php">
                <?php echo display_error(); ?>
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="error success" >
                        <h3>
                            <?php
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </h3>
                    </div>
                <?php endif ?>
                <div class="input-group">
                    <?php 
                        if(isset($_SESSION['user'])){
                            $user_data = $_SESSION['user'];
                            if($user_data['user_type'] == "admin"){
                                echo '<button type="submit" class="btn" name="manage_users_btn">Manage Users</button><br>';
                                echo '<button type="submit" class="btn" name="manage_user_order_btn">Manage Order</button><br>';                                
                                echo '<button type="submit" class="btn" name="manage_inventory_btn">Manage Inventory</button><br>';
                                echo '<button type="submit" class="btn" name="logout_btn">Logout</button><br> ';
                            }else{
                                echo '<button type="submit" class="btn" name="browse_rocks_btn">Browse Rocks</button><br>';
                                echo '<button type="submit" class="btn" name="view_order_btn">View Orders</button><br>';
                                echo '<button type="submit" class="btn" name="view_cart_btn">View Cart</button><br>';
                                echo '<button type="submit" class="btn" name="manage_account_btn">Manage Account</button><br>';
                                echo '<button type="submit" class="btn" name="logout_btn">Logout</button><br> ';
                            }
                        }else{
                            echo '<button type="submit" class="btn" name="browse_rocks_btn">Browse Rocks</button><br>';
                            echo '<button type="submit" class="btn" name="login_btn">Login / Register</button><br> ';
                        }
                    ?>              
                </div>
            </form>
    </body>
</html>
