<html>
    <body>
        <?php
            session_start();
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $_SESSION["email"] = $_POST['email'];
                $_SESSION["pass"] = $_POST['password'];
                $pass=crypt($_SESSION["pass"],'$1$somethin$');

                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "banking_system";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
                }
                if ($_POST["submit"] === "Customer") {
                    if ($_POST["email"] === "" or $_POST["password"] === "") {
                        $conn->close();
                        session_unset(); 
                        session_destroy(); 
                        header("Location: frontPage.php");
                    } else {
                        $sql = "select c_password from customer where email ='" . $_SESSION["email"] . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        if (crypt($row["c_password"],'$1$somethin$') === $pass) {
                            $conn->close();
                            header("Location: customer.php");
                        } else {
                            $conn->close();
                            session_unset(); 
                            session_destroy(); 
                            header("Location: frontPage.php");
                        }
                    }
                } else if ($_POST["submit"] === "Staff") {
                    if ($_POST["email"] === "" or $_POST["password"] === "") {
                        $conn->close();
                        session_unset(); 
                        session_destroy(); 
                        header("Location: frontPage.php");
                    } else {
                        $sql = "select s_password from staff where email ='" . $_SESSION["email"] . "'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        if (crypt($row["s_password"],'$1$somethin$') === $pass) {
                            $conn->close();
                            header("Location: staff.php");
                        } else {
                            $conn->close();
                            session_unset(); 
                            session_destroy(); 
                            header("Location: frontPage.php");
                        }
                    }
                }
            } else {
                session_unset(); 
                session_destroy(); 
        ?>
            <form action="/frontPage.php" method="post">
                E-mail: <input type="text" name="email"><br>
                password: <input type="password" name="password"><br>
                <input type="submit" name="submit" value="Customer">
                <input type="submit" name="submit" value="Staff">
            </form>
        <?php 
            }
        ?>
    </body>
</html>