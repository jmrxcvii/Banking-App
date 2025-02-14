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
                if ($_POST["submit"] === "Update Cash Total") {
                    if ($_POST["update"] === "") {
                        $conn->close();
                        header("Location: updateBranch.php");
                    } else {
                        $sql = "update branch set cash_held = " . $_POST["update"] . " where brchNo = (select branch from staff where email = '" . $_SESSION['email'] . "')";
                        $conn->query($sql);
                        echo " Cash total updated!";
                        $conn->close();
                    }
                } else if ($_POST["submit"] === "Update Number of Employees") {
                    if ($_POST["update"] === "") {
                        $conn->close();
                        header("Location: updateBranch.php");
                    } else {
                        $sql = "update branch set num_employees = " . $_POST["update"] . " where brchNo = (select branch from staff where email = '" . $_SESSION['email'] . "')";
                        $conn->query($sql);
                        echo " Number of employees updated!";
                        $conn->close();
                    }
                }
            } else {
        ?>
            <form action="/updateBranch.php" method="post">
                Enter New Information: <input type="text" name="update"><br>
                <input type="submit" name="submit" value="Update Cash Total">
                <input type="submit" name="submit" value="Update Number of Employees">
            </form>
        <?php 
            }
            echo "<br><a href=\"staff.php\">Go Back</a>";
        ?>
    </body>
</html>