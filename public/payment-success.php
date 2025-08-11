<?php
require '../includes/bootstrap.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$applicationId = (int)$_GET['id'];
$sessionId = isset($_GET['session_id']) ? trim($_GET['session_id']) : '';

$stmt = $pdo->prepare('SELECT * FROM applications WHERE id = ?');
$stmt->execute([$applicationId]);
$application = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$application) {
    redirect('index.php');
}

$verifiedPaid = false;
$error = '';

if ($sessionId !== '' && defined('STRIPE_SECRET_KEY') && STRIPE_SECRET_KEY !== 'sk_test_your_secret_key') {
    $ch = curl_init('https://api.stripe.com/v1/checkout/sessions/' . urlencode($sessionId));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . STRIPE_SECRET_KEY
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        $error = 'Stripe connection error: ' . $curlErr;
    } else {
        $data = json_decode($response, true);
        if ($httpCode >= 200 && $httpCode < 300) {
            if (($data['payment_status'] ?? '') === 'paid') {
                $verifiedPaid = true;
            } else {
                $error = 'Payment not completed yet.';
            }
        } else {
            $error = 'Stripe error: HTTP ' . $httpCode . ' - ' . ($data['error']['message'] ?? 'Unknown error');
        }
    }
}

if ($verifiedPaid && $application['payment_status'] !== 'completed') {
    $upd = $pdo->prepare('UPDATE applications SET payment_status = ? WHERE id = ?');
    $upd->execute(['completed', $applicationId]);

    // Try to send confirmation email (may require SMTP configuration on server)
    $to = $application['email'];
    $subject = 'Intern Connect: Payment Confirmation';
    $message = "Hello " . $application['full_name'] . ",\n\nWe have received your payment for the Internship Program (" . $application['duration'] . ").\n\nThank you!\nIntern Connect";
    @mail($to, $subject, $message, 'From: no-reply@intern-connect.local');
}

include '../includes/header.php';
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <?php if ($verifiedPaid || $application['payment_status'] === 'completed'): ?>
                <div class="alert alert-success">
                    <h4 class="alert-heading">Payment Successful</h4>
                    <p>Thank you, <?= htmlspecialchars($application['full_name']) ?>. Your payment has been received.</p>
                </div>
                <a class="btn btn-primary" href="/">Back to Home</a>
                <a class="btn btn-secondary" href="/admin/dashboard.php">Go to Admin</a>
            <?php else: ?>
                <div class="alert alert-warning">
                    <h4 class="alert-heading">Awaiting Payment</h4>
                    <p><?= htmlspecialchars($error ?: 'We could not verify the payment. If you completed the payment, please wait a moment and refresh this page.') ?></p>
                </div>
                <a class="btn btn-outline-primary" href="/payment.php?id=<?= $applicationId ?>">Try Payment Again</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
