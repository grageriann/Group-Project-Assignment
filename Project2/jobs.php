<?php
require_once "settings.php";
require_once "header.inc";

$search = $_GET["search"] ?? "";

if ($search !== "") {
  $query = "SELECT * FROM jobs 
            WHERE title LIKE ? 
            OR description LIKE ? 
            OR reference_number LIKE ?";

  $stmt = mysqli_prepare($conn, $query);
  $search_term = "%" . $search . "%";
  mysqli_stmt_bind_param($stmt, "sss", $search_term, $search_term, $search_term);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
} else {
  $query = "SELECT * FROM jobs ORDER BY id";
  $result = mysqli_query($conn, $query);
}

if (!$result) {
  die("Query failed: " . mysqli_error($conn));
}
?>


    <main class="page-container jobs-container">
      <h1 style="text-align: center;">Current Career Opportunities</h1>

      <p style="text-align: center;">
        Join G06 Creative Digital Media Agency and help create engaging digital
        experiences for a wide range of clients.
      </p>

      <form method="get" action="jobs.php" class="search-form">
        <label for="search">Search jobs:</label>
        <input
          type="text"
          id="search"
          name="search"
          value="<?php echo htmlspecialchars($search); ?>"
        >
        <button type="submit">Search</button>
      </form>

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

      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($job = mysqli_fetch_assoc($result)): ?>
          <section class="job-card">
            <h2><?php echo htmlspecialchars($job["title"]); ?></h2>

            <p class="job-meta">
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
      <?php else: ?>
        <p>No jobs matched your search.</p>
      <?php endif; ?>
    </main>

<?php 
include_once("footer.inc"); 
?>

<?php
mysqli_close($conn);
?>