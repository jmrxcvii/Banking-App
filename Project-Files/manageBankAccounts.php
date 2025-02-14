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

            if (isset($_POST['add_account'])) {
                if (!empty($_POST['selected_email'])) {
                    //create loan query with appropriate values
                    $Branch_ID = 1;
                    $sql = "insert into Accounts (acc_type, balance, a_status, Brch_ID) values ('" . $_POST['acc_type'] . "'," . $_POST['balance'] . ", 'open' ," . $Branch_ID . ")";
                    $conn->query($sql);

                    //return last created account to be linked to an person's account
                    $last_acc = "select * from Accounts order by accNo desc limit 1";
                    $acc_result = $conn->query($last_acc);
                    $needed_acc = $acc_result->fetch_assoc();
                    $acc_ID = $needed_acc['accNo'];

                    //link the created account to either the selected customer or staff
                    $account_type = $_POST['table_type'];
                    if ($account_type === "Staff") {
                            $retrieve_account = "select staffNo from Staff where email = \"{$_POST['selected_email']}\"";
                            $result = $conn->query($retrieve_account);
                            $needed_Account = $result->fetch_assoc();
                            $account_ID = $needed_Account['staffNo'];

                            $link_sql = "insert into Staff_Holds values ($acc_ID,$account_ID)";
                            $conn->query($link_sql);

                    }
                    else if ($account_type === "Customer") {
                            $retrieve_account = "select custNo from Customer where email = \"{$_POST['selected_email']}\"";
                            $result = $conn->query($retrieve_account);
                            $needed_Account = $result->fetch_assoc();
                            $account_ID = $needed_Account['custNo'];

                            $link_sql = "insert into Customer_Holds values ($acc_ID,$account_ID)";
                            $conn->query($link_sql);

                    }
                    echo "<h3>Success: Account Created!</h3>";
                    echo "<a href=\"manageBankAccounts.php\">Back to Bank Account Management Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"manageBankAccounts.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['delete_account'])) {
                if (!empty($_POST['acc_ID'])) {
                    //check which borrow table the account is located in
                    $Staff_acc = $conn->query("select Acc_ID from Staff_Holds where exists(select * from Staff_Holds where Acc_ID = {$_POST['acc_ID']})");
                    $Customer_acc = $conn->query("select Acc_ID from Customer_Holds where exists(select * from Customer_Holds where Acc_ID = {$_POST['acc_ID']})");

                    if ($Staff_acc->fetch_assoc()) { //true if in staff holds table
                        //delete information from Staff Holds Table
                        $conn->query("delete from Staff_Holds where Acc_ID = {$_POST['acc_ID']}");
                        //delete information from Accounts table
                        $conn->query("delete from Accounts where accNo = {$_POST['acc_ID']}");
                        $conn->close();
                    }
                    else if ($Customer_acc->fetch_assoc()) {
                        //delete information from Customer Holds Table
                        $conn->query("delete from Customer_Holds where Acc_ID = {$_POST['acc_ID']}");
                        //delete information from Accounts table
                        $conn->query("delete from Accounts where accNo = {$_POST['acc_ID']}");
                        $conn->close();
                    }

                    echo "<h3>Success: you deleted the Bank Account</h3> <a href=\"manageBankAccounts.php\">Back to Bank Account Management Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required loan ID</h3> <a href=\"manageBankAccounts.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['update_account'])) {
                if (!empty($_POST['acc_ID'])) { //checks whether an ID was entered for the required staff email field
                    $acc_ID = $_POST['acc_ID'];
                    $post_keys_array = array_keys($_POST);
                    $post_names_array = array_values($_POST);
                    $combined_array = array_combine($post_keys_array,$post_names_array);
                    //the unset methods use 'splice' the array to remove the required staff email and submit button from the associative array
                    unset($combined_array["acc_ID"]);
                    unset($combined_array["update_account"]);

                    foreach ($combined_array as $key => $element) {
                        if (!empty($element)) { //if the input field wasn't left empty (theres a value to be updated)
                            switch ($key) {
                                //cases needed for the specific sql formatting of integer values
                                case "balance":
                                    $conn->query("update Accounts set $key = $element where accNo = $acc_ID");

                                     //create transaction history - so we know of manual adjustment
                                    $t_date = date("Y-m-d");
                                    $t_time = date("h:i:sa");
                                    $conn->query("insert into Transactions (trans_type,t_date,t_time,amount,Acc_ID) values (\"STAFF SET BALANCE\",\"{$t_date}\",\"{$t_time}\",{$element},{$acc_ID})");

                                    break;
                                default:
                                    //default case for the sql formatting of string values
                                    $conn->query("update Accounts set $key = \"$element\" where accNo = $acc_ID");
                            }
                        }
                    }

                    echo "<h3>Success: you updated the Account!</h3><a href=\"manageBankAccounts.php\">Back to Bank Account Management Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required staff email</h3> <a href=\"manageBankAccounts.php\">Resubmit</a>";
                }
            }

        } else {
    ?>

        <h3>Bank Account Management Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">Add Bank Account</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link css-tab" data-bs-toggle="tab">Delete Bank Account</a>
            </li>
            <li class="nav-items">
                <a href="#tab3" class="nav-link css-tab" data-bs-toggle="tab">Update Bank Account</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
            <form action="/manageBankAccounts.php" method="post" class="d-flex flex-column">
                <label class="fw-bold text-danger">Enter Email For Created Account (required):</label>
                <input type="text" name="selected_email">
                <label>Select Account Type (Staff or Customer):</label>
                <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                    <input type="radio" name="table_type" value="Staff" id="staffInpt">
                    <label for="staffInpt" class="me-3">Staff</label>
                    <input type="radio" name="table_type" value="Customer" id="customerInpt">
                    <label for="customerInpt">Customer</label>
                </div>
                <hr>
                <label>Account Type:</label>
                <input type="text" name="acc_type">
                <label>Balance:</label>
                <input type="text" name="balance">
                <input type="submit" value="Add Bank Account" name="add_account" class="mt-3">
            </form>
            </div>

            <div class="tab-pane container mt-3" id="tab2">
                <form action="/manageBankAccounts.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Account ID (required):</label>
                    <input type="text" name="acc_ID">
                    <input type="submit" value="Delete Bank Account" name="delete_account" class="mt-3">
                </form>
            </div>

            <div class="tab-pane container mt-3" id="tab3">
                <h4>Select all fields you wish to update (empty fields will be ignored)</h4>
                <form action="/manageBankAccounts.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Account ID (required):</label>
                    <input type="text" name="acc_ID">
                    <hr>
                    <label>Account Type:</label>
                    <input type="text" name="acc_type">
                    <label class="fw-bold text-primary">Set Balance:</label>
                    <input type="text" name="balance">
                    <label>Account Status:</label>
                    <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                        <input type="radio" name="a_status" value="open" id="openInpt">
                        <label for="openInpt" class="me-3">Open</label>
                        <input type="radio" name="a_status" value="closed" id="closedInpt">
                        <label for="closedInpt">Closed</label>
                    </div>
                    <input type="submit" value="Update Bank Account" name="update_account" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>