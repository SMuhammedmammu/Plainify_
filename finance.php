<?php
// finance.php
session_start();
require_once 'db.php'; // your DB connection

// Basic login guard
if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    header("Location: user.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];

// CSRF token helper
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf_token'];

$notice = '';
$error = '';

// Ensure finance row exists for user
$stmt = $conn->prepare("SELECT id, balance FROM user_finances WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$fin = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$fin) {
    // create with zero balance
    $ins = $conn->prepare("INSERT INTO user_finances (user_id, balance) VALUES (?, 0)");
    $ins->bind_param("i", $user_id);
    $ins->execute();
    $ins->close();
    $fin = ['id' => $conn->insert_id, 'balance' => 0.00];
}

// Handle POST actions: add credit, request withdrawal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // basic CSRF check
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($csrf, $token)) {
        $error = "Invalid request (CSRF).";
    } else {
        if (isset($_POST['add_credit'])) {
            $amount = (float)str_replace(',', '', ($_POST['amount'] ?? '0'));
            $description = trim($_POST['description'] ?? 'Wallet top-up');
            if ($amount <= 0) {
                $error = "Enter a valid amount.";
            } else {
                // update balance and record transaction
                $conn->begin_transaction();
                try {
                    $upd = $conn->prepare("UPDATE user_finances SET balance = balance + ? WHERE user_id = ?");
                    $upd->bind_param("di", $amount, $user_id);
                    $upd->execute();
                    $upd->close();

                    $ins = $conn->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'credit', ?, ?)");
                    $ins->bind_param("ids", $user_id, $amount, $description);
                    $ins->execute();
                    $ins->close();

                    $conn->commit();
                    $notice = "Credit added: ₹" . number_format($amount, 2);
                } catch (Exception $e) {
                    $conn->rollback();
                    $error = "Failed to add credit. Try again.";
                }
            }
        } elseif (isset($_POST['request_withdrawal'])) {
            $amount = (float)str_replace(',', '', ($_POST['withdraw_amount'] ?? '0'));
            $note = trim($_POST['withdraw_note'] ?? '');
            if ($amount <= 0) {
                $error = "Enter a valid withdrawal amount.";
            } elseif ($amount > (float)$fin['balance']) {
                $error = "Insufficient balance for this withdrawal.";
            } else {
                // create withdrawal request and deduct immediately (simple model)
                $conn->begin_transaction();
                try {
                    $insW = $conn->prepare("INSERT INTO withdrawals (user_id, amount, status) VALUES (?, ?, 'pending')");
                    $insW->bind_param("id", $user_id, $amount);
                    $insW->execute();
                    $insW->close();

                    $upd = $conn->prepare("UPDATE user_finances SET balance = balance - ? WHERE user_id = ?");
                    $upd->bind_param("di", $amount, $user_id);
                    $upd->execute();
                    $upd->close();

                    $insT = $conn->prepare("INSERT INTO transactions (user_id, type, amount, description) VALUES (?, 'debit', ?, ?)");
                    $insT->bind_param("ids", $user_id, $amount, $note ?: 'Withdrawal request');
                    $insT->execute();
                    $insT->close();

                    $conn->commit();
                    $notice = "Withdrawal requested: ₹" . number_format($amount, 2) . " (status: pending)";
                } catch (Exception $e) {
                    $conn->rollback();
                    $error = "Failed to create withdrawal request.";
                }
            }
        }
    }

    // refresh finance row after change
    $stmt = $conn->prepare("SELECT id, balance FROM user_finances WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $fin = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// Fetch recent transactions (latest 50)
$stmt = $conn->prepare("SELECT id, type, amount, description, created_at FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result();
$stmt->close();

// Fetch user withdrawal requests (latest 10)
$stmt = $conn->prepare("SELECT id, amount, status, created_at FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$withdrawals = $stmt->get_result();
$stmt->close();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Finance - Planify</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <style>
    .card-quick { border-radius:10px; box-shadow:0 6px 16px rgba(16,24,40,0.06); }
    .tx-credit { color:#10b981; font-weight:700; }
    .tx-debit { color:#ef4444; font-weight:700; }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<main id="page-content">
  <div class="d-flex justify-content-between align-items-start mb-3">
    <div>
      <h2 style="color:#0a4f9a">Finance</h2>
      <div style="color:#334155;">Manage your wallet, view transactions and request withdrawals.</div>
    </div>
  </div>

  <?php if ($notice): ?><div class="notice-success"><?php echo htmlspecialchars($notice); ?></div><?php endif; ?>
  <?php if ($error): ?><div class="notice-err"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>

  <div class="row g-3 mb-4">
    <div class="col-md-6">
      <div class="card card-quick p-3">
        <small class="text-muted">Current Balance</small>
        <div style="font-size:1.8rem; font-weight:800;">₹<?php echo number_format((float)$fin['balance'], 2); ?></div>
        <div class="mt-2">
          <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#addCreditForm">Add Credit</button>
          <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#withdrawForm">Request Withdrawal</button>
        </div>

        <!-- Add credit form -->
        <div id="addCreditForm" class="collapse mt-3">
          <form method="post" class="row g-2">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
            <div class="col-12">
              <label class="form-label">Amount (₹)</label>
              <input name="amount" type="number" step="0.01" min="0.01" class="form-control" placeholder="100.00" required>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <input name="description" type="text" maxlength="255" class="form-control" placeholder="Top up for courses (optional)">
            </div>
            <div class="col-12">
              <button name="add_credit" class="btn btn-primary">Add Credit</button>
            </div>
          </form>
        </div>

        <!-- Withdraw form -->
        <div id="withdrawForm" class="collapse mt-3">
          <form method="post" class="row g-2">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
            <div class="col-12">
              <label class="form-label">Amount (₹)</label>
              <input name="withdraw_amount" type="number" step="0.01" min="1" class="form-control" placeholder="50.00" required>
            </div>
            <div class="col-12">
              <label class="form-label">Note (optional)</label>
              <input name="withdraw_note" type="text" maxlength="255" class="form-control" placeholder="Bank transfer to ...">
            </div>
            <div class="col-12">
              <button name="request_withdrawal" class="btn btn-danger">Request Withdrawal</button>
            </div>
          </form>
        </div>

      </div>
    </div>

    <div class="col-md-6">
      <div class="card p-3">
        <h6>Recent Withdrawal Requests</h6>
        <?php if ($withdrawals->num_rows > 0): ?>
          <ul class="list-group list-group-flush">
            <?php while ($w = $withdrawals->fetch_assoc()): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  ₹<?php echo number_format($w['amount'],2); ?> <small class="text-muted">on <?php echo $w['created_at']; ?></small>
                </div>
                <div>
                  <?php if ($w['status'] === 'pending'): ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                  <?php elseif ($w['status'] === 'approved'): ?>
                    <span class="badge bg-success">Approved</span>
                  <?php else: ?>
                    <span class="badge bg-secondary">Rejected</span>
                  <?php endif; ?>
                </div>
              </li>
            <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <div>No withdrawal requests yet.</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Transactions table -->
  <div class="card p-3">
    <h5>Recent Transactions</h5>
    <?php if ($transactions->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr><th>#</th><th>Type</th><th>Amount</th><th>Description</th><th>Date</th></tr>
          </thead>
          <tbody>
            <?php $i=0; while ($t = $transactions->fetch_assoc()): $i++; ?>
              <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo htmlspecialchars(ucfirst($t['type'])); ?></td>
                <td>
                  <?php if ($t['type'] === 'credit'): ?>
                    <span class="tx-credit">+ ₹<?php echo number_format($t['amount'],2); ?></span>
                  <?php else: ?>
                    <span class="tx-debit">- ₹<?php echo number_format($t['amount'],2); ?></span>
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($t['description']); ?></td>
                <td><?php echo $t['created_at']; ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div>No recent transactions.</div>
    <?php endif; ?>
  </div>

</main>

</body>
</html>
