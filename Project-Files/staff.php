<html>
  <body>
    <?php
      session_start();
      if($_SERVER['REQUEST_METHOD'] === 'POST') { 
        header("Location: " . $_POST["page"]);
      }
    ?> 
    <form action="/staff.php" method="post">
      <label for="page"> What would you like to do? </label>
      <select name="page" id="page">
        <option value="cash.php"> View bank cash total </option>';
        <option value="customerMessagePortal.php">View Customer(s) Message Portal</option>
        <option value="reportCustomer.php"> See customer report </option>';
        <option value="viewTransactions.php"> View transactions </option>';
        <option value="loanSearch.php"> Search Loans </option>';
        <option value="staffSearch.php">Search Staff Information</option>
        <option value="branchSearch.php">Search Branch</option>
        <option value="staffAccountSearch.php">Search Staff Accounts</option>
        <option value="customerSearch.php">Search Customer(s) Accounts</option>
        <option value="updateCustomer.php"> Update customer information </option>';
        <option value="updateBranch.php"> Update branch information </option>';
        <option value="authenticateStaff.php">Manage Staff</option>
        <option value="manageLoans.php">Manage Loans / Perform Loan Transactions</option>
        <option value="manageBankAccounts.php">Manage Bank Accounts (Staff and Customer)</option>
        <option value="accountTransactions.php">Perform Account Transactions</option>
      </select>
      <input type="submit" value="Submit">
      <?php
        echo "<br><a href=\"frontPage.php\">Log out</a>";
      ?>
    </form> 
  </body>
</html>