<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking System</title>
    <link rel="stylesheet" href="./css/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css">
</head>

<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">Coding Legend Courses</a>
        <button class="navbar-toggler custom-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
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
                    <a class="nav-link" href="search.php">Search</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Booking System -->
<section id="booking" class="container mt-5 text-center booking-title">
    <h2>Booking System</h2>

    <!-- Calendar -->
    <div class="row row-custom">
        <div class="col-md-8 offset-md-2">
            <!-- Apply the custom class to the calendar container -->
            <div id="calendar" class="calendar-container"></div>
        </div>
    </div>

    <!-- Selected Date, Time, and Name -->
    <div class="row mt-4">
        <div class="col-md-6 offset-md-3">
            <!-- Add this div for success message -->
            <div id="bookingSuccessMessage" class="alert alert-success" style="display: none;">
                Booking successful! Your appointment has been scheduled.
            </div>
            <div id="bookingAlert" class="alert alert-danger"  style="display: none;" >
                This time slot is already booked. Please choose another slot.
            </div>
            <form id="selectDateTimeForm">
                <h4 class="mb-3">Select Date, Time, and Enter Your Name</h4>
                <div class="form-group">
                    <label for="selectedDate">Select Date:</label>
                    <input type="date" class="form-control" id="selectedDate" required>
                </div>
                <div class="form-group">
                    <label for="selectedTime">Select Time Slot:</label>
                    <select class="form-control" id="selectedTime" required>
                        <!-- Time slots will be dynamically populated using JavaScript -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="userName">Your Name:</label>
                    <input type="text" class="form-control" id="userName" required>
                </div>
                <div class="form-group">
                    <label for="select_course">Select Course:</label>
                    <select class="form-control" id="select_course" required>
                        <option value="Programming Fundamentals" data-cost="50">Programming Fundamentals</option>
                        <option value="Web Development" data-cost="100">Web Development</option>
                        <option value="Python Mastery" data-cost="75">Python Mastery</option>
                    </select>
                </div>
                <!-- Display the selected course cost here -->
                <div class="form-group">
                    <label for="courseCost">Course Cost (£):</label>
                    <input type="text" class="form-control" id="courseCost" readonly>
                </div>
                <!-- Updated button to trigger the modal -->
                <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#additionalInfoModal">
                    Book Appointment
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Additional Information Modal -->
<div class="modal fade" id="additionalInfoModal" tabindex="-1" role="dialog" aria-labelledby="additionalInfoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="additionalInfoModalLabel">Payment Information:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Additional Information Form -->
                <form id="additionalInfoForm" action="process_form.php" method="post">
                    <div class="form-group">
                        <label for="cardHolderName">Name on Card:</label>
                        <input type="text" class="form-control" id="cardHolderName" required>
                    </div>
                    <div class="form-group">
                        <label for="cardNumber">Card Number:</label>
                        <input type="text" class="form-control" id="cardNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="expiryDate">Expiry Date:</label>
                        <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label for="cvc">CVC:</label>
                        <input type="text" class="form-control" id="cvc" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include necessary JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

<script>
    $(document).ready(function () {
        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'month',
            events: [], // No initial events
            eventClick: function (event) {
                // Display details without edit and delete options
                var modalContent = `
                    <p><strong>Customer:</strong> ${event.title}</p>
                    <p><strong>Start:</strong> ${event.start.format('DD-MM-YYYY HH:mm')}</p>
                    <p><strong>End:</strong> ${event.end.format('DD-MM-YYYY HH:mm')}</p>
                    <p><strong>Course:</strong> ${event.course}</p>
                `;

                // Create Bootstrap modal
                $('#eventDetailsModal .modal-body').html(modalContent);
                $('#eventDetailsModal').modal('show');
            }
        });

        // Array to track booked time slots
        var bookedSlots = [];

        $('#selectDateTimeForm').submit(function (event) {
            event.preventDefault();

            var selectedDate = $('#selectedDate').val();
            var selectedTime = $('#selectedTime').val();
            var userName = $('#userName').val();
            var selectedCourse = $('#select_course').val(); // Fetch the selected course

            // Check if the time slot is already booked
            if (isTimeSlotBooked(selectedDate, selectedTime)) {
                $('#bookingAlert').show();
                $('#bookingSuccessMessage').hide(); // Hide success message if error occurs
                return;
            }

            $('#bookingAlert').hide();

            var newEvent = {
                title: `${userName} - ${selectedCourse}`,
                start: moment(`${selectedDate} ${selectedTime}`, 'YYYY-MM-DD HH:mm').format(),
                end: moment(`${selectedDate} ${selectedTime}`, 'YYYY-MM-DD HH:mm').add(1, 'hours').format(),
                course: selectedCourse, // Assign the selected course
                color: 'Black'
            };

            // Add the booked time slot to the array
            bookedSlots.push({
                date: selectedDate,
                time: selectedTime
            });

            calendar.fullCalendar('renderEvent', newEvent, true);

            // Display success message
            $('#bookingSuccessMessage').show();
        });

        // Populate time slots
        var startTime = moment('09:00', 'HH:mm');
        var endTime = moment('17:00', 'HH:mm');
        var timeSlotInterval = 60; // in minutes

        var timeSlots = [];
        var currentTime = startTime.clone();

        while (currentTime.isBefore(endTime)) {
            timeSlots.push(currentTime.format('HH:mm'));
            currentTime.add(timeSlotInterval, 'minutes');
        }

        var timeSlotDropdown = $('#selectedTime');
        timeSlotDropdown.empty();
        $.each(timeSlots, function (index, timeSlot) {
            timeSlotDropdown.append($('<option></option>').attr('value', timeSlot).text(timeSlot));
        });

        function isTimeSlotBooked(selectedDate, selectedTime) {
            // Check if the selected time slot is already booked
            return bookedSlots.some(function (slot) {
                return slot.date === selectedDate && slot.time === selectedTime;
            });
        }

        // Update course cost when a course is selected
        $('#select_course').change(function () {
            var selectedCourse = $(this).find(':selected');
            var courseCost = selectedCourse.data('cost');
            $('#courseCost').val(courseCost);
        });

        // Additional Information Form Submission
        $('#additionalInfoForm').submit(function (event) {
            event.preventDefault();

            // Retrieve values from the additional information form
            var cardHolderName = $('#cardHolderName').val();
            var cardNumber = $('#cardNumber').val();
            var expiryDate = $('#expiryDate').val();
            var cvc = $('#cvc').val();
            var email = $('#email').val();
            var selectedCourse = $('#select_course').val();
            var courseCost = $('#courseCost').val();

            // Perform any additional processing or validation as needed

            // Add the event to the calendar
            var selectedDate = $('#selectedDate').val();
            var selectedTime = $('#selectedTime').val();
            var userName = $('#userName').val();

            var newEvent = {
                title: `${userName} - ${selectedCourse}`,
                start: moment(`${selectedDate} ${selectedTime}`, 'YYYY-MM-DD HH:mm').format(),
                end: moment(`${selectedDate} ${selectedTime}`, 'YYYY-MM-DD HH:mm').add(1, 'hours').format(),
                course: selectedCourse,
                color: 'Black'
            };

            calendar.fullCalendar('renderEvent', newEvent, true);

            // Close the modal
            $('#additionalInfoModal').modal('hide');

            // Display success message (you may customize this part)
            alert('Additional information submitted successfully!');
        });
    });
</script>






<!-- Bootstrap Modal for Event Details -->
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
                <!-- Content will be dynamically filled -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

</body>

</html>