<?php
require_once "settings.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["update_status"])) {
    $eoi_number = $_POST["eoi_number"];
    $status = $_POST["status"];

    $query = "UPDATE eoi SET Status = ? WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $eoi_number);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }

  if (isset($_POST["delete_eoi"])) {
    $eoi_number = $_POST["eoi_number"];

    $query = "DELETE FROM eoi WHERE EOInumber = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $eoi_number);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
  }
}

$query = "SELECT * FROM eoi ORDER BY DateSubmitted DESC";
$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Manage Applications</title>
    <link rel="stylesheet" href="styles.css">
  </head>

  <body>
    <header>
      <nav>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="jobs.php">Jobs</a></li>
          <li><a href="apply.php">Apply</a></li>
          <li><a href="about.php">About</a></li>
          <li><a href="manage.php">Manage</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <h1>Manage Job Applications</h1>

      <table>
        <thead>
          <tr>
            <th>EOI Number</th>
            <th>Job Ref</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Skills</th>
            <th>Status</th>
            <th>Update</th>
            <th>Delete</th>
          </tr>
        </thead>

        <tbody>
          <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row["EOInumber"]); ?></td>
              <td><?php echo htmlspecialchars($row["JobReferenceNumber"]); ?></td>
              <td>
                <?php echo htmlspecialchars($row["FirstName"] . " " . $row["LastName"]); ?>
              </td>
              <td><?php echo htmlspecialchars($row["EmailAddress"]); ?></td>
              <td><?php echo htmlspecialchars($row["PhoneNumber"]); ?></td>
              <td><?php echo htmlspecialchars($row["Skills"]); ?></td>

              <td>
                <form method="post" action="manage.php">
                  <input type="hidden" name="eoi_number" value="<?php echo htmlspecialchars($row["EOInumber"]); ?>">

                  <select name="status">
                    <option value="New" <?php if ($row["Status"] === "New") echo "selected"; ?>>New</option>
                    <option value="Current" <?php if ($row["Status"] === "Current") echo "selected"; ?>>Current</option>
                    <option value="Final" <?php if ($row["Status"] === "Final") echo "selected"; ?>>Final</option>
                  </select>
              </td>

              <td>
                  <button type="submit" name="update_status">Update</button>
                </form>
              </td>

              <td>
                <form method="post" action="manage.php">
                  <input type="hidden" name="eoi_number" value="<?php echo htmlspecialchars($row["EOInumber"]); ?>">
                  <button type="submit" name="delete_eoi">Delete</button>
                </form>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </main>

    <footer>
      <p>&copy; 2026 G06 Creative Digital Media Agency</p>
    </footer>
  </body>
</html>

<?php
mysqli_close($conn);
?>