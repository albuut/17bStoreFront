<?php
include('inventory_functions.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Manage Inventory</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div class="header">
            <h2>Manage Inventory</h2>
        </div>
            <!-- notification message -->
            <form method="post" action="manage_inventory.php">
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
                    <label><strong>Add an item or edit</strong></label>
                    <select name="item">
                        <?php
                            $query_inventory = "SELECT * FROM anguyen_store_entity_inventory ORDER by name";
                            $result_inventory= mysqli_query($db,$query_inventory);
                            while($row = mysqli_fetch_array($result_inventory)){
                                echo '<option value="'.$row['item_id'].'">'.$row['name'].'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="input-group">
                    <button type="submit" class="btn" name="home_btn">Home</button>     
                    <button type="submit" class="btn" name="add_item_btn">Add Item</button>
                    <button type="submit" class="btn" name="modify_item_btn">Modify Item</button>
                    <button type="submit" class="btn" name="delete_item_btn">Delete Item</button>         
                </div>
            </form>
    </body>
</html>
