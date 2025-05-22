<?php
// transactions/create.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';

// Initialize variables with default values
$title = '';
$target_amount = 0.00;
$target_date = date('Y-m-d', strtotime('+1 week'));
$frequency = 'weekly';
$description = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get and sanitize inputs
  $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $target_amount = filter_input(INPUT_POST, 'target_amount', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
  $target_date = filter_input(INPUT_POST, 'target_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $frequency = filter_input(INPUT_POST, 'frequency', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

  // Validation
  if (empty($title)) $errors[] = 'Plan title is required';
  if (!is_numeric($target_amount) || $target_amount <= 0) $errors[] = 'Invalid target amount';
  if (!strtotime($target_date)) $errors[] = 'Invalid target date';

  if (empty($errors)) {
    try {
      // Date calculations
      $today = new DateTime();
      $target = new DateTime($target_date);
      $interval = $today->diff($target);

      $frequency_days = [
        'daily' => 1,
        'weekly' => 7,
        'monthly' => 30
      ];

      // Calculate installments
      $installments = floor($interval->days / $frequency_days[$frequency]);
      $amount_per_installment = $installments > 0 ? $target_amount / $installments : 0;

      // Database operations

      $stmt = $pdo->prepare("INSERT INTO savings_plans 
    (user_id, title, target_amount, target_date, frequency, description, amount_per_installment)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

      $stmt->execute([
        $_SESSION['user_id'],
        $title,
        $target_amount,
        $target_date,
        $frequency,
        $description,
        round($amount_per_installment, 2)
      ]);

      $plan_id = $pdo->lastInsertId();

      // Create initial transaction
      $stmt = $pdo->prepare("
    INSERT INTO transactions 
    (user_id, plan_id, amount, type, description)
    VALUES (?, ?, ?, 'deposit', ?)
");
      $stmt->execute([
        $_SESSION['user_id'],
        $plan_id,
        round($amount_per_installment, 2),
        "Initial deposit for plan: " . htmlspecialchars($title)
      ]);

      // Update savings plan current amount
      $stmt = $pdo->prepare("
    UPDATE savings_plans 
    SET saved = saved + ? 
    WHERE id = ?
");
      $stmt->execute([round($amount_per_installment, 2), $plan_id]);

      header('Location: transactions.php?success=1');
      exit;
    



      header('Location: transactions.php?success=1');
      exit;
    } catch (PDOException $e) {
      $errors[] = "Database error: " . $e->getMessage();
    }
  }
}



include $_SERVER['DOCUMENT_ROOT'] . '/Project_Cowry/includes/userheader.php';
?>

<div class="container-fluid mt-5 py-5">
  <div class="row">
    <?php require_once __DIR__ . '/../includes/nav.php'; ?>

    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
          <h1 class="h3 fw-bold">Create New Savings Plan</h1>
          <p class="text-muted">Set up your financial goal</p>
        </div>
      </div>

      <!-- Error Messages -->
      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger mb-4">
          <?php foreach ($errors as $error): ?>
            <p class="mb-0"><?= htmlspecialchars($error) ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Creation Form -->
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form method="POST">
            <div class="mb-4">
              <label class="form-label">Plan Title</label>
              <input type="text" name="title" value="<?= htmlspecialchars($title) ?>" class="form-control form-control-lg" required>
            </div>

            <div class="row g-4 mb-4">
              <div class="col-md-6">
                <label class="form-label">Target Amount</label>
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" name="target_amount" value="<?= htmlspecialchars($target_amount) ?>" step="0.01" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Target Date</label>
                <input type="date" name="target_date" value="<?= htmlspecialchars($target_date) ?>" class="form-control"
                  min="<?= date('Y-m-d', strtotime('+1 week')) ?>"
                  required>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label">Savings Frequency</label>
              <select name="frequency" class="form-select" required>
                <option value="daily" <?= $frequency === 'daily' ? 'selected' : '' ?>>Daily</option>
                <option value="weekly" <?= $frequency === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                <option value="monthly" <?= $frequency === 'monthly' ? 'selected' : '' ?>>Monthly</option>
              </select>
            </div>

            <div class="mb-4">
              <label class="form-label">Description (Optional)</label>
              <textarea name="description" <?= htmlspecialchars($description) ?> class="form-control" rows="3"></textarea>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                Create Savings Plan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/Project_Cowry/includes/footer.php'; ?>