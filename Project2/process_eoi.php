<?php
require_once "settings.php";

function clean_input($data) {
  return trim($data);
}

$job_reference = clean_input($_POST["job_reference"] ?? "");
$first_name = clean_input($_POST["first_name"] ?? "");
$last_name = clean_input($_POST["last_name"] ?? "");
$dob = clean_input($_POST["dob"] ?? "");
$gender = clean_input($_POST["gender"] ?? "");
$street_address = clean_input($_POST["street_address"] ?? "");
$suburb = clean_input($_POST["suburb"] ?? "");
$state = clean_input($_POST["state"] ?? "");
$postcode = clean_input($_POST["postcode"] ?? "");
$email = clean_input($_POST["email"] ?? "");
$phone = clean_input($_POST["phone"] ?? "");
$other_skills = clean_input($_POST["other_skills"] ?? "");

$skills = "";

if (isset($_POST["skills"]) && is_array($_POST["skills"])) {
  $skills = implode(", ", $_POST["skills"]);
}

$errors = [];

if (!preg_match("/^[A-Za-z0-9]{5}$/", $job_reference)) {
  $errors[] = "Job reference number must be exactly 5 alphanumeric characters.";
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
  $errors[] = "First name must only contain letters and be 20 characters or less.";
}

if (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
  $errors[] = "Last name must only contain letters and be 20 characters or less.";
}

if (empty($dob)) {
  $errors[] = "Date of birth is required.";
}

if (empty($gender)) {
  $errors[] = "Gender is required.";
}

if (strlen($street_address) > 40 || empty($street_address)) {
  $errors[] = "Street address is required and must be 40 characters or less.";
}

if (strlen($suburb) > 40 || empty($suburb)) {
  $errors[] = "Suburb is required and must be 40 characters or less.";
}

if (!in_array($state, ["VIC", "NSW", "QLD", "NT", "WA", "SA", "TAS", "ACT"])) {
  $errors[] = "State must be a valid Australian state or territory.";
}

if (!preg_match("/^[0-9]{4}$/", $postcode)) {
  $errors[] = "Postcode must be exactly 4 digits.";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $errors[] = "Email address is invalid.";
}

if (!preg_match("/^[0-9 ]{8,12}$/", $phone)) {
  $errors[] = "Phone number must be between 8 and 12 digits.";
}

if (count($errors) > 0) {
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Application Error</title>
    <link rel="stylesheet" href="styles.css">
  </head>
  <body>
    <main>
      <h1>Application Error</h1>

      <ul>
        <?php foreach ($errors as $error): ?>
          <li><?php echo htmlspecialchars($error); ?></li>
        <?php endforeach; ?>
      </ul>

      <p><a href="apply.php">Return to application form</a></p>
    </main>
  </body>
</html>
<?php
  exit;
}

$query = "INSERT INTO eoi 
(JobReferenceNumber, FirstName, LastName, DOB, Gender, StreetAddress, SuburbTown, State, Postcode, EmailAddress, PhoneNumber, Skills, OtherSkills, Status)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
  $stmt,
  "sssssssssssss",
  $job_reference,
  $first_name,
  $last_name,
  $dob,
  $gender,
  $street_address,
  $suburb,
  $state,
  $postcode,
  $email,
  $phone,
  $skills,
  $other_skills
);

$success = mysqli_stmt_execute($stmt);

$eoi_number = mysqli_insert_id($conn);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Application Submitted</title>
    <link rel="stylesheet" href="styles.css">
  </head>

  <body>
    <main>
      <?php if ($success): ?>
        <h1>Application Submitted</h1>
        <p>Your application has been successfully submitted.</p>
        <p>Your EOI number is: <strong><?php echo htmlspecialchars($eoi_number); ?></strong></p>
        <p><a href="index.php">Return to Home</a></p>
      <?php else: ?>
        <h1>Submission Failed</h1>
        <p>There was a problem submitting your application.</p>
        <p><a href="apply.php">Try again</a></p>
      <?php endif; ?>
    </main>
  </body>
</html>