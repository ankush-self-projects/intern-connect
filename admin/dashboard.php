<?php
require '../config/config.php';
$applications = $pdo->query("SELECT * FROM applications ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container mt-4">
    <h2>Applications</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Duration</th><th>Payment</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($applications as $app): ?>
            <tr>
                <td><?= $app['full_name'] ?></td>
                <td><?= $app['email'] ?></td>
                <td><?= $app['duration'] ?></td>
                <td><?= ucfirst($app['payment_status']) ?></td>
                <td><?= ucfirst($app['admin_status']) ?></td>
                <td>
                    <a href="view_application.php?id=<?= $app['id'] ?>" class="btn btn-info btn-sm">View</a>
                    <a href="update_status.php?id=<?= $app['id'] ?>&status=reviewed" class="btn btn-success btn-sm">Mark Reviewed</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>