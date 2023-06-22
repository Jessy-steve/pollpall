<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
</head>
<body>

<div class="container">
    <h2>Reset Password</h2>

    <?php echo form_open('auth/process_reset_password', ['class' => 'mt-4']); ?>

    <div class="mb-3">
        <label for="new_password" class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" id="new_password" required>
    </div>

    <div class="mb-3">
        <label for="confirm_password" class="form-label">Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
    </div>

    <button type="submit" class="btn btn-primary">Reset Password</button>

    <?php echo form_close(); ?>

</div>

</body>
</html>