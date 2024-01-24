<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "bookings_db";

// Creating a new mysqli connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Checking the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieving form data
    $userName = $_POST["userName"];
    $selectedDate = $_POST["selectedDate"];
    $selectedTime = $_POST["selectedTime"];
    $selectedCourse = $_POST["selectedCourse"];
    $courseCost = $_POST["courseCost"];
    $paid = 'no';

    // Checking if the selected time slot is already booked
    if (isTimeSlotBooked($conn, $selectedDate, $selectedTime)) {
        echo "This time slot is already booked. Please choose another slot.";
    } else {
        // Inserting booking information into the database
        $sql = "INSERT INTO bookings (username, selecteddate, selectedtime, selectedcourse, coursecost, paid)
                VALUES ('$userName', '$selectedDate', '$selectedTime', '$selectedCourse', '$courseCost', '$paid')";

        // Sending a confirmation email
        $to = '$userName';
        $subject = 'Booking Confirmation';
        $message = "Thank you, $username, for your booking. Please head to the payment page to complete your booking.";
        mail($to, $subject, $message);

        // Checking if the booking was successful
        if ($conn->query($sql) === TRUE) {
            echo "Booking successful! Your appointment has been scheduled.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Function to check if a time slot is already booked
function isTimeSlotBooked($conn, $selectedDate, $selectedTime)
{
    $formattedDateTime = $selectedDate . ' ' . $selectedTime;
    $sql = "SELECT * FROM bookings WHERE selecteddate = '$selectedDate' AND selectedtime = '$selectedTime'";
    $result = $conn->query($sql);

    return $result->num_rows > 0;
}

// Function to fetch the cost of a selected course
function fetchCourseCost($conn, $selectedCourse)
{
    $sql = "SELECT course_cost FROM courses WHERE course_name = '$selectedCourse'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['course_cost'];
    } else {
        return 0;
    }
}

// Fetching events for the calendar
$events = array();
$result = $conn->query("SELECT * FROM bookings");

while ($row = $result->fetch_assoc()) {
    $event = array(
        'title' => $row['userName'] . ' - ' . $row['selectedCourse'],
        'start' => date('c', strtotime($row['selectedDate'] . ' ' . $row['selectedTime'])),
        'end' => date('c', strtotime($row['selectedDate'] . ' ' . $row['selectedTime'] . '+1 hour')),
        'course' => $row['selectedCourse'],
        'color' => '#2196F3'
    );

    $events[] = $event;
}

// Closing the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and title -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System</title>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="./css/styles.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
</head>

<body>
    <!-- Navigation bar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">Coding Legend Courses</a>
            <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <!-- Navigation links -->
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="booking.php">Booking</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="quiz.php">Quiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="results.php">Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Payment.php">Payment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Search</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Booking section -->
    <section id="booking" class="container mt-5 text-center booking-title">
        <h2>Booking System</h2>

        <!-- Calendar container -->
        <div class="row row-custom">
            <div class="col-md-8 offset-md-2">
                <div id="calendar" class="calendar-container"></div>
            </div>
        </div>

        <!-- Booking form -->
        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <form id="selectDateTimeForm" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <h4 class="mb-3">Select Date, Time, and Enter Your Name</h4>
                    <!-- Date input -->
                    <div class="form-group">
                        <label for="selectedDate">Select Date:</label>
                        <input type="date" class="form-control" id="selectedDate" name="selectedDate" required>
                    </div>
                    <!-- Time slot selection -->
                    <div class="form-group">
                        <label for="selectedTime">Select Time Slot:</label>
                        <select class="form-control" id="selectedTime" name="selectedTime" required>
                            <?php
                            // Generating time slots
                            $startTime = strtotime("09:00");
                            $endTime = strtotime("17:00");

                            while ($startTime < $endTime) {
                                echo '<option value="' . date("H:i", $startTime) . '">' . date("h:i A", $startTime) .
                                    '</option>';
                                $startTime = strtotime('+1 hour', $startTime);
                            }
                            ?>
                        </select>
                    </div>
                    <!-- User name input -->
                    <div class="form-group">
                        <label for="userName">Your Email:</label>
                        <input type="email" class="form-control" id="userName" name="userName" required>
                    </div>
                    <!-- Course selection -->
                    <div class="form-group">
                        <label for="select_course">Select Course:</label>
                        <select class="form-control" id="select_course" name="selectedCourse" required>
                            <option value="Programming Fundamentals">Programming Fundamentals</option>
                            <option value="Web Development">Web Development</option>
                            <option value="Python Mastery">Python Mastery</option>
                        </select>
                    </div>
                    <!-- Course cost display -->
                    <div class="form-group">
                        <label for="courseCost">Course Cost (£):</label>
                        <input type="text" class="form-control" id="courseCost" name="courseCost" readonly>
                    </div>
                    <!-- Booking button -->
                    <button type="submit" class="btn btn-custom">
                        Book Appointment
                    </button>
                </form>

                <!-- Script to dynamically update course cost based on selection -->
                <script>
                    document.getElementById('select_course').addEventListener('change', function () {
                        var selectedCourse = this.value;
                        var courseCostField = document.getElementById('courseCost');
                        
                        // Default course cost
                        var courseCost = 50; 

                        // Adjusting course cost based on selection
                        if (selectedCourse === 'Web Development') {
                            courseCost = 100;
                        } else if (selectedCourse === 'Python Mastery') {
                            courseCost = 75;
                        }

                        // Updating the displayed course cost
                        courseCostField.value = courseCost;
                    });
                </script>

            </div>
        </div>
    </section>

    <!-- JavaScript and Bootstrap dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <!-- Calendar initialization script -->
    <script>
        $(document).ready(function () {
            var events = <?php echo json_encode($events); ?>;

            var calendar = $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                defaultView: 'month',
                events: events,
                eventClick: function (event) {
                    // Handling event click
                    var modalContent = `
                        <p><strong>Customer:</strong> ${event.title}</p>
                        <p><strong>Start:</strong> ${moment(event.start).format('DD-MM-YYYY HH:mm')}</p>
                        <p><strong>End:</strong> ${moment(event.end).format('DD-MM-YYYY HH:mm')}</p>
                        <p><strong>Course:</strong> ${event.course}</p>
                    `;

                    // Displaying event details in a modal
                    $('#eventDetailsModal .modal-body').html(modalContent);
                    $('#eventDetailsModal').modal('show');
                },
                eventRender: function (event, element) {
                    // Rendering event on the calendar
                    var formattedEvent = '<br>' + event.title;
                    element.find('.fc-title').html(formattedEvent);
                }
            });
        });
    </script>

    <!-- Event details modal -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" role="dialog" aria-labelledby="eventDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">Booking Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Modal content -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
