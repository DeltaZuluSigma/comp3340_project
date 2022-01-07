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
?>

<html>
    <head>
        <title>Manager Dashboard</title>
        <!--Responsive Web Design-->
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.css">
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/jquery.min.js"></script>
        <script src="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/bootstrap.min.js"></script>
        <!--Style Sheets & JS file-->
        <link rel="stylesheet" href="http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/css/fullsite.css">
        <link rel="stylesheet" href="../css/dboard_admin.css">
        <!--Google Charts-->
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
            google.charts.load("current", {packages:['corechart']});
            google.charts.setOnLoadCallback(drawItemPref);
            google.charts.setOnLoadCallback(drawCtgyPref);
            google.charts.setOnLoadCallback(drawRating);
            google.charts.setOnLoadCallback(drawFigComp);
            google.charts.setOnLoadCallback(drawPtgComp);
            google.charts.setOnLoadCallback(drawRevSrc);
            google.charts.setOnLoadCallback(drawRevComp);
            
            function drawItemPref() {
                var data = google.visualization.arrayToDataTable([
                    ["Menu Item", "Purchases"],
                    ["RD27", 16],
                    ["RD28", 22],
                    ["RD29", 19],
                    ["RD30", 21]
                ]);
                
                var options = {
                    width: 160,
                    height: 160,
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    colors: ['#a2c922'],
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("item_pref"));
                chart.draw(data, options);
            }
            
            function drawCtgyPref() {
                var data = google.visualization.arrayToDataTable([
                    ["Category", "Purchases"],
                    ["RD", 78],
                    ["A", 89],
                    ["B", 96],
                    ["C", 91]
                ]);
                
                var options = {
                    width: 160,
                    height: 160,
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    colors: ['#a2c922'],
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("ctgy_pref"));
                chart.draw(data, options);
            }
            
            function drawRating() {
                var data = google.visualization.arrayToDataTable([
                    ["Rating", "Reviews"],
                    ["5", 78],
                    ["4", 90],
                    ["3", 57],
                    ["2", 31],
                    ["1", 12]
                ]);
                
                var options = {
                    width: 160,
                    height: 160,
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    colors: ['#a2c922'],
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.BarChart(document.getElementById("rating"));
                chart.draw(data, options);
            }
            
            function drawFigComp() {
                var data = google.visualization.arrayToDataTable([
                    ["Figure", "Amount ($thousands)"],
                    ["Revenues", 92],
                    ["Expenses", 64],
                    ["Profit", 28]
                ]);
                
                var options = {
                    width: 160,
                    height: 160,
                    bar: {groupWidth: "50%"},
                    legend: { position: "none" },
                    colors: ['#a2c922'],
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.ColumnChart(document.getElementById("fig_comp"));
                chart.draw(data, options);
            }
            
            function drawPtgComp() {
                var data = google.visualization.arrayToDataTable([
                    ["Quarter", "Revenue", "Expenses", "Profit"],
                    ["I", 80, 59, 21],
                    ["II", 87, 69, 18],
                    ["III", 92, 64, 28]
                ]);
                
                var options = {
                    width: 160,
                    height: 160,
                    legend: { position: "bottom" },
                    colors: ['red','blue','green'],
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.LineChart(document.getElementById("ptg_comp"));
                chart.draw(data, options);
            }
            
            function drawRevSrc() {
                var data = google.visualization.arrayToDataTable([
                    ["Revenue Category", "Amount ($thousands)"],
                    ["Chef Specials", 13],
                    ["Congee & Soups", 14],
                    ["Fried & Tossed", 15.5],
                    ["Grilled", 14.5],
                    ["Side Dishes", 15.2],
                    ["Desserts", 10],
                    ["Drinks", 9.8]
                ]);
                
                var options = {
                    width: 160,
                    height: 160,
                    legend: { position: "none" },
                    backgroundColor: '#f0ffc2'
                };
                
                var chart = new google.visualization.PieChart(document.getElementById("rev_src"));
                chart.draw(data, options);
            }
            
            function drawRevComp() {
                var data = google.visualization.arrayToDataTable([
                    ["Quarter", "Revenue ($thousands)", "Change"],
                    ["I", 80, 80],
                    ["II", 87, 87],
                    ["III", 92, 92]
                ]);
                
                var options = {
                    width: 160,
                    height: 160,
                    legend: { position: "none" },
                    backgroundColor: '#f0ffc2',
                    seriesType: 'bars',
                    series: {1: {type: 'line'}}
                };
                
                var chart = new google.visualization.ComboChart(document.getElementById("rev_comp"));
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
        <aside id="left" class="hide">
            <h2>Receipts</h2>
            <div id="rof" class="ovfw">
                <?php
                    /*  **RECEIPT DISPLAY PHP CODE**
                        *This code looks at 2 different tables to construct simplified receipts
                        - 'Active Tickets' (orders being served and not yet billed) are set to display as white
                        - 'Inactive Tickets' (orders billed and paid/to-be paid) are set to display as red
                    */
                    
                    $query1 = "SELECT receipt_num,date,DATE_FORMAT(time,'%l:%i %p'),order_state,order_id FROM receipts ORDER BY date DESC";
                    $result1 = $conn->query($query1);
                    if (!$result1) die ("Database access failed: " . $conn->error);
                    $rows1 = $result1->num_rows;
                    
                    if ($rows1 == 0) {
                        echo "No receipts/tickets have been processed yet.";
                    }
                    else {
                        for ($j = 0; $j < $rows1; ++$j) {
                            $result1->data_seek($j);
                            $row1 = $result1->fetch_array(MYSQLI_NUM);
                            
                            if ($row1[3] == 'active') {
                                echo "<p class=\"active\"><b> Receipt #" . $row1[0] . "<br>" . $row1[1] . " " . $row1[2] . "</b><br>";
                            }
                            else if ($row1[3] == 'inactive') {
                                echo "<p class=\"inactive\"><b> Receipt #" . $row1[0] . "<br>" . $row1[1] . " " . $row1[2] . "</b><br>";
                            }
                            
                            $query2 = "SELECT * FROM orders WHERE order_id=" . $row1[4];
                            $result2 = $conn->query($query2);
                            if (!$result2) die ("Database access failed: " . $conn->error);
                            $rows2 = $result2->num_rows;
                            
                            if ($rows2 != 1) {
                                echo "Order Retrieval Error.";
                            }
                            else {
                                $result2->data_seek(0);
                                $row2 = $result2->fetch_array(MYSQLI_NUM);
                                
                                $quantity = 0;
                                
                                for ($k = 1; $k < 31; ++$k) {
                                    $quantity += $row2[$k];
                                }
                                echo "Ticket includes " . $quantity . " menu items.<br>";
                                
                                if ($row1[3] == 'active') {
                                    echo "<u>Total:</u> Pending";
                                }
                                if ($row1[3] == 'inactive') {
                                    echo "<u>Total:</u> $" . $row2[31];
                                }
                                echo "</p>";
                            }
                            echo "<br>";
                        }
                    }
                ?>
            </div>
            <br>
        </aside>
        <aside id="right" class="hide">
            <h2>Statistics & Graphs</h2>
            <h4>Quarter III</h4>
            <div id="sof" class="ovfw">
                <div id="item_pref"></div>
                <p class="statchart">Menu Item Purchase Comparison</p>
                <br>
                <div id="ctgy_pref"></div>
                <p class="statchart">Category Purchase Comparison</p>
                <br>
                <div id="rating"></div>
                <p class="statchart">Rating Chart - Overall</p>
                <br>
            </div>
        </aside>
        <section class="hide">
            <h2>Sales Figures Breakdown</h2>
            <h3> Quarter IV</h3>
            <p class="recording">
                <b>Total Item Quantity Sold:</b>
                <?php
                    /*  **QUANTITY COUNT PHP CODE**
                        *This code looks at 2 different tables to calculate the 'total item quantity sold' in quarter 4
                        > Only 'inactive receipts/tickets'
                    */
                    
                    $query1 = "SELECT order_id FROM receipts WHERE order_state='inactive'";
                    $result1 = $conn->query($query1);
                    if (!$result1) die ("Database access failed: " . $conn->error);
                    $rows1 = $result1->num_rows;
                    
                    $quantity = 0;
                    
                    for ($j = 0; $j < $rows1; ++$j) {
                        $result1->data_seek($j);
                        $row1 = $result1->fetch_array(MYSQLI_NUM);
                        
                        $query2 = "SELECT * FROM orders WHERE order_id=" . $row1[0];
                        $result2 = $conn->query($query2);
                        if (!$result2) die ("Database access failed: " . $conn->error);
                        $rows2 = $result2->num_rows;
                        
                        if ($rows2 != 1) {
                            echo "Order Retrieval Error.";
                        }
                        else {
                            $result2->data_seek(0);
                            $row2 = $result2->fetch_array(MYSQLI_NUM);
                            
                            for ($k = 1; $k < 31; ++$k) {
                                $quantity += $row2[$k];
                            }
                        }
                    }
                    echo $quantity . " items";
                ?>
            </p>
            <p class="recording">
                <b>Total Number of Receipts:</b>
                <?php
                    /*  **RECEIPT COUNT PHP CODE**
                        *This code looks at the 'receipts' table to calculate the 'total # of receipts' in quarter 4
                    */
                    
                    $query = "SELECT COUNT(receipt_num) FROM receipts";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    $result->data_seek(0);
                    
                    echo $result->fetch_array()['COUNT(receipt_num)'] . " receipts";
                ?>
            </p>
            <p class="recording">
                <b>Cost of Goods Sold:</b>
                <?php
                    /*  **COST TOTALING PHP CODE**
                        *This code looks at 2 table to calculate the 'cost of goods sold' in quarter 4
                        > Only 'inactive receipts/tickets'
                    */
                    
                    $query1 = "SELECT order_id FROM receipts WHERE order_state='inactive'";
                    $result1 = $conn->query($query1);
                    if (!$result1) die ("Database access failed: " . $conn->error);
                    $rows1 = $result1->num_rows;
                    
                    $cost = 0;
                    
                    for ($j = 0; $j < $rows1; ++$j) {
                        $result1->data_seek($j);
                        $row1 = $result1->fetch_array(MYSQLI_NUM);
                        
                        $query2 = "SELECT total_cost FROM orders WHERE order_id=" . $row1[0];
                        $result2 = $conn->query($query2);
                        if (!$result2) die ("Database access failed: " . $conn->error);
                        $rows2 = $result2->num_rows;
                        
                        $result2->data_seek($j);
                        
                        $cost += $result2->fetch_array()['total_cost'];
                        
                    }
                    echo "$" . $cost;
                ?>
            </p>
            <p class="recording">
                <b>Revenue:</b>
                <?php
                    /*  **REVENUE TOTALING PHP CODE**
                        *This code looks at 2 table to calculate the 'revenue' in quarter 4
                        > Only 'inactive receipts/tickets'
                    */
                    
                    $total = 0;
                    
                    for ($j = 0; $j < $rows1; ++$j) {
                        $result1->data_seek($j);
                        $row1 = $result1->fetch_array(MYSQLI_NUM);
                        
                        $query2 = "SELECT bill FROM orders WHERE order_id=" . $row1[0];
                        $result2 = $conn->query($query2);
                        if (!$result2) die ("Database access failed: " . $conn->error);
                        $rows2 = $result2->num_rows;
                        
                        $result2->data_seek($j);
                        
                        $total += $result2->fetch_array()['bill'];
                        
                    }
                    echo "$" . $total;
                ?>
            </p>
            <p class="recording">
                <b>Average Cost Per Receipt:</b>
                <?php
                    /*  **COST AVERAGE PHP CODE**
                        *This code looks at 2 table to calculate the 'average cost per receipt' in quarter 4
                        > Only 'inactive receipts/tickets'
                    */
                    
                    $query = "SELECT COUNT(receipt_num) FROM receipts WHERE order_state='inactive'";
                    $result = $conn->query($query);
                    if (!$result) die ("Database access failed: " . $conn->error);
                    $rows = $result->num_rows;
                    
                    $result->data_seek(0);
                    $row = $result->fetch_array(MYSQLI_NUM);
                    
                    $cost = $cost / $row[0];
                    
                    echo "$" . round($cost,2) . "/receipt";
                ?>
            </p>
            <p class="recording">
                <b>Average Revenue Per Receipt:</b>
                <?php
                    /*  **REVENUE AVERAGE PHP CODE**
                        *This code looks at 2 table to calculate the 'average revenue per receipt' in quarter 4
                        > Only 'inactive receipts/tickets'
                    */
                    
                    $result->data_seek(0);
                    $row= $result->fetch_array(MYSQLI_NUM);
                    
                    $total = $total / $row[0];
                    
                    echo "$" . round($total,2) . "/ receipt";
                ?>
            </p>
            <br>
            <h4>Quarter III</h4>
            <table>
                <tr>
                    <td>
                        <div id="fig_comp"></div>
                        <p class="statchart">Figure Totals Comparison</p>
                    </td>
                    <td>
                        <div id="ptg_comp"></div>
                        <p class="statchart">Figure Percentage Comparisons</p>
                    </td>
                    <td>
                        <div id="rev_src"></div>
                        <p class="statchart">Categorical Revenue</p>
                    </td>
                    <td>
                        <div id="rev_comp"></div>
                        <p class="statchart">Quarter Revenue Comparison</p>
                    </td>
                </tr>
            </table>
        </section>
        <!--Website Footer-->
        <br class="hide">
        <footer>
            <p id="ffront">&#169; Darius Zhou, 2020</p>
        </footer>
    </body>
</html>