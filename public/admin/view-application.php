<?php
require_once __DIR__ . '/../../includes/bootstrap.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect('/admin/dashboard.php');
}

$id = (int)$_GET['id'];
$application = getApplicationById($pdo, $id);
if (!$application) {
    redirect('/admin/dashboard.php');
}
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Application Details</h2>
                <a href="/admin/dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Application #<?= $application['id'] ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Personal Information</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Full Name:</strong></td>
                                    <td><?= htmlspecialchars($application['full_name']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td><?= htmlspecialchars($application['email']) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td><?= htmlspecialchars($application['phone']) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Internship Details</h6>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Duration:</strong></td>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($application['duration']) ?></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <span class="badge <?= getBadgeClassForPayment($application['payment_status']) ?>"><?= ucfirst($application['payment_status']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Admin Status:</strong></td>
                                    <td>
                                        <span class="badge <?= getBadgeClassForAdmin($application['admin_status']) ?>"><?= ucfirst($application['admin_status']) ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Applied Date:</strong></td>
                                    <td><?= date('F d, Y \a\t g:i A', strtotime($application['created_at'])) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if ($application['cv_filename']): ?>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>CV/Resume</h6>
                            <p><strong>Filename:</strong> <?= htmlspecialchars($application['cv_filename']) ?></p>
                            <a href="/assets/uploads/<?= $application['cv_filename'] ?>" target="_blank" class="btn btn-outline-primary">
                                <i class="bi bi-download"></i> Download CV
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6>Actions</h6>
                            <div class="btn-group" role="group">
                                <a href="/admin/update-status.php?id=<?= $application['id'] ?>&status=reviewed" class="btn btn-success">Mark as Reviewed</a>
                                <a href="/admin/update-status.php?id=<?= $application['id'] ?>&status=approved" class="btn btn-primary">Approve Application</a>
                                <a href="/admin/update-status.php?id=<?= $application['id'] ?>&status=rejected" class="btn btn-danger">Reject Application</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>