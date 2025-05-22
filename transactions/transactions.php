<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/userheader.php';

// Fetch transactions with plan information
$stmt = $pdo->prepare("
    SELECT t.*, s.title AS plan_title 
    FROM transactions t
    LEFT JOIN savings_plans s ON t.plan_id = s.id
    WHERE t.user_id = ?
    ORDER BY t.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>

<div class="container-fluid mt-5">
  <div class="row">
    <?php include(__DIR__ . '/../includes/nav.php'); ?>

    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
          <h1 class="h3 fw-bold">Transaction History</h1>
          <p class="text-muted">Your recent financial transactions</p>
        </div>
        <a href="create.php" class="btn btn-primary px-4 py-3 rounded-pill">
          + New Transaction
        </a>
      </div>

      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <?php if (empty($transactions)): ?>
            <p class="text-muted mb-0">No transactions yet. <a href="create.php" class="text-primary">Create a new plan</a></p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-hover align-middle">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Plan</th>
                    <th>Description</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($transactions as $txn): ?>
                    <tr>
                      <td><?= date('M j, Y', strtotime($txn['created_at'])) ?></td>
                      <td class="<?= $txn['type'] === 'deposit' ? 'text-success' : 'text-danger' ?>">
                        ₦<?= number_format($txn['amount'], 2) ?>
                      </td>
                      <td><?= ucfirst($txn['type']) ?></td>
                      <td><?= $txn['plan_title'] ?? 'General' ?></td>
                      <td><?= htmlspecialchars($txn['description']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include(__DIR__ . '/../includes/footer.php'); ?>