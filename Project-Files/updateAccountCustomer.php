<html>
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
                if ($_POST["submit"] === "Update E-mail") {
                    if ($_POST["update"] === "") {
                        $conn->close();
                        header("Location: updateAccountCustomer.php");
                    } else {
                        $sql = "update customer set email = '" . $_POST["update"] . "' where email = '" . $_SESSION['email'] . "'";
                        $conn->query($sql);
                        echo " E-mail updated!";
                        $conn->close();
                    }
                } else if ($_POST["submit"] === "Update Phone") {
                    if ($_POST["update"] === "") {
                        $conn->close();
                        header("Location: updateAccountCustomer.php");
                    } else {
                        $sql = "update customer set phone = '" . $_POST["update"] . "' where email = '" . $_SESSION['email'] . "'";
                        $conn->query($sql);
                        echo " Phone number updated!";
                        $conn->close();
                    }
                } else if ($_POST["submit"] === "Update Address") {
                    if ($_POST["update"] === "") {
                        $conn->close();
                        header("Location: updateAccountCustomer.php");
                    } else {
                        $sql = "update customer set street = '" . $_POST["update"] . "' where email = '" . $_SESSION['email'] . "'";
                        $conn->query($sql);
                        echo " Address updated!";
                        $conn->close();
                    }
                }
            } else {
        ?>
            <form action="/updateAccountCustomer.php" method="post">
                Enter New Information: <input type="text" name="update"><br>
                <input type="submit" name="submit" value="Update E-mail">
                <input type="submit" name="submit" value="Update Phone">
                <input type="submit" name="submit" value="Update Address">
            </form>
        <?php 
            }
            echo "<br><a href=\"customer.php\">Go Back</a>";
        ?>
    </body>
</html>