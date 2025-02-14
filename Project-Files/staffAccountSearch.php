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

            if (isset($_POST['specific_staff'])) {
                if (!empty($_POST['selected_email'])) {

                    //create query with appropriate values
                    $sql = "select s.First_name, s.Last_name, a.accNo, a.acc_type, a.balance, a.a_status, a.Brch_ID from Accounts a join Staff_Holds as sh on a.accNo = sh.Acc_ID join Staff as s on s.staffNo = sh.Staff_ID where s.email = \"{$_POST['selected_email']}\"";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Staff Account(s) Table (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Account ID</th><th style=\"border: 1px solid black;\">Account Type</th><th style=\"border: 1px solid black;\">Balance</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Branch ID</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['accNo']}</td><td style=\"border: 1px solid black;\">{$row['acc_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['a_status']}</td><td style=\"border: 1px solid black;\">{$row['Brch_ID']}</td></tr>";
                    }
                    echo "</table>";
                    echo "<a href=\"staffAccountSearch.php\">Back to Staff Account Search Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"staffAccountSearch.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['staff_account'])) {
                if (!empty($_POST['selected_account'])) {

                    //create query with appropriate values
                    $sql = "select a.accNo, a.acc_type, a.balance, a.a_status, a.Brch_ID, t.transNo, t.trans_type, t.t_date, t.t_time, t.amount, co.c_message, s.Last_name, s.email from Accounts as a left join Transactions as t on a.accNo = t.Acc_ID left join Comments as co on co.Trans_ID = t.transNo left join Staff as s on co.Staff_ID = s.staffNo where a.accNo = {$_POST['selected_account']}";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Staff Account(s) Table (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">Account ID</th><th style=\"border: 1px solid black;\">Type</th><th style=\"border: 1px solid black;\">Balance</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Branch ID</th><th style=\"border: 1px solid black;\">Transaction Number</th><th style=\"border: 1px solid black;\">Transaction Type</th><th style=\"border: 1px solid black;\">Date</th><th style=\"border: 1px solid black;\">Time</th><th style=\"border: 1px solid black;\">Amount</th><th style=\"border: 1px solid black;\">Transaction Note</th><th style=\"border: 1px solid black;\">Left By (Staff)</th><th style=\"border: 1px solid black;\">Staff Email</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['accNo']}</td><td style=\"border: 1px solid black;\">{$row['acc_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['a_status']}</td><td style=\"border: 1px solid black;\">{$row['Brch_ID']}</td><td style=\"border: 1px solid black;\">{$row['transNo']}</td><td style=\"border: 1px solid black;\">{$row['trans_type']}</td><td style=\"border: 1px solid black;\">{$row['t_date']}</td><td style=\"border: 1px solid black;\">{$row['t_time']}</td><td style=\"border: 1px solid black;\">{$row['amount']}</td><td style=\"border: 1px solid black;\">{$row['c_message']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['email']}</td></tr>";
                    }

                    echo "</table>";
                    echo "<a href=\"staffAccountSearch.php\">Back to Staff Account Search Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"staffAccountSearch.php\">Resubmit</a>";
                }
            }
        } else {
    ?>

        <h3>Staff Account Search Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link css-tab active" data-bs-toggle="tab">Search For Staff Accounts</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link  css-tab" data-bs-toggle="tab">Search Account</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container mt-3 active" id="tab1">
                <form action="/staffAccountSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Email For Search (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <input type="submit" value="View Specific Staff" name="specific_staff" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab2">
                <form action="/staffAccountSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Account Number For Search (required):</label>
                    <input type="text" name="selected_account">
                    <hr>
                    <input type="submit" value="View Specific Account" name="staff_account" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>