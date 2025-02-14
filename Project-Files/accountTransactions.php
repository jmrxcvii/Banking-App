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

            if(isset($_POST['staff_trans'])) {
                if (!empty($_POST['selected_email']) && !empty($_POST['selected_account']) && !empty($_POST['trans_amount'])) {
                    
                    //check to see if account actually exist in the Staff Holds Table
                    $pulled_row = $conn->query("select * from Staff_Holds where Acc_ID = {$_POST['selected_account']} and Staff_ID = (select staffNo from Staff where email = \"{$_POST['selected_email']}\")");
                    
                    if ($pulled_row->fetch_assoc()) {
                        //pull account to be used
                        $account = $conn->query("select * from accounts where accNo = {$_POST['selected_account']}");
                        $needed_balance = $account->fetch_assoc();
                        $account_balance = $needed_balance['balance'];
                        $balance_exist = $account_balance - $_POST['trans_amount']; //to check if we've exceeded the balance of the account for withdrawals

                        //collect staff ID to later be used (for whicever staff member is conducting the transaction - signed into session)
                        $staff_account = $conn->query("select staffNo from Staff where email = \"{$_SESSION["email"]}\"");
                        $needed_staff = $staff_account->fetch_assoc();
                        $staff_ID = $needed_staff['staffNo'];

                        //update account balance if possible (and create transaction history)
                        if (($_POST['trans_type'] === "withdrawal") && ($balance_exist >= 0)) {
                            $conn->query("update accounts set balance = {$balance_exist} where accNo = {$_POST['selected_account']}");

                             //create transaction history
                            $t_date = date("Y-m-d");
                            $t_time = date("h:i:sa");
                            $conn->query("insert into Transactions (trans_type,t_date,t_time,amount,Acc_ID) values (\"{$_POST['trans_type']}\",\"{$t_date}\",\"{$t_time}\",{$_POST['trans_amount']},{$_POST['selected_account']})");

                            //create transaction comment if needed
                            if(!empty($_POST['trans_message'])) {
                                //return last created loan to be linked to an account
                               $last_transaction = "select transNo from Transactions order by transNo desc limit 1";
                               $trans_result = $conn->query($last_transaction);
                               $needed_trans_ID = $trans_result->fetch_assoc();
                               $used_trans_ID = $needed_trans_ID['transNo'];
                               
                               //create comment
                               $c_date = date("Y-m-d");
                               $conn->query("insert into Comments (c_message,threat_flag,c_date,Trans_ID,Staff_ID) values (\"{$_POST['trans_message']}\",{$_POST['trans_level']},\"{$c_date}\",{$used_trans_ID},{$staff_ID})");
                           }

                           echo "<h3>Success!</h3> <a href=\"accountTransactions.php\">Go Back to Account Transaction Portal</a>"; 

                        }
                        else if ($_POST['trans_type'] === "deposit") {
                            $new_balance = $account_balance + $_POST['trans_amount'];
                            $conn->query("update accounts set balance = {$new_balance} where accNo = {$_POST['selected_account']}");

                             //create transaction history
                            $t_date = date("Y-m-d");
                            $t_time = date("h:i:sa");
                            $conn->query("insert into Transactions (trans_type,t_date,t_time,amount,Acc_ID) values (\"{$_POST['trans_type']}\",\"{$t_date}\",\"{$t_time}\",{$_POST['trans_amount']},{$_POST['selected_account']})");

                            //create transaction comment if needed
                            if(!empty($_POST['trans_message'])) {
                                //return last created loan to be linked to an account
                               $last_transaction = "select transNo from Transactions order by transNo desc limit 1";
                               $trans_result = $conn->query($last_transaction);
                               $needed_trans_ID = $trans_result->fetch_assoc();
                               $used_trans_ID = $needed_trans_ID['transNo'];
                               
                               //create comment
                               $c_date = date("Y-m-d");
                               $conn->query("insert into Comments (c_message,threat_flag,c_date,Trans_ID,Staff_ID) values (\"{$_POST['trans_message']}\",{$_POST['trans_level']},\"{$c_date}\",{$used_trans_ID},{$staff_ID})");
                           }

                           echo "<h3>Success!</h3> <a href=\"accountTransactions.php\">Go Back to Account Transaction Portal</a>"; 

                        }
                        else {
                            echo "<h3>ERROR: the entered transaction amount exceeds the account balance </h3> <a href=\"accountTransactions.php\">Resubmit</a>";
                        }

                    }
                    else {
                        echo "<h3>ERROR: the account does not exist (wrong email and/or account number selected)</h3> <a href=\"accountTransactions.php\">Resubmit</a>";
                    }
                }
            }
            else if (isset($_POST['customer_trans'])) {
                if (!empty($_POST['selected_email']) && !empty($_POST['selected_account']) && !empty($_POST['trans_amount'])) {
                    
                    //check to see if account actually exist in the Customer Holds Table
                    $pulled_row = $conn->query("select * from Customer_Holds where Acc_ID = {$_POST['selected_account']} and Cust_ID = (select custNo from Customer where email = \"{$_POST['selected_email']}\")");
                    
                    if ($pulled_row->fetch_assoc()) {
                        //pull account to be used
                        $account = $conn->query("select * from accounts where accNo = {$_POST['selected_account']}");
                        $needed_balance = $account->fetch_assoc();
                        $account_balance = $needed_balance['balance'];
                        $balance_exist = $account_balance - $_POST['trans_amount']; //to check if we've exceeded the balance of the account for withdrawals

                        //collect staff ID to later be used (for whicever staff member is conducting the transaction - signed into session)
                        $staff_account = $conn->query("select staffNo from Staff where email = \"{$_SESSION["email"]}\"");
                        $needed_staff = $staff_account->fetch_assoc();
                        $staff_ID = $needed_staff['staffNo'];

                        //update account balance if possible (and create transaction history)
                        if (($_POST['trans_type'] === "withdrawal") && ($balance_exist >= 0)) {
                            $conn->query("update accounts set balance = {$balance_exist} where accNo = {$_POST['selected_account']}");

                             //create transaction history
                            $t_date = date("Y-m-d");
                            $t_time = date("h:i:sa");
                            $conn->query("insert into Transactions (trans_type,t_date,t_time,amount,Acc_ID) values (\"{$_POST['trans_type']}\",\"{$t_date}\",\"{$t_time}\",{$_POST['trans_amount']},{$_POST['selected_account']})");

                            //create transaction comment if needed
                            if(!empty($_POST['trans_message'])) {
                                //return last created loan to be linked to an account
                               $last_transaction = "select transNo from Transactions order by transNo desc limit 1";
                               $trans_result = $conn->query($last_transaction);
                               $needed_trans_ID = $trans_result->fetch_assoc();
                               $used_trans_ID = $needed_trans_ID['transNo'];
                               
                               //create comment
                               $c_date = date("Y-m-d");
                               $conn->query("insert into Comments (c_message,threat_flag,c_date,Trans_ID,Staff_ID) values (\"{$_POST['trans_message']}\",{$_POST['trans_level']},\"{$c_date}\",{$used_trans_ID},{$staff_ID})");
                           }

                           echo "<h3>Success!</h3> <a href=\"accountTransactions.php\">Go Back to Account Transaction Portal</a>"; 

                        }
                        else if ($_POST['trans_type'] === "deposit") {
                            $new_balance = $account_balance + $_POST['trans_amount'];
                            $conn->query("update accounts set balance = {$new_balance} where accNo = {$_POST['selected_account']}");

                             //create transaction history
                            $t_date = date("Y-m-d");
                            $t_time = date("h:i:sa");
                            $conn->query("insert into Transactions (trans_type,t_date,t_time,amount,Acc_ID) values (\"{$_POST['trans_type']}\",\"{$t_date}\",\"{$t_time}\",{$_POST['trans_amount']},{$_POST['selected_account']})");

                            //create transaction comment if needed
                            if(!empty($_POST['trans_message'])) {
                                //return last created loan to be linked to an account
                               $last_transaction = "select transNo from Transactions order by transNo desc limit 1";
                               $trans_result = $conn->query($last_transaction);
                               $needed_trans_ID = $trans_result->fetch_assoc();
                               $used_trans_ID = $needed_trans_ID['transNo'];
                               
                               //create comment
                               $c_date = date("Y-m-d");
                               $conn->query("insert into Comments (c_message,threat_flag,c_date,Trans_ID,Staff_ID) values (\"{$_POST['trans_message']}\",{$_POST['trans_level']},\"{$c_date}\",{$used_trans_ID},{$staff_ID})");
                           }

                           echo "<h3>Success!</h3> <a href=\"accountTransactions.php\">Go Back to Account Transaction Portal</a>"; 

                        }
                        else {
                            echo "<h3>ERROR: the entered transaction amount exceeds the account balance </h3> <a href=\"accountTransactions.php\">Resubmit</a>";
                        }

                    }
                    else {
                        echo "<h3>ERROR: the account does not exist (wrong email and/or account number selected)</h3> <a href=\"accountTransactions.php\">Resubmit</a>";
                    }
                }
                else {
                    echo "<h3>Try Again: a required field was not entered</h3> <a href=\"accountTransactions.php\">Resubmit</a>";
                }
            }

           
        } else {
    ?>

        <h3>Account Transaction Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">Staff Accounts</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link  css-tab" data-bs-toggle="tab">Customer Accounts</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
                <form action="/accountTransactions.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Staff Email (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <label class="fw-bold text-danger">Enter Staff Account Number (required):</label>
                    <input type="text" name="selected_account">
                    <label class="fw-bold text-danger">Enter Amount (amount applied to account balance - required):</label>
                    <input type="text" name="trans_amount">
                    <label>Transaction Type:</label>
                    <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                        <input type="radio" name="trans_type" value="deposit" id="depositInpt">
                        <label for="managerInpt" class="me-3">Deposit</label>
                        <input type="radio" name="trans_type" value="withdrawal" id="withdrawalInpt">
                        <label for="withdrawalInpt">Withdrawal</label>
                    </div>
                    <hr>
                    <label>Transaction Comment (ignored if message left blank):</label>
                    <input type="text" name="trans_message">
                    <label>Transaction Security Level (min = 0 | max = 5):</label>
                    <input type="range" name="trans_level" min="0" max="5">
                    <input type="submit" value="Perfom Staff Transaction" name="staff_trans" class="mt-3">
                </form>
            </div>

            <div class="tab-pane container mt-3" id="tab2">
                <form action="/accountTransactions.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Customer Email (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <label class="fw-bold text-danger">Enter Customer Account Number (required):</label>
                    <input type="text" name="selected_account">
                    <label class="fw-bold text-danger">Enter Amount (amount applied to account balance - required):</label>
                    <input type="text" name="trans_amount">
                    <label>Transaction Type:</label>
                    <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                        <input type="radio" name="trans_type" value="deposit" id="depositInpt">
                        <label for="managerInpt" class="me-3">Deposit</label>
                        <input type="radio" name="trans_type" value="withdrawal" id="withdrawalInpt">
                        <label for="withdrawalInpt">Withdrawal</label>
                    </div>
                    <hr>
                    <label>Transaction Comment (ignored if message left blank):</label>
                    <input type="text" name="trans_message">
                    <label>Transaction Security Level (min = 0 | max = 5):</label>
                    <input type="range" name="trans_level" min="0" max="5">
                    <input type="submit" value="Perform Customer Transaction" name="customer_trans" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>