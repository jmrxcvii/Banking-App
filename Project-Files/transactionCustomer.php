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

                if ($_POST["submit"] === "Deposit") {
                    if ($_POST["transaction"] === "") {
                        $conn->close();
                        header("Location: transactionCustomer.php");
                    } else {
                        $sql = "insert into transactions (trans_type, t_date, t_time, amount, Acc_ID) values('deposit','" . date("Y-m-d") . "','" . date("h:i") . "'," . $_POST["transaction"] . ", (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "')))";
                        $conn->query($sql);

                        $sql = "select balance from accounts where accNo = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        $sql = "update accounts set balance = " . ($row["balance"] + $_POST["transaction"]) . " where accNo = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);

                        $sql = "select balance from accounts where accNo = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        echo "New Account Balance: $" . $row["balance"];
                        $conn->close();
                    }
                } else if ($_POST["submit"] === "Withdraw") {
                    if ($_POST["transaction"] === "") {
                        $conn->close();
                        header("Location: transactionCustomer.php");
                    } else {
                        $sql = "insert into transactions (trans_type, t_date, t_time, amount, Acc_ID) values('withdraw','" . date("Y-m-d") . "','" . date("h:i") . "'," . $_POST["transaction"] . ", (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "')))";
                        $conn->query($sql);

                        $sql = "select balance from accounts where accNo = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        $sql = "update accounts set balance = " . ($row["balance"] - $_POST["transaction"]) . " where accNo = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);

                        $sql = "select balance from accounts where accNo = (select Acc_ID from customer_holds where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        echo "New Account Balance: $" . $row["balance"];
                        $conn->close();
                    }
                } else if ($_POST["submit"] === "Loan Payment") {
                    if ($_POST["transaction"] === "") {
                        $conn->close();
                        header("Location: transactionCustomer.php");
                    } else {
                        $sql = "insert into payments (p_date,p_time,amount,Loan_ID) values('" . date("Y-m-d") . "','" . date("h:i") . "'," . $_POST["transaction"] . ", (select Loan_ID from customer_borrows where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "')))";
                        $conn->query($sql);

                        $sql = "select balance from loan where loanNo = (select Loan_ID from customer_borrows where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();

                        $sql = "update loan set balance = " . ($row["balance"] - $_POST["transaction"]) . " where loanNo = (select Loan_ID from customer_borrows where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);

                        $sql = "select balance from loan where loanNo = (select Loan_id from customer_borrows where Cust_ID = (select custNo from customer where email = '" . $_SESSION['email'] . "'))";
                        $conn->query($sql);
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        echo " New Loan Balance: $" . $row["balance"];
                        $conn->close();
                    }
                }
            } else {
        ?>
            <form action="/transactionCustomer.php" method="post">
                Enter Amount: <input type="text" name="transaction"><br>
                <input type="submit" name="submit" value="Deposit">
                <input type="submit" name="submit" value="Withdraw">
                <input type="submit" name="submit" value="Loan Payment">
            </form>
        <?php 
            }
            echo "<br><a href=\"customer.php\">Go Back</a>";
        ?>
    </body>
</html>