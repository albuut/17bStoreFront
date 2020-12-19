<?php include('inventory_functions.php') ?>
<html>
    <head>
        <title>Manage Inventory</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>Add Item</h2> 
        </div>
        <!--Registration Form-->
        <form method="post" action="add_item.php" enctype="multipart/form-data">
            <?php echo display_error(); ?>
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
                <input type="number" name="item_stock" value="<?php echo $item_stock; ?>">                
            </div>
            <div class ="input-group">
                <label>Price</label>
                <input type="number" name="item_price" value="$<?php echo $item_price;?>" step="0.01">                
            </div>
            <div class ="input-group">
                <label>Image</label>
                <input type="file" name="file" id="file">                
            </div>
            <div class ="input-group">
                <button type="submit" class="btn" name="manage_inventory_btn">Back</button>
                <button type="submit" class="btn" name="confirm_add_item_btn">Add Item</button>            
            </div>
 
        </form>
    </body>
</html>

