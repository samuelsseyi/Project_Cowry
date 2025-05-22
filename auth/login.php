<?php
// PROJECT_COWRY/auth/login.php
require_once __DIR__ . '/../includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = $_POST['email'];
  $password = $_POST['password'];

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->execute([$email]);
  $user = $stmt->fetch();

  if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR']; // Add this
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT']; // Add this
    $_SESSION['created'] = time(); // Add this
    header("Location: ../dashboard.php");
    exit();
  } else {
    $error = "Invalid email or password";
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
            <h2 class="mb-4 text-center">Welcome Back</h2>

            <?php if ($error): ?>
              <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control py-3" id="email" name="email" required>
              </div>

              <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control py-3" id="password" name="password" required>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill">Log In</button>
            </form>

            <div class="text-center mt-4">
              <p class="text-muted">Don't have an account?
                <a href="../auth/register.php" class="text-primary text-decoration-none">Sign up</a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>