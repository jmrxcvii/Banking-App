<html>
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
            $sql = "select cash_held from branch where brchNo = (select branch from staff where email = '" . $_SESSION['email'] . "')";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            echo "Bank cash total: $" . $row["cash_held"];
            $conn->close();
            echo "<br><a href=\"staff.php\">Go Back</a>";
        ?>
    </body>
</html>