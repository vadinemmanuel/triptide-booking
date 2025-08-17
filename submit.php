<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TripTide Bus Booking – Confirmation</title>
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
        .confirmation-box {
            background: rgba(255,255,255,0.95);
            max-width: 600px;
            margin: 40px auto;
            padding: 25px;
            border-left: 5px solid #28a745;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            text-align: center;
        }
        .confirmation-box h3 {
            color: #28a745;
            margin-bottom: 10px;
        }
        .confirmation-box p {
            margin: 8px 0;
        }
        .confirmation-box img {
            max-width: 150px;
            border-radius: 8px;
            margin-top: 10px;
        }
        .button-group {
            margin-top: 20px;
        }
        .button-group a button {
            padding: 10px 20px;
            margin: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .button-group a button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php
include 'db_config.php';

// ✅ Define seat number function
function generateSeatNumber($conn, $bus_type, $take_off_date): string {
    $count = 0; // ✅ Initialize to avoid "unassigned variable" warning

    $stmt = $conn->prepare("SELECT COUNT(*) FROM bookings WHERE bus_type = ? AND take_off_date = ?");
    $stmt->bind_param("ss", $bus_type, $take_off_date);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return " " . ($count + 1);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $service_type = $_POST['service_type'];
    $bus_type = $_POST['bus_type'];
    $from_location = $_POST['from_location'];
    $to_location = $_POST['to_location'];
    $take_off_date = $_POST['take_off_date'];
    $contact = $_POST['contact'];
    $payment_method = $_POST['payment_method'];

    $target_dir = "uploads/";
    $photo_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . time() . "_" . $photo_name;

    echo '<div class="confirmation-box">';

    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        // ✅ Generate seat number
        $seat_number = generateSeatNumber($conn, $bus_type, $take_off_date);

        // ✅ Insert booking with seat number
        $stmt = $conn->prepare("INSERT INTO bookings 
            (full_name, service_type, bus_type, from_location, to_location, take_off_date, contact, payment_method, photo_path, seat_number) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $full_name, $service_type, $bus_type, $from_location, $to_location, $take_off_date, $contact, $payment_method, $target_file, $seat_number);

        if ($stmt->execute()) {
            echo "<h3>✔ Booking Successful!</h3>";
            echo "<p><strong>Name:</strong> $full_name</p>";
            echo "<p><strong>From:</strong> $from_location → <strong>To:</strong> $to_location</p>";
            echo "<p><strong>Date:</strong> $take_off_date</p>";
            echo "<p><strong>Contact:</strong> $contact</p>";
            echo "<p><strong>Payment Method:</strong> $payment_method</p>";
            echo "<p><strong>Seat Number:</strong> $seat_number</p>";
            echo "<img src='$target_file' alt='Passenger Photo'>";
            echo '<div class="button-group">
                    <a href="booking.html"><button>Add Another Booking</button></a>
                    <a href="TripTide.html"><button>Return Home</button></a>
                  </div>';
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:red;'>File upload failed.</p>";
    }

    echo '</div>';
    $conn->close();
}
?>
