  <?php
display_message();

    $sql = "SELECT * FROM posts ";
    $result_post_by_id = execute($sql);

    while ($row = mysqli_fetch_assoc($result_post_by_id)) {
        $post_user = $row['post_user'];
    }
    if (isset($_POST['create_post'])) {
        $post_category_id = $_POST['post_category'];
        $post_title       = $_POST['title'];
        $post_user       = $_POST['post_user'];
        $post_status      = $_POST['post_status'];

        $post_image       = $_FILES['post_image']['name']; //Destination
        $post_image_temp  = $_FILES['post_image']['tmp_name']; //FileName

        $post_tags        = $_POST['post_tags'];
        $post_content      = $_POST['post_content'];
        $post_date       = date('d-m-y');

        // $post_comment_count = '';

        ///CREATING FUNCTION FOR IMAGE UPLOAD
        move_uploaded_file($post_image_temp, "../images/$post_image");
        $sql = "INSERT INTO posts(post_category_id,post_title,post_user,";
        $sql .= "post_date,post_image,post_content,post_tags , ";
        $sql .=  "post_status)";
        $sql .= "VALUES($post_category_id, '$post_title','$post_user','$post_date','$post_image','$post_content','$post_tags','$post_status')";
        $result = execute($sql);
        confirm($result);
        $get_post_id = mysqli_insert_id($dataBaseConnection);
        echo "<h4 class='bg-success'> Post created Successful! " . " <a href='../post.php?p_id=$get_post_id'>View Add User</a><h4>";
    }

    ?>


  <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
          <label for="title">Post Title</label>
          <input type="text" class="form-control" name="title">
      </div>

      <div class="form-group">
          <label for="categories">categories</label>

          <select name="post_category" id="post_category" class="form-control">
              <?php
                $sql = "SELECT * FROM categories ";
                $query_update  = execute($sql);
                while ($row = mysqli_fetch_assoc($query_update)) {
                    $cat_id  = $row['cat_id'];
                    $cat_title = $row['cat_title'];

                    echo "<option value='$cat_id'>$cat_title</option>";
                }
                ?>
          </select>
      </div>



      <div class="form-group">
          <label for="users">Users</label>

          <select name="post_user" id="post_user" class="form-control">
               <?php
              
                $sql_user = "SELECT * FROM users ";
                $query_users = execute($sql_user);
                confirm($query_users);      
                while ($row = mysqli_fetch_assoc($query_users)) {
                    $id  = $row['id'];
                    $username = $row['username'];
                     
                    echo "<option value='$id'>$username</option>";
                }
                ?>
          </select>
      </div>



      <div class="form-group">
          <label for="post_image">Post image</label>
          <input type="file" name="post_image" class="form-control">
      </div>

      <div class="form-group">
          <label for="post_tags">Post Tags</label>
          <input type="text" name="post_tags" class="form-control">
      </div>

      <div class="form-group">
          <label for="post_content">Post Content</label>
          <textarea name="post_content" id="editor" cols="30" rows="10" class="form-control"></textarea>
      </div>

      <div class="form-group">
          <label for="post_status">Status</label>
          <select name="post_status" class="form-control">
              <option value="draft">Post status</option>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
          </select>
      </div>

      <div class="form-group">
          <input type="submit" name="create_post" value="Publish Post" class="btn btn-primary">
      </div>

  </form>


  