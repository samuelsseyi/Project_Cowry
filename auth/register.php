<?php
// PROJECT_COWRY/auth/register.php
require_once __DIR__ . '/../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validation
    if ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        
        if ($stmt->rowCount() > 0) {
            $error = "Email already registered";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
            if ($stmt->execute([$email, $hashed_password])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                header("Location: ../dashboard.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<main class="min-vh-100 py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg">
          <div class="card-body p-4">
            <h2 class="mb-4 text-center">Get Started</h2>
            
            <?php if ($error): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control py-3" id="email" name="email" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
              </div>
              
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control py-3" id="password" name="password" required>
              </div>

              <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control py-3" id="confirm_password" name="confirm_password" required>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill">Create Account</button>
            </form>

            <div class="text-center mt-4">
              <p class="text-muted">Already have an account? 
                <a href="login.php" class="text-primary text-decoration-none">Log in</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>