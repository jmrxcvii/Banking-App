<html>
    <body>
        <table border=1>
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
                    if ($_POST["email"] === "") {
                        $conn->close();
                        header("Location: reportCustomer.php");
                    } else {
                        echo "<tr><th> Account Balance </th><th> Loan Balance </th></tr>";
                        echo "<tr>";
                        $sql = "select balance from accounts where accNo = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_POST['email'] . "'))";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<td> $" . $row["balance"] . "</td>";
                            }
                        } else {
                            echo "<td> No Account </td>";
                        }
                        $sql = "select balance from loan where loanNo = (select Loan_ID from customer_borrows where Cust_ID = (select custNo from customer where email = '" . $_POST['email'] . "'))";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<td> $" . $row["balance"] . "</td>";
                            }
                        } else {
                            echo "<td> No Loan </td>";
                        }
                        echo "</tr>";

                        echo "<tr><th > Comments </th><th> Date </th></tr>";
                        $sql = "select f_message, f_date from feedback where Cust_ID = (select custNo from customer where email = '" . $_POST["email"] . "')";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo "<tr><td> " . $row["f_message"] . "</td><td>" . $row["f_date"] . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td> No Comments </td></tr>";
                        }
                        $conn->close();
                    }
                } else {
            ?>
                <form action="/reportCustomer.php" method="post">
                    Enter customer E-mail: <input type="text" name="email"><br>
                    <input type="submit" value="Submit">
                </form>
            <?php 
                }
                echo "<br><a href=\"staff.php\">Go Back</a>";
            ?>
        </table> 
    </body>
</html>