<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'User'; 
$message = '';

//generate API key
if (isset($_POST['generate'])) {
    $key_name = $_POST['key_name'];
    $api_key = md5(uniqid(rand(), true));

    $stmt = $pdo->prepare("INSERT INTO tbl_api_keys (user_id, api_key, name, status) VALUES (?, ?, ?, 'Active')");
    if ($stmt->execute([$user_id, $api_key, $key_name])) {
        $message = "<div class='message success'>API key berhasil dibuat!</div>";
    } else {
        $message = "<div class='message error'>Gagal membuat API key.</div>";
    }
}

//menghapus api key
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_key'])) {
    $key_id = $_POST['key_id'];

    $stmt = $pdo->prepare("DELETE FROM tbl_api_keys WHERE id = ? AND user_id = ?");
    $stmt->execute([$key_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        $message = "API key deleted successfully!";
    } else {
        $error = "Failed to delete API key or no matching key found.";
    }

    header("Location: dashboard.php?message=" . urlencode($message ?? '') . "&error=" . urlencode($error ?? ''));
    exit();
}


// Ambil daftar API keys milik user
$stmt = $pdo->prepare("SELECT * FROM tbl_api_keys WHERE user_id = ?");
$stmt->execute([$user_id]);
$api_keys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Dashboard - Filmera Key</title>
    <link rel="stylesheet" href="/assets/dashboard.css">
    <style>
        .key-box { background: #f1f1f1; border: 1px solid #ccc; padding: 5px; border-radius: 5px; word-break: break-all; }
        .key-list { margin-top: 20px; }
        .key-item { display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ddd; }
        .key-item button { padding: 6px 12px; cursor: pointer; }
        .message { padding: 10px; margin: 10px 0; border-radius: 5px; }
        .success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <?php include '../includes/navbar_logout.php'; ?>

    <main class="dashboard" style="padding: 20px; font-family: sans-serif;">
        <h2>Welcome, <?php echo $username; ?> </h2>
        <p>Manage your API key below:</p>

        <!-- Pesan sukses / error -->
        <?php if (isset($success)): ?>
            <div class="message success"><?php echo $success; ?></div>
        <?php elseif (isset($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Form Generate Key -->
        <form method="POST">
            <label for="key_name">API key name</label>
            <input type="text" name="key_name" id="key_name" placeholder="API key name" required />
            <button type="submit" name="generate">Generate</button>
        </form>

        <!-- Daftar Key -->
        <div class="key-list">
            <div class="key-item" style="font-weight: bold;">
                <div>Key</div>
                <div>Name</div>
                <div>Status</div>
                <div>Actions</div>
            </div>

            <?php if (count($api_keys) > 0): ?>
                <?php foreach ($api_keys as $key): ?>
                    <div class="key-item">
                        <div class="key-box"><?php echo $key['api_key']; ?></div>
                        <div><?php echo htmlspecialchars($key['name']); ?></div>
                        <div><?php echo $key['status']; ?></div>
                        <div>
                            <form method="POST" action="dashboard.php" style="display: inline;">
                                <input type="hidden" name="key_id" value="<?php echo $key['id']; ?>">
                                <button type="submit" name="delete_key" class="btn" 
                                        onclick="return confirm('Are you sure you want to delete this API key?')">❌</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="key-item">
                    <span>No API keys found.</span>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
