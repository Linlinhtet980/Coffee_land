<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Coffee Land</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="<?= ASSET_URL ?>/css/auth/auth.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <i class="fa-solid fa-mug-hot"></i>
                <h1>Welcome Back</h1>
                <p>Login to your Coffee Land account</p>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-msg">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['msg']) && $_GET['msg'] === 'registered'): ?>
                <div class="success-msg">
                    <i class="fas fa-check-circle"></i>
                    Account created successfully! Please login.
                </div>
            <?php endif; ?>

            <form action="<?= APP_URL ?>/login" method="POST">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="auth-btn">Login Now</button>
            </form>

            <div class="auth-footer">
                Don't have an account? <a href="<?= APP_URL ?>/register">Register</a>
                <br><br>
                <a href="<?= APP_URL ?>/home" style="font-size: 0.8rem; color: var(--text-dim);"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
