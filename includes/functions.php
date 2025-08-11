<?php
function sanitizeInput($data) {
    return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function getAmountCentsForDuration(string $duration): int {
    $normalized = strtolower(trim($duration));
    if ($normalized === '3 months') {
        return 49900; // €499.00 in cents
    }
    if ($normalized === '6 months') {
        return 69900; // €699.00 in cents
    }
    return 0;
}

function getBadgeClassForPayment(string $paymentStatus): string {
    $paymentStatus = strtolower($paymentStatus);
    if ($paymentStatus === 'completed') {
        return 'bg-success';
    }
    if ($paymentStatus === 'failed') {
        return 'bg-danger';
    }
    return 'bg-warning'; // pending/default
}

function getBadgeClassForAdmin(string $adminStatus): string {
    $adminStatus = strtolower($adminStatus);
    if ($adminStatus === 'approved') {
        return 'bg-success';
    }
    if ($adminStatus === 'rejected') {
        return 'bg-danger';
    }
    if ($adminStatus === 'reviewed') {
        return 'bg-info';
    }
    return 'bg-secondary'; // pending/default
}

function getApplicationById(PDO $pdo, int $applicationId): ?array {
    $stmt = $pdo->prepare('SELECT * FROM applications WHERE id = ?');
    $stmt->execute([$applicationId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function updateApplication(PDO $pdo, int $applicationId, array $fields): bool {
    if (empty($fields)) {
        return false;
    }
    $setClauses = [];
    $values = [];
    foreach ($fields as $column => $value) {
        $setClauses[] = "$column = ?";
        $values[] = $value;
    }
    $values[] = $applicationId;
    $sql = 'UPDATE applications SET ' . implode(', ', $setClauses) . ' WHERE id = ?';
    $stmt = $pdo->prepare($sql);
    return $stmt->execute($values);
}

function sendMailSafely(string $to, string $subject, string $message, string $from = 'no-reply@intern-connect.local'): void {
    @mail($to, $subject, $message, 'From: ' . $from);
}
?>