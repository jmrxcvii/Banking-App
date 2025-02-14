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
                if ($_POST["message"] === "") {
                    $conn->close();
                    header("Location: message.php");
                } else {
                    $sql = "select custNo from customer where email = '" . $_SESSION['email'] . "'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $sql = "insert into feedback (f_message, f_date, Cust_ID) values ('" . $_POST["message"] . "','" . date("Y-m-d") . "','" . $row["custNo"] . "')";
                    $conn->query($sql);
                    echo " Message sent!";
                    $conn->close();
                }
            } else {
        ?>
        <form action="/message.php" method="post">
            Message: <input type="text" name="message"><br>
            <input type="submit" value="Submit">
        </form>
        <?php
            }
            echo "<br><a href=\"customer.php\">Go Back</a>";
        ?>
    </body>
</html?