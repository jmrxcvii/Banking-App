<html>
  <body>
    <table border=1>
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
            $sql = "select trans_type, amount, t_date, t_time from transactions where Acc_ID = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<tr> <th> Transaction Type </th><th> Amount </th> <th> Date </th><th> Time </th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>". $row["trans_type"]. "</td><td> $" .  $row["amount"] . "</td><td>" . $row["t_date"]. "</td><td>" . $row["t_time"].  "</td></tr>";
                }
            }
            $sql = "select amount, p_date, p_time from payments where Loan_ID = (select Loan_ID from customer_borrows where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td> loan </td><td> $" .  $row["amount"] . "</td><td>" . $row["p_date"]. "</td><td>" . $row["p_time"].  "</td></tr>";
                }
            } else {
                echo "<tr> <td> No Transactions </td> </tr>";
            }
            $conn->close();
            echo "<br><a href=\"customer.php\">Go Back</a>";
        ?>
    </table> 
  </body>
</html>