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

            if (isset($_POST['view_all_messages'])) {
                //create query with appropriate values
                $sql = "select Cust_ID, First_name, Last_name, feedNo, f_message, f_date from Feedback join Customer on Cust_ID = custNo";
                $result = $conn->query($sql);
                $conn->close();

                echo"<h3>All Messages Table (values from database)</h3>";
                echo "<table style=\"border: 1px solid black;\">";
                echo "<tr><th style=\"border: 1px solid black;\">Customer ID</th><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Message ID</th><th style=\"border: 1px solid black;\">Message</th><th style=\"border: 1px solid black;\">Date</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td style=\"border: 1px solid black;\">{$row['Cust_ID']}</td><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['feedNo']}</td><td style=\"border: 1px solid black;\">{$row['f_message']}</td><td style=\"border: 1px solid black;\">{$row['f_date']}</td></tr>";
                }
                echo "</table>";
                echo "<a href=\"customerMessagePortal.php\">Back to Customer Message Portal</a>";

            }
            else if (isset($_POST['specific_customer'])) {
                if (!empty($_POST['selected_email'])) {
                    //create query with appropriate values
                    $sql = "select Cust_ID, First_name, Last_name, feedNo, f_message, f_date from Feedback join Customer on Cust_ID = custNo where email = \"{$_POST['selected_email']}\"";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Specific Customer Messages (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">Customer ID</th><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Message ID</th><th style=\"border: 1px solid black;\">Message</th><th style=\"border: 1px solid black;\">Date</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['Cust_ID']}</td><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['feedNo']}</td><td style=\"border: 1px solid black;\">{$row['f_message']}</td><td style=\"border: 1px solid black;\">{$row['f_date']}</td></tr>";
                    }
                    echo "</table>";
                    echo "<a href=\"customerMessagePortal.php\">Back to Customer Message Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required customer email</h3> <a href=\"customerMessagePortal.php\">Resubmit</a>";
                }
            }
        } else {
    ?>

        <h3>Customer Message Portal</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">View All Customer(s) Messages</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link  css-tab" data-bs-toggle="tab">Search For Specific Customer Message(s)</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
                <form action="/customerMessagePortal.php" method="post" class="d-flex flex-column">
                    <input type="submit" value="View All Messages" name="view_all_messages" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab2">
                <form action="/customerMessagePortal.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Email For Search (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <input type="submit" value="View Specific Customer Message(s)" name="specific_customer" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>