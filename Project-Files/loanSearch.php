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

            if (isset($_POST['view_all_loans'])) {
                //create query with appropriate values
                $sql = "select * from loan";
                $result = $conn->query($sql);
                $conn->close();

                echo"<h3>Loan Table (values from database)</h3>";
                echo "<table style=\"border: 1px solid black;\">";
                echo "<tr><th style=\"border: 1px solid black;\">Loan ID</th><th style=\"border: 1px solid black;\">Type</th><th style=\"border: 1px solid black;\">Balance Owed</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Interest Rate</th><th style=\"border: 1px solid black;\">Branch ID</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td style=\"border: 1px solid black;\">{$row['loanNo']}</td><td style=\"border: 1px solid black;\">{$row['loan_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['l_status']}</td><td style=\"border: 1px solid black;\">{$row['interest_rate']}</td><td style=\"border: 1px solid black;\">{$row['Brch_ID']}</td></tr>";
                }
                echo "</table>";
                echo "<a href=\"loanSearch.php\">Back to View Loans Portal</a>";

            }
            else if (isset($_POST['staff_loans'])) {
                if (!empty($_POST['selected_email'])) {
                        //create query with appropriate values
                        $sql = "select s.First_name,s.Last_name,l.loanNo,l.loan_type,l.balance,l.l_status,l.interest_rate from Staff_Borrows as sb join Staff as s on sb.Staff_ID = s.staffNo join loan as l on sb.Loan_ID = l.loanNo where s.email = \"{$_POST['selected_email']}\"";
                        $result = $conn->query($sql);
                        $conn->close();

                        echo"<h3>Loans For Specific Staff Member (values from database)</h3>";
                        echo "<table style=\"border: 1px solid black;\">";
                        echo "<tr><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Loan ID</th><th style=\"border: 1px solid black;\">Type</th><th style=\"border: 1px solid black;\">Balance Owed</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Interest Rate</th></tr>";
                        while($row = $result->fetch_assoc()) {
                            echo "<tr><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['loanNo']}</td><td style=\"border: 1px solid black;\">{$row['loan_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['l_status']}</td><td style=\"border: 1px solid black;\">{$row['interest_rate']}</td></tr>";
                        }
                        echo "</table>";
                        echo "<a href=\"loanSearch.php\">Back to Loan Search Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required staff email</h3> <a href=\"loanSearch.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['customer_loans'])) {
                if (!empty($_POST['selected_email'])) {
                    //create query with appropriate values
                    $sql = "select c.First_name,c.Last_name,l.loanNo,l.loan_type,l.balance,l.l_status,l.interest_rate from Customer_Borrows as cb join Customer as c on cb.Cust_ID = c.custNo join loan as l on cb.Loan_ID = l.loanNo where c.email = \"{$_POST['selected_email']}\"";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Loans For Specific Customer (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Loan ID</th><th style=\"border: 1px solid black;\">Type</th><th style=\"border: 1px solid black;\">Balance Owed</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Interest Rate</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['loanNo']}</td><td style=\"border: 1px solid black;\">{$row['loan_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['l_status']}</td><td style=\"border: 1px solid black;\">{$row['interest_rate']}</td></tr>";
                    }
                    echo "</table>";
                    echo "<a href=\"loanSearch.php\">Back to Loan Search Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"loanSearch.php\">Resubmit</a>";   
                }
            }
            else if (isset($_POST['selected_loan'])) {
                if (!empty($_POST['loan_number'])) {
                    $sql = "select * from Payments left join loan on Loan_ID = loanNo where loanNo = {$_POST['loan_number']}";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Payments on Specific Loan (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">Loan Payment ID</th><th style=\"border: 1px solid black;\">Date</th><th style=\"border: 1px solid black;\">Time</th><th style=\"border: 1px solid black;\">Amount</th><th style=\"border: 1px solid black;\">Attached Loan ID</th><th style=\"border: 1px solid black;\">Type</th><th style=\"border: 1px solid black;\">Balance</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Interest Rate</th><th style=\"border: 1px solid black;\">Branch ID</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['payNo']}</td><td style=\"border: 1px solid black;\">{$row['p_date']}</td><td style=\"border: 1px solid black;\">{$row['p_time']}</td><td style=\"border: 1px solid black;\">{$row['amount']}</td><td style=\"border: 1px solid black;\">{$row['loanNo']}</td><td style=\"border: 1px solid black;\">{$row['loan_type']}</td><td style=\"border: 1px solid black;\">{$row['balance']}</td><td style=\"border: 1px solid black;\">{$row['l_status']}</td><td style=\"border: 1px solid black;\">{$row['interest_rate']}</td><td style=\"border: 1px solid black;\">{$row['Brch_ID']}</td></tr>";
                    }
                    echo "</table>";
                    echo "<a href=\"loanSearch.php\">Back to Loan Search Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required loan number</h3> <a href=\"loanSearch.php\">Resubmit</a>"; 
                }
            }
        } else {
    ?>

        <h3>Loan Search Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">View All Loans</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link  css-tab" data-bs-toggle="tab">Select Staff Loan Information</a>
            </li>
            <li class="nav-items">
                <a href="#tab3" class="nav-link  css-tab" data-bs-toggle="tab">Select Customer Loan Information</a>
            </li>
            <li class="nav-items">
                <a href="#tab4" class="nav-link  css-tab" data-bs-toggle="tab">Search Loan Account</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
                <form action="/loanSearch.php" method="post" class="d-flex flex-column">
                    <input type="submit" value="View All Loans" name="view_all_loans" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab2">
                <form action="/loanSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Email For Search (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <input type="submit" value="View Specific Staff" name="staff_loans" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab3">
                <form action="/loanSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Email For Search (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <input type="submit" value="View Specific Customer" name="customer_loans" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab4">
                <form action="/loanSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Loan Number For Search (required):</label>
                    <input type="text" name="loan_number">
                    <hr>
                    <input type="submit" value="Search Loan Account" name="selected_loan" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>