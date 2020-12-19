<?php include('inventory_functions.php') ?>
<html>
    <head>
        <title>View Order</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>View Order</h2> 
        </div>
        <!--Registration Form-->
        <form method="post" action="view_order.php" enctype="multipart/form-data">
            <div class='input-group'>
            <?php
                $user = $_SESSION['user'];
                $user_id = $user['id_user'];
                $query_order = "SELECT * FROM anguyen_store_entity_order WHERE user_id = '$user_id'";
                $result_order = mysqli_query($db,$query_order);
                echo '<select name="order">';
                while($row = mysqli_fetch_array($result_order)){
                    if($row['order_id'] == $_SESSION['view_order']){
                        echo '<option value="'.$row['order_id'].'"selected>Order #'.$row['order_id'].' on '.$row['ordered'].'</option>';
                    }else{
                        echo '<option value="'.$row['order_id'].'">Order #'.$row['order_id'].' on '.$row['ordered'].'</option>';
                    }
                }
                echo '</select>';
            ?>
            </div>
            <div clas='input-group'>
            <?php
                if(isset($_SESSION['view_order'])){
                    $user = $_SESSION['user'];
                    $order_id = $_SESSION['view_order'];
                    $query_info = "SELECT * FROM anguyen_store_entity_order WHERE order_id='$order_id'";
                    $result_info = mysqli_query($db,$query_info);
                    $data_info = mysqli_fetch_assoc($result_info);
                    
                    echo '<label><strong>User: </strong>'.$user['username'].'</label><br>';
                    echo '<label><strong>Order Status: </strong>'.$data_info['status'].'</label><br>';
                    echo '<label><strong>Date Ordered: </strong>'.$data_info['ordered'].'</label><br>';
                    echo '<label><strong>Estimated Arrival: </strong>'.$data_info['arrive'].'</label><br>';
                    
 
                    $query_items = "SELECT * FROM anguyen_store_xref_order_item WHERE order_id='$order_id'";
                    $result_item = mysqli_query($db,$query_items);                    
                                       
                }
            
            ?>
                <table class='cart_table'>
                <colgroup>
                    <col style="width:80px">
                    <col style="width:300px">
                    <col style="width:80px">
                    <col style="width:80px">
                    <col style="width:80px">
                    <col>

                </colgroup>
                <?php 
                if(isset($_SESSION['view_order'])){
                    echo'<tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>#</th>
                    </tr>';    
                    while($row = mysqli_fetch_array($result_item)){
                        $item_id = $row['item_id'];
                        $query_inventory = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id='$item_id'";
                        $result_inventory = mysqli_query($db,$query_inventory);
                        $item_data = mysqli_fetch_assoc($result_inventory);
                        $item_stock = $item_data['stock'];
                        $item_in_cart = $row['num_item'];
                        
                        $formatted = number_format((float)$item_data['price'], 2, '.', '');
                        
                        echo '<tr><td>'.$row['item_id'].'</td>';
                        echo '<td>'.$item_data['name'].'</td>';
                        echo '<td>$'.$formatted.'</td>';
                        echo '<td>'.$row['num_item'].'</td></tr>';
                    }
                }
                ?>                
            </table>
                <?php 
                if(isset($_SESSION['view_order'])){
                    echo '<label><strong>Total: $</strong>'.$data_info['total'].'</label><br>';
                }
                ?>
            </div>
            <div class ="input-group">
                <button type="submit" class="btn" name="home_btn">Back</button>                
                <?php 
                    if(mysqli_num_rows($result_order) > 0){
                        echo '<button type="submit" class="btn" name="view_order_btn">View</button>';
                    }
                ?> 
            </div>
 
        </form>
    </body>
</html>

