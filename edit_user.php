<?php
if(isset($_GET['edit_user'])){

    $id = $_GET['edit_user'];




$sql = "SELECT * FROM users ";
    $result = execute($sql);
    confirm($sql);
    
    while($row       =  mysqli_fetch_assoc($result)){
        $id                 = $row['id'];
        $username    = $row['username'];
        $firstname    = $row['firstname'];
        $lastname     = $row['lastname'];
        $email           = $row['email'];
        $passowrd    = $row['passwd'];
    }

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $username            = trim($_POST['username']);
        $password            = trim($_POST['password']);
        $firstname           = trim($_POST['firstname']);
        $lastname            = trim($_POST['lastname']);
        $email                  = trim($_POST['email']);
        $username           = escape($username);
        $password           = escape($password);
        $firstname           = escape($firstname);
        $lastname            = escape($lastname);
        $email                  = escape($email);

        $sql = "SELECT passwd FROM users WHERE id = $id ";
        $query_password = execute($sql);
        confirm($query_password);
        $row  = mysqli_fetch_array($query_password); 
        $db_password     = $row['passwd'];
        if($db_password != $password){
                    $password = password_hash($password, PASSWORD_BCRYPT,array('cost' =>10));
        }
                $sql  = " UPDATE users SET username='$username', passwd='$password', firstname='$firstname',lastname='$lastname',email='$email'  WHERE id ='$   id' ";
                         $result = execute($sql);
                         confirm($result);
       
                echo "<h4 class='text-center text-success'>Your account updated !</h4>";
        
    }

}
else{
        header("Location: ../index.php");
}

    
?>













<div class="col-xs-6">
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
 <input type="password" name="password" class="form-control" autocomplete="off">
</div>

<div class="form-group">
<input type="submit" name="edit_user" value="Edit User" class="btn btn-primary">
</div>
</form>
</div>

 
