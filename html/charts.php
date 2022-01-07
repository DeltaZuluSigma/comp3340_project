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
    
    /*  **CHART VALUE GENERATION PHP CODE**
        *Generates the variables to be used in charts
    */
    $items = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
    $ctgy = array(0,0,0,0,0,0,0);
    $rev = 0;
    $exp = 0;
    
    $query1  = "SELECT order_id FROM receipts WHERE order_state='inactive'";
    $result1 = $conn->query($query1);
    if (!$result1) die ("Database access failed: " . $conn->error);
    $rows1 = $result1->num_rows;
    
    for ($j = 0; $j < $rows1; ++$j) {
        $result1->data_seek($j);
        $query2  = "SELECT * FROM orders WHERE order_id=" . $result1->fetch_array()['order_id'];
        $result2 = $conn->query($query2);
        if (!$result2) die ("Database access failed: " . $conn->error);
        $rows2 = $result2->num_rows;
        
        $result2->data_seek(0);
        $row2 = $result2->fetch_array(MYSQLI_NUM);
        
        for ($k = 1; $k < 31; ++$k) {
            $items[$k-1] += $row2[$k];
        }
        
        $rev += $row2[31];
        $exp += $row2[32];
    }
    $prof = $rev - $exp;
    
    for ($j = 26; $j < 30; ++$j) {
        $ctgy[0] += $items[$j];
    }
    for ($j = 0; $j < 4; ++$j) {
        $ctgy[1] += $items[$j];
    }
    for ($j = 4; $j < 9; ++$j) {
        $ctgy[2] += $items[$j];
    }
    for ($j = 9; $j < 12; ++$j) {
        $ctgy[3] += $items[$j];
    }
    for ($j = 12; $j < 18; ++$j) {
        $ctgy[4] += $items[$j];
    }
    for ($j = 18; $j < 21; ++$j) {
        $ctgy[5] += $items[$j];
    }
    for ($j = 21; $j < 26; ++$j) {
        $ctgy[6] += $items[$j];
    }
?>

<html>
    <head>
        <title>Business Live & Full Charts</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets & JS file-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/charts.css">
        <!--Google Charts-->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawFullCtgyPref);
            google.charts.setOnLoadCallback(drawFullItemPref);
            google.charts.setOnLoadCallback(drawLiveCtgyPref);
            google.charts.setOnLoadCallback(drawLiveItemPref);
            google.charts.setOnLoadCallback(drawLiveFigComp);
            
            /*Live Variables*/
            var items = [<?php echo $items[0]?>,<?php echo $items[1]?>,<?php echo $items[2]?>,<?php echo $items[3]?>,<?php echo $items[4]?>,<?php echo $items[5]?>,
                <?php echo $items[6]?>,<?php echo $items[7]?>,<?php echo $items[8]?>,<?php echo $items[9]?>,<?php echo $items[10]?>,<?php echo $items[11]?>,
                <?php echo $items[12]?>,<?php echo $items[13]?>,<?php echo $items[14]?>,<?php echo $items[15]?>,<?php echo $items[16]?>,<?php echo $items[17]?>,
                <?php echo $items[18]?>,<?php echo $items[19]?>,<?php echo $items[20]?>,<?php echo $items[21]?>,<?php echo $items[22]?>,<?php echo $items[23]?>,
                <?php echo $items[24]?>,<?php echo $items[25]?>,<?php echo $items[26]?>,<?php echo $items[27]?>,<?php echo $items[28]?>,<?php echo $items[29]?>];
            var ctgy = [<?php echo $ctgy[0]?>,<?php echo $ctgy[1]?>,<?php echo $ctgy[2]?>,<?php echo $ctgy[3]?>,<?php echo $ctgy[4]?>,<?php echo $ctgy[5]?>,
                <?php echo $ctgy[6]?>];
            var rev = <?php echo $rev?>;
            var exp = <?php echo $exp?>;
            var prof = <?php echo $prof?>;
            
            function drawFullCtgyPref() {
                var data = google.visualization.arrayToDataTable([
                    ["Category", "Purchases", {role:'style'}],
                    ["RD", 78, '#89ad11'],
                    ["A", 86, '#93b71b'],
                    ["B", 96, '#9dc126'],
                    ["C", 89, '#a8cc31'],
                    ["D", 98, '#b2d63c'],
                    ["E", 67, '#bce047'],
                    ["F", 70, '#c7eb52']
                ]);
                
                var options = {
                    width: 500,
                    height: 200,
                    hAxis:{title: 'Menu Category',titleTextStyle:{italic:false,bold:true}},
                    vAxis:{title: '# of Purchases',titleTextStyle:{italic:false,bold:true}},
                    chartArea:{left:60,right:30,top:30,bottom:60},
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("full_ctgy_pref"));
                chart.draw(data, options);
            }
            
            function drawFullItemPref() {
                var data = google.visualization.arrayToDataTable([
                    ["Menu Item", "Purchases", {role:'style'}],
                    ["RD27", 16, '#89ad11'],["RD28", 22, '#9dc126'],["RD29", 19, '#b2d63c'],["RD30", 21, '#c7eb52'],
                    ["A1", 24, '#27a0d9'],["A2", 17, '#59b8e5'],["A3", 23, '#8bd1f2'],["A4", 22, '#bdeaff'],
                    ["B5", 19, '#89ad11'],["B6", 20, '#98bc21'],["B7", 16, '#a8cc31'],["B8", 18, '#b7db41'],["B9", 23, '#c7eb52'],
                    ["C10", 30, '#27a0d9'],["C11", 28, '#72c5ec'],["C12", 31, '#bdeaff'],
                    ["D13", 11, '#89ad11'],["D14", 10, '#95b91e'],["D15", 11, '#a1c52b'],["D16", 22, '#aed238'],["D17", 21, '#bade45'],["D18", 23, '#c7eb52'],
                    ["E19", 23, '#27a0d9'],["E20", 21, '#72c5ec'],["E21", 23, '#bdeaff'],
                    ["F22", 15, '#89ad11'],["F23", 14, '#98bc21'],["F24", 17, '#a8cc31'],["F25", 13, '#b7db41'],["F26", 11, '#c7eb52']
                ]);
                
                var options = {
                    width: 900,
                    height: 300,
                    hAxis:{title: 'Menu Item ID',titleTextStyle:{italic:false,bold:true}},
                    vAxis:{title: '# of Purchases',titleTextStyle:{italic:false,bold:true}},
                    chartArea:{left:75,right:30,top:30,bottom:75},
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    colors: ['#a2c922'],
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("full_item_pref"));
                chart.draw(data, options);
            }
            
            function drawLiveCtgyPref() {
                var data = google.visualization.arrayToDataTable([
                    ["Category", "Purchases", {role:'style'}],
                    ['RD', ctgy[0], '#89ad11'],
                    ['A', ctgy[1], '#93b71b'],
                    ['B', ctgy[2], '#9dc126'],
                    ['C', ctgy[3], '#a8cc31'],
                    ['D', ctgy[4], '#b2d63c'],
                    ['E', ctgy[5], '#bce047'],
                    ['F', ctgy[6], '#c7eb52'],
                ]);
                
                var options = {
                    width: 500,
                    height: 200,
                    hAxis:{title: 'Menu Category',titleTextStyle:{italic:false,bold:true}},
                    vAxis:{title: '# of Purchases',titleTextStyle:{italic:false,bold:true}},
                    chartArea:{left:60,right:30,top:30,bottom:60},
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("live_ctgy_pref"));
                chart.draw(data, options);
            }
            
            function drawLiveItemPref() {
                var data = google.visualization.arrayToDataTable([
                    ["Menu Item", "Purchases", {role:'style'}],
                    ["RD27", items[26], '#89ad11'],["RD28", items[27], '#9dc126'],["RD29", items[28], '#b2d63c'],["RD30", items[29], '#c7eb52'],
                    ["A1", items[0], '#27a0d9'],["A2", items[1], '#59b8e5'],["A3", items[2], '#8bd1f2'],["A4", items[3], '#bdeaff'],
                    ["B5", items[4], '#89ad11'],["B6", items[5], '#98bc21'],["B7", items[6], '#a8cc31'],["B8", items[7], '#b7db41'],["B9", items[8], '#c7eb52'],
                    ["C10", items[9], '#27a0d9'],["C11", items[10], '#72c5ec'],["C12", items[11], '#bdeaff'],
                    ["D13", items[12], '#89ad11'],["D14", items[13], '#95b91e'],["D15", items[14], '#a1c52b'],["D16", items[15], '#aed238'],
                        ["D17", items[16], '#bade45'],["D18", items[17], '#c7eb52'],
                    ["E19", items[18], '#27a0d9'],["E20", items[19], '#72c5ec'],["E21", items[20], '#bdeaff'],
                    ["F22", items[21], '#89ad11'],["F23", items[22], '#98bc21'],["F24", items[23], '#a8cc31'],["F25", items[24], '#b7db41'],["F26", items[25], '#c7eb52']
                ]);
                
                var options = {
                    width: 900,
                    height: 300,
                    hAxis:{title: 'Menu Item ID',titleTextStyle:{italic:false,bold:true}},
                    vAxis:{title: '# of Purchases',titleTextStyle:{italic:false,bold:true}},
                    chartArea:{left:75,right:30,top:30,bottom:75},
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    colors: ['#a2c922'],
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("live_item_pref"));
                chart.draw(data, options);
            }
            
            function drawLiveFigComp() {
                var data = google.visualization.arrayToDataTable([
                    ["Figure", "Amount ($)", {role:'style'}],
                    ["Revenues", rev, '#89ad11'],
                    ["Expenses", exp, '#a8cc31'],
                    ["Profit", prof, '#c7eb52']
                ]);
                
                var options = {
                    width: 300,
                    height: 250,
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("live_fig_comp"));
                chart.draw(data, options);
            }
        </script>
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
                <a href="records.php?table=receipts">Records Review</a>
            </p>
        </nav>
        <!--Website Body-->
        <div id="warning" class="response">For security reasons, all pages accessible to managers, owners, administrators, etc. will not display for mobile use.</div>
        <section class="hide">
            <h2>Quarter III</h2>
            <p>Menu Item Purchase Comparison</p>
            <div id="full_item_pref"></div>
            <br>
            <p>Menu Category Purchase Comparison</p>
            <div id="full_ctgy_pref"></div>
            <br>
            <h2> Quarter IV</h2>
            <p>Menu Item Purchase Comparison</p>
            <div id="live_item_pref"></div>
            <br>
            <p>Menu Category Purchase Comparison</p>
            <div id="live_ctgy_pref"></div>
            <br>
            <p>Figure Totals Comparison</p>
            <div id="live_fig_comp"></div>
            <br>
        </section>
        <!--Website Footer-->
        <br class="hide">
        <footer>
            <p id="ffront">&#169; Darius Zhou, 2020</p>
        </footer>
    </body>
</html>