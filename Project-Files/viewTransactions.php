<html>
    <body>
        <?php
            session_start();
            if($_SERVER['REQUEST_METHOD'] === 'POST'){

                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "banking_system";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if ($_POST["submit"] === "View Comments") {
                    $sql = "select trans_type, t_date, t_time, amount, c_message, threat_flag from transactions cross join comments where Trans_ID = transNo";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo "<table border=1><tr><th> Transaction Type </th><th> Date </th><th> Time </th><th> Amount </th><th> Message </th><th> Threat Flag </th></tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>". $row["trans_type"]. "</td><td> " .  $row["t_date"] . "</td><td>" . $row["t_time"]. "</td><td> $" . $row["amount"].  "</td><td>" . $row["c_message"] . "</td><td>" . $row["threat_flag"] . "</td></tr>";
                        }
                        echo "</table>";
                    }
                } else if ($_POST["submit"] === "View Transactions") {
                    $sql = "select trans_type, t_date, t_time, amount, Acc_ID from transactions";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        echo "<table border=1><tr><th> Transaction Type </th><th> Date </th><th> Time </th><th> Amount </th><th> Account ID </th></tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td>". $row["trans_type"]. "</td><td> " .  $row["t_date"] . "</td><td>" . $row["t_time"]. "</td><td> $" . $row["amount"]. "</td><td>" . $row["Acc_ID"] . "</td></tr>";
                        }
                        echo "</table>";
                    }
                }
                $conn->close();
            } else {
        ?>
        <form action="/viewTransactions.php" method="post">
            <input type="submit" name="submit" value="View Comments">
            <input type="submit" name="submit" value="View Transactions">
        </form>
        <?php
            }
            echo "<br><a href=\"staff.php\">Go Back</a>";
        ?>
    </body>
</html?