<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
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

            if (isset($_POST['view_all_customers'])) {
                //create query with appropriate values
                $sql = "select * from Customer";
                $result = $conn->query($sql);
                $conn->close();

                echo"<h3>All Customer(s) Table (values from database)</h3>";
                echo "<table style=\"border: 1px solid black;\">";
                echo "<tr><th style=\"border: 1px solid black;\">Customer ID</th><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Street</th><th style=\"border: 1px solid black;\">City</th><th style=\"border: 1px solid black;\">Zip Code</th><th style=\"border: 1px solid black;\">State</th><th style=\"border: 1px solid black;\">Date of Birth</th><th style=\"border: 1px solid black;\">Phone Number</th><th style=\"border: 1px solid black;\">Email</th><th style=\"border: 1px solid black;\">Password</th></tr>";
          while($row = $result->fetch_assoc()) {
                    echo "<tr><td style=\"border: 1px solid black;\">{$row['custNo']}</td><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['street']}</td><td style=\"border: 1px solid black;\">{$row['city']}</td><td style=\"border: 1px solid black;\">{$row['zip']}</td><td style=\"border: 1px solid black;\">{$row['c_state']}</td><td style=\"border: 1px solid black;\">{$row['date_of_birth']}</td><td style=\"border: 1px solid black;\">{$row['phone']}</td><td style=\"border: 1px solid black;\">{$row['email']}</td><td style=\"border: 1px solid black;\">{$row['c_password']}</td></tr>";
                }      
                echo "</table>";
                echo "<a href=\"customerSearch.php\">Back to Customer Search Portal</a>";

            }
            else if (isset($_POST['specific_customers'])) {
                if (!empty($_POST['selected_email'])) {

                    //create query with appropriate values
                    $sql = "select c.First_name, c.Last_name, a.accNo, a.acc_type, a.balance, a.a_status, a.Brch_ID from Accounts a join Customer_Holds as ch on a.accNo = ch.Acc_ID join Customer as c on c.custNo = ch.Cust_ID where c.email = \"{$_POST['selected_email']}\"";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Customer Account(s) Table (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Account ID</th><th style=\"border: 1px solid black;\">Type</th><th style=\"border: 1px solid black;\">Balance</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Branch ID</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['accNo']}</td><td style=\"border: 1px solid black;\">{$row['acc_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['a_status']}</td><td style=\"border: 1px solid black;\">{$row['Brch_ID']}</td></tr>";
                    }
                    echo "</table>";
                    echo "<a href=\"customerSearch.php\">Back to Customer Search Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"customerSearch.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['customer_account'])) {
                if (!empty($_POST['selected_account'])) {

                    //create query with appropriate values
                    $sql = "select a.accNo, a.acc_type, a.balance, a.a_status, a.Brch_ID, t.transNo, t.trans_type, t.t_date, t.t_time, t.amount, co.c_message, s.Last_name, s.email from Accounts as a left join Transactions as t on a.accNo = t.Acc_ID left join Comments as co on co.Trans_ID = t.transNo left join Staff as s on co.Staff_ID = s.staffNo where a.accNo = {$_POST['selected_account']}";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Customer Account(s) Table (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">Account ID</th><th style=\"border: 1px solid black;\">Type</th><th style=\"border: 1px solid black;\">Balance</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Branch ID</th><th style=\"border: 1px solid black;\">Transaction ID</th><th style=\"border: 1px solid black;\">Transaction Type</th><th style=\"border: 1px solid black;\">Date</th><th style=\"border: 1px solid black;\">Time</th><th style=\"border: 1px solid black;\">Transaction Amount</th><th style=\"border: 1px solid black;\">Note</th><th style=\"border: 1px solid black;\">Staff Last Name (wrote note)</th><th style=\"border: 1px solid black;\">Staff Email</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['accNo']}</td><td style=\"border: 1px solid black;\">{$row['acc_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['a_status']}</td><td style=\"border: 1px solid black;\">{$row['Brch_ID']}</td><td style=\"border: 1px solid black;\">{$row['transNo']}</td><td style=\"border: 1px solid black;\">{$row['trans_type']}</td><td style=\"border: 1px solid black;\">{$row['t_date']}</td><td style=\"border: 1px solid black;\">{$row['t_time']}</td><td style=\"border: 1px solid black;\">{$row['amount']}</td><td style=\"border: 1px solid black;\">{$row['c_message']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['email']}</td></tr>";
                    }

                    echo "</table>";
                    echo "<a href=\"customerSearch.php\">Back to Customer Search Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"customerSearch.php\">Resubmit</a>";
                }
            }
        } else {
    ?>

        <h3>Customer(s) Search Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">View All Customer(s)</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link  css-tab" data-bs-toggle="tab">Search For Customer Accounts</a>
            </li>
            <li class="nav-items">
                <a href="#tab3" class="nav-link  css-tab" data-bs-toggle="tab">Search Account</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
                <form action="/customerSearch.php" method="post" class="d-flex flex-column">
                    <input type="submit" value="View All Customer(s)" name="view_all_customers" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab2">
                <form action="/customerSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Email For Search (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <input type="submit" value="View Specific Customer" name="specific_customers" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab3">
                <form action="/customerSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Account Number For Search (required):</label>
                    <input type="text" name="selected_account">
                    <hr>
                    <input type="submit" value="View Specific Account" name="customer_account" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>