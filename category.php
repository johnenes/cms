 <?php include_once("includes/header.php");
    include("includes/navbar.php");   ?>


 <!-- Navigation -->

 <!-- Page Content -->
 <div class="container">

     <div class="row">
         <!-- Blog Entries Column -->
         <div class="col-md-8">
             <?php
                if (isset($_GET['category'])) {
                    $post_category_id = $_GET['category'];
                    $published = 'published';

                    if (is_admin(isset($_SESSION['username']))) {

                        $sql = "SELECT   * FROM posts WHERE post_category_id='$post_category_id' ";
                    } else {
                        $sql = "SELECT * FROM posts WHERE post_category_id='$post_category_id' AND post_status='$published' ";
                    }
                    $select_all_post = execute($sql);
                    if (mysqli_num_rows($select_all_post) < 1) {
                        echo "<h1 class='text-center'> No post available</h1>";
                    } else {
                        while ($row = mysqli_fetch_assoc($select_all_post)) {

                            $post_id        = $row['post_id'];
                            $post_title     = $row['post_title'];
                            $post_user     = $row['post_user'];
                            $post_date     = $row['post_date'];
                            $post_image   = $row['post_image'];
                            $post_content = substr($row['post_content'], 0, 150); //use substr to limit the number of post post comment
                            $post_status  = $row['post_status'];



                ?>
                         <h2>
                             <a href="post.php?p_id=<?php echo $post_id ?>"><?php echo $post_title ?></a>
                         </h2>
                         <p class="lead">
                             by <a href="index.php"><?php echo $post_user ?></a>
                         </p>
                         <p><span class="glyphicon glyphicon-date"></span><?php echo $post_date ?></p>
                         <hr>
                         <img class="img-responsive" src='images/<?php echo $post_image; ?>' alt="">
                         <!-- <hr> -->
                         <p><?php echo $post_content ?></p>

             <?php }
                    }
                }


                ?>

         </div>


         <?php include("includes/sidebar.php") ?>


         <?php include("includes/footer.php"); ?>