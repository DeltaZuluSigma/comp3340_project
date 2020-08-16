<?php
    session_start();
    if ($_SESSION['state'] != 2) {
        $_SESSION['state'] = -1;
        header('Location:../staff/html/loginpage.php');
    }
    
    require_once 'login_prj.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    if ($conn->connect_error) die($conn->connect_error);
    
    function get_full_strip($conn, $var) {
        $var = $conn->real_escape_string($_POST[$var]);
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $var;
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
    
    $table = $_GET['table'];
?>

<html>
    <head>
        <title>Manager Records Review</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets & JS file-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/records.css">
        <script src="../js/records.js"></script>
    </head>
    <body>
        <!--Website Head-->
        <header>
            <img src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/images/logo.png" alt="logo" id="logo">
            <h1>Refined Dining</h1>
            <button type="button" onclick="location.href='../staff/html/loginpage.php'" class="btn btn-info btn-sm">Logout</button>
        </header>
        <nav class="hide">
            <p>
                <a href="dboard_admin.php">Dashboard</a>
                <a href="charts.php">Live & Full Charts</a>
                <a href="records.php">Records Review</a>
            </p>
        </nav>
        <!--Website Body-->
        <div id="warning" class="response">For security reasons, all pages accessible to managers, owners, administrators, etc. will not display for mobile use.</div>
        <aside id="left" class="hide">
            <h2>Create Record</h2>
            <?php
                /*  **CREATION PHP CODE**
                    *Performs the addition of an entry to the selected database.
                */
                
                switch ($table) {
                    case 'receipts':        /*RECEIPTS CREATION PHP CODE*/
                        if (isset($_POST['cname']) && isset($_POST['tablenum']) && isset($_POST['rdate']) && isset($_POST['rtime'])) {
                            $cname = get_full_strip($conn,'cname');
                            $tablenum = $_POST['tablenum'];
                            $rdate = $_POST['rdate'];
                            $rtime = $_POST['rtime'];
                            
                            if (!empty($cname) && !empty($tablenum) && !empty($rdate) && !empty($rtime)) {
                                $clone = $_POST['items'];
                                $items = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
                                
                                for ($j = 0; $j < 30; ++$j) {
                                    $items[$j] = (int) $clone[$j];
                                }
                                
                                if (($orderid = check_order($conn,$items)) > 0) {
                                    $stmt = $conn->prepare("INSERT INTO receipts VALUES(NULL,?,?,?,'active',?,?)");
                                    $stmt->bind_param("sssii",$rdate,$rtime,$cname,$tablenum,$orderid);
                                    if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                                    
                                    header('Location: records.php?table=receipts');
                                }
                                else {
                                    $stmt = $conn->prepare("INSERT INTO orders VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,0,0)");
                                    $stmt->bind_param("iiiiiiiiiiiiiiiiiiiiiiiiiiiiii",$items[0],$items[1],$items[2],$items[3],$items[4],$items[5],$items[6],$items[7],
                                        $items[8],$items[9],$items[10],$items[11],$items[12],$items[13],$items[14],$items[15],$items[16],$items[17],$items[18],
                                        $items[19],$items[20],$items[21],$items[22],$items[23],$items[24],$items[25],$items[26],$items[27],$items[28],$items[29]);
                                    if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                                    
                                    $orderid = check_order($conn,$items);
                                    
                                    $stmt = $conn->prepare("INSERT INTO receipts VALUES(NULL,?,?,?,'active',?,?)");
                                    $stmt->bind_param("sssii",$rdate,$rtime,$cname,$tablenum,$orderid);
                                    if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                                    
                                    header('Location: records.php?table=receipts');
                                }
                            }
                            else {
                                echo "<p class='prompt'>You missed something.</p>";
                            }
                        }
                        
                        break;
                    case 'reserve':         /*RESERVATIONS CREATION PHP CODE*/
                        if (isset($_POST['cname']) && isset($_POST['gsize']) && isset($_POST['rdate']) && isset($_POST['rtime'])) {
                            $cname = get_full_strip($conn, 'cname');
                            $gsize = $_POST['gsize'];
                            $rdate = $_POST['rdate'];
                            $rtime = $_POST['rtime'];
                            
                            if (!empty($cname) && !empty($gsize) && !empty($rdate) && !empty($rtime)) {
                                $stmt = $conn->prepare("INSERT INTO reservations VALUES(NULL,?,?,?,?)");
                                $stmt->bind_param("siss",$cname,$gsize,$rdate,$rtime);
                                if (!($state = $stmt->execute())) echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                                
                                header('Location: records.php?table=reserve');
                            }
                            else {
                                echo "<p class='prompt'>You missed something.</p>";
                            }
                        }
                        
                        break;
                    case 'queue':           /*QUEUE CREATION PHP CODE*/
                        if (isset($_POST['cname']) && isset($_POST['gsize']) && isset($_POST['atime'])) {
                            $cname = get_full_strip($conn,'cname');
                            $gsize = $_POST['gsize'];
                            $atime = $_POST['atime'];
                            
                            if(!empty($cname) && !empty($gsize) && !empty($atime)) {
                                $stmt = $conn->prepare("INSERT INTO queue VALUES(NULL,?,?,?)");
                                $stmt->bind_param("sis",$cname,$gsize,$atime);
                                if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                
                                header('Location: records.php?table=queue');
                            }
                            else {
                                echo "<p class='prompt'>You missed something.</p>";
                            }
                        }
                        break;
                }
                
                /*  **TABLE SENSITIVE FORM PHP CODE**
                    *A JS imitation that changes the webpage appropriate to a given table
                    - This code specifically provides appropriate elements that are needed to create an entry for the appropriate table's data currently
                        selected/given
                */
                
                switch ($table) {
                    case 'receipts':        /*RECEIPTS FORM PHP CODE*/
                        echo <<<_END
                        <form action="records.php?table=receipts" method="post">
                            <label for="cname">Customer Name</label> <br>
                            <input type="text" name="cname" id="cname"> <br>
                            <label for="tablenum">Table #</label> <br>
                            <input type="number" name="tablenum" id="tablenum"> <br>
                            <label for="rdate">Date</label> <br>
                            <input type="date" name="rdate" id="rdate"> <br>
                            <label for="rtime">Table #</label> <br>
                            <input type="time" name="rtime" id="rtime"> <br>
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
_END;
                        break;
                    case 'reserve':         /*RESERVATIONS FORM PHP CODE*/
                        echo <<<_END
                            <form action="records.php?table=reserve" method="post">
                                <label for="cname">Name</label> <br>
                                <input type="text" name="cname" id="cname"> <br>
                                <label for="gsize">Group Size</label> <br>
                                <input type="number" name="gsize" id="gsize"> <br>
                                <label for="rdate">Date</label> <br>
                                <input type="date" name="rdate" id="rdate"> <br>
                                <label for="rtime">Time</label> <br>
                                <input type="time" name="rtime" id="rtime"> <br>
                                <input type="submit" value="Add">
                        </form>
_END;
                        break;
                    case 'queue':           /*QUEUE FORM PHP CODE*/
                        echo <<<_END
                            <form action="records.php?table=queue" method="post">
                                <label for="cname">Customer Name</label> <br>
                                <input type="text" name="cname" id="cname"> <br>
                                <label for="gsize">Group Size</label> <br>
                                <input type="number" name="gsize" id="gsize"> <br>
                                <label for="atime">Time</label> <br>
                                <input type="time" name="atime" id="atime"> <br>
                                <input type="submit" value="Add">
                            </form>
_END;
                        break;
                }
            ?>
        </aside>
        <aside id="right" class="hide">
            <h2>Modify Records</h2>
            <?php
                /*  **MODIFICATION PHP CODE**
                    *Performs a change/removal of an entry in the selected database.
                */
                
                switch ($table) {
                    case 'receipts':        /*RECEIPTS MODIFICATION PHP CODE*/
                        if (isset($_POST['rmv_num']) && !empty($_POST['rmv_num'])) {
                            $num = $_POST['rmv_num'];
                            
                            switch($_POST['chg_perm']) {
                                case 'remove':
                                    $query  = "DELETE FROM receipts WHERE receipt_num=" . $num;
                                    $result = $conn->query($query);
                                    if (!$result) die ("Database access failed: " . $conn->error);
                                    
                                    break;
                                case 'update':
                                    $bill = 0.0;
                                    $cost = 0.0;
                                    
                                    $query  = "SELECT order_id FROM receipts WHERE receipt_num=" . $num;
                                    $result = $conn->query($query);
                                    if (!$result) die ("Database access failed: " . $conn->error);
                                    
                                    $result->data_seek(0);
                                    $oid = $result->fetch_array()['order_id'];
                                    
                                    $query = "SELECT * FROM orders WHERE order_id=" . $oid;
                                    $result = $conn->query($query);
                                    if (!$result) die ("Database access failed: " . $conn->error);
                                    $rows = $result->num_rows;
                                    
                                    $result->data_seek(0);
                                    $row1 = $result->fetch_array(MYSQLI_NUM);
                                    
                                    $query = "SELECT item_id,price,cost FROM menu";
                                    $result = $conn->query($query);
                                    if (!$result) die ("Database access failed: " . $conn->error);
                                    $rows = $result->num_rows;
                                    
                                    for ($j = 0; $j < $rows; ++$j) {
                                        $result->data_seek($j);
                                        $row2 = $result->fetch_array(MYSQLI_NUM);
                                        
                                        for ($k = 1; $k < 31; ++$k) {
                                            if ($k == $row2[0]) {
                                                $bill += $row1[$j] * $row2[1];
                                                $cost += $row1[$j] * $row2[2];
                                            }
                                        }
                                    }
                                    
                                    $query  = "UPDATE orders SET bill=" . $bill . " WHERE order_id=" . $oid;
                                    $result = $conn->query($query);
                                    if (!$result) die ("Database access failed: " . $conn->error);
                                    
                                    $query  = "UPDATE orders SET total_cost=" . $cost . " WHERE order_id=" . $oid;
                                    $result = $conn->query($query);
                                    if (!$result) die ("Database access failed: " . $conn->error);
                                    
                                    $query  = "UPDATE receipts SET order_state='inactive' WHERE receipt_num=" . $num;
                                    $result = $conn->query($query);
                                    if (!$result) die ("Database access failed: " . $conn->error);
                                    
                                    break;
                            }
                            
                            header('Location: records.php?table=receipts');
                        }
                        
                        if (isset($_POST['chg_receipt']) && !empty($_POST['chg_receipt'])) {
                            $receipt = $_POST['chg_receipt'];
                            
                            if (isset($_POST['chg_detail']) && !empty($_POST['chg_detail'])) {
                                switch ($_POST['chg_option']) {
                                    case 'date':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE receipts SET date=? WHERE receipt_num=?");
                                        $stmt->bind_param("si",$detail,$receipt);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'time':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE receipts SET time=? WHERE receipt_num=?");
                                        $stmt->bind_param("si",$detail,$receipt);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'name':
                                        $detail = get_full_strip($conn, 'chg_detail');
                                        
                                        $stmt = $conn->prepare("UPDATE receipts SET customer_name=? WHERE receipt_num=?");
                                        $stmt->bind_param("si",$detail,$receipt);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'table':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE receipts SET table_num=? WHERE receipt_num=?");
                                        $stmt->bind_param("ii",$detail,$receipt);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'item':
                                        $detail = $_POST['chg_detail'];
                                        
                                        if (isset($_POST['chg_qtty']) && !empty($_POST['chg_qtty'])) {
                                            $quantity = $_POST['chg_qtty'];
                                            
                                            $query = "SELECT order_id FROM receipts WHERE receipt_num=" . $receipt;
                                            $result = $conn->query($query);
                                            if (!$result) die ("Database access failed: " . $conn->error);
                                            $rows = $result->num_rows;
                                            
                                            $result->data_seek(0);
                                            $oid = $result->fetch_array()['order_id'];
                                            
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
                                                
                                                $items[((int) $detail) - 1] = $quantity;
                                                $check = check_order($conn,$items);
                                                
                                                if ($check) {
                                                    $stmt = $conn->prepare("UPDATE receipts SET order_id=? WHERE receipt_num=?");
                                                    $stmt->bind_param("iii",$check,$receipt);
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
                                                    $stmt->bind_param("ii",$check,$receipt);
                                                    if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                                }
                                            }
                                            else {
                                                $field = "item_" . $detail;
                                                $stmt = $conn->prepare("UPDATE orders SET " . $field . "=? WHERE order_id=?");
                                                $stmt->bind_param("ii",$quantity,$oid);
                                                if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                            }
                                        }
                                        
                                        break;
                                }
                                header('Location: records.php?table=receipts');
                            }
                            else echo "<p class='prompt'>Enter the appropriate value.</p>";
                        }
                        else echo "<p class='prompt'>Enter a receipt number.</p>";
                        
                        break;
                    case 'reserve':         /*RESERVATIONS MODIFICATION PHP CODE*/
                        if (isset($_POST['rmv_num']) && !empty($_POST['rmv_num'])) {
                            $num = $_POST['rmv_num'];
                            
                            $query  = "DELETE FROM reservations WHERE reservation_num=" . $num;
                            $result = $conn->query($query);
                            if (!$result) die ("Database access failed: " . $conn->error);
                            
                            header('Location: records.php?table=reserve');
                        }
                        
                        if (isset($_POST['chg_reserve']) && !empty($_POST['chg_reserve'])) {
                            $reserve = $_POST['chg_reserve'];
                            
                            if (isset($_POST['chg_detail']) && !empty($_POST['chg_detail'])) {
                                switch ($_POST['chg_option']) {
                                    case 'name':
                                        $detail = get_full_strip($conn,'chg_detail');
                                        
                                        $stmt = $conn->prepare("UPDATE reservations SET customer_name=? WHERE reservation_num=?");
                                        $stmt->bind_param("si",$detail,$reserve);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'size':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE reservations SET party_size=? WHERE reservation_num=?");
                                        $stmt->bind_param("ii",$detail,$reserve);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'date':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE reservations SET date=? WHERE reservation_num=?");
                                        $stmt->bind_param("si",$detail,$reserve);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'time':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE reservations SET time=? WHERE reservation_num=?");
                                        $stmt->bind_param("si",$detail,$reserve);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                }
                                header('Location: records.php?table=reserve');
                            }
                            else echo "<p class='prompt'>Enter the appropriate value.</p>";
                        }
                        else echo "<p class='prompt'>Enter a reservation number.</p>";
                        
                        break;
                    case 'queue':           /*QUEUE MODIFICATION PHP CODE*/
                        if (isset($_POST['rmv_num']) && !empty($_POST['rmv_num'])) {
                            $num = $_POST['rmv_num'];
                            
                            $query  = "DELETE FROM queue WHERE queue_num=" . $num;
                            $result = $conn->query($query);
                            if (!$result) die ("Database access failed: " . $conn->error);
                            
                            header('Location: records.php?table=queue');
                        }
                        
                        if (isset($_POST['chg_queue']) && !empty($_POST['chg_queue'])) {
                            $queue = $_POST['chg_queue'];
                            
                            if (isset($_POST['chg_detail']) && !empty($_POST['chg_detail'])) {
                                switch ($_POST['chg_option']) {
                                    case 'name':
                                        $detail = get_full_strip($conn,'chg_detail');
                                        
                                        $stmt = $conn->prepare("UPDATE queue SET customer_name=? WHERE queue_num=?");
                                        $stmt->bind_param("si",$detail,$queue);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'size':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE queue SET party_size=? WHERE queue_num=?");
                                        $stmt->bind_param("ii",$detail,$queue);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                    case 'time':
                                        $detail = $_POST['chg_detail'];
                                        
                                        $stmt = $conn->prepare("UPDATE queue SET arrival_time=? WHERE queue_num=?");
                                        $stmt->bind_param("si",$detail,$queue);
                                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                        
                                        break;
                                }
                                header('Location: records.php?table=queue');
                            }
                            else echo "<p class='prompt'>Enter the appropriate value.</p>";
                        }
                        else echo "<p class='prompt'>Enter a queue number.</p>";
                        
                        break;
                }
                
                /*  **TABLE SENSITIVE FORM PHP CODE**
                    *A JS imitation that changes the webpage appropriate to a given table
                    - This code specifically provides potential modifications that can be performed on the appropriate table currently selected/given
                */
                
                switch ($table) {
                    case 'receipts':        /*RECEIPTS FORM PHP CODE*/
                        echo <<<_END
                            <form action="records.php?table=receipts" method="post">
                                <label for="chg_perm">Receipt Action:</label> <br>
                                <select name="chg_perm" id="chg_perm" onchange="change_perm()">
                                    <option value="remove" selected>Remove</option>
                                    <option value="update">Update</option>
                                </select>
                                <br>
                                <label for="rmv_num" id="cpl">Remove Receipt by #</label> <br>
                                <input type="number" name="rmv_num" id="rmv_num"> <br>
                                <hr>
                                <label for="chg_option">Change:</label> <br>
                                <select name="chg_option" id="chg_option" onchange="change_receipt()">
                                    <option value="date" selected>Date</option>
                                    <option value="time">Time</option>
                                    <option value="name">Customer Name</option>
                                    <option value="table">Table #</option>
                                    <option value="item">Item Quantity</option>
                                </select>
                                <br>
                                <label for="chg_receipt">Receipt #</label> <br>
                                <input type="number" name="chg_receipt" id="chg_receipt"> <br>
                                <label for="chg_detail" id="cdl">Date</label> <br>
                                <input type="date" name="chg_detail" id="chg_detail"> <br>
                                <label for="chg_qtty">Item Quantity</label> <br>
                                <input type="number" name="chg_qtty" id="chg_qtty" disabled> <br>
                                <input type="submit" value="Update">
                            </form>
_END;
                        break;
                    case 'reserve':         /*RESERVATIONS FORM PHP CODE*/
                        echo <<<_END
                            <form action="records.php?table=reserve" method="post">
                                <label for="rmv_num">Remove Reservation by #</label> <br>
                                <input type="number" name="rmv_num" id="rmv_num"> <br>
                                <hr>
                                <label for="chg_option">Change:</label> <br>
                                <select name="chg_option" id="chg_option" onchange="change_reserve()">
                                    <option value="name" selected>Customer Name</option>
                                    <option value="size">Group Size</option>
                                    <option value="date">Date</option>
                                    <option value="time">Time</option>
                                </select>
                                <br>
                                <label for="chg_reserve">Reservation #</label> <br>
                                <input type="number" name="chg_reserve" id="chg_reserve"> <br>
                                <label for="chg_detail" id="cdl">Customer Name</label> <br>
                                <input type="text" name="chg_detail" id="chg_detail"> <br>
                                <input type="submit" value="Update">
                            </form>
_END;
                        break;
                    case 'queue':           /*QUEUE FORM PHP CODE*/
                        echo <<<_END
                            <form action="records.php?table=queue" method="post">
                                <label for="rmv_num">Kick from Queue by #</label> <br>
                                <input type="number" name="rmv_num" id="rmv_num"> <br>
                                <hr>
                                <label for="chg_option">Change:</label> <br>
                                <select name="chg_option" id="chg_option" onchange="change_queue()">
                                    <option value="name" selected>Customer Name</option>
                                    <option value="size">Group Size</option>
                                    <option value="time">Time</option>
                                </select>
                                <br>
                                <label for="chg_queue">Queue #</label> <br>
                                <input type="number" name="chg_queue" id="chg_queue"> <br>
                                <label for="chg_detail" id="cdl">Customer Name</label> <br>
                                <input type="text" name="chg_detail" id="chg_detail"> <br>
                                <input type="submit" value="Update">
                            </form>
_END;
                        break;
                }
            ?>
        </aside>
        <section class="hide">
            <h2>View Records</h2>
            <label for="table">Choose a Database:</label>
            <select id="table" name="table" onchange="chg_tbl()">
                <?php
                    /*  **TABLE SENSITIVE FORM PHP CODE**
                        *A JS imitation that changes the webpage appropriate to a given table
                        - This code specifically displays the appropriate table's data currently selected/given
                    */
                    
                    switch($table) {
                        case 'receipts':
                            echo <<<_END
                        <option value="receipts" selected>Receipts</option>
                        <option value="reserve">Reservations</option>
                        <option value="queue">Queue</option>
_END;
                            break;
                        case 'reserve':
                            echo <<<_END
                        <option value="receipts">Receipts</option>
                        <option value="reserve" selected>Reservations</option>
                        <option value="queue">Queue</option>
_END;
                            break;
                        case 'queue':
                            echo <<<_END
                        <option value="receipts">Receipts</option>
                        <option value="reserve">Reservations</option>
                        <option value="queue" selected>Queue</option>
_END;
                            break;
                    }
                ?>
            </select>
            <div>
                <table>
                    <?php
                        /*  **TABLE DISPLAY PHP CODE**
                            *This code displays a given table in full excluding the 'orders' table which is dependent on the 'receipts' table
                        */
                        
                        switch($table) {
                            case 'receipts':        /*RECEIPTS TABLE VIEW*/
                                echo <<<_END
                                    <tr>
                                        <th>Receipt #</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Customer Name</th>
                                        <th>Table #</th>
                                        <th>Order</th>
                                        <th>Bill</th>
                                        <th>Total Cost</th>
                                    </tr>
_END;
                                
                                $query1 = "SELECT receipt_num,date,DATE_FORMAT(time,'%l:%i %p'),customer_name,order_state,table_num,order_id FROM receipts ORDER BY
                                    date DESC";
                                $result1 = $conn->query($query1);
                                if (!$result1) die ("Database access failed: " . $conn->error);
                                $rows1 = $result1->num_rows;
                                
                                for ($j = 0; $j < $rows1; ++$j) {
                                    $result1->data_seek($j);
                                    $row1 = $result1->fetch_array(MYSQLI_NUM);
                                    
                                    echo <<<_END
                                        <tr>
                                            <td> $row1[0] </td>
                                            <td> $row1[1] </td>
                                            <td> $row1[2] </td>
                                            <td> $row1[3] </td>
                                            <td> $row1[5] </td>
_END;
                                    $query2 = "SELECT * FROM orders WHERE order_id=" . $row1[6];
                                    $result2 = $conn->query($query2);
                                    if (!$result2) die ("Database access failed: " . $conn->error);
                                    $rows2 = $result2->num_rows;
                                    
                                    $result2->data_seek(0);
                                    $row2 = $result2->fetch_array(MYSQLI_NUM);
                                    
                                    if ($row1[4] == 'active') {
                                        echo "<td class='active'>";
                                    }
                                    else if ($row1[4] == 'inactive') {
                                        echo "<td class='inactive'>";
                                    }
                                    
                                    for ($k = 1; $k < 31; ++$k) {
                                        if ($row2[$k] > 0) {
                                            if (($k <= 4) && ($k >= 1)) {
                                                echo "A";
                                            }
                                            else if (($k <= 9) && ($k >= 5)) {
                                                echo "B";
                                            }
                                            else if (($k <= 12) && ($k >= 10)) {
                                                echo "C";
                                            }
                                            else if (($k <= 18) && ($k >= 13)) {
                                                echo "D";
                                            }
                                            else if (($k <= 21) && ($k >= 19)) {
                                                echo "E";
                                            }
                                            else if (($k <= 26) && ($k >= 22)) {
                                                echo "F";
                                            }
                                            else if (($k <= 30) && ($k >= 27)) {
                                                echo "RD";
                                            }
                                            echo $k . " x" . $row2[$k] . "<br>";
                                        }
                                    }
                                    echo "</td>";
                                    
                                    echo <<<_END
                                        <td> $$row2[31] </td>
                                        <td> $$row2[32] </td>
                                    </tr>
_END;
                                }
                                
                                break;
                            case 'reserve':        /*RESERVATIONS TABLE VIEW*/
                                echo <<<_END
                                    <tr>
                                        <th>Reservation #</th>
                                        <th>Customer Name</th>
                                        <th>Group Size</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
_END;
                                
                                $query = "SELECT reservation_num,customer_name,party_size,date,DATE_FORMAT(time,'%l:%i %p') FROM reservations ORDER BY date";
                                $result = $conn->query($query);
                                if (!$result) die ("Database access failed: " . $conn->error);
                                $rows = $result->num_rows;
                                
                                for ($j = 0; $j < $rows; ++$j) {
                                    $result->data_seek($j);
                                    $row = $result->fetch_array(MYSQLI_NUM);
                                    
                                    echo <<<_END
                                        <tr>
                                            <td> $row[0] </td>
                                            <td> $row[1] </td>
                                            <td> $row[2] </td>
                                            <td> $row[3] </td>
                                            <td> $row[4] </td>
                                        </tr>
_END;
                                }
                                
                                break;
                            case 'queue':           /*QUEUE TABLE VIEW*/
                                echo <<<_END
                                    <tr>
                                        <th>Queue #</th>
                                        <th>Customer Name</th>
                                        <th>Group Size</th>
                                        <th>Time</th>
                                    </tr>
_END;
                                
                                $query = "SELECT queue_num,customer_name,party_size,DATE_FORMAT(arrival_time,'%l:%i %p') FROM queue ORDER BY arrival_time";
                                $result = $conn->query($query);
                                if (!$result) die ("Database access failed: " . $conn->error);
                                $rows = $result->num_rows;
                                
                                for ($j = 0; $j < $rows; ++$j) {
                                    $result->data_seek($j);
                                    $row = $result->fetch_array(MYSQLI_NUM);
                                    
                                    echo <<<_END
                                        <tr>
                                            <td> $row[0] </td>
                                            <td> $row[1] </td>
                                            <td> $row[2] </td>
                                            <td> $row[3] </td>
                                        </tr>
_END;
                                }
                                
                                break;
                        }
                    ?>
                </table>
            </div>
        </section>
        <!--Website Footer-->
        <br class="hide">
        <footer>
            <p id="ffront">&#169; Darius Zhou, 2020</p>
        </footer>
    </body>
</html>