<html>
    <head><title>You Are NOT Supposed To Be Here...</title></head>
    <?php
        session_start();
        require_once 'login_epl.php';
        $conn = new mysqli($hn, $un, $pw, $db);
        if (mysqli_connect_errno()) exit('Failed to connect to MySQL: ' . mysqli_connect_error());
        
        if (isset($_POST['username']) && isset($_POST['password'])) {
            echo "fields filled; ";
            $stmt = $conn->prepare('SELECT account_id,password FROM accounts WHERE username = ?');
        	$user = get_full_strip($conn,'username');
        	$pwd = get_full_strip($conn,'password');
        	
        	$stmt->bind_param('s', $user);
        	$stmt->execute();
        	$stmt->store_result();
        	
            if ($stmt->num_rows) {
                echo "valid username; ";
            	$stmt->bind_result($id, $password);
            	$stmt->fetch();
            	
            	$saltpre = "a1&g7";
                $saltpost = "c3*t0";
                $token = hash('ripemd128',"$saltpre$pwd$saltpost");
            	
            	if ($token == $password) {
            	    echo "valid password; ";
            		session_regenerate_id();
            		$_SESSION['user'] = $user;
            		$_SESSION['id'] = $id;
            		if ($id == 1) {
            		    $_SESSION['state'] = 2;
            		    header('Location:http://zhou16g.myweb.cs.uwindsor.ca/60334/project/manager/html/dboard_admin.php');
            		}
            		else if ($id == 2) {
            		    $_SESSION['state'] = 1;
            		    header('Location:dboard_staff.php');
            		}
            	} else {
            	    $_SESSION['state'] = 0;
            		header('Location:loginpage.php');    
                    $connection->close();
            	}
            }
            else {
            	$_SESSION['state'] = 0;
            	header('Location:loginpage.php');    
            }
            
        }
        else {
            echo "first visit; ";
            $_SESSION['state'] = -1;
            header('Location:loginpage.php');
        }
        
        //Santizing Functions
        function get_full_strip($conn, $var) {
            $var = stripslashes($_POST[$var]);
            $var = strip_tags($var);
            $var = $conn->real_escape_string($var);
            return htmlentities($var);
        }
    ?>
</html>