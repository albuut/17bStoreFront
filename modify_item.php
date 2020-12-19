<?php include('inventory_functions.php') ?>
<html>
    <head>
        <title>Manage Inventory</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>Modify Item</h2> 
        </div>
        <form method="post" action="modify_item.php" enctype="multipart/form-data">
            <?php echo display_error(); 
                if(isset($_SESSION['first'])){
                    $item_id = $_SESSION['modify_item_id'];
                    $query_item = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id ='$item_id'";
                    $result_item = mysqli_query($db, $query_item);
                    $data_item = mysqli_fetch_assoc($result_item);                    
                    
                    $_SESSION['item_data'] = $data_item;
                    
                    $item_name = $data_item['name'];
                    $item_desc = $data_item['description'];
                    $item_stock = $data_item['stock'];
                    $item_price = $data_item['price'];
                    
                    unset($_SESSION['first']);
                }
            ?>
            <div class =  "input-group">
                <label>Item Name</label>
                <input type="text" name="item_name" value="<?php echo $item_name; ?>">                
            </div>
            <div class ="input-group">
                <label>Item Description</label>
                <input type="text" name="item_desc" value="<?php echo $item_desc; ?>">             
            </div>
            <div class ="input-group">
                <label>Stock</label>
                <input type="number" name="item_stock" value="<?php echo $item_stock; ?>"/>                
            </div>
            <div class ="input-group">
                <label>Price</label>
                <input type="number" name="item_price" step="0.01" value="<?php echo $item_price; ?>"/>                
            </div>
            <div class ="input-group">
                <img src='images/<?php echo $item_id.$data_item['image']; ?>' width="250" height="250">               
            </div>
            <div class ="input-group">
                <label>Image</label>
                <input type="file" name="file" id="file">                
            </div>
            <div class ="input-group">
                <button type="submit" class="btn" name="manage_inventory_btn">Back</button>
                <button type="submit" class="btn" name="confirm_modify_item_btn">Modify Item</button>            
            </div>
 
        </form>
    </body>
</html>
