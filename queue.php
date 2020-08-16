<?php
    session_start();
    if ($_SESSION['state'] != 1) {
        $_SESSION['state'] = -1;
        header('Location:loginpage.php');
    }
?>

<html>
    <head>
        <title>Staff Queue List</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets & JS file-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/universal.css">
        <script src="../js/queue.js"></script>
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
            <h2>Add to Queue</h2>
            <?php
                require_once 'login_epl.php';
                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) die($conn->connect_error);
                
                if (isset($_POST['cname']) && isset($_POST['gsize']) && isset($_POST['atime'])) {
                    $cname = get_full_strip($conn,'cname');
                    $gsize = $_POST['gsize'];
                    $atime = $_POST['atime'];
                    
                    if(!empty($cname) && !empty($gsize) && !empty($atime)) {
                        $stmt = $conn->prepare("INSERT INTO queue VALUES(NULL,?,?,?)");
                        $stmt->bind_param("sis",$cname,$gsize,$atime);
                        if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                        else if ($state) echo "<p>Successfully Added.</p>";
                        header('Location: queue.php');
                    }
                    else {
                        echo "<p>You missed something.</p>";
                    }
                }
            ?>
            <form action="queue.php" method="post">
                <label for="cname">Customer Name</label> <br class="hide">
                <input type="text" name="cname" id="cname">
                <br>
                <label for="detail">Group Size</label> <br class="hide">
                <input type="number" name="gsize" id="gsize">
                <br>
                <label for="detail">Time</label> <br class="hide">
                <input type="time" name="atime" id="atime">
                <br>
                <input type="submit" value="Add">
            </form>
        </aside>
        <section>
            <h2>Walk-in Queue List</h2>
            <div>
                <?php
                    $query  = "SELECT queue_num,customer_name,party_size,DATE_FORMAT(arrival_time,'%l:%i %p') FROM queue";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    if ($rows == 0) {
                        echo "Empty queue for now.";
                    }
                    else {
                        echo <<<_END
                        <table>
                            <tr>
                                <th>Queue #</th>
                                <th>Customer Name</th>
                                <th>Group Size</th>
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
                            </tr>
_END;
                        }
                        
                        echo "</table>";
                    }
                ?>
            </div>
            <?php
                if (($opt=$_POST['changeque']) != "remove") {
                    if (isset($_POST['num']) && isset($_POST['changedlt']) && !empty($_POST['num']) && !empty($_POST['changedlt'])) {
                        $index = $_POST['num'];
                        
                        if ($opt == "name") {
                            $name = get_full_strip($conn,'changedlt');
                            
                            $stmt = $conn->prepare("UPDATE queue SET customer_name=? WHERE queue_num=?");
                            $stmt->bind_param("si",$name,$index);
                            if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                            else if ($state) echo "<p>Successful Update.</p>";
                            header('Location:queue.php');
                        }
                        else if ($opt == "gsize") {
                            $gsize = $_POST['changedlt'];
                            
                            $stmt = $conn->prepare("UPDATE queue SET party_size=? WHERE queue_num=?");
                            $stmt->bind_param("ii",$gsize,$index);
                            if (!($state = $stmt->execute())) echo "UPDATE failed: $stmt<br>" . $conn->error . "<br><br>";
                            else if ($state) echo "<p>Successful Update.</p>";
                            header('Location: queue.php');
                        }
                    }
                    else {
                        echo "<p>Please fill all fields.</p>";
                    }
                }
                else if ($opt == "remove") {
                    if (isset($_POST['num']) && !empty($_POST['num'])) {
                        $index = $_POST['num'];
                        
                        $query  = "DELETE FROM queue WHERE queue_num=" . $index;
                        $result = $conn->query($query);
                        if (!$result) die ("Database access failed: " . $conn->error);
                        echo "<p>Reservation " . $index . " was removed.</p>";
                        header('Location: queue.php');
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
            <form action="queue.php" method="post">
                <select name="changeque" id="cg_que" onchange="changedetail()">
                    <option value="remove" selected>Remove Customer</option>
                    <option value="name">Customer Name</option>
                    <option value="gsize">Group Size</option>
                </select>
                <br class="response">
                <label for="num" id="compnum">Queue #</label>
                <input type="number" name="num" id="num"> <br class="response">
                <label for="detail" id="compdtl">Customer Name</label>
                <input type="text" name="changedlt" id="detail" disabled> <br class="response">
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