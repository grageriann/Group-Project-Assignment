<?php
require_once "settings.php";

$query = "SELECT * FROM jobs ORDER BY id";
$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Jobs | G06 Creative Digital Media Agency</title>
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
        </ul>
      </nav>
    </header>

    <main class="jobs-container">
      <h1 style="text-align: center;">Current Career Opportunities</h1>

      <p style="text-align: center;">
        Join G06 Creative Digital Media Agency and help create engaging digital
        experiences for a wide range of clients.
      </p>

      <aside>
        <h2>Why Join G06?</h2>
        <p>
          We work on exciting client projects in web design, branding, and digital media.
          Team members contribute to real-world projects and grow their technical and
          creative skills in a collaborative environment.
        </p>

        <h3>Employee Benefits</h3>
        <ul>
          <li>Creative team culture</li>
          <li>Professional development support</li>
          <li>Flexible project collaboration</li>
          <li>Experience across multiple client industries</li>
        </ul>
      </aside>

      <?php while ($job = mysqli_fetch_assoc($result)): ?>
        <section class="job-card">
          <h2><?php echo htmlspecialchars($job["title"]); ?></h2>

          <p>
            Reference Number: <?php echo htmlspecialchars($job["reference_number"]); ?>
          </p>

          <p>
            <?php echo htmlspecialchars($job["description"]); ?>
          </p>

          <p>
            <strong>Salary:</strong> <?php echo htmlspecialchars($job["salary"]); ?>
          </p>

          <p>
            <strong>Reports to:</strong> <?php echo htmlspecialchars($job["reports_to"]); ?>
          </p>
        </section>
      <?php endwhile; ?>
    </main>

    <footer>
      <p>&copy; 2026 G06 Creative Digital Media Agency</p>
    </footer>
  </body>
</html>

<?php
mysqli_close($conn);
?>