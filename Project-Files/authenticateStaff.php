<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
          <?php
            session_start();

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "banking_system";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
            }

            $sql =  "select * from Staff where email = \"{$_SESSION['email']}\" and position = \"Manager\"";
            $result = $conn->query($sql);
            $exist= $result->fetch_assoc();


            if($exist){ //if the logged in employee for the session is a Manager
                $conn->close();
                header("Location: manageStaff.php");

            } else {
                $conn->close(); 
                echo "<h3>Access Denied: You can not access the staff management system.</h3>";
                echo "<a href = \"staff.php\"> Return to Staff Portal</>";
            }
        ?>
</body>
</html>