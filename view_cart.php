<?php include('inventory_functions.php') ?>
<html>
    <head>
        <title>View Cart</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>Cart</h2> 
        </div>
        <!--Registration Form-->
        <form method="post" action="view_cart.php" enctype="multipart/form-data">
            <table class='cart_table'>
                <colgroup>
                    <col style="width:80px">
                    <col style="width:300px">
                    <col style="width:80px">
                    <col style="width:80px">
                    <col style="width:80px">
                    <col>

                </colgroup>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Price</th>
                    <th>In Cart</th>
                    <th>Set Item</th>
                </tr>    
                <?php 
                    $user = $_SESSION['user'];
                    $user_id = $user['id_user'];
                    $query_cart = "SELECT * FROM anguyen_store_cart WHERE user_id='$user_id'";
                    $result_cart = mysqli_query($db, $query_cart);
                    $list_item_id = array();
                    $total_cart = 0;
                    while($row = mysqli_fetch_array($result_cart)){
                        $item_id = $row['item_id'];
                        $query_item = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id='$item_id'";
                        $result_item = mysqli_query($db,$query_item);
                        $item_data = mysqli_fetch_assoc($result_item);
                        $item_stock = $item_data['stock'];
                        $item_in_cart = $row['num_item'];
                        
                        $formatted = number_format((float)$item_data['price'], 2, '.', '');
                        
                        echo '<tr><td>'.$row['item_id'].'</td>';
                        echo '<td>'.$item_data['name'].'</td>';
                        echo '<td>$'.$formatted.'</td>';
                        echo '<td>'.$row['num_item'].'</td>';
                        echo '<td><select name="modify[]">';
                        for($x = 0; $x <= $item_stock; $x++){                            
                            if($x == $item_in_cart){
                                echo '<option value="'.$x.'" selected>'.$x.'</option>';
                            }else{
                                echo '<option value="'.$x.'">'.$x.'</option>';
                            }                            
                        }
                        echo '</select></td></tr>';
                        array_push($list_item_id,$item_id);
                        $total_cart += $item_in_cart * $item_data['price'];
                    }
                    $_SESSION['list_item_id'] = $list_item_id;
                ?>                
            </table>
            <td><strong> Total: </strong>$<?php echo $total_cart;?></td>
            
            
            <div class ="input-group">
                <button type="submit" class="btn" name="home_btn">Back</button>                
                <?php 
                    if(mysqli_num_rows($result_cart) > 0){
                        echo '<button type="submit" class="btn" name="modify_cart_btn">Set Item</button>';
                        echo '<button type="submit" class="btn" name="input_shipping_btn">Checkout</button>';
                    }
                ?> 
            </div>
 
        </form>
    </body>
</html>

