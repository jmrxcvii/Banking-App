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

            if (isset($_POST['add_loan'])) {
                if (!empty($_POST['selected_email'])) {
                    //create loan query with appropriate values
                    $loan_Brch_ID = 1;
                    $sql = "insert into loan (loan_type, balance, l_status, interest_rate, Brch_ID) values ('" . $_POST['loan_type'] . "'," . $_POST['balance'] . ", 'open' ," . $_POST['interest_rate'] . "," . "{$loan_Brch_ID}" . ")";
                    $conn->query($sql);

                    //return last created loan to be linked to an account
                    $last_loan = "select * from loan order by loanNo desc limit 1";
                    $loan_result = $conn->query($last_loan);
                    $needed_Loan = $loan_result->fetch_assoc();
                    $loan_ID = $needed_Loan['loanNo'];

                    //link the created loan to either the selected customer or staff
                    $account_type = $_POST['table_type'];
                    if ($account_type === "Staff") {
                            $retrieve_account = "select staffNo from Staff where email = \"{$_POST['selected_email']}\"";
                            $result = $conn->query($retrieve_account);
                            $needed_Account = $result->fetch_assoc();
                            $account_ID = $needed_Account['staffNo'];

                            $link_sql = "insert into Staff_Borrows values ($loan_ID,$account_ID)";
                            $conn->query($link_sql);

                    }
                    else if ($account_type === "Customer") {
                            $retrieve_account = "select custNo from Customer where email = \"{$_POST['selected_email']}\"";
                            $result = $conn->query($retrieve_account);
                            $needed_Account = $result->fetch_assoc();
                            $account_ID = $needed_Account['custNo'];

                            $link_sql = "insert into Customer_Borrows values ($loan_ID,$account_ID)";
                            $conn->query($link_sql);

                    }
                    
                    echo "<a href=\"manageLoans.php\">Back to Loan Management Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"manageLoans.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['delete_loan'])) {
                if (!empty($_POST['loan_ID'])) {
                    //check which borrow table the loan is located in
                    $Staff_Loan = $conn->query("select Loan_ID from Staff_Borrows where exists(select * from Staff_Borrows where Loan_ID = {$_POST['loan_ID']})");
                    $Customer_Loan = $conn->query("select Loan_ID from Customer_Borrows where exists(select * from Customer_Borrows where Loan_ID = {$_POST['loan_ID']})");

                    if ($Staff_Loan->fetch_assoc()) { //true if in staff loan borrow table
                        //delete information from Staff Borrows Table
                        $conn->query("delete from Staff_Borrows where Loan_ID = {$_POST['loan_ID']}");
                        //delete information from loan table
                        $conn->query("delete from loan where loanNo = {$_POST['loan_ID']}");
                        $conn->close();
                    }
                    else if ($Customer_Loan->fetch_assoc()) {
                        //delete information from Customer Borrows Table
                        $conn->query("delete from Customer_Borrows where Loan_ID = {$_POST['loan_ID']}");
                        //delete information from loan table
                        $conn->query("delete from loan where loanNo = {$_POST['loan_ID']}");
                        $conn->close();
                    }

                    echo "<h3>Success: you deleted the Loan</h3> <a href=\"manageLoans.php\">Back to Loan Management Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required loan ID</h3> <a href=\"manageLoans.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['update_loan'])) {
                if (!empty($_POST['loan_ID'])) { //checks whether an ID was entered for the required staff email field
                    $loan_ID = $_POST['loan_ID'];
                    $post_keys_array = array_keys($_POST);
                    $post_names_array = array_values($_POST);
                    $combined_array = array_combine($post_keys_array,$post_names_array);
                    //the unset methods use 'splice' the array to remove the required staff email and submit button from the associative array
                    unset($combined_array["loan_ID"]);
                    unset($combined_array["update_loan"]);

                    foreach ($combined_array as $key => $element) {
                        if (!empty($element)) { //if the input field wasn't left empty (theres a value to be updated)
                            switch ($key) {
                                //cases needed for the specific sql formatting of integer values
                                case "amount_paid":
                                    //create payment and store it in the payment table
                                    $pay_date = date("Y-m-d");
                                    $pay_time = date("h:i:sa");
                                    $conn->query("insert into payments (p_date,p_time,amount,Loan_ID) values(\"{$pay_date}\",\"{$pay_time}\",$element,$loan_ID)");
                                    //deduct pay amount from the specific loan
                                    $conn->query("update loan set balance = balance - $element where loanNo = $loan_ID");
                                    break;
                                case "interest_rate":
                                    $conn->query("update loan set $key = $element where loanNo = $loan_ID");
                                    break;
                                default:
                                    //default case for the sql formatting of string values
                                    $conn->query("update loan set $key = \"$element\" where loanNo = $loan_ID");
                            }
                        }
                    }

                    echo "<h3>Success: you updated the Loan (view in 'View Loans' portal)</h3><a href=\"manageLoans.php\">Back to Loan Management Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required staff email</h3> <a href=\"manageLoans.php\">Resubmit</a>";
                }
            }

        } else {
    ?>

        <h3>Loan Management Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">Add Loan Account</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link css-tab" data-bs-toggle="tab">Delete Loan Account</a>
            </li>
            <li class="nav-items">
                <a href="#tab3" class="nav-link css-tab" data-bs-toggle="tab">Update Loan Account</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
            <form action="/manageLoans.php" method="post" class="d-flex flex-column">
                <label class="fw-bold text-danger">Enter Email For Created Loan (required):</label>
                <input type="text" name="selected_email">
                <label>Select Account Type (Staff or Customer):</label>
                <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                    <input type="radio" name="table_type" value="Staff" id="staffInpt">
                    <label for="staffInpt" class="me-3">Staff</label>
                    <input type="radio" name="table_type" value="Customer" id="customerInpt">
                    <label for="customerInpt">Customer</label>
                </div>
                <hr>
                <label>loan_type:</label>
                <input type="text" name="loan_type">
                <label>Balance:</label>
                <input type="text" name="balance">
                <label>Interest Rate:</label>
                <input type="text" name="interest_rate">
                <input type="submit" value="Add Loan Account" name="add_loan" class="mt-3">
            </form>
            </div>

            <div class="tab-pane container mt-3" id="tab2">
                <form action="/manageLoans.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Loan ID (required):</label>
                    <input type="text" name="loan_ID">
                    <input type="submit" value="Delete Loan" name="delete_loan" class="mt-3">
                </form>
            </div>

            <div class="tab-pane container mt-3" id="tab3">
                <h4>Select all fields you wish to update (empty fields will be ignored)</h4>
                <form action="/manageLoans.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Loan ID (required):</label>
                    <input type="text" name="loan_ID">
                    <hr>
                    <label>loan_type:</label>
                    <input type="text" name="loan_type">
                    <label class="fw-bold text-primary">Amount Paid (to be deducted from standing loan balance):</label>
                    <input type="text" name="amount_paid">
                    <label>Loan Status:</label>
                    <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                        <input type="radio" name="l_status" value="open" id="openInpt">
                        <label for="openInpt" class="me-3">Open</label>
                        <input type="radio" name="l_status" value="closed" id="closedInpt">
                        <label for="closedInpt">Closed</label>
                    </div>
                    <label>Interest Rate:</label>
                    <input type="text" name="interest_rate">
                    <input type="submit" value="Update Loan" name="update_loan" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>