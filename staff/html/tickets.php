<?php
    session_start();
    if ($_SESSION['state'] != 1) {
        $_SESSION['state'] = -1;
        header('Location:loginpage.php');
    }
    
    function check_order($conn,$items) {
        $imatch = true;
        
        $query = "SELECT * FROM orders";
        $result = $conn->query($query);
        if (!$result) die ("Database access failed: " . $conn->error);
        $rows = $result->num_rows;
        
        for ($j = 0; $j < $rows; ++$j) {
            $result->data_seek($j);
            $row = $result->fetch_array(MYSQLI_NUM);
            
            for ($k = 1; $k < 31; ++$k) {
                if ($items[$k - 1] != $row[$k]) {
                    $imatch = false;
                    break;
                }
            }
            
            if ($imatch == true) {
                return $row[0];
            }
            $imatch = true;
        }
        return 0;
    }
?>

<html>
    <head>
        <title>Staff Tickets View</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets & JS file-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/tickets.css">
        <script src="../js/tickets.js"></script>
    </head>
    <body>
        <!--Website Head-->
        <header>
            <img src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/images/logo.png" alt="logo" id="logo">
            <h1>Refined Dining</h1>
            <button type="button" onclick="location.href='loginpage.php'" class="btn btn-info btn-sm">Logout</button>
        </header>
        <nav>
            <p>
                <a href="dboard_staff.php">Dashboard</a>
                <a href="tickets.php">Tickets</a>
                <a href="queue.php">Queue</a>
                <a href="backresv.php">Reservations</a>
            </p>
        </nav>
        <!--Website Body-->
        <aside id="left">
            <h2>Add Ticket</h2>
            <?php
                require_once 'login_epl.php';
                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) die($conn->connect_error);
                
                /*  **ADD TICKET FORM**
                    *Adds a new ticket to the system both in the 'receipts' and 'orders' tables
                */
                
                if (isset($_POST['cname']) && isset($_POST['tablenum'])) {
                    $cname = get_full_strip($conn,'cname');
                    $tablenum = $_POST['tablenum'];
                    
                    if (!empty($cname) && !empty($tablenum)) {
                        $items = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                        
                        foreach ($_POST['items'] as $value) {
                            $idx = (int) $value;
                            --$idx;
                            $items[$idx]++;
                        }
                        
                        if (($orderid = check_order($conn,$items)) > 0) {
                            $stmt = $conn->prepare("INSERT INTO receipts VALUES(NULL,CURRENT_DATE,CURRENT_TIME,?,'active',?,?)");
                            $stmt->bind_param("sii",$cname,$tablenum,$orderid);
                            if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                            
                            header('Location: tickets.php');
                        }
                        else {
                            $stmt = $conn->prepare("INSERT INTO orders VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,0)");
                            $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiiiiiiii",$items[0],$items[1],$items[2],$items[3],$items[4],$items[5],$items[6],$items[7],
                                $items[8],$items[9],$items[10],$items[11],$items[12],$items[13],$items[14],$items[15],$items[16],$items[17],$items[18],
                                $items[19],$items[20],$items[21],$items[22],$items[23],$items[24],$items[25],$items[26],$items[27],$items[28],$items[29]);
                            if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                            
                            $orderid = check_order($conn,$items);
                            
                            $stmt = $conn->prepare("INSERT INTO receipts VALUES(NULL,CURRENT_DATE,CURRENT_TIME,?,'active',?,?)");
                            $stmt->bind_param("sii",$cname,$tablenum,$orderid);
                            if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                            
                            header('Location: tickets.php');
                        }
                    }
                    else {
                        echo "<p>You missed something.</p>";
                    }
                }
            ?>
            <form action="tickets.php" method="post">
                <label for="cname">Customer Name</label> <br class="hide">
                <input type="text" name="cname" id="cname">
                <br>
                <label for="tablenum">Table #</label> <br class="hide">
                <input type="number" name="tablenum" id="tablenum">
                <br>
                <table>
                    <tr>
                        <td>01<input type="checkbox" name="items[]" value="1"></td>
                        <td>02<input type="checkbox" name="items[]" value="2"></td>
                        <td>03<input type="checkbox" name="items[]" value="3"></td>
                        <td>04<input type="checkbox" name="items[]" value="4"></td>
                        <td>05<input type="checkbox" name="items[]" value="5"></td>
                        <td>06<input type="checkbox" name="items[]" value="6"></td>
                        <td>07<input type="checkbox" name="items[]" value="7"></td>
                        <td>08<input type="checkbox" name="items[]" value="8"></td>
                    </tr>
                    <tr>
                        <td>09<input type="checkbox" name="items[]" value="9"></td>
                        <td>10<input type="checkbox" name="items[]" value="10"></td>
                        <td>11<input type="checkbox" name="items[]" value="11"></td>
                        <td>12<input type="checkbox" name="items[]" value="12"></td>
                        <td>13<input type="checkbox" name="items[]" value="13"></td>
                        <td>14<input type="checkbox" name="items[]" value="14"></td>
                        <td>15<input type="checkbox" name="items[]" value="15"></td>
                        <td>16<input type="checkbox" name="items[]" value="16"></td>
                    </tr>
                    <tr>
                        <td>17<input type="checkbox" name="items[]" value="17"></td>
                        <td>18<input type="checkbox" name="items[]" value="18"></td>
                        <td>19<input type="checkbox" name="items[]" value="19"></td>
                        <td>20<input type="checkbox" name="items[]" value="20"></td>
                        <td>21<input type="checkbox" name="items[]" value="21"></td>
                        <td>22<input type="checkbox" name="items[]" value="22"></td>
                        <td>23<input type="checkbox" name="items[]" value="23"></td>
                        <td>24<input type="checkbox" name="items[]" value="24"></td>
                    </tr>
                    <tr>
                        <td>25<input type="checkbox" name="items[]" value="25"></td>
                        <td>26<input type="checkbox" name="items[]" value="26"></td>
                        <td>27<input type="checkbox" name="items[]" value="27"></td>
                        <td>28<input type="checkbox" name="items[]" value="28"></td>
                        <td>29<input type="checkbox" name="items[]" value="29"></td>
                        <td>30<input type="checkbox" name="items[]" value="30"></td>
                    </tr>
                </table>
                <br>
                <input type="submit" value="Add">
            </form>
        </aside>
        <section>
            <h2>Day's Tickets</h2>
            <div>
                <?php
                    /*  **TICKET DISPLAY PHP CODE**
                        *This code looks at 3 different tables to construct comprehensive tickets
                        - 'Active Tickets' (orders being served and not yet billed) are set to display as white
                        - 'Inactive Tickets' (orders billed and paid/to-be paid) are set to display as red
                    */
                    
                    $query1 = "SELECT receipt_num,DATE_FORMAT(time,'%l:%i %p'),customer_name,order_state,table_num,order_id FROM receipts WHERE order_state='active' 
                        OR date=CURRENT_DATE";
                    $result1 = $conn->query($query1);
                    if (!$result1) die ("Database access failed: " . $conn->error);
                    $rows1 = $result1->num_rows;
                    
                    if ($rows1 == 0) {
                        echo "No tickets that are 'active' or processed and paid today.";
                    }
                    else {
                        for ($j = 0; $j < $rows1; ++$j) {
                            $result1->data_seek($j);
                            $row1 = $result1->fetch_array(MYSQLI_NUM);
                            
                            if ($row1[3] == 'active') {
                                echo "<p class=\"active\"><b> Table #" . $row1[4] . " (" . $row1[1] . ") | Receipt #" .$row1[0]. "  \"" . $row1[2] . "\"</b><br>";
                            }
                            else if ($row1[3] == 'inactive') {
                                echo "<p class=\"paid\"><b> Table #" . $row1[4] . " (" . $row1[1] . ") | Receipt #" .$row1[0]. "  \"" . $row1[2] . "\"</b><br>";
                            }
                            
                            $query2 = "SELECT * FROM orders WHERE order_id=" . $row1[5];
                            $result2 = $conn->query($query2);
                            if (!$result2) die ("Database access failed: " . $conn->error);
                            $rows2 = $result2->num_rows;
                            
                            if ($rows2 != 1) {
                                echo "Order Retrieval Error.";
                            }
                            else {
                                $result2->data_seek($j);
                                $row2 = $result2->fetch_array(MYSQLI_NUM);
                                
                                for ($k = 1; $k < 31; ++$k) {
                                    if($row2[$k] > 0) {
                                        $query3 = "SELECT item_name FROM menu WHERE item_id=" . $k;
                                        $result3 = $conn->query($query3);
                                        if (!$result3) die ("Database access failed: " . $conn->error);
                                        $result3->data_seek($j);
                                        $row3 = $result3->fetch_array(MYSQLI_NUM);
                                        
                                        echo $row3[0] . "&nbsp;&nbsp;&nbsp; x" . $row2[$k] . "<br>";
                                    }
                                }
                                
                                if ($row1[3] == 'inactive') {
                                    echo "Total: $" . $row2[31];
                                }
                                echo "</p>";
                            }
                            echo "<br>";
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <?php
                /*  **TICKET ALTERING FORM**
                    inc: updates an item's quantity within a given receipt and order
                    update: updates a receipt's/order's state
                */
                
                if (isset($_POST['index']) && !empty($_POST['index'])) {
                    $index = $_POST['index'];
                    
                    $query = "SELECT order_id FROM receipts WHERE receipt_num=" . $index;
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $result->data_seek(0);
                    $oid = $result->fetch_assoc()['order_id'];
                    
                    if (($opt=$_POST['chg_opt']) == "inc") {
                        if (isset($_POST['itemid']) && isset($_POST['quantity']) && !empty($_POST['itemid']) && !empty($_POST['quantity'])) {
                            $itemid = $_POST['itemid'];
                            $quantity = $_POST['quantity'];
                            
                            $query = "SELECT receipt_num FROM receipts WHERE order_id=" . $oid;
                            $result = $conn->query($query);
                            if (!$result) die ("Database access failed: " . $conn->error);
                            $rows = $result->num_rows;
                            
                            if ($rows > 1) {
                                $items = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                                
                                $query = "SELECT * FROM orders WHERE order_id=" . $oid;
                                $result = $conn->query($query);
                                if (!$result) die ("Database access failed: " . $conn->error);
                                $rows = $result->num_rows;
                                
                                $result->data_seek(0);
                                $row = $result->fetch_array(MYSQLI_NUM);
                                
                                for ($j = 1; $j < 31; ++$j) {
                                    $items[$j - 1] = $row[$j];
                                }
                                
                                $items[((int) $itemid) - 1] = $quantity;
                                $check = check_order($conn,$items);
                                
                                if ($check) {
                                    $stmt = $conn->prepare("UPDATE receipts SET order_id=? WHERE receipt_num=?");
                                    $stmt->bind_param("ii",$check,$index);
                                    if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                }
                                else {
                                    $stmt = $conn->prepare("INSERT INTO orders VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,
                                        0,0)");
                                    $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiiiiiiii",$items[0],$items[1],$items[2],$items[3],$items[4],$items[5],
                                        $items[6],$items[7],$items[8],$items[9],$items[10],$items[11],$items[12],$items[13],$items[14],$items[15],
                                        $items[16],$items[17],$items[18],$items[19],$items[20],$items[21],$items[22],$items[23],$items[24],$items[25],
                                        $items[26],$items[27],$items[28],$items[29]);
                                    if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                                    
                                    $check = check_order($conn,$items);
                                    
                                    $stmt = $conn->prepare("UPDATE receipts SET order_id=? WHERE receipt_num=?");
                                    $stmt->bind_param("ii",$check,$index);
                                    if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                }
                            }
                            else {
                                $field = "item_" . $itemid;
                                $stmt = $conn->prepare("UPDATE orders SET " . $field . "=? WHERE order_id=?");
                                $stmt->bind_param("ii",$quantity,$oid);
                                if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                            }
                            
                            echo "<p>Receipt #" . $index . " Updated its State.</p>";
                            header('Location:tickets.php');
                        }
                        else {
                            echo "<p>Please fill all fields.</p>";
                        }
                    }
                    else if ($opt == "update") {
                        $bill = 0;
                        $tcost = 0;
                        
                        $query1 = "SELECT * FROM orders WHERE order_id=" . $oid;
                        $result1 = $conn->query($query1);
                        if (!$result1) die ("Database access failed: " . $conn->error);
                        $result1->data_seek(0);
                        $row1 = $result1->fetch_array(MYSQLI_NUM);
                        
                        $query2 = "SELECT item_id,price,cost FROM menu";
                        $result2 = $conn->query($query2);
                        if (!$result2) die ("Database access failed: " . $conn->error);
                        $rows2 = $result2->num_rows;
                        
                        for ($j = 1; $j < 31; ++$j) {
                            $result2->data_seek($j);
                            $row2 = $result->fetch_assoc(MYSQLI_NUM);
                            if ($j == $row2[0]) {
                                $bill += $row1[$j] * $row2[1];
                                $tcost += $row1[$j] * $row2[2];
                            }
                        }
                        
                        $query  = "UPDATE orders SET bill=" . $bill . " WHERE order_id=" . $oid;
                        $result = $conn->query($query);
                        if (!$result) die ("Database access failed: " . $conn->error);
                        
                        $query  = "UPDATE orders SET total_cost=" . $tcost . " WHERE order_id=" . $oid;
                        $result = $conn->query($query);
                        if (!$result) die ("Database access failed: " . $conn->error);
                        
                        $query = "UPDATE receipts SET order_state='inactive' WHERE receipt_num=" . $index;
                        $result = $conn->query($query);
                        if (!$result) die ("Database access failed: " . $conn->error);
                        
                        echo "<p>Receipt #" . $index . " Updated.</p>";
                        header('Location: tickets.php');
                    }
                    else {
                        echo "<p>Please indicate the index.</p>";
                    }
                }
                else {
                    echo "<p>Please indicate the index.</p>";
                }
                
                /*  **STRIPPING FUNCTION**
                    *Strips the input of all potential injections
                */
                
                function get_full_strip($conn, $var) {
                    $var = $conn->real_escape_string($_POST[$var]);
                    $var = stripslashes($var);
                    $var = strip_tags($var);
                    $var = htmlentities($var);
                    return $var;
                }
            ?>
            <form action="tickets.php" method="post">
                <select name="chg_opt" id="chg_opt" onchange="changeoption()">
                    <option value="update" selected>Push Ticket</option>
                    <option value="inc">Update Item</option>
                </select>
                <br class="response">
                <label for="index">Receipt #</label>
                <input type="number" name="index" id="index">
                <br class="response">
                <label for="itemid">Item #</label>
                <input type="number" name="itemid" id="itemid" disabled>
                <br class="response">
                <label for="quantity">Item Quantity</label>
                <input type="number" name="quantity" id="quantity" disabled>
                <br class="response">
                <input type="submit" value="Update">
            </form>
        </section>
        <!--Website Footer-->
        <br class="hide">
        <footer>
            <p id="ffront">&#169; Darius Zhou, 2020</p>
        </footer>
    </body>
</html>