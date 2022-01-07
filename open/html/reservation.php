<html>
    <head>
        <title>Reserving a Table - Refined Dining</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/reservation.css">
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
            <h2>Reserving a Table</h2>
            <p>Please enter the following information and submit it to reserve a table at the preferred time slot.</p>
            <p class="disclaimer">
                *<u>Cancelling a table reservation</u> must be done over the phone up to 24 hours before your reservation date and time. <br>
                *In the case of <u>not arriving on time to your reservation</u>, you will follow walk-in traffic order unless informed otherwise.
            </p>
            <?php
                require_once 'login_usr.php';
                $conn = new mysqli($hn, $un, $pw, $db);
                if ($conn->connect_error) die($conn->connect_error);
                
                if (isset($_POST['cname']) && isset($_POST['gsize']) && isset($_POST['rdate']) && isset($_POST['rtime'])) {
                    $cname = get_full_strip($conn, 'cname');
                    $gsize = $_POST['gsize'];
                    $rdate = $_POST['rdate'];
                    $rtime = $_POST['rtime'];
                    
                    $stmt = $conn->prepare("INSERT INTO reservations VALUES(NULL,?,?,?,?)");
                    $stmt->bind_param("siss",$cname,$gsize,$rdate,$rtime);
                    
                    if (!($state = $stmt->execute())) {
                        echo "INSERT failed: $stmt<br>" . $conn->error . "<br><br>";
                    }
                    else if ($state) {
                        echo "<p id=\"out\">Table Reserved. Mention your name (". $cname .") to the receptionist to get your table at the reserved date and time.</p>";
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
            <form action="reservation.php" method="post">
                <p>
                    <label for="cname_lbl">Name</label>
                    <input type="text" name="cname" id="cname_lbl" required>
                </p>
                <p>
                    <label for="gsize_lbl">Group Size</label>
                    <input type="number" value="1" min="1" name="gsize" id="gsize_lbl" required>
                </p>
                <p>
                    <label for="rdate_lbl">Date</label>
                    <input type="date" name="rdate" id="rdate_lbl" required>
                </p>
                <p>
                    <label for="rtime_lbl">Time</label>
                    <input type="time" min="08:00" max="22:00" name="rtime" id="rtime_lbl" required>
                </p>
                <input type="submit" value="Reserve" class="btn btn-outline-warning" id="resvbtn">
            </form>
        </section>
        <br class="hide">
        <img src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/images/logo.png" alt="logo" id="divider">
        <!--Website Footer-->
        <br class="hide">
        <footer>
            <p id="ffront">&#169; Darius Zhou, 2020</p>
        </footer>
    </body>
</html>