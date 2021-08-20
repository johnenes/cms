<?php
include 'includes/delete_modal.php';
// include_once 'includes/header.php';



if (isset($_POST['checkBoxArray'])) {
    ///checkbox on the posts
    $checkBoxArray = $_POST['checkBoxArray'];
    foreach ($checkBoxArray as $key => $checkBoxValueid) {
        $bulk_options = $_POST['bulk_options'];
        switch ($bulk_options) {
            case 'published':
                $sql = "UPDATE posts SET post_status = '$bulk_options'  WHERE post_id = $checkBoxValueid";

                $published_status_update = execute($sql);
                break;
            case 'draft':
                $sql = "UPDATE posts SET post_status = '$bulk_options' WHERE post_id = $checkBoxValueid ";
                $draft_status_update = execute($sql);
                break;
            case 'delete':
                $sql = "DELETE FROM posts WHERE post_id = $checkBoxValueid ";
                $delete_status = execute($sql);
                break;
            case 'clone':
                ////reading value from db

                $sql = "SELECT  * FROM posts WHERE post_id =" . escape($checkBoxValueid) . " ";
                $select_query = execute($sql);

                while ($row = mysqli_fetch_assoc($select_query)) {
                    $post_id                        = $row['post_id'];
                    $post_category_id        = $row['post_category_id'];
                    $post_title                     = $row['post_title'];
                    $post_author                 = $row['post_author'];
                    $post_user                      = $row['post_user'];
                    $post_status                  = $row['post_status'];
                    $post_image                  = $row['post_image'];
                    $post_tags                      = $row['post_tags'];
                    $post_date                      = date('y-m-d');
                    $post_content                = $row['post_content'];
                    if (empty($post_tags)) {
                        $post_tags = "Generic";
                    }
                }
                ///inserting the value read from db 
                $sql = "INSERT  INTO posts(post_category_id, post_title ,";
                $sql .= "post_author,post_user, post_date,post_image,";
                $sql .=  "post_content,post_tags ,post_status)";
                $sql .= "VALUES('$post_category_id' ,'$post_title', '$post_author', '$post_user', '$post_date', '$post_image', '$post_content', '$post_tags', '$post_status')";
                $result = execute($sql);
                confirm($result);
                break;
            case 'reset':
                $sql = "UPDATE posts SET post_views_count = 0 WHERE post_id =" .  escape($checkBoxValueid) . " ";
                $reset_posts =  execute($sql);
                break;
            default:

                break;
        }
    }
}
?>

<form method="post" action=" ">
    <table class="table table-bordered table-hover">

        <div id='bulkoptioncontainer' class="col-xs-4" style='padding: 0px;'>
            <select name="bulk_options" id="" class="form-control">
                <option value=""> select option</option>
                <option value="published">published</option>
                <option value="draft">Draft</option>
                <option value="delete">Delete</option>
                <option value="clone">Clone</option>
                <option value="reset">Reset Post View</option>

            </select>
        </div>
        <div class="col-xs-4">
            <input type="submit" name="submit" class=" py-3 btn btn-success" value="Apply">
            <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>


        </div>


        <caption class="text-center">View all the posts list</caption>
        <thead>
            <tr>
                <th><input type="checkbox" name="" id="selectAllBoxes"></th>
                <th>Id</th>
                <th>User</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>view all comment on a post</th>
                <th>Date</th>
                <th>View Post</th>
                <th>Edit</th>
                <th> View</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // $query_post = "SELECT * FROM posts ORDER BY post_id DESC";


            /////////////////////////////////////////////////////////////////////////////////////
            // JOINING MORE THAN ONE DB TABLE
            $sql  = "SELECT posts.post_id,posts.post_category_id, posts.post_author,
                posts.post_title,  posts.post_user,posts.post_comment_count,
                posts.post_status, posts.post_date,posts.post_image,
                posts.post_tags,  posts.post_views_count, categories.cat_id, categories.cat_title
                FROM posts LEFT JOIN categories ON posts.post_category_id = categories.cat_id
                ORDER BY posts.post_id DESC";

            $result =  execute($sql);
            confirm($result);
            foreach ($result as $key => $value) {
                $post_category_id   = $value['post_category_id'];
                $post_id                    = $value['post_id'];
                $post_user                = $value['post_user'];
                $post_author            = $value['post_author'];
                $post_title                 = $value['post_title'];
                $post_comment_count = $value['post_comment_count'];
                $post_status              = $value['post_status'];
                $post_date                 = $value['post_date'];
                $post_image              = $value['post_image'];
                $post_tags                 = $value['post_tags'];
                $post_views_count   = $value['post_views_count'];
                $cat_id                      = $value['cat_id'];
                $cat_title                   = $value['cat_title'];
            ?>
                <tr>
                    <td><input type="checkbox" name="checkBoxArray[]" value="<?php echo $post_id ?>" class="checkBoxes"></td>

                    <td><?php echo $post_id ?></td>


                    <?php
                    //////////////////////////////////////////////////
                    // CHECKING FOR THE AUTHOR OR USERS////////////// 
                    if (!empty($post_user)) {
                        echo "<td> $post_user </td>";
                    } elseif (!empty($post_author)) {
                        echo "<td> $post_author </td>";
                    }
                    ?>
                    <td><?php echo $post_title ?></td>
                    <td><?php echo $cat_title ?></td>
                    <td><?php echo $post_status ?></td>
                    <td><a href='#'><img class='image-responsive' src='../images/<?php echo $post_image ?>' style="width: 60px;" alt="image" /></a></td>
                    <td><?php echo $post_tags ?></td>
                    <?php
                    ////Dispaly the number of comment on each post views
                    $sql = "SELECT  * FROM comments WHERE comment_post_id = $post_id ";
                    $query_comment_count = execute($sql);

                    $count_comment =  mysqli_num_rows($query_comment_count);
 
                    ?>


                    <td><a class="badge  badge-info alert alert-primary" role="alert" 
                    href="post_comment.php?id=<?php echo $post_id ?>"><?php echo $count_comment  ?></a></td>


                    <td><?php echo $post_date ?></td>
                    <td><a class="btn btn-primary" href='../?post.php?p_id=<?php echo $post_id ?>'> View Post</a></td>



                    <td><a class='btn btn-info' href='posts.php?source=edit_post&p_id=<?php echo $post_id ?>'>edit</a></td>



                    <td><a class="badge  badge-info alert alert-primary" href="posts.php?reset=<?php echo $post_id ?>"><?php echo $post_views_count ?></a></td>

                    <form method="post">
                        <input type="hidden" name='post_id' value="<?php echo $post_id ?>">

                        <td> <input type="submit" href="javascript:void(0)" rel="<?php echo $post_id ?>" name="delete" class="btn btn-danger delete_link" value="Delete">
                        <td>

                    </form>
                    <!--<td><a href="javascript:void(0)"  rel="<?php //echo$post_id 
                                                                ?>" class="btn btn-danger delete_link">Delete</a></td>-->


                    <!-- // echo "<td><a onClick=\"javascript:return confirm('Are you sure you want to delete the post');\" href='posts.php?delete=$post_id'> Delete</a>   </td>"  -->

                </tr>
            <?php } ?>


            <?php

## UPDATE FUNCTIONS
if(isset($_GET['edit'])){
        $get_post = $_GET['edit'];
        $sql_update_post = "UPDATE  posts SET post_id = '$get_post' ";
       $result  = execute($sql);
       confirm($result);
        // header("Location: posts.php");
      
    }   else{
        $get_post_id = '';
    }


## RESET FUNCTIONS
    if(isset($_GET['reset'])){
        // global $link;
        $post_id = $_GET['reset'];
        $sql = "UPDATE posts SET post_views_count = 0 WHERE post_id =".escape($_GET['reset'])." ";
        $reset_posts = execute($sql);
        confirm($reset_posts);
        header("Location: posts.php");
         
    }           


 ##  DELETE FUNCTIONS
    if(isset($_POST['delete'])){
        // global $link;
        if(isset($_SESSION['user_role'])) {
                /////this permit any user_role to delete record/////
        if($_SESSION['user_role'] =='admin'){        
        }    
            $get_post_id = $_POST['post_id'];
            $get_post_id =   escape($get_post_id);
            $sql_delete_post = "DELETE FROM posts WHERE post_id ='$get_post_id' ";
            $result = execute($sql_delete_post);
            confirm($result);
                header("Location: posts.php");
        }
    }else{
                $get_post_id ="";
    }

            ?>
        </tbody>
    </table>

</form>

<script>
    $(document).ready(function() {
        $('.delete_link').on('click', function() {
            var id = $(this).attr('rel');
            var delete_url = "posts.php?delete=" + id + "";
            // alert(delete_url);
            $('.model_delete_link').attr('href', delete_url);
                $('#myModal').modal('show');
        });

    });
</script>
