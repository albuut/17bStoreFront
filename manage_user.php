<?php
include('login_functions.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage User</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="header">
            <h2>Manage User</h2>
        </div>
            <!-- notification message -->
            <form method="post" action="manage_user.php">
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
                <div class ="input-group">
                    <?php echo display_error();?>
                    <label><strong>Pick a User or Create</strong></label>
                    <select name="username">
                        <?php
                            $query_username = "SELECT * FROM anguyen_store_entity_user";
                            $result_username = mysqli_query($db,$query_username);
                            while($row = mysqli_fetch_array($result_username)){
                                echo '<option value="'.$row['id_user'].'">'.$row['username'].'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <button type="submit" class="btn" name="create_user_btn">Create User</button>
                    <button type="submit" class="btn" name="edit_user_btn">Edit User</button>
                    <button type="submit" class="btn" name="delete_user_btn">Delete User</button>
                    <button type="submit" class="btn" name="home_btn">Home</button>              
                </div>
            </form>
    </body>
</html>
