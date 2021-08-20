     <?php
require_once'./config/users.php';
validate_user_registrations();
    ?>
 <div class="col-xs-6">
     <form action="" method="post" enctype="multipart/form-data">
         <div class="form-group">
             <label for="firstname">First Name</label>
             <input type="text" name="firstname" class="form-control">
         </div>

         <div class="form-group">
             <label for=" lastname">Last Name</label>
             <input type="text" name="lastname" class="form-control">
         </div>

         <div class="form-group">
             <select name="user_role" class="form-control">
                 <option value="subscriber">Select options</option>
                 <option value="admin">Admin</option>
                 <option value="subscriber">Subscriber</option>
             </select>
         </div>

         <div class="form-group">
             <label for="username">Username</label>
             <input type="text" class="form-control" name="username">
         </div>

         <div class="form-group">
             <label for="email">Email</label>
             <input type="text" class="form-control" name="email">
         </div>

         <div class="form-group">
             <label for="password">Password</label>
             <input type="password" name="password" class="form-control">
         </div>
         <div class="form-group">
             <input type="submit" name="create_user" value="Add User Account" class="btn btn-primary">
         </div>

     </form>
 </div>