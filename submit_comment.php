<?php
$host = "localhost";
$user = "root"; 
$pass = "";     
$dbname = "bus_booking"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
  die("<div class='message error'>Connection failed: " . $conn->connect_error . "</div>");
}

// Get form data
$name = $_POST['name'];
$comment = $_POST['comment'];

// Prepare and execute SQL
$sql = "INSERT INTO comments (name, comment) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $name, $comment);

if ($stmt->execute()) {
  echo "<div class='message success'>Comment submitted successfully!</div>";
} else {
  echo "<div class='message error'>Error: " . $stmt->error . "</div>";
}
echo "<div class='message success'>Thank You!</div>";
echo "<div class='button-container'><a href='TripTide.html' class='home-button'>Return Home</a></div>";

$stmt->close();
$conn->close();
?>
