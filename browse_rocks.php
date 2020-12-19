<?php include('inventory_functions.php') ?>
<html>
    <head>
        <title>Browse</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>Browse Items</h2> 
        </div>
        <form method="post" action="browse_rocks.php">
            <?php echo display_error(); ?>
                        <form method="post" action="index.php">
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
            <div class =  "input-group">
                <select name="display">
                    <?php
                        $query = "SELECT * FROM anguyen_store_entity_inventory ORDER by name";
                        $result = mysqli_query($db,$query);
                        while($row = mysqli_fetch_array($result)){
                            if($_SESSION['view'] == $row['item_id']){
                                echo '<option value="'.$row['item_id'].'"selected>'.$row['name'].'</option>';
                            }else{
                                echo '<option value="'.$row['item_id'].'">'.$row['name'].'</option>';
                            }
                        } 
                    ?>                    
                </select>
                <?php 
                    if(isset($_SESSION['view'])){
                        if(isset($_SESSION['user'])){
                            $user = $_SESSION['user'];
                            $user_id = $user['id_user'];
                        }
                        $item_id = $_SESSION['view'];
                        
                        $query_item = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id='$item_id'";
                        $result_item = mysqli_query($db,$query_item);
                        $item_data = mysqli_fetch_assoc($result_item);
                        
                        $num_in_cart = 0;
                        
                        $item_image = $item_data['image'];
                        $item_name = $item_data['name'];
                        $item_desc = $item_data['description'];
                        $item_price = $item_data['price'];
                        $item_stock =$item_data['stock'];
                        
                        $formatted = number_format((float)$item_price, 2, '.', '');
                        
                        if(isset($_SESSION['user'])){
                            $query_cart = "SELECT * FROM anguyen_store_cart WHERE item_id='$item_id' AND user_id='$user_id'";
                            $result_cart = mysqli_query($db,$query_cart);
                            if(mysqli_num_rows($result_cart) != 0){
                                $data_cart = mysqli_fetch_assoc($result_cart);
                                $num_in_cart = $data_cart['num_item'];
                            }     
                        }
                        
                                           
                        
                        echo "<label><strong>Item Name: </strong></label>";
                        echo "<label>".$item_name."</label>";
                        echo "<label><strong>Item Description: </strong></label>";
                        echo "<label>".$item_desc."</label>";
                        echo "<label><strong>Item Stock: </strong></label>";
                        echo "<label>".$item_stock."</label>";
                        echo "<label><strong>In Cart: </strong></label>";
                        echo "<label>".$num_in_cart."</label>";
                        echo "<label><strong>Item Price: </strong></label>";
                        echo "<label>$$formatted</label><br>";
                        echo "<img src='images/".$item_id.$item_image."'width='250' height='250'><br>";
                        
                        echo '<label><strong># of Items</strong></label>';
                        echo '<select name="buy">';

                        for($x = 0; $x <= $item_stock - $num_in_cart; $x++){
                            echo '<option value="'.$x.'">'.$x.'</option>';
                        }
                        echo '</select>';
                        
                    }
                ?>
            </div>            
            <div class ="input-group">
                <button type="submit" class="btn" name="home_btn">Back</button>
                <button type="submit" class="btn" name="view_btn">View</button>
                <?php
                if (isset($_SESSION['view']) && isset($_SESSION['user'])) {
                    echo '<button type="submit" class="btn" name="buy_item_btn">Add Item</button>';
                }
                ?>          
            </div>
 
        </form>
    </body>
</html>
