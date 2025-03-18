<?php
// Database connection
$servername = "localhost"; // Replace with your server name
$username = "root";        // Replace with your database username
$password = "";            // Replace with your database password
$dbname = "your_database"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

// Handle login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Successful login
        header('Location: home.php'); // Redirect to home page
        exit();
    } else {
        $message = "Invalid username or password.";
    }
}

// Handle signup
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
    $newUsername = $_POST['newUsername'];
    $newEmail = $_POST['email'];
    $newPassword = $_POST['newPassword'];

    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $newUsername, $newEmail, $newPassword);
    if ($stmt->execute()) {
        $message = "Account created successfully!";
    } else {
        $message = "Error creating account: " . $stmt->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up</title>
    <link rel="stylesheet" href="stylelogin.css">
</head>
<body>
    <div class="container">
        <div id="loginSection">
            <h2>Login</h2>
            <form method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                <button type="submit" name="login">Login</button>
            </form>
            <p><?php echo $message; ?></p>
            <p>Don't have an account? <a href="#" id="showSignup">Sign Up</a></p>
        </div>

        <div id="signupSection" style="display: none;">
            <h2>Sign Up</h2>
            <form method="POST">
                <label for="newUsername">Username:</label>
                <input type="text" name="newUsername" id="newUsername" required>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
                <label for="newPassword">Password:</label>
                <input type="password" name="newPassword" id="newPassword" required>
                <button type="submit" name="signup">Sign Up</button>
            </form>
            <p><?php echo $message; ?></p>
            <p>Already have an account? <a href="#" id="showLogin">Login</a></p>
        </div>
    </div>
    <script>
        document.getElementById('showSignup').addEventListener('click', function() {
            document.getElementById('loginSection').style.display = 'none';
            document.getElementById('signupSection').style.display = 'block';
        });

        document.getElementById('showLogin').addEventListener('click', function() {
            document.getElementById('signupSection').style.display = 'none';
            document.getElementById('loginSection').style.display = 'block';
        });
    </script>
</body>
</html>
