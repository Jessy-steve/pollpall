<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Forgot Password</title>
        <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row" style="margin-top: 45px;">
                <div class="col-md-6 col-md-offset-3">
                    <h4>Forgot Password</h4><hr>
                    <?php if (session()->getFlashdata('failed')) : ?>
                        <div class="alert alert-danger"><?= session()->getFlashdata('failed') ?></div>
                    <?php endif; ?>
                    <?php if (session()->getFlashdata('success')) : ?>
                        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                    <?php endif; ?>
                    <form action="<?= base_url('auth/send_reset_link') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="<?= set_value('email') ?>">
                            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'email') : '' ?></span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Reset Password</button>
                        </div>
                        <a href="<?= site_url('/auth') ?>">Back to Login</a>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>