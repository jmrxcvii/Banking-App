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

            if (isset($_POST['add_staff'])) {
                //create query with appropriate values
                $sql = "insert into staff (First_name, Last_name, street, city, zip, s_state, date_of_birth, phone, email, branch, position, salary, s_password) values ('" . $_POST['First_name'] . "','" . $_POST['Last_name'] . "','" . $_POST['street'] . "','" . $_POST['city'] . "'," . $_POST['zip']. ",'" . $_POST['s_state'] . "','" . $_POST['date_of_birth'] . "','" . $_POST['phone'] . "','" . $_POST['email'] . "'," . $_POST['branch'] . ",'" . $_POST['position'] . "'," . $_POST['salary'] . ",'" . $_POST['s_password'] . "')";
                $conn->query($sql);

                echo "<a href=\"manageStaff.php\">Back to Manage Portal</a>";


            }
            else if (isset($_POST['delete_staff'])) {
                if (!empty($_POST['staff_email'])) {
                    $sql = "delete from staff where email = '" . $_POST['staff_email'] . "'";
                    $conn->query($sql);
                    
                    
                    echo "<a href=\"manageStaff.php\">Back to Manage Portal</a>";
                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required staff email</h3> <a href=\"manageStaff.php\">Resubmit</a>";
                }
            }
            else if (isset($_POST['update_staff'])) {
                if (!empty($_POST['staff_email'])) { //checks whether an email was entered for the required staff email field
                    $staff_email = $_POST['staff_email'];
                    $post_keys_array = array_keys($_POST);
                    $post_names_array = array_values($_POST);
                    $combined_array = array_combine($post_keys_array,$post_names_array);
                    //the unset methods use 'splice' the array to remove the required staff email and submit button from the associative array
                    unset($combined_array["staff_email"]);
                    unset($combined_array["update_staff"]);

                    foreach ($combined_array as $key => $element) {
                        if (!empty($element)) { //if the input field wasn't left empty (theres a value to be updated)
                            switch ($key) {
                                //cases needed for the specific sql formatting of integer values
                                case "zip":
                                    $sql = "update staff set $key = $element where email = \"$staff_email\"";
                                    $conn->query($sql);
                                    break;
                                case "branch":
                                    $sql = "update staff set $key = $element where email = \"$staff_email\"";
                                    $conn->query($sql);
                                    break;
                                case "salary":
                                    $sql = "update staff set $key = $element where email = \"$staff_email\"";
                                    $conn->query($sql);
                                    break;
                                default:
                                //default case for the sql formatting of string values
                                    $sql = "update staff set $key = \"$element\" where email = \"$staff_email\"";
                                    $conn->query($sql);
                            }
                        }
                    }

                    
                    echo "<a href=\"manageStaff.php\">Back to Manage Portal</a>";

                }
                else {
                    $conn->close();
                    echo "<h3>Try Again: you did not enter the required staff email</h3> <a href=\"manageStaff.php\">Resubmit</a>";
                }
            }

        } else {
    ?>

        <h3>Staff Management Page</h3>
        <ul class="nav nav-tabs mt-3">
            <li class="nav-items">
                <a href="#tab1" class="nav-link  css-tab active" data-bs-toggle="tab">Add Staff Member</a>
            </li>
            <li class="nav-items">
                <a href="#tab2" class="nav-link css-tab" data-bs-toggle="tab">Delete Staff Member</a>
            </li>
            <li class="nav-items">
                <a href="#tab3" class="nav-link css-tab" data-bs-toggle="tab">Update Staff Member</a>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane container active mt-3" id="tab1">
            <form action="/manageStaff.php" method="post" class="d-flex flex-column">
                <label>First Name:</label>
                <input type="text" name="First_name">
                <label>Last Name:</label>
                <input type="text" name="Last_name">
                <label>Street:</label>
                <input type="text" name="street">
                <label>City:</label>
                <input type="text" name="city">
                <label>State:</label>
                <input type="text" name="s_state">
                <label>Zip Code:</label>
                <input type="text" name="zip">
                <label>Date of Birth:</label>
                <input type="text" name="date_of_birth">
                <label>Phone:</label>
                <input type="text" name="phone">
                <label>Email:</label>
                <input type="text" name="email">
                <label>Branch:</label>
                <input type="text" name="branch">
                <label>Position:</label>
                <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                    <input type="radio" name="position" value="Manager" id="managerInpt">
                    <label for="managerInpt" class="me-3">Manager</label>
                    <input type="radio" name="position" value="Teller" id="tellerInpt">
                    <label for="tellerInpt">Teller</label>
                </div>
                <label>Salary:</label>
                <input type="text" name="salary">
                <label>Password:</label>
                <input type="text" name="s_password">
                <input type="submit" value="Add Staff Member" name="add_staff" class="mt-3">
            </form>
            </div>

            <div class="tab-pane container mt-3" id="tab2">
                <form action="/manageStaff.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Staff Email (required):</label>
                    <input type="text" name="staff_email">
                    <input type="submit" value="Delete Staff Member" name="delete_staff" class="mt-3">
                </form>
            </div>

            <div class="tab-pane container mt-3" id="tab3">
                <h4>Select all fields you wish to update (empty fields will be ignored)</h4>
                <form action="/manageStaff.php" method="post" class="d-flex flex-column">
                    <label class="fw-bold text-danger">Enter Staff Email (required):</label>
                    <input type="text" name="staff_email">
                    <hr>
                    <label>First Name:</label>
                    <input type="text" name="First_name">
                    <label>Last Name:</label>
                    <input type="text" name="Last_name">
                    <label>Street:</label>
                    <input type="text" name="street">
                    <label>City:</label>
                    <input type="text" name="city">
                    <label>State:</label>
                    <input type="text" name="s_state">
                    <label>Zip Code:</label>
                    <input type="text" name="zip">
                    <label>Date of Birth:</label>
                    <input type="text" name="date_of_birth">
                    <label>Phone:</label>
                    <input type="text" name="phone">
                    <label>Email:</label>
                    <input type="text" name="email">
                    <label>Branch:</label>
                    <input type="text" name="branch">
                    <label>Position:</label>
                    <div style="border: 1px solid darkslategrey; border-radius: 2px; padding: 2px;">
                        <input type="radio" name="position" value="Manager" id="managerInpt">
                        <label for="managerInpt" class="me-3">Manager</label>
                        <input type="radio" name="position" value="Teller" id="tellerInpt">
                        <label for="tellerInpt">Teller</label>
                    </div>
                    <label>Salary:</label>
                    <input type="text" name="salary">
                    <label>Password:</label>
                    <input type="text" name="s_password">
                    <input type="submit" value="Update Staff Member" name="update_staff" class="mt-3">
                </form>
            </div>
        </div>

    <?php
        }
        echo "<br><a href=\"staff.php\">Staff Portal</a>";
    ?>
</body>
</html>