 <!-- <div class='jumbotron jumbotron-fluid text-center  ' style="background-color: #ecf0f1"> -->

    <div class="container">
        <?php display_message();?>         
    </div>
<!-- </div> -->
 
 
 <?php

if(isset($_GET['p_id'])){
    $get_post_id = $_GET['p_id'];

   $sql = "SELECT * FROM posts WHERE post_id = $get_post_id";
   $select_post_by_id = execute($sql);
   confirm($select_post_by_id);
   foreach ($select_post_by_id as $key => $row) {
       $post_id                    = $row['post_id'];
       $post_title                 = $row['post_title'];
       $post_user                = $row['post_user'];
        $post_image            = $row['post_image'];
        $post_content          = $row['post_content'];
       $post_status              = $row['post_status'];
       $post_tags                 = $row['post_tags'];
       $post_id                    = $row['post_status'];
       $email                       = $row['post_email'];
       }



       if(isset($_POST['submit'])){
           $post_title                  = $_POST['post_title'];
           $post_user                 = $_POST['post_user'];
           $post_categories       = $_POST['categories_post'];
           $post_content            = $_POST['post_content'];
           $post_image               = $_FILES['post_image']['name'];//Destination
           $post_image_temp    = $_FILES['post_image']['tmp_name'];
           $post_status               = $_POST['post_status'];
           $post_tags                 = $_POST['post_tags'];

           $post_date                 = date('y-m-d');
           $post_comment_count      = '4';
          move_uploaded_file($post_image_temp,"../images/$post_image");

       if(empty($post_image)){
           $select_image = "SELECT post_image FROM posts WHERE post_id=$get_post_id";
           $send_select_image =execute($select_image);
           confirm($send_select_image); 
           foreach ($send_select_image as $key => $value) {
               $post_image = $value['post_image'];
           }
       }

        $sql ="UPDATE  posts SET  post_category_id='$post_categories',";
        $sql.="post_title='$post_title',post_user='$post_user',";
        $sql.="post_date='$post_date',post_image='$post_image',";
        $sql.="post_content='$post_content',post_tags='$post_tags',";
        $sql.="post_comment_count=$post_comment_count,";
        $sql.="post_status='$post_status'  WHERE post_id=$get_post_id ";
        $result= execute($sql);
        confirm($result);
        set_message("<p class='bg-success'>Post Updated <a href='../?post.php?p_id=$get_post_id'>
            View Post</a>  or <a href='posts.php' class='py-2'>Edit more Post</a>");
        }
}
?>
<!-- EDIT PAGE --> 

    <h3>Update Post</h3>
<form action="" method="post" accept-charset="utf-8" enctype="multipart/form-data">

<div class='form-group'>
<input type="text" name="post_title" class="form-control" 
value="<?php echo $post_title;?>" placeholder="Post Title" value=""> 
</div>
 

<div class="form-group">
   <label for="users">Users</label>
       <select name="post_user" id="post_user" class="form-control">
       <?php  //echo "<option value='$post_user'>$post_user</option>";?>
        <?php //
           $sql_user = "SELECT * FROM users ";
           $query_users =  execute($sql_user);
          confirm($query_users);
           while ($row = mysqli_fetch_assoc($query_users)) {
               $user_id  = $row['user_id'];
               $username = $row['username'];
               echo "<option value='$username'>$username</option>";
           }
        ?>
       </select>
</div>




<div class="form-group" .>
   <label for="">Categories</label>
<select name="categories_post" class="form-control">
<?php
   $sql = "SELECT * FROM categories";
   $result = execute($sql);
    confirm($result);
       while ($row = mysqli_fetch_assoc($result)) {
           $cat_id = $row['cat_id'];
           $cat_title = $row['cat_title'];
           if(row_count($result) == 1){
               echo "<option  selected value='$cat_id'>$cat_title</option>";
        }else{
           echo"<option value=' $cat_id'>$cat_title</option>";
       }  }  ?>
</select>
</div>

<div class="form-group">
 <textarea name="post_content" id="editor" rows='10' cols='65' class='form-control'  ><?php echo str_replace('\r\n', '</br>', $post_content);?> </textarea>
</div> 


<div class="form-group">
<label for="post_image">Post image</label>
<input type="file" name="post_image" >
<img class="img-responsive" style="width:300px; " src="../images/<?php echo $post_image;?>" alt="">
</div>

<div class="form-group ">
   <label for="post_status" >Post Status</label>

   <select name='post_status' class="form-control">
   <option  value='<?php echo $post_status?>'><?php echo $post_status;?></option>
 <?php
           if($post_status == 'published'){
               echo "<option value='draft'>Draft</option>";
           }else{
               echo "<option value='published'>Published</option>";
           }
       ?>
   </select>
</div>
 
<div class="form-group">
<input type="text" name="post_tags" class="form-control" value="<?php echo $post_tags;?>" placeholder="Post Tags" autocomplete="off"> 
</div>
<input type="submit" name="submit" value="Add Post" class='btn btn-primary form-control'>
</form>
 
 
