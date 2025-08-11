<?php
require_once __DIR__ . '/../../includes/bootstrap.php';

$applications = $pdo->query("SELECT * FROM applications ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<div class="container mt-4">
    <h2>Admin Dashboard - Applications</h2>
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>Total Applications:</strong> <?= count($applications) ?>
            </div>
        </div>
    </div>
    
    <?php if (empty($applications)): ?>
        <div class="alert alert-warning">
            <h4>No applications found</h4>
            <p>There are currently no internship applications in the system.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Duration</th>
                        <th>Payment Status</th>
                        <th>Admin Status</th>
                        <th>Applied Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($applications as $app): ?>
                    <tr>
                        <td><?= $app['id'] ?></td>
                        <td><?= htmlspecialchars($app['full_name']) ?></td>
                        <td><?= htmlspecialchars($app['email']) ?></td>
                        <td><?= htmlspecialchars($app['phone']) ?></td>
                        <td><span class="badge bg-primary"><?= htmlspecialchars($app['duration']) ?></span></td>
                        <td>
                            <span class="badge <?= getBadgeClassForPayment($app['payment_status']) ?>"><?= ucfirst($app['payment_status']) ?></span>
                        </td>
                        <td>
                            <span class="badge <?= getBadgeClassForAdmin($app['admin_status']) ?>"><?= ucfirst($app['admin_status']) ?></span>
                        </td>
                        <td><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/admin/view-application.php?id=<?= $app['id'] ?>" class="btn btn-info btn-sm">View</a>
                                <a href="/admin/update-status.php?id=<?= $app['id'] ?>&status=reviewed" class="btn btn-success btn-sm">Review</a>
                                <a href="/admin/update-status.php?id=<?= $app['id'] ?>&status=approved" class="btn btn-primary btn-sm">Approve</a>
                                <a href="/admin/update-status.php?id=<?= $app['id'] ?>&status=rejected" class="btn btn-danger btn-sm">Reject</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>