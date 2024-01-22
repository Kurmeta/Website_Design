<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Private Tutor Website</title>
    <link rel="stylesheet" href="./css/styles.css">
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
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
                    <a class="nav-link" href="payment.php">Payment</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Homepage -->
<section id="home" class="container mt-5">
    <div class="jumbotron text-center">
        <h1 class="display-4">Welcome to Coding Legend Courses</h1>
        <p class="lead">Unlock Your Potential in Computing with a Dedicated Tutor</p>
        <p>Hi, I'm Sarah Johnson, your experienced and passionate computing tutor. With a background in Computer Science and a wealth of industry experience, I am dedicated to guiding you through the exciting world of programming and technology.</p>
        <p>Whether you're just starting your coding journey or aiming to master advanced concepts, I'm here to provide clear explanations, hands-on exercises, and a supportive learning environment. My goal is to empower you to become a confident and skilled coder.</p>
        <p>Join me on this coding adventure, where we'll turn complex ideas into lines of code and transform challenges into opportunities for growth. Ready to elevate your computing skills?</p>
    </div>
</section>

<!-- Subjects and Services -->
<section id="subjects" class="container mt-5">
   	<h2 class="mb-4 text-center">Subjects and Services</h2>
	<div class="card-deck">
  <div class="card text-center">
    <img class="card-img-top" src="./img/1.jpg" alt="Card image cap">
    <div class="card-body">
      <h5 class="card-title">Programming Fundamentals</h5>
      <p class="card-text">Build a solid foundation in coding with a focus on algorithms, data structures, and problem-solving techniques. Perfect for beginners!</p>
    </div>
  </div>
  <div class="card text-center">
    <img class="card-img-top" src="./img/2.jpg" alt="Card image cap">
    <div class="card-body">
      <h5 class="card-title">Web Development</h5>
      <p class="card-text">Create dynamic and interactive websites. Learn HTML, CSS, JavaScript, and popular frameworks to bring your web projects to life.</p>
    </div>
  </div>
  <div class="card text-center">
    <img class="card-img-top" src="./img/3.jpg" alt="Card image cap">
    <div class="card-body">
      <h5 class="card-title">Python Mastery</h5>
      <p class="card-text">Explore the versatility of Python programming. From basic syntax to advanced topics like machine learning and data analysis.</p>
    </div>
  </div>
</div>

   
    <!-- Testimonials -->
    <div class="mt-4 text-center">
        <h3>Student Testimonials</h3>
        <p class="mb-0">"Sarah's teaching style is fantastic! She makes complex coding concepts easy to understand. Highly recommended!"</p>
        <p>"I started with zero coding knowledge, and now I'm confidently building websites thanks to Sarah's guidance."</p>
    </div>
</section>

<!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
