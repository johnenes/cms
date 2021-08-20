<?php require_once './includes/header.php';
require_once './includes/navbar.php'; ?>

<!-- <div class="container"> -->

<div class="row " style="padding:25px;">
    <div class="col-lg-6 col-lg-offset-3">
        <?php display_message();
        validate_user_registrations() ?>
    </div>

</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-login">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <a href="login.php">Login</a>
                    </div>
                    <div class="col-xs-6">
                        <a href="register.php" class="active" id="">Register</a>
                    </div>
                </div>
                <hr>

            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form id="register-form" method="post" role="form">
                            <div class="form-group">
                                <input type="text" name="firstname" id="firstname" tabindex="1" class="form-control" placeholder="First Name" value="<?php echo isset($firstname) ? $firstname : '' ?>" required autocomplete="on">
                            </div>


                            <div class="form-group">
                                <input type="text" name="lastname" id="lastname" tabindex="1" class="form-control" placeholder="Last Name" value="<?php echo isset($lastname) ? $lastname : '' ?>" required autocomplete="on">
                            </div>


                            <div class="form-group">
                                <input type="text" name="username" id="username" tabindex="1" class="form-control" placeholder="Username" value="<?php echo isset($username) ? $username : '' ?>" required autocomplete="on">
                            </div>


                            <div class="form-group">
                                <input type="email" name="email" id="register_email" tabindex="1" class="form-control" placeholder="Email Address" value="<?php echo isset($email) ? $email : '' ?>" required autocomplete="on">
                            </div>


                            <div class="form-group">
                                <input type="password" name="password" id="password" tabindex="2" class="form-control" placeholder="Password" required>
                            </div>


                            <div class="form-group">
                                <input type="password" name="confirm_password" id="confirm-password" tabindex="2" class="form-control" placeholder="Confirm Password" required>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 col-sm-offset-3">
                                        <input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Register Now">
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




<?php require_once './includes/footer.php'; ?>