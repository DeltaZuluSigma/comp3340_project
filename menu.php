<html>
    <head>
        <title>Menu - Refined Dining</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/menu.css">
    </head>
    <body>
        <!--Website Head-->
        <header>
            <img src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/images/logo.png" alt="logo" id="logo">
            <h1>Refined Dining</h1>
        </header>
        <nav>
            <p>
                <a href="hpage.html">Home</a>
                <a href="location.html">Location</a>
                <a href="menu.php">Menu</a>
                <a href="reservation.php">Reservation</a>
            </p>
        </nav>
        <br class="hide">
        <!--Website Body-->
        <section>
            <?php
                require_once 'login_usr.php';
                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) die($conn->connect_error);
                
                /*Chef Specials - RD*/
                echo "<h2>Chef Specials</h2><div>";
                
                $query  = "SELECT item_id,item_name,price FROM menu WHERE MATCH(category) AGAINST('chef specials')";
                $result = $conn->query($query);
                if (!$result) die ("Database access failed: " . $conn->error);
                $rows = $result->num_rows;
                
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    echo "<b>RD$row[0]</b> $row[1] &nbsp;&nbsp;&nbsp; $$row[2] <br>";
                }
                
                echo "</div>";
                
                /*Congee and Soups - A*/
                echo "<h2>Congee & Soups</h2><div>";
                
                $query  = "SELECT item_id,item_name,price FROM menu WHERE MATCH(category) AGAINST('congee soups')";
                $result = $conn->query($query);
                if (!$result) die ("Database access failed: " . $conn->error);
                $rows = $result->num_rows;
                
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    echo "<b>A$row[0]</b> $row[1] &nbsp;&nbsp;&nbsp; $$row[2] <br>";
                }
                
                echo "</div>";
                
                /*Fried and Tossed - B*/
                echo "<h2>Fried & Tossed</h2><div>";
                
                $query  = "SELECT item_id,item_name,price FROM menu WHERE MATCH(category) AGAINST('fried tossed')";
                $result = $conn->query($query);
                if (!$result) die ("Database access failed: " . $conn->error);
                $rows = $result->num_rows;
                
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    echo "<b>B$row[0]</b> $row[1] &nbsp;&nbsp;&nbsp; $$row[2] <br>";
                }
                
                echo "</div>";
                
                /*Grilled - C*/
                echo "<h2>Grilled</h2><div>";
                
                $query  = "SELECT item_id,item_name,price FROM menu WHERE MATCH(category) AGAINST('grilled')";
                $result = $conn->query($query);
                if (!$result) die ("Database access failed: " . $conn->error);
                $rows = $result->num_rows;
                
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    echo "<b>C$row[0]</b> $row[1] &nbsp;&nbsp;&nbsp; $$row[2] <br>";
                }
                
                echo "</div>";
                
                /*Side Dishes - D*/
                echo "<h2>Side Dishes</h2><div>";
                
                $query  = "SELECT item_id,item_name,price FROM menu WHERE MATCH(category) AGAINST('side dishes')";
                $result = $conn->query($query);
                if (!$result) die ("Database access failed: " . $conn->error);
                $rows = $result->num_rows;
                
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    echo "<b>D$row[0]</b> $row[1] &nbsp;&nbsp;&nbsp; $$row[2] <br>";
                }
                
                echo "</div>";
                
                /*Desserts - E*/
                echo "<h2>Desserts</h2><div>";
                
                $query  = "SELECT item_id,item_name,price FROM menu WHERE MATCH(category) AGAINST('desserts')";
                $result = $conn->query($query);
                if (!$result) die ("Database access failed: " . $conn->error);
                $rows = $result->num_rows;
                
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    echo "<b>E$row[0]</b> $row[1] &nbsp;&nbsp;&nbsp; $$row[2] <br>";
                }
                
                echo "</div>";
                
                /*Drinks - F*/
                echo "<h2>Drinks</h2><div>";
                
                $query  = "SELECT item_id,item_name,price FROM menu WHERE MATCH(category) AGAINST('drinks')";
                $result = $conn->query($query);
                if (!$result) die ("Database access failed: " . $conn->error);
                $rows = $result->num_rows;
                
                for ($j = 0; $j < $rows; ++$j) {
                    $result->data_seek($j);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    echo "<b>F$row[0]</b> $row[1] &nbsp;&nbsp;&nbsp; $$row[2] <br>";
                }
                
                echo "</div>";
            ?>
            <br>
        </section>
        <!--Website Footer-->
        <br class="hide">
        <footer>
            <table>
                <tr>
                    <td id="ffront"><p>&#169; Darius Zhou, 2020</p></td>
                    <td id="fback">
                        <button type="button" onclick="location.href='http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/staff/html/authenticate.php'" class="btn btn-info btn-sm">
                            Staff Login
                        </button>
                    </td>
                </tr>
            </table>
        </footer>
    </body>
</html>