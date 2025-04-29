<?php
    session_start();
    if(isset ($_SESSION['user'])) {
        header("Location: dashboard.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FilmeraKey Login</title>
    <link rel="stylesheet" href="/assets/login.css" />
</head>
<body>
    <div class="container_layout">
        
        <!-- Kiri: Form Login -->
        <div class="container_login">
            <div class="Container_form">
            <h3>Welcome Back to <br>FilmeraKey</h3>
            <h4>Log in to access your API key and manage your movie data requests.</h4>
            <?php if (isset($_GET['message'])) echo "<p style='color:green'>" . $_GET['message'] . "</p>";?>
            <?php if (isset($_GET['error'])) echo "<p style='color:red'>" . $_GET['error'] . "</p>";?>
            <form id="login-form" method="post" action="proses_login.php">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo isset ($_POST['username']) ? $_POST['username'] : ''; ?>" required/>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required />

                <button type="submit">Submit</button>
            </form>
            </div>
            
        </div>

        <!-- Kanan: Gambar -->
        <div class="container_picture">
            <img src="/assets/picture/image_4.png" alt="Login Visual" />
        </div>

    </div>
</body>
</html>
