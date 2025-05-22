<!-- PROJECT_COWRY/includes/sidebar.php -->
<div class="col-lg-3 mb-4">
  <div class="card border-0 shadow-sm h-100">
    <div class="card-body">
      <div class="d-flex flex-column gap-3">
        <div class="text-center mb-4">
          <img src="/Project_Cowry/assets/images/user-icon.png"
            class="rounded-circle"
            width="80"
            alt="Profile">
          <h5 class="mt-2 fw-bold"><?= $_SESSION['user_email'] ?></h5>
        </div>

        <nav class="nav flex-column gap-2">
          <a href="dashboard.php" class="nav-link text-dark">
            <i class="fas fa-home me-2"></i> Dashboard
          </a>
          <a href="transactions.php" class="nav-link text-dark">
            <i class="fas fa-exchange-alt me-2"></i> Transactions
          </a>
          <a href="profile.php" class="nav-link text-dark">
            <i class="fas fa-user me-2"></i> Profile
          </a>
          <a href="auth/logout.php" class="nav-link text-danger">
            <i class="fas fa-sign-out-alt me-2"></i> Log Out
          </a>
        </nav>
      </div>
    </div>
  </div>
</div>