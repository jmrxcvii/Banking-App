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

            if (isset($_POST['view_all_Staff'])) {
                //create query with appropriate values
                $sql = "select * from Staff";
                $result = $conn->query($sql);
                $conn->close();

                echo"<h3>All Staff Table (values from database)</h3>";
                echo "<table style=\"border: 1px solid black;\">";
                echo "<tr><th style=\"border: 1px solid black;\">Staff ID</th><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Street</th><th style=\"border: 1px solid black;\">City</th><th style=\"border: 1px solid black;\">Zip Code</th><th style=\"border: 1px solid black;\">State</th><th style=\"border: 1px solid black;\">Date of Birth</th><th style=\"border: 1px solid black;\">Phone Number</th><th style=\"border: 1px solid black;\">Email</th><th style=\"border: 1px solid black;\">Branch</th><th style=\"border: 1px solid black;\">Positon</th><th style=\"border: 1px solid black;\">Salary</th><th style=\"border: 1px solid black;\">Password</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td style=\"border: 1px solid black;\">{$row['staffNo']}</td><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['street']}</td><td style=\"border: 1px solid black;\">{$row['city']}</td><td style=\"border: 1px solid black;\">{$row['zip']}</td><td style=\"border: 1px solid black;\">{$row['s_state']}</td><td style=\"border: 1px solid black;\">{$row['date_of_birth']}</td><td style=\"border: 1px solid black;\">{$row['phone']}</td><td style=\"border: 1px solid black;\">{$row['email']}</td><td style=\"border: 1px solid black;\">{$row['branch']}</td><td style=\"border: 1px solid black;\">{$row['position']}</td><td style=\"border: 1px solid black;\">{$row['salary']}</td><td style=\"border: 1px solid black;\">{$row['s_password']}</td></tr>";
                }
                echo "</table>";
                echo "<a href=\"staffSearch.php\">Back to Staff Search Portal</a>";

            }
            else if (isset($_POST['specific_staff'])) {
                if (!empty($_POST['selected_email'])) {
                    //create query with appropriate values
                    $sql = "select * from Staff where email = \"{$_POST['selected_email']}\"";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Specific Staff Table (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">Staff ID</th><th style=\"border: 1px solid black;\">First Name</th><th style=\"border: 1px solid black;\">Last Name</th><th style=\"border: 1px solid black;\">Street</th><th style=\"border: 1px solid black;\">City</th><th style=\"border: 1px solid black;\">Zip Code</th><th style=\"border: 1px solid black;\">State</th><th style=\"border: 1px solid black;\">Date of Birth</th><th style=\"border: 1px solid black;\">Phone Number</th><th style=\"border: 1px solid black;\">Email</th><th style=\"border: 1px solid black;\">Branch</th><th style=\"border: 1px solid black;\">Positon</th><th style=\"border: 1px solid black;\">Salary</th><th style=\"border: 1px solid black;\">Password</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['staffNo']}</td><td style=\"border: 1px solid black;\">{$row['First_name']}</td><td style=\"border: 1px solid black;\">{$row['Last_name']}</td><td style=\"border: 1px solid black;\">{$row['street']}</td><td style=\"border: 1px solid black;\">{$row['city']}</td><td style=\"border: 1px solid black;\">{$row['zip']}</td><td style=\"border: 1px solid black;\">{$row['s_state']}</td><td style=\"border: 1px solid black;\">{$row['date_of_birth']}</td><td style=\"border: 1px solid black;\">{$row['phone']}</td><td style=\"border: 1px solid black;\">{$row['email']}</td><td style=\"border: 1px solid black;\">{$row['branch']}</td><td style=\"border: 1px solid black;\">{$row['position']}</td><td style=\"border: 1px solid black;\">{$row['salary']}</td><td style=\"border: 1px solid black;\">{$row['s_password']}</td></tr>";
                    }
                    echo "</table>";
                    echo "<a href=\"staffSearch.php\">Back to Staff Search Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required staff email</h3> <a href=\"staffSearch.php\">Resubmit</a>";
                }
            }
        } else {
    ?>

        <h3>Staff Search Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">View All Staff</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link  css-tab" data-bs-toggle="tab">Select Specific Staff</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
                <form action="/staffSearch.php" method="post" class="d-flex flex-column">
                    <input type="submit" value="View All Staff" name="view_all_Staff" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab2">
                <form action="/staffSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Email For Search (required):</label>
                    <input type="text" name="selected_email">
                    <hr>
                    <input type="submit" value="View Specific Staff" name="specific_staff" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>