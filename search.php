<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Payment</title>
    <link rel="stylesheet" href="css\styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="Payment.php">Payment</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="search.php">Search</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Search Bar -->
<div class="container mt-3">
    <input type="text" id="searchInput" class="form-control" placeholder="Search for a course...">
</div>

<!-- Bootstrap Cards -->
<div class="container mt-3">
    <div class="row" id="courseCards">
        <!-- Your Bootstrap cards will be dynamically added here -->
    </div>
</div>

<!-- Course Details Modal -->
<div class="modal fade" id="courseDetailsModal" tabindex="-1" role="dialog" aria-labelledby="courseDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseDetailsModalLabel">Course Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalCourseImage" class="text-center mb-3"></div>
                <h5 id="modalCourseTitle"></h5>
                <p id="modalCourseDescription"></p>
            </div>
        </div>
    </div>
</div>

<!-- Include necessary JavaScript libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
    // Sample data for courses (replace this with your actual data)
    var courses = [
    { title: "Blockchain Development Workshop", description: "Explore the revolutionary technology of blockchain and gain hands-on experience in developing decentralized applications. Learn the principles of distributed ledger technology and smart contracts to build secure and transparent systems." , image: "./img/3.jpg"},
    { title: "DevOps: Continuous Integration and Deployment", description: "Dive into the world of DevOps and discover best practices for continuous integration and deployment. Streamline software development workflows, automate testing, and deploy applications efficiently, ensuring a seamless and agile development process."  , image: "./img/1.jpg"},
    { title: "Cloud Computing with AWS", description: "Learn the fundamentals of cloud computing with Amazon Web Services (AWS). Gain practical skills in setting up scalable and reliable cloud infrastructure, deploying applications, and optimizing resources for enhanced performance."  , image: "./img/2.jpg"},
    { title: "Cybersecurity Fundamentals", description: "Develop a solid foundation in cybersecurity, covering essential concepts and techniques to protect digital assets. Explore topics such as network security, encryption, threat detection, and risk management in the ever-evolving landscape of cybersecurity."  , image: "./img/1.jpg"},
    { title: "Machine Learning Essentials", description: "Delve into the core principles of machine learning. Understand algorithms, model training, and predictive analytics. Gain practical insights into machine learning applications and enhance your ability to make data-driven decisions."  , image: "./img/1.jpg"},
    { title: "Android App Development with Kotlin", description: "Master Android app development using Kotlin, the modern programming language endorsed by Google. Learn to build feature-rich and user-friendly Android applications, covering UI design, data storage, and integration with external APIs."  , image: "./img/1.jpg"},
    { title: "iOS App Development with Swift", description: "Explore the world of iOS app development using Swift, Apple's powerful and intuitive programming language. Acquire skills in building responsive and engaging applications for iPhone and iPad devices."  , image: "./img/2.jpg"},
    { title: "Back-End Development with Node.js", description: "Become proficient in back-end development using Node.js. Learn to create scalable and efficient server-side applications, handle data storage, and implement APIs for seamless integration with front-end technologies."  , image: "./img/3.jpg"},
    { title: "Front-End Development with React", description: "Master front-end development with React, a popular JavaScript library for building user interfaces. Explore component-based architecture, state management, and responsive design to create dynamic and interactive web applications."  , image: "./img/2.jpg"},
    { title: "Java Programming: From Basics to Advanced", description: "Cover the entire spectrum of Java programming, from fundamental concepts to advanced topics. Develop a strong foundation in object-oriented programming, Java syntax, and application development for diverse platforms."  , image: "./img/3.jpg"},
    { title: "Data Structures and Algorithms in C++", description: "Deepen your understanding of data structures and algorithms using the C++ programming language. Enhance problem-solving skills, optimize code performance, and explore the intricacies of algorithmic design."  , image: "./img/3.jpg"},
    { title: "Full Stack Web Development Bootcamp", description: "Enroll in a comprehensive bootcamp covering both front-end and back-end web development. Acquire skills in HTML, CSS, JavaScript, and server-side technologies to become a proficient full-stack developer."  , image: "./img/2.jpg"},
    { title: "JavaScript for Beginners", description: "Start your journey into web development with JavaScript. Learn the basics of programming, DOM manipulation, and asynchronous programming, laying the foundation for building dynamic and interactive websites."  , image: "./img/1.jpg"},
    { title: "Python Programming Mastery", description: "Achieve mastery in Python programming, a versatile and beginner-friendly language. Explore its applications in data science, machine learning, and web development, and gain practical experience in building Python-based solutions."  , image: "./img/2.jpg"},
    { title: "Web Development Fundamentals", description: "Begin your exploration of web development by mastering the fundamentals. Cover HTML, CSS, and JavaScript to create well-designed and interactive websites, laying the groundwork for advanced web development skills."  , image: "./img/1.jpg"},
];


    
        // Initialize cards with sample data
        initializeCards(courses);

        // Attach an event listener to the search input
        $('#searchInput').on('input', function () {
            var searchText = $(this).val().toLowerCase();
            filterCards(searchText, courses);
        });

        // Function to initialize Bootstrap cards with sample data
        function initializeCards(data) {
            var cardContainer = $('#courseCards');
            cardContainer.empty();

            $.each(data, function (index, course) {
                var card = $(`
                    <div class="col-md-4 mb-3">
                        <div class="card text-center">
                            <img class="card-img-top" src="${course.image}" alt="Course Image">
                            <div class="card-body">
                                <h5 class="card-title">${course.title}</h5>
                                <p class="card-text" style="display: none;">${course.description}</p>
                                <button class="btn btn-primary view-details-btn" data-toggle="modal" data-target="#courseDetailsModal" data-title="${course.title}" data-description="${course.description}" data-image="${course.image}">View Details</button>
                            </div>
                        </div>
                    </div>
                `);
                cardContainer.append(card);
            });

            // Attach event listener to dynamically created "View Details" buttons
            $('.view-details-btn').on('click', function () {
                var title = $(this).data('title');
                var description = $(this).data('description');
                var image = $(this).data('image');

                // Set modal content
                $('#modalCourseTitle').text(title);
                $('#modalCourseDescription').text(description);
                $('#modalCourseImage').html(`<img src="${image}" alt="Course Image" style="max-width: 100%;">`);
            });
        }

        // Function to filter Bootstrap cards based on search text
        function filterCards(searchText, data) {
            var filteredData = data.filter(function (course) {
                return course.title.toLowerCase().includes(searchText) || course.description.toLowerCase().includes(searchText);
            });

            initializeCards(filteredData);
        }
    });
</script>


</body>
</html>
