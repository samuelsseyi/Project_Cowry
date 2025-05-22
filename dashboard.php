<?php
require_once 'includes/config.php';
require_once 'includes/userheader.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Get user data for sidebar
$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Get financial data
$stmt = $pdo->prepare(" SELECT COUNT(id) AS total_plans, SUM(target_amount) AS total_goals, SUM(amount_per_installment) AS per_installment_total
    FROM savings_plans 
    WHERE user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$total_savings = $stmt->fetch()['total_savings'] ?? 0;

$stmt = $pdo->prepare("SELECT * FROM savings_plans WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$plans = $stmt->fetchAll();

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>

<main class="bg-backdrop-gray min-vh-100">
    <div class="container py-5">
        <!-- New Dashboard Layout with Sidebar -->
        <div class="row g-4">
            <!-- Sidebar Column -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex flex-column gap-3">
                            <!-- User Profile Section -->
                            <div class="text-center mb-4">
                                <img src="/Project_Cowry/assets/images/user-icon.png"
                                    class="rounded-circle"
                                    width="80"
                                    alt="Profile">
                                <h5 class="mt-2 fw-bold"><?= htmlspecialchars($user['email']) ?></h5>
                            </div>

                            <!-- Navigation Menu -->
                            <nav class="nav flex-column gap-2">
                                <a href="dashboard.php" class="nav-link text-dark active">
                                    <i class="fas fa-home me-2"></i> Dashboard
                                </a>
                                <a href="transactions/transactions.php" class="nav-link text-dark">
                                    <i class="fas fa-exchange-alt me-2"></i> Transactions
                                </a>
                                <a href="auth/profile.php" class="nav-link text-dark">
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

            <!-- Main Content Column -->
            <div class="col-lg-9">
                <!-- Existing Dashboard Content -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h1 class="h3 fw-bold">Savings Dashboard</h1>
                        <p class="text-muted">Welcome back! Here's your financial overview</p>
                    </div>
                    <a href="transactions/create.php" class="btn btn-primary px-4 py-3 rounded-pill">
                        + Create New Plan
                    </a>
                </div>

                <!-- Rest of your existing dashboard content -->
                <!-- ... (keep the summary cards, savings plans, and transactions sections) ... -->

            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>