<?php
require '../includes/bootstrap.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('index.php');
}

$applicationId = (int)$_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM applications WHERE id = ?');
$stmt->execute([$applicationId]);
$application = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$application) {
    redirect('index.php');
}

$amountCents = getAmountCentsForDuration($application['duration']);
if ($amountCents <= 0) {
    die('Invalid program duration for payment');
}

$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
$successUrl = $baseUrl . '/public/payment-success.php?session_id={CHECKOUT_SESSION_ID}&id=' . $applicationId;
$cancelUrl  = $baseUrl . '/public/payment.php?id=' . $applicationId;

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_checkout'])) {
    if (!defined('STRIPE_SECRET_KEY') || STRIPE_SECRET_KEY === 'sk_test_your_secret_key') {
        $error = 'Stripe secret key is not configured. Please set STRIPE_SECRET_KEY.';
    } else {
        $postFields = [
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types[]' => 'card',
            'line_items[0][price_data][currency]' => APP_CURRENCY,
            'line_items[0][price_data][product_data][name]' => 'Internship Program - ' . $application['duration'],
            'line_items[0][price_data][unit_amount]' => $amountCents,
            'line_items[0][quantity]' => 1,
            'metadata[application_id]' => (string)$applicationId,
            'metadata[email]' => $application['email']
        ];

        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . STRIPE_SECRET_KEY,
            'Content-Type: application/x-www-form-urlencoded'
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
            if ($httpCode >= 200 && $httpCode < 300 && isset($data['url'])) {
                header('Location: ' . $data['url']);
                exit;
            }
            $error = 'Stripe error: HTTP ' . $httpCode . ' - ' . ($data['error']['message'] ?? 'Unknown error');
        }
    }
}

include '../includes/header.php';
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Complete Your Payment</h2>
            <div class="card mt-3">
                <div class="card-body">
                    <p><strong>Name:</strong> <?= htmlspecialchars($application['full_name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($application['email']) ?></p>
                    <p><strong>Program:</strong> <?= htmlspecialchars($application['duration']) ?></p>
                    <p><strong>Amount:</strong> €<?= number_format($amountCents / 100, 2) ?></p>

                    <?php if ($application['payment_status'] === 'completed'): ?>
                        <div class="alert alert-success">Payment already completed for this application.</div>
                        <a class="btn btn-success" href="payment-success.php?id=<?= $applicationId ?>">View Confirmation</a>
                    <?php else: ?>
                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form method="post">
                            <button type="submit" name="create_checkout" class="btn btn-primary">Pay €<?= number_format($amountCents / 100, 2) ?> with Card (Test)</button>
                        </form>
                        <p class="text-muted mt-2">Use Stripe test cards, e.g., 4242 4242 4242 4242, any future date, any CVC.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
