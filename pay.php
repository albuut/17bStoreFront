<?php include('payment_function.php') ?>
<html>
    <head>
        <title>Checkout</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class = "header">
            <h2>Payment Information</h2> 
        </div>
        <!--Registration Form-->
        <form method="post" action="pay.php" >
            <?php echo display_error(); ?>
            <div class="input-group">                
                <label><strong>Shipping Information</strong></label>
                <label>Name</label>
                <input type="text" name="ship_name" value="<?php echo $ship_name; ?>">
                <label>Address Line 1</label>
                <input type="text" name="ship_ad1" value="<?php echo $ship_ad1; ?>">
                <label>Address Line 2</label>
                <input type="text" name="ship_ad2" value="<?php echo $ship_ad2; ?>">
                <label>City</label>
                <input type="text" name="ship_city" value="<?php echo $ship_city; ?>">
                <label>State</label>
                <select name="ship_state">
                    <?php 
                        $query_state_ship = "SELECT * FROM anguyen_store_entity_state";
                        $result_state_ship = mysqli_query($db, $query_state_ship);
                        while($row = mysqli_fetch_array($result_state_ship)){
                            if($ship_state == $row['abbreviation']){
                                echo '<option value="'.$row['abbreviation'].'" selected>'.$row['name'].'</option>';
                            }else{
                                 echo '<option value="'.$row['abbreviation'].'">'.$row['name'].'</option>';
                            }
                        }            
                    ?>
                </select>
                <label>Zip Code</label>                
                <input type="number" name="ship_zip" value="<?php echo $ship_zip; ?>">
                <label><strong>Payment Information</strong></label>  
                <label>Name</label>
                <input type="text" name="payment_name" value="<?php echo $payment_name; ?>">
                <label>Credit Card Number</label>
                <input type="number" name="payment_card" value="<?php echo $payment_card; ?>">
                <label>Security Number</label>
                <input type="number" name="sec_num" value="<?php echo $sec_num; ?>">
                <label>Expiration Date (mm-dd-yy)</label>
                <input type="text" name="exp" value="<?php echo $exp_date ?>" />                
                <label><strong>Billing Information</strong></label>
                <label>Name</label>
                <input type="text" name="bill_name" value="<?php echo $bill_name; ?>">
                <label>Address Line 1</label>
                <input type="text" name="bill_ad1" value="<?php echo $bill_ad1; ?>">
                <label>Address Line 2</label>
                <input type="text" name="bill_ad2" value="<?php echo $bill_ad2; ?>">
                <label>City</label>
                <input type="text" name="bill_city" value="<?php echo $bill_city; ?>">
                <label>State</label>
                <select name="bill_state">
                    <?php
                    $query_state_bill = "SELECT * FROM anguyen_store_entity_state";
                    $result_state_bill = mysqli_query($db, $query_state_bill);
                    while ($row = mysqli_fetch_array($result_state_bill)) {
                        if ($bill_state == $row['abbreviation']) {
                            echo '<option value="' . $row['abbreviation'] . '" selected>' . $row['name'] . '</option>';
                        } else {
                            echo '<option value="' . $row['abbreviation'] . '">' . $row['name'] . '</option>';
                        }
                    }
                    ?>
                </select>
                <label>Zip Code</label>
                <input type="number" name="bill_zip" value="<?php echo $bill_zip; ?>">
            </div>
                <div class ="radio-group">
                    <label><strong>Delivery Option</strong></label><br>
                    <input type="radio" id="2day" name="delivery" value="25">
                    <label>2 Day Delivery       ($25)</label><br>
                    <input type="radio" id="3day" name="delivery" value="15">
                    <label>3-5 Day Delivery   ($15)</label><br>
                    <input type="radio" id="7day" name="delivery" value="8" checked>
                    <label>7-14 Day Delivery   ($8)</label>
                    <p id="delivery"></p>
                </div>
            <div class ="input-group">
                <label><strong>Order Summary</strong></label>
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
                    </tr>    
                    <?php
                    $user = $_SESSION['user'];
                    $user_id = $user['id_user'];
                    $query_cart = "SELECT * FROM anguyen_store_cart WHERE user_id='$user_id'";
                    $result_cart = mysqli_query($db, $query_cart);
                    $list_item_id = array();
                    $total_cart = 0;
                    while ($row = mysqli_fetch_array($result_cart)) {
                        $item_id = $row['item_id'];
                        $query_item = "SELECT * FROM anguyen_store_entity_inventory WHERE item_id='$item_id'";
                        $result_item = mysqli_query($db, $query_item);
                        $item_data = mysqli_fetch_assoc($result_item);
                        $item_stock = $item_data['stock'];
                        $item_in_cart = $row['num_item'];

                        $formatted = number_format((float) $item_data['price'], 2, '.', '');

                        echo '<tr><td>' . $row['item_id'] . '</td>';
                        echo '<td>' . $item_data['name'] . '</td>';
                        echo '<td>$' . $formatted . '</td>';
                        echo '<td>' . $row['num_item'] . '</td>';
                        echo '</select></td></tr>';
                        array_push($list_item_id, $item_id);
                        $total_cart += $item_in_cart * $item_data['price'];
                    }
                    $_SESSION['list_item_id'] = $list_item_id;
                    ?>                
                </table>
                <?php 
                    if(isset($_SESSION['view_total'])){
                        $total_cart_print = number_format((float) $total_cart, 2, '.', '');
                        echo '<br><strong> Items: </strong>$'.$total_cart_print.'<br>';
                        $delivery_print = number_format((float) $delivery, 2, '.', '');
                        echo '<strong> Shipping: </strong>$'.$delivery_print.'<br>';
                        $tax = $total_cart * .09;                        
                        $tax_print = number_format((float) $tax, 2, '.', '');
                        echo '<strong> Tax 9%: </strong>$'.$tax_print.'<br>';
                        $total_payment = $tax + $total_cart + $delivery;
                        $total_payment_print = number_format((float) $total_payment, 2, '.', '');
                        echo '<strong> Total: </strong>$'.$total_payment_print.'<br><br>';
                        switch($delivery){
                            case 25:
                                echo '<strong> Estimated Arrival Date: </strong>'.date('m-d-Y', mktime(0, 0, 0, date('m'), date('d') + 2, date('Y'))).'<br>';
                                break;
                            case 15;
                                echo '<strong> Estimated Arrival Date: </strong>'.date('m-d-Y', mktime(0, 0, 0, date('m'), date('d') + 5, date('Y'))).'<br>';
                                break;
                            case 8:
                                echo '<strong> Estimated Arrival Date: </strong>'.date('m-d-Y', mktime(0, 0, 0, date('m'), date('d') + 7, date('Y'))).'<br>';
                                break;
                        }
                        $_SESSION['total_cost'] = $total_payment;
                    }
                ?>
            </div>
            
        <div class ="input-group">
            
            <br><button type="submit" class="btn" name="home_btn">Back</button>
            <button type="submit" class="btn" name="view_total_btn">View Total</button>
            <?php 
                if(isset($_SESSION['view_total'])){
                    echo '<button type="submit" class="btn" name="checkout_btn">Purchase</button>';
                }
            ?>
            
        </div>
 
        </form>
    </body>
</html>