<?php
session_start();
require_once 'backend/config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$bus_id = isset($_GET['bus_id']) ? (int)$_GET['bus_id'] : 0;
$date = isset($_GET['date']) ? $_GET['date'] : '';

if (!$bus_id || !$date) {
    header("Location: index.php");
    exit();
}

// Get bus details
$sql = "SELECT b.*, r.from_location, r.to_location, r.departure_time, r.arrival_time, r.fare
        FROM buses b
        JOIN routes r ON b.route_id = r.id
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();
$bus = $result->fetch_assoc();

if (!$bus) {
    header("Location: index.php");
    exit();
}

// Get booked seats
$booked_sql = "SELECT seat_number FROM bookings 
               WHERE bus_id = ? AND booking_date = ? AND status != 'cancelled'";
$booked_stmt = $conn->prepare($booked_sql);
$booked_stmt->bind_param("is", $bus_id, $date);
$booked_stmt->execute();
$booked_result = $booked_stmt->get_result();
$booked_seats = [];
while ($row = $booked_result->fetch_assoc()) {
    $booked_seats[] = $row['seat_number'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - Bus Booking System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .seat {
            width: 40px;
            height: 40px;
            margin: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #4B5563;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: all 0.2s;
        }
        .seat:hover:not(.booked) {
            border-color: #2563EB;
            background-color: #DBEAFE;
        }
        .seat.selected {
            background-color: #2563EB;
            border-color: #2563EB;
            color: white;
        }
        .seat.booked {
            background-color: #E5E7EB;
            border-color: #9CA3AF;
            color: #9CA3AF;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-blue-700 text-white p-4 mb-8">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-2xl font-bold">BusBooking</a>
            <div class="space-x-4">
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="backend/logout.php" class="hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4">

        <!-- Bus Info -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-bold mb-4">Select Your Seats</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-lg font-semibold"><?php echo htmlspecialchars($bus['bus_name']); ?></p>
                    <p class="text-gray-600">Bus Number: <?php echo htmlspecialchars($bus['bus_number']); ?></p>
                </div>
                <div>
                    <p><strong>From:</strong> <?php echo htmlspecialchars($bus['from_location']); ?></p>
                    <p><strong>To:</strong> <?php echo htmlspecialchars($bus['to_location']); ?></p>
                    <p><strong>Date:</strong> <?php echo date('d M Y', strtotime($date)); ?></p>
                    <p><strong>Departure:</strong> <?php echo date('h:i A', strtotime($bus['departure_time'])); ?></p>
                </div>
            </div>
        </div>

        <!-- Seat Layout -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h3 class="text-xl font-semibold mb-4">Choose Seats</h3>
            <div class="text-center" id="seatMap">
                <?php
                $total_seats = $bus['total_seats'];
                $rows = ceil($total_seats / 4);
                for ($i = 0; $i < $rows; $i++) {
                    echo '<div class="flex justify-center gap-8 mb-4">';
                    for ($j = 0; $j < 4; $j++) {
                        $seat_num = ($i * 4) + $j + 1;
                        if ($seat_num <= $total_seats) {
                            $seat_label = 'S' . str_pad($seat_num, 2, '0', STR_PAD_LEFT);
                            $booked = in_array($seat_label, $booked_seats) ? ' booked' : '';
                            echo "<div class='seat$booked' data-seat='$seat_label'>$seat_label</div>";
                        }
                    }
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Booking Summary -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h3 class="text-xl font-bold mb-4">Booking Summary</h3>
            <div class="mb-4">
                <p>Fare per seat: Rs. <span id="farePerSeat"><?php echo number_format($bus['fare'], 2); ?></span></p>
                <p>Selected seats: <span id="selectedSeatsText">None</span></p>
                <p class="text-xl font-bold mt-2">Total Fare: Rs. <span id="totalAmount">0.00</span></p>
            </div>
            <form id="bookingForm" action="backend/process-booking.php" method="POST">
                <input type="hidden" name="bus_id" value="<?php echo $bus_id; ?>">
                <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
                <input type="hidden" name="selected_seats" id="selectedSeatsInput" value="">
                <button type="submit" id="bookButton" disabled
                    class="w-full bg-blue-700 text-white py-3 px-6 rounded-lg hover:bg-blue-800 transition duration-200 disabled:bg-gray-400 disabled:cursor-not-allowed">
                    Confirm Booking
                </button>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const seatMap = document.getElementById('seatMap');
        const selectedSeatsText = document.getElementById('selectedSeatsText');
        const selectedSeatsInput = document.getElementById('selectedSeatsInput');
        const totalAmount = document.getElementById('totalAmount');
        const bookButton = document.getElementById('bookButton');
        const farePerSeat = parseFloat(document.getElementById('farePerSeat').textContent);
        let selectedSeats = [];

        seatMap.addEventListener('click', e => {
            if (e.target.classList.contains('seat') && !e.target.classList.contains('booked')) {
                const seat = e.target.dataset.seat;
                e.target.classList.toggle('selected');

                if (selectedSeats.includes(seat)) {
                    selectedSeats = selectedSeats.filter(s => s !== seat);
                } else {
                    selectedSeats.push(seat);
                }

                selectedSeatsText.textContent = selectedSeats.length ? selectedSeats.join(', ') : 'None';
                totalAmount.textContent = (selectedSeats.length * farePerSeat).toFixed(2);
                selectedSeatsInput.value = selectedSeats.join(',');
                bookButton.disabled = selectedSeats.length === 0;
            }
        });

        document.getElementById('bookingForm').addEventListener('submit', e => {
            if (selectedSeats.length === 0) {
                e.preventDefault();
                alert('Please select at least one seat.');
            }
        });
    });
    </script>

</body>
</html>
