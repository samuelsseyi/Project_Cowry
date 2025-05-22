<!-- Navigation Menu -->
<div class="col-lg-3">
    <div class="sticky-top" style="top: 20px;">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <nav class="nav flex-column gap-2">
                    <a href="../dashboard.php" class="nav-link text-dark <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">
                        <i class="fas fa-home me-2"></i> Dashboard
                    </a>
                    <a href="../transactions/transactions.php" class="nav-link text-dark <?= basename($_SERVER['PHP_SELF']) == 'transactions.php' ? 'active' : '' ?>">
                        <i class="fas fa-exchange-alt me-2"></i> Transactions
                    </a>
                    <a href="../auth/profile.php" class="nav-link text-dark <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">
                        <i class="fas fa-user me-2"></i> Profile
                    </a>
                    <a href="../auth/logout.php" class="nav-link text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Log Out
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>