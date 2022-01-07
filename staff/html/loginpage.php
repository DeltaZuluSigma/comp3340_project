<html>
    <head>
        <title>Staff Login</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/loginpage.css">
    </head>
    <body>
        <!--Website Head-->
        <header>
            <img src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/images/logo.png" alt="logo" id="logo">
            <h1>Refined Dining</h1>
        </header>
        <br class="hide">
        <!--Website Body-->
        <section>
            <h2>Staff Login</h2>
            <?php
                session_start();
                if ($_SESSION['state'] == 0) echo "<span>Invalid Login.</span>";
                if ($_SESSION['state'] >= 1) $_SESSION['state'] = -1;
            ?>
            <form action="authenticate.php" method="post">
                <p>
                    <label for="un_lbl">User</label>
                    <input type="text" name="username" id="un_lbl" required>
                </p>
                <p>
                    <label for="pw_lbl">Password</label>
                    <input type="password" name="password" id="pw_lbl" required>
                </p>
                <input type="submit" value="Login" class="btn btn-outline-info" id="signinbtn">
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