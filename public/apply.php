<?php include '../includes/header.php'; ?>
<div class="container mt-4">
    <h2>Internship Application</h2>
    <form action="process_application.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="full_name" placeholder="Full Name" class="form-control mb-2" required>
        <input type="email" name="email" placeholder="Email" class="form-control mb-2" required>
        <input type="text" name="phone" placeholder="Phone" class="form-control mb-2" required>
        <select name="duration" class="form-control mb-2" required>
            <option value="">Select Duration</option>
            <option value="3 months">3 Months (€499)</option>
            <option value="6 months">6 Months (€699)</option>
        </select>
        <input type="file" name="cv" class="form-control mb-2" accept=".pdf" required>
        <button type="submit" class="btn btn-primary">Submit & Proceed to Payment</button>
    </form>
</div>
<?php include '../includes/footer.php'; ?>