
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Username</th>
             <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
             <th>User Role</th>
             <th>Admin</th>
             <th>Subscriber</th>
             <th>Edit</th>
             <th>Remove</th>
            
        </tr>
    </thead>
    <tbody>
       <?php
        $sql = "SELECT * FROM users";
        $select_user = execute($sql);
        while($row = mysqli_fetch_assoc($select_user)){
            $user_id         = $row['id'];
            $username        = $row['username'];
            $user_password   = $row['passwd'];
            $user_firstname  = $row['firstname'];
            $user_lastname   = $row['lastname'];
            $user_email      = $row['email'];           
            $user_role       = $row['user_role'];
            $user_image      = $row['user_image'];
            // $comment_date      = $row['comment_date'];
        ?>     
        <tr >      
            <td><?php echo $user_id ?></td>
            <td><?php echo $username ?></td>
            <td><?php echo $user_firstname ?></td>
            <td><?php echo $user_lastname ?></td>
            <td><?php echo $user_email ?></td>
            <td><?php echo $user_role ?></td>
             <td><a href="users.php?change_to_admin=<?php echo $user_id?>">Admin</a></td>
            <td><a href="users.php?change_to_sub=<?php echo $user_id ?>">Subscriber</a></td> 
             <td><a href="users.php?source=edit_user&edit_user=<?php echo $user_id ?>">Edit</a></td> 
            <td><a href="users.php?delete=<?php echo $user_id ?>">Remove</a></td> 
        </tr>
        <?php } ?> 

    </tbody> 
     
</table>        
    
<?php


//Approve   comment
 if(isset($_GET['change_to_admin'])){
    $the_user_id = $_GET['change_to_admin'];
    $sql = " UPDATE  users SET user_role ='admin' WHERE id= $the_user_id";
    $query =execute($sql);
    confirm($query);
    header('location: users.php');
    
}




//Unapprove   comment
 if(isset($_GET['change_to_sub'])){
    $the_user_id = $_GET['change_to_sub'];
    $sql         = " UPDATE  users SET user_role='subscriber' WHERE id = $the_user_id";
    $subscriber_select  = execute($sql);
    confirm($subscriber_select);
    header('location: users.php');

     
}






///DELETING POST FROM POST
if(isset($_GET['delete'])){
    ///VALIDATE THE CODE TO AVOID DELETION OF USE DATA
    if(isset($_SESSION['user_role'])){
        if($_SESSION['user_role'] == 'admin'){
            $the_user_id =escape($_GET['delete']);
            $sql = "DELETE FROM  users WHERE id='$the_user_id' ";
            $delet_user = execute($sql);
            confirm($delet_user);
            header('location: users.php');
        }
    }
}
?>

