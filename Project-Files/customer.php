<html>
  <body>
    <?php
      session_start();
      if($_SERVER['REQUEST_METHOD'] === 'POST') { 
        header("Location: " . $_POST["page"]);
      }
    ?> 
    <form action="/customer.php" method="post">
      <label for="page"> What would you like to do? </label>
      <select name="page" id="page">
        <option value="transactionCustomer.php"> Make a transaction </option>';
        <option value="viewCustomer.php"> View transactions </option>';
        <option value="message.php"> Leave a message </option>';
        <option value="updateAccountCustomer.php"> Update account information </option>';
      </select>
      <input type="submit" value="Submit">
      <?php
        echo "<br><a href=\"frontPage.php\">Log out</a>";
      ?>
    </form> 
  </body>
</html>