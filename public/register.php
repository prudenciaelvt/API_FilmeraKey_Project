<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>FilmeraKey Register</title>
    <link rel="stylesheet" href="/assets/register.css" />
</head>
<body>
    <div class="container_layout">

        <!-- Kanan: Gambar -->
        <div class="container_picture">
            <img src="/assets/picture/image_5_left.png" alt="Register Visual" />
        </div>

        <!-- Kiri: Form Register -->
        <div class="container_login">
            <div class="Container_form">
                <h3>Welcome to <br>FilmeraKey</h3>
                <h4>Create your account to get your personal API key and start exploring a world of movie data.</h4>

                <?php if (isset($_GET['error'])): ?>
                    <p style="color:red"><?php echo $_GET['error']; ?></p>
                <?php endif; ?>

                <form id="register-form" method="post" action="proses_register.php">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required />

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" placeholder="user@example.com" autocomplete="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required />

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required />

                    <button type="submit">Register</button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>
