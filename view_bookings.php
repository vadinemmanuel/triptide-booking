<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TripTide Bus Booking – My Bookings</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('images/bus_station.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .navbar {
            background: #222;
            padding: 15px;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
        .navbar a {
            color: #fff;
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .navbar a.active {
            background: yellow;
            color: #000;
        }
        .hero {
            background: rgba(255,255,255,0.9);
            padding: 30px;
            text-align: center;
        }
        .hero h1 {
            color: red;
            margin-bottom: 10px;
        }
        .hero p {
            font-size: 1.1em;
        }
        .booking-list {
            list-style: none;
            padding: 20px;
            max-width: 600px;
            margin: auto;
        }
        .booking-list li {
            background: rgba(255,255,255,0.95);
            margin-bottom: 20px;
            padding: 15px;
            border-left: 5px solid #007BFF;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .booking-list img {
            max-width: 150px;
            border-radius: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="view_bookings.php" class="active">My Bookings</a>
</div>

<ul class="booking-list">
<?php
$conn = new mysqli("localhost", "root", "", "bus_booking");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM bookings ORDER BY take_off_date ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "<strong>Bus Type:</strong> " . htmlspecialchars($row['bus_type']) . "<br>";
        echo "<strong>Date:</strong> " . htmlspecialchars($row['take_off_date']) . "<br>";
        echo "<strong>From:</strong> " . htmlspecialchars($row['from_location']) . " to " . htmlspecialchars($row['to_location']) . "<br>";
        echo "<strong>Payment:</strong> " . htmlspecialchars($row['payment_method']) . "<br>";
        echo "<strong>Contact:</strong> " . htmlspecialchars($row['contact']) . "<br>";
        echo "<strong>Passenger:</strong> " . htmlspecialchars($row['full_name']) . "<br>";

        if (!empty($row['photo_path'])) {
            echo "<img src='" . htmlspecialchars($row['photo_path']) . "' alt='Passenger Photo'>";
        }

        echo "</li>";
    }
} else {
    echo "<li style='background:#ffecec; border-left:5px solid #ff4d4d;'>No bookings found.</li>";
}

$conn->close();
?>
</ul>

</body>
</html>
