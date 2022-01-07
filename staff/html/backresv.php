<?php
    session_start();
    if ($_SESSION['state'] != 1) {
        $_SESSION['state'] = -1;
        header('Location: loginpage.php');
    }
?>

<html>
    <head>
        <title>Staff Reservations List</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets & JS file-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/universal.css">
        <script src="../js/backresv.js"></script>
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
        <section>
                <h2>Reservations</h2>
            <div>
                <?php
                    require_once 'login_epl.php';
                    $conn = new mysqli($hn, $un, $pw, $db);
                    if ($conn->connect_error) die($conn->connect_error);
                    
                    $query  = "SELECT reservation_num,customer_name,party_size,DATE_FORMAT(date,'%c/%e'),DATE_FORMAT(time,'%k:%i') FROM reservations ORDER BY date";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    if ($rows == 0) {
                        echo "No reservations ... weird ...";
                    }
                    else {
                        echo <<<_END
                        <table>
                            <tr>
                                <th>Reservation #</th>
                                <th>Customer Name</th>
                                <th>Group Size</th>
                                <th>Date</th>
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
                                <td> $row[3] </td>
                                <td> $row[4] </td>
                            </tr>
_END;
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <?php
                if (($opt=$_POST['changersv']) != "remove") {
                    if (isset($_POST['index']) && isset($_POST['changeval_rsv']) && !empty($_POST['index']) && !empty($_POST['changeval_rsv'])) {
                        $index = $_POST['index'];
                        
                        if ($opt == "name") {
                            $name = get_full_strip($conn,'changeval_rsv');
                            
                            $stmt = $conn->prepare("UPDATE reservations SET customer_name=? WHERE reservation_num=?");
                            $stmt->bind_param("si",$name,$index);
                            if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                            else if ($state) echo "<p>Successful Update.</p>";
                            header('Location: backresv.php');
                        }
                        else if ($opt == "gsize") {
                            $gsize = $_POST['changeval_rsv'];
                            
                            $stmt = $conn->prepare("UPDATE reservations SET party_size=? WHERE reservation_num=?");
                            $stmt->bind_param("ii",$gsize,$index);
                            if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                            else if ($state) echo "<p>Successful Update.</p>";
                            header('Location: backresv.php');
                        }
                        else if ($opt == "date") {
                            $date = $_POST['changeval_rsv'];
                            
                            $stmt = $conn->prepare("UPDATE reservations SET date=? WHERE reservation_num=?");
                            $stmt->bind_param("si",$date,$index);
                            if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                            else if ($state) echo "<p>Successful Update.</p>";
                            header('Location: backresv.php');
                        }
                        else if ($opt == "time") {
                            $time = $_POST['changeval_rsv'];
                                
                                $stmt = $conn->prepare("UPDATE reservations SET time=? WHERE reservation_num=?");
                                $stmt->bind_param("si",$time,$index);
                                if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                                else if ($state) echo "<p>Successful Update.</p>";
                                header('Location: backresv.php');
                        }
                    }
                    else {
                        echo "<p>Please fill all fields.</p>";
                    }
                }
                else if ($opt == "remove") {
                    if (isset($_POST['index']) && !empty($_POST['index'])) {
                        $index = $_POST['index'];
                        
                        $query  = "DELETE FROM reservations WHERE reservation_num=" . $index;
                        $result = $conn->query($query);
                        if (!$result) die ("Database access failed: " . $conn->error);
                        echo "<p>Reservation " . $index . " was removed.</p>";
                        header('Location: backresv.php');
                    }
                    else {
                        echo "<p>Please indicate the index.</p>";
                    }
                }
                
                function get_full_strip($conn, $var) {
                    $var = $conn->real_escape_string($_POST[$var]);
                    $var = stripslashes($var);
                    $var = strip_tags($var);
                    $var = htmlentities($var);
                    return $var;
                }
            ?>
            <form action="backresv.php" method="post">
                <select name="changersv" id="cg_rsv" onchange="changefield()">
                    <option value="remove" selected>Remove Reservation</option>
                    <option value="name">Customer Name</option>
                    <option value="gsize">Group Size</option>
                    <option value="date">Date</option>
                    <option value="time">Time</option>
                </select>
                <br class="response">
                <label for="index" id="compidx">Reservation #</label>
                <input type="number" name="index" id="index"> <br class="response">
                <label for="field" id="compfld">Customer Name</label>
                <input type="text" name="changeval_rsv" id="field" disabled> <br class="response">
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