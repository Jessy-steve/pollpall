<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, intitial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie-edge">
        <title>Sign Up</title>
        <link rel="stylesheet" href="<?= base_url('bootstrap/css/bootstrap.min.css') ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row" style="margin-top:45px">
                <div class="col-md-6 col-md-offset-4">
                    <h4>Sign Up</h4><hr>
                    <form action="<?= base_url('auth/save'); ?>" method="post">
                        <?= csrf_field(); ?>
                        <?php if(!empty(session()->getFlashdata('failed'))) : ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('failed'); ?></div>
                        <?php endif ?>


                        <?php if(!empty(session()->getFlashdata('success'))) : ?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
                        <?php endif ?>

                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Your Full Name" value="<?= set_value('name'); ?>">
                            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'name') : '' ?></span>
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input type="text" class="form-control" name="email" placeholder="Enter Your Email" value="<?= set_value('email'); ?>">
                            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'email') : '' ?></span>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter Password"  value="<?= set_value('password'); ?>">
                            <span class="pass-eye"> <i class="fa-solid fa-eye" id="show-password"></i> </span>
                            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'password') : '' ?></span>
                        </div>
                        <div class="form-group">
                            <label for="">Confirm Password</label>
                            <input type="password" class="form-control" name="cpassword" placeholder="Confirm Your Password" value="<?= set_value('cpassword'); ?>">
                            <span class="cpass-eye"> <i class="fa-solid fa-eye" id="show-password"></i> </span>
                            <span class="text-danger"><?= isset($validation) ? display_error($validation, 'cpassword') : '' ?></span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary btn-block" type="submit">Sign Up</button>
                        </div>
                        <br>
                        <a href="<?= site_url('auth') ?>"> I already have an account, Login Now </a>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>