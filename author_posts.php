<?php require_once'./includes/header.php';require_once'./includes/navbar.php'; ?>

     <!-- Navigation -->
     


<!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">






             <?php 
             
             
             
             
             
             
    if(isset($_GET['p_id'])){
        $get_post_id = $_GET['p_id'];
        $get_author = $_GET['author'];

    global $connection;
    $sql = "SELECT * FROM posts WHERE post_user = '$get_author' ";
    $sql_posts = execute($sql);
    confirm($sql_posts);    
    while ($row = mysqli_fetch_assoc($sql_posts)) {
        $post_id      = $row['post_id'];
        $post_title   = $row['post_title'];
        $post_user    = $row['post_user'];
        $post_date    = $row['post_date'];
        $post_image   = $row['post_image'];
        $post_content = $row['post_content'];
        ?>
        

        <!-- First Blog Post -->
        <h2>
            <a href="post.php?p_id=<?php echo $post_id ?>"><?php echo $post_title;?></a>
        </h2>
        <p class='lead'>
              by <a href= '#'><?php echo $post_user; ?></a>
        </p>
        <p><span class='glyphicon glyphicon-time'></span><?php echo $post_date;?></p>
        <hr>
            <a href="post.php?p_id=<?php echo $post_id ?>"> <img class='img-responsive' src='images/<?php echo $post_image;?>' alt='images'></a>
        <hr>
        <p><?php echo $post_content;?></p>
 
        <!-- <hr> -->
    <?php }} ?>


    


                <!-- Blog Comments -->     
                <!-- Comment -->
                <div class="media">
                    <a class="pull-left" href="#">
                        <img class="media-object" src="http://placehold.it/64x64" alt="">
                    </a>


                    
                    <div class="media-body">       
                         <!-- Nested Comment -->
                        <div class="media">
                            <a class="pull-left" href="#">
                                <img class="media-object" src="http://placehold.it/64x64" alt="">
                            </a>

                             
                        </div>
                        <!-- End Nested Comment -->
                    </div>
                </div>

            </div>



         <?php include'./includes/sidebar.php';?>

         
     </div>
        <!-- /.row -->

<!-- <hr> -->
        </div>
       
    <div>

     <hr>


        <?php include'./includes/footer.php';?>     