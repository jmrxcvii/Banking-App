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

            if (isset($_POST['view_all_branches'])) {
                //create query with appropriate values
                $sql = "select * from Branch join Bank on B_ID = ID";
                $result = $conn->query($sql);
                $conn->close();

                echo"<h3>All Staff Table (values from database)</h3>";
                echo "<table style=\"border: 1px solid black;\">";
                echo "<tr><th style=\"border: 1px solid black;\">Branch ID</th><th style=\"border: 1px solid black;\">Street</th><th style=\"border: 1px solid black;\">City</th><th style=\"border: 1px solid black;\">Zip Code</th><th style=\"border: 1px solid black;\">State</th><th style=\"border: 1px solid black;\">Number of Employees</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Cash Held</th><th style=\"border: 1px solid black;\">Branch Name</th><th style=\"border: 1px solid black;\">Associated Bank ID</th><th style=\"border: 1px solid black;\">Associated Bank Name</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td style=\"border: 1px solid black;\">{$row['brchNo']}</td><td style=\"border: 1px solid black;\">{$row['street']}</td><td style=\"border: 1px solid black;\">{$row['city']}</td><td style=\"border: 1px solid black;\">{$row['zip']}</td><td style=\"border: 1px solid black;\">{$row['b_state']}</td><td style=\"border: 1px solid black;\">{$row['num_employees']}</td><td style=\"border: 1px solid black;\">{$row['b_status']}</td><td style=\"border: 1px solid black;\">{$row['cash_held']}</td><td style=\"border: 1px solid black;\">{$row['b_name']}</td><td style=\"border: 1px solid black;\">{$row['ID']}</td><td style=\"border: 1px solid black;\">{$row['bank_name']}</td></tr>";
                }
                echo "</table>";
                echo "<a href=\"branchSearch.php\">Back to Branch Search Portal</a>";

            }
            else if (isset($_POST['specific_branch'])) {
                if (!empty($_POST['selected_branch'])) {
                    //create query with appropriate values
                    $sql = "select * from Branch join Bank on B_ID = ID where b_name = \"{$_POST['selected_branch']}\"";
                    $result = $conn->query($sql);
                    $conn->close();

                    echo"<h3>Specific Branch Table (values from database)</h3>";
                    echo "<table style=\"border: 1px solid black;\">";
                    echo "<tr><th style=\"border: 1px solid black;\">Branch ID</th><th style=\"border: 1px solid black;\">Street</th><th style=\"border: 1px solid black;\">City</th><th style=\"border: 1px solid black;\">Zip Code</th><th style=\"border: 1px solid black;\">State</th><th style=\"border: 1px solid black;\">Number of Employees</th><th style=\"border: 1px solid black;\">Status</th><th style=\"border: 1px solid black;\">Cash Held</th><th style=\"border: 1px solid black;\">Branch Name</th><th style=\"border: 1px solid black;\">Associated Bank ID</th><th style=\"border: 1px solid black;\">Associated Bank Name</th></tr>";
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td style=\"border: 1px solid black;\">{$row['brchNo']}</td><td style=\"border: 1px solid black;\">{$row['street']}</td><td style=\"border: 1px solid black;\">{$row['city']}</td><td style=\"border: 1px solid black;\">{$row['zip']}</td><td style=\"border: 1px solid black;\">{$row['b_state']}</td><td style=\"border: 1px solid black;\">{$row['num_employees']}</td><td style=\"border: 1px solid black;\">{$row['b_status']}</td><td style=\"border: 1px solid black;\">{$row['cash_held']}</td><td style=\"border: 1px solid black;\">{$row['b_name']}</td><td style=\"border: 1px solid black;\">{$row['ID']}</td><td style=\"border: 1px solid black;\">{$row['bank_name']}</td></tr>";
                    }

                    echo "</table>";
                    echo "<a href=\"branchSearch.php\">Back to Branch Search Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required branch name</h3> <a href=\"branchSearch.php\">Resubmit</a>";
                }
            }
        } else {
    ?>

        <h3>Branch Search Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">View All Branches</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link  css-tab" data-bs-toggle="tab">Search Branch</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
                <form action="/branchSearch.php" method="post" class="d-flex flex-column">
                    <input type="submit" value="View All Branches" name="view_all_branches" class="mt-3">
                </form>
            </div>
            <div class="tab-pane container mt-3" id="tab2">
                <form action="/branchSearch.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Branch Name For Search (required):</label>
                    <input type="text" name="selected_branch">
                    <hr>
                    <input type="submit" value="View Specific Branch" name="specific_branch" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>