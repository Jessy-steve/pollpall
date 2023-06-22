<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intitial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie-edge">
        <title>Sign In</title>
        <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="<?php echo base_url(); ?>public/js/script.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="row" style="margin-top:45px">
                <div class="col-md-6 col-md-offset-4">
                    <h4>Sign In</h4><hr>
                    <form action="<?= base_url('auth/check'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <?php if(!empty(session()->getFlashdata('failed'))) : ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('failed'); ?></div>
                        <?php endif ?>

                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Enter Your Email" value="<?= set_value('email'); ?>">
                            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'email') : '' ?></span>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Enter Password" value="<?= set_value('password'); ?>">
                                <div class="input-group-text" style="margin-left: 10px; cursor: pointer;">
                                    <input class="form-check-input" type="checkbox" id="show-password">
                                    <label class="form-check-label" for="show-password">Show Password</label>
                                </div>
                            </div>
                            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'password') : '' ?></span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Sign In</button>
                        </div>
                        <br>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe" <?= set_checkbox('rememberMe', '1', false) ?>>
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <div class="form-group">
                            <a href="<?= site_url('auth/forgot_password') ?>">Forgot password?</a>
                            <a href="<?= site_url('auth/register') ?>">Have no account, Register Here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const passwordField = document.getElementById('password');
            const showPassword = document.getElementById('show-password');

            showPassword.addEventListener('change', function() {
                if (showPassword.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            });
        </script>

    </body>
</html>