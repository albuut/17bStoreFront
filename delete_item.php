<?php include('inventory_functions.php'); 
    $item_id = $_SESSION['delete_item_id'];
    $query_item = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id ='$item_id'";
    $result_item = mysqli_query($db, $query_item);
    $data_item = mysqli_fetch_assoc($result_item);
    $_SESSION['delete_data'] = $data_item;
?>
<html>
    <head>
        <title>Manage Inventory</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>Delete Item - <?php echo $data_item['name'];?></h2> 
        </div>
        <!--Registration Form-->
        <form method="post" action="delete_item.php">
            <?php echo display_error(); ?>
            <label><strong>Submit confirm to delete "<?php echo $data_item['name']; ?>"</strong></label>
            <div class="input-group">
                <input type="text" name="confirm">
            </div>
            <div class ="input-group">
                <button type="submit" class="btn" name="manage_inventory_btn">Back</button>
                <button type="submit" class="btn" name="confirm_delete_item_btn">Delete Item</button>            
            </div>
 
        </form>
    </body>
</html>
