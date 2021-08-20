 <?php require_once './includes/header.php'; ?>
 <!-- Navigation -->
 <?php require_once './includes/navbar.php'; ?>

 <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
 <?php require_once './includes/sidebar.php'; ?>


<div id="page-wrapper">
<div class="container-fluid">
<!-- Page Heading -->
<div class="row">
<div class="col-lg-12">
   
   
    <h1 class="page-header text-center">
        User Profile 
    </h1>


<?php
if(isset($_GET['email'])){
    $email            = $_GET['email'];

    $sql = "SELECT * FROM users";
    $result = execute($sql);
    foreach ($result as $key => $value) {
        $firstname = $value['firstname'];
        $lastname  = $value['lastname'];
        $username   = $value['username'];
        $email     = $value['email'];
        $password  = $value['passwd'];
        
    }
}
?>



<?php
if(isset($_POST['update_profile'])){
    $firstname = $_POST['firstname'];
    $lastname  = $_POST['lastname'];
    $username = $_POST['username'];
    $email         = $_POST['email'];
    $password  = $_POST['password'];
    $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));
   
    $sql = "UPDATE users SET username='$username',passwd='$password',firstname='$firstname',lastname='$lastname',email='$email' WHERE username='$username' AND email='$email' ";
    $result = execute($sql);
    confirm($result);
 
        echo   "<h3 text-center>Your profile is Successfully Updated!</h3>";
    }
?>

<div class="">
    <form action="" method = "post" enctype="multipart/form-data">
        <div class="form-group">
         <label for="firstname">First Name</label>
         <input type="text" value="<?php echo $firstname ?>" name="firstname" class="form-control">
        </div>

        <div class="form-group">
         <label for=" lastname">Last Name</label>
         <input type="text" value="<?php echo $lastname ?>" name="lastname" class="form-control">
        </div>
    
        
        <div class="form-group">
         <label for="username">Username</label>
         <input type="text" value="<?php echo $username ?>" class="form-control" name="username">
        </div> 
         
        <div class="form-group">
         <label for="email">Email</label>
         <input type="text" value="<?php echo $email ?>" class="form-control" name="email">
        </div> 
         
        <div class="form-group">
         <label for="password">Password</label>
         <input type="password" name="password"   class="form-control" autocomplete="off">
        </div>

        <div class="form-group">
        <input type="submit" name="update_profile" value="Update Profile" class="btn btn-primary">
    </div>
   </form>
</div>
</div>
</div>
<!-- /.row -->
</div>
<!-- /.container-fluid -->
</div>

<?php include  'includes/footer.php';?>
    