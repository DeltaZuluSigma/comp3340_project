<?php
    session_start();
    if ($_SESSION['state'] != 1) {
        $_SESSION['state'] = -1;
        header('Location:loginpage.php');
    }
?>

<html>
    <head>
        <title>Staff Dashboard</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets & JS file-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/dboard_staff.css">
        <script src="../js/collapse.js"></script>
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
            <h2>Active Tickets</h2>
            <div id="tof">
                <?php
                    require_once 'login_epl.php';
                    $conn = new mysqli($hn, $un, $pw, $db);
                    if ($conn->connect_error) die($conn->connect_error);
                    
                    $query1 = "SELECT customer_name,table_num,order_id FROM receipts WHERE order_state='active'";
                    $result1 = $conn->query($query1);
                    if (!$result1) die ("Database access failed: " . $conn->error);
                    $rows1 = $result1->num_rows;
                    
                    if ($rows1 == 0) {
                        echo "No tickets yet, <a href=\"tickets.php\">click here</a> to view the day's tickets.";
                    }
                    else {
                        for ($j = 0; $j < $rows1; ++$j) {
                            $result1->data_seek($j);
                            $row1 = $result1->fetch_array(MYSQLI_NUM);
                            
                            echo "<b> Table #" . $row1[1] . " - \"" . $row1[0] . "\"</b><br>";
                            
                            $query2 = "SELECT * FROM orders WHERE order_id=" . $row1[2];
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
                            }
                            echo "<br>";
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <a href="tickets.php">View the Day's Tickets</a>
            <br>
        </aside>
        <aside id="right" class="hide">
            <h2>Reservations</h2>
            <div id="qof">
                <?php
                    require_once 'login_epl.php';
                    $conn = new mysqli($hn, $un, $pw, $db);
                    if ($conn->connect_error) die($conn->connect_error);
                    
                    $query  = "SELECT customer_name,party_size,DATE_FORMAT(time,'%l:%i %p') FROM reservations WHERE date=CURRENT_DATE";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    if ($rows == 0) {
                        echo "No reservations today. <a href=\"backresv.php\">Click Here</a> to view the reservations coming up.";
                    }
                    else {
                        echo <<<_END
                        <table>
                            <tr>
                                <th>Customer Name</th>
                                <th>Group size</th>
                                <th>Time</th>
                            </tr>
_END;
                        for ($j = 0; $j < $rows; ++$j) {
                            $result->data_seek($j);
                            $row = $result->fetch_array(MYSQLI_NUM);
                            
                            echo <<<_END
                            <tr>
                                <td> $row[0] </td>
                                <td> $row[1] </td>
                                <td> $row[2] </td>
                            </tr>
_END;
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <a href="backresv.php">View All Reservations</a>
            <br><br>
            <h2>Walk-in Queue</h2>
            <div id="qof">
                <?php
                    require_once 'login_epl.php';
                    $conn = new mysqli($hn, $un, $pw, $db);
                    if ($conn->connect_error) die($conn->connect_error);
                    
                    $query  = "SELECT customer_name,party_size,DATE_FORMAT(arrival_time,'%l:%i %p') FROM queue";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    if ($rows == 0) {
                        echo "The queue is empty, <a href=\"queue.php\">click here</a> to edit who is in the queue.";
                    }
                    else {
                        echo <<<_END
                        <table>
                            <tr>
                                <th>Customer Name</th>
                                <th>Group size</th>
                                <th>Time</th>
                            </tr>
_END;
                        for ($j = 0; $j < $rows; ++$j) {
                            $result->data_seek($j);
                            $row = $result->fetch_array(MYSQLI_NUM);
                            
                            echo <<<_END
                            <tr>
                                <td> $row[0] </td>
                                <td> $row[1] </td>
                                <td> $row[2] </td>
                            </tr>
_END;
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <a href="queue.php">View Full Queue</a>
        </aside>
        <section>
            <h2>Restaurant Layout</h2>
            <img src="../css/images/layout.png" alt="restaurant layout">
        </section>
        <aside class="response">
            <h2>Reservations</h2>
            <div id="qof">
                <?php
                    require_once 'login_epl.php';
                    $conn = new mysqli($hn, $un, $pw, $db);
                    if ($conn->connect_error) die($conn->connect_error);
                    
                    $query  = "SELECT customer_name,party_size,DATE_FORMAT(time,'%l:%i %p') FROM reservations WHERE date=CURRENT_DATE";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    if ($rows == 0) {
                        echo "No reservations today. <a href=\"queue.php\">Click Here</a> to view the reservations coming up.";
                    }
                    else {
                        echo <<<_END
                        <table>
                            <tr>
                                <th>customer name</th>
                                <th>group size</th>
                                <th>time</th>
                            </tr>
_END;
                        for ($j = 0; $j < $rows; ++$j) {
                            $result->data_seek($j);
                            $row = $result->fetch_array(MYSQLI_NUM);
                            
                            echo <<<_END
                            <tr>
                                <td> $row[0] </td>
                                <td> $row[1] </td>
                                <td> $row[2] </td>
                            </tr>
_END;
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <a href="queue.php">View Full Queue</a>
        </aside>
        <aside class="response">
            <h2>Walk-in Queue</h2>
            <div id="qof">
                <?php
                    require_once 'login_epl.php';
                    $conn = new mysqli($hn, $un, $pw, $db);
                    if ($conn->connect_error) die($conn->connect_error);
                    
                    $query  = "SELECT customer_name,party_size,DATE_FORMAT(arrival_time,'%l:%i %p') FROM queue";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    if ($rows == 0) {
                        echo "No reservations today. <a href=\"queue.php\">Click Here</a> to view the reservations coming up.";
                    }
                    else {
                        echo <<<_END
                        <table>
                            <tr>
                                <th>customer name</th>
                                <th>group size</th>
                                <th>time</th>
                            </tr>
_END;
                        for ($j = 0; $j < $rows; ++$j) {
                            $result->data_seek($j);
                            $row = $result->fetch_array(MYSQLI_NUM);
                            
                            echo <<<_END
                            <tr>
                                <td> $row[0] </td>
                                <td> $row[1] </td>
                                <td> $row[2] </td>
                            </tr>
_END;
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <a href="queue.php">View Full Queue</a>
        </aside>
        <!--Website Footer-->
        <br class="hide">
        <footer>
            <p id="ffront">&#169; Darius Zhou, 2020</p>
        </footer>
    </body>
</html>