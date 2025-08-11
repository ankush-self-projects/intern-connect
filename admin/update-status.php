<?php
require '../config/config.php';
require '../includes/functions.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['status'])) {
    redirect('dashboard.php');
}

$id = (int)$_GET['id'];
$status = sanitizeInput($_GET['status']);

// Validate status
$valid_statuses = ['pending', 'reviewed', 'approved', 'rejected'];
if (!in_array($status, $valid_statuses)) {
    redirect('dashboard.php');
}

try {
    $stmt = $pdo->prepare("UPDATE applications SET admin_status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    
    if ($stmt->rowCount() > 0) {
        $message = "Application status updated successfully to: " . ucfirst($status);
        $alert_class = "alert-success";
    } else {
        $message = "Application not found or no changes made.";
        $alert_class = "alert-warning";
    }
} catch (PDOException $e) {
    $message = "Error updating application status: " . $e->getMessage();
    $alert_class = "alert-danger";
}
?>

<?php include '../includes/header.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Update Application Status</h5>
                </div>
                <div class="card-body">
                    <div class="alert <?= $alert_class ?>" role="alert">
                        <?= htmlspecialchars($message) ?>
                    </div>
                    
                    <div class="text-center">
                        <a href="dashboard.php" class="btn btn-primary">← Back to Dashboard</a>
                        <a href="view-application.php?id=<?= $id ?>" class="btn btn-info">View Application</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
