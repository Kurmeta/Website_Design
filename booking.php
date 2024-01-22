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
    <style>
        /* Custom style for the calendar container */
        .calendar-container {
            height: 600px; /* Adjust the height as needed */
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="#">CodeCraft Tutorials</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="quiz.php">Quiz</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="payment.php">Payment</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <!-- Booking System -->
    <section id="booking" class="container mt-5 text-center">
        <h2>Booking System</h2>

        <!-- Calendar -->
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Apply the custom class to the calendar container -->
                <div id="calendar" class="calendar-container"></div>
            </div>
        </div>

        <!-- Selected Date, Time, and Name -->
        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
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
                    <button type="submit" class="btn btn-primary">Book Appointment</button>
                </form>
            </div>
        </div>

        <!-- Alert for Already Booked Slot -->
        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <div class="alert alert-danger" role="alert" style="display: none;" id="bookingAlert">
                    This time slot is already booked. Please choose another slot.
                </div>
            </div>
        </div>
    </section>

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
                editable: true, // Enable editing
                eventClick: function (event) {
                    // Display details and provide options for editing and deleting the booking
                    var modalContent = `
                        <p><strong>Title:</strong> ${event.title}</p>
                        <p><strong>Start:</strong> ${event.start.format('YYYY-MM-DD HH:mm')}</p>
                        <p><strong>End:</strong> ${event.end.format('YYYY-MM-DD HH:mm')}</p>
                    `;

                    modalContent += `
                        <div class="mt-3">
                            <button class="btn btn-primary btn-edit-event" data-event-id="${event.id}">Edit</button>
                            <button class="btn btn-danger btn-delete-event" data-event-id="${event.id}">Delete</button>
                        </div>
                    `;

                    // Create Bootstrap modal
                    $('#eventDetailsModal .modal-body').html(modalContent);
                    $('#eventDetailsModal').modal('show');

                    // Handle edit button click
                    $('.btn-edit-event').click(function () {
                        // Handle the edit functionality as needed
                        alert('Edit event clicked. Event ID: ' + $(this).data('event-id'));
                    });

                    // Handle delete button click
                    $('.btn-delete-event').click(function () {
                        // Handle the delete functionality
                        var eventId = $(this).data('event-id');
                        var eventToRemove = calendar.fullCalendar('clientEvents', eventId)[0];

                        // Remove the event from the calendar
                        calendar.fullCalendar('removeEvents', eventId);

                        // Remove the booked time slot from the array
                        var indexToRemove = bookedSlots.findIndex(function (slot) {
                            return slot.date === eventToRemove.start.format('YYYY-MM-DD') && slot.time === eventToRemove.start.format('HH:mm');
                        });

                        if (indexToRemove !== -1) {
                            bookedSlots.splice(indexToRemove, 1);
                        }

                        $('#eventDetailsModal').modal('hide');
                    });
                }
            });

            populateTimeSlots();

            // Array to track booked time slots
            var bookedSlots = [];

            $('#selectDateTimeForm').submit(function (event) {
                event.preventDefault();

                var selectedDate = $('#selectedDate').val();
                var selectedTime = $('#selectedTime').val();
                var userName = $('#userName').val();

                // Check if the time slot is already booked
                if (isTimeSlotBooked(selectedDate, selectedTime)) {
                    $('#bookingAlert').show();
                    return;
                }

                $('#bookingAlert').hide();

                var event = {
                    title: 'Booked - ' + userName,
                    start: moment(selectedDate + ' ' + selectedTime, 'YYYY-MM-DD HH:mm').format(),
                    end: moment(selectedDate + ' ' + selectedTime, 'YYYY-MM-DD HH:mm').add(1, 'hours').format(),
                    color: 'red'
                };

                // Add the booked time slot to the array
                bookedSlots.push({
                    date: selectedDate,
                    time: selectedTime
                });

                calendar.fullCalendar('renderEvent', event, true);
            });

            function populateTimeSlots() {
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
            }

            function isTimeSlotBooked(selectedDate, selectedTime) {
                // Check if the selected time slot is already booked
                return bookedSlots.some(function (slot) {
                    return slot.date === selectedDate && slot.time === selectedTime;
                });
            }
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
