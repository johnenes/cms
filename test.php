// if (isset($_GET['category'])) {

// $get_category_id = trim($_GET['category']);

// $post_status = 'published';
// // if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin')
// if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {
// $sql = "SELECT post_id='$post_id' , post_title='$post_title', post_author='$post_author',";
// $sql .= "post_date='$post_date',post_image='$post_image',post_content='$post_content' FROM posts WHERE post_category_id=$get_category_id ";
// $result = execute($sql);
// confirm($result);
// } else {
// $sql1 = "SELECT post_id='$post_id' , post_title='$post_title', post_author='$post_author',";
// $sql1 .= "post_date='$post_date',post_image='$post_image', post_content='$post_content' ";
// $sql1 .= "FROM posts WHERE post_category_id='$get_category_id' AND post_status='$post_status' ";
// $result1 = execute($sql1);
// confirm($result1);
// }
// if (isset($sql)) {
// $result2 = $result;
// } else {

// $result2 = $result1;
// }
// while ($value = mysqli_fetch_assoc($result2)) {




?>





$post_status = 'published';
// if(isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin')
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin') {

$sql = "SELECT post_id='$post_id' , post_title='$post_title', post_author='$post_author',";
$sql .= "post_date='$post_date',post_image='$post_image',post_content='$post_content' FROM posts WHERE post_id = '$get_post_id' ";
$result = execute($sql);
confirm($result);
} else {
$sql1 = "SELECT post_id='$post_id' , post_title='$post_title', post_author='$post_author',";
$sql1 .= "post_date='$post_date',post_image='$post_image', post_content='$post_content' ";
$sql1 .= "FROM posts WHERE post_category_id='$get_post_id' AND post_status='$post_status' ";
$result1 = execute($sql1);
confirm($result1);
}

if (isset($sql)) {
$result2 = $result;
} else {

$result2 = $result1;
}
while ($value = mysqli_fetch_assoc($result2)) {




















</div>
</header>
<div class="  b-example-divider"></div>

<nav class="  card-header-custom   border-bottom  navbar-fixed-top">
    <div class="container d-flex flex-wrap">
        <ul class="nav me-auto">

            <li class="nav-item"><a href="index.php" class="nav-link link-dark px-6 active">Home</a></li>

            <?php
            $sql = "SELECT  * FROM categories";
            $result = execute($sql);
            confirm($result);
            while ($row = mysqli_fetch_assoc($result,)) {
                $cat_id = $row['cat_id'];
                $cat_title = $row['cat_title'];
            ?>
                <li class='nav-item'><a href="category.php?category=<?php echo  $cat_id ?>" class='nav-link link-dark px-6'><?php echo $cat_title ?></a></li>
            <?php } ?>



            <li class="nav-item"><a href="#" class="nav-link link-dark px-2"></a></li>
            <li class="nav-item"><a href="#" class="nav-link link-dark px-2">About</a></li>


        </ul>


        <ul class="nav">
            <?php if (logged_in()) : ?>
                <li class="nav-item"><a href="logout.php" class="nav-link link-dark px-2">Logout</a></li>
                <li class="nav-item"><a href="./admin/index.php" class="nav-link link-dark px-2"> admin </a></li>
            <?php endif; ?>

            <?Php if (!logged_in()) : ?>
                <li class="nav-item"><a href="login.php" class="nav-link link-dark px-2">Login</a></li>
            <?php endif; ?>


        </ul>
    </div>
</nav>
<header class="py-1     mb-3      border-bottom">


    <!-- <p class="text-lead">ICYBERSEC NETWORK NEWS</p> -->
    </div>
    <!-- </div> -->



    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">


                <?php
                $per_page = 10;

                if (isset($_GET['page'])) {

                    ///SET CONDITOIN TO DETERMINE THE NEXT PAGENATION
                    $page = $_GET['page'];
                } else {

                    $page = '';
                }
                if ($page == '' || $page == 1) {

                    $page_1 = 0;
                } else {


                    $page_1 = ($page * $per_page) - $per_page;
                }
                ///FINDING HOW MANY ROWS OF ITEM ARE ON THE TABLE
                if (is_admin($_SESSION['user_role'])) {

                    $sql_post_count = "SELECT * FROM posts ";
                } else {
                    $sql_post_count = "SELECT * FROM posts WHERE post_status='published' ";
                }
                $find_post_count = execute($sql_post_count);
                $count = mysqli_num_rows($find_post_count);

                if ($count < 1) {
                    #check if post is less than 1 then dislay no post on the page otherwise show all published post.
                    echo "<h1 class='text-center' >No post available</h1>";
                } else {

                    $count = ceil($count / 3);
                    $sql = "SELECT * FROM posts LIMIT $page_1, $per_page";
                    $sql_posts = execute($sql);
                    confirm($sql_posts);

                    while ($row = mysqli_fetch_assoc($sql_posts)) {
                        $post_id      = $row['post_id'];
                        $post_title   = $row['post_title'];
                        $post_user  = $row['post_user'];
                        $post_date    = $row['post_date'];
                        $post_image   = $row['post_image'];
                        $post_content = substr($row['post_content'], 0, 150); //use substr to limit the number of post post comment
                        $post_status  = $row['post_status'];
                ?>

                        <!-- First Blog Post -->
                        <h2>
                            <a href="post.php?p_id=<?php echo $post_id ?>"><?php echo $post_title; ?></a>
                        </h2>
                        <p class='lead'>
                            by <a href='author_posts.php?author=<?php echo $post_user; ?>&p_id=<?php echo $post_id; ?>'><?php echo $post_user; ?></a>
                        </p>
                        <p><span class='glyphicon glyphicon-time'></span><?php echo $post_date; ?></p>
                        <!-- <hr> -->
                        <a href="post.php?p_id=<?php echo $post_id ?>"> <img class='img-responsive' src='images/<?php echo $post_image; ?>' alt='images'></a>
                        <!-- <hr> -->
                        <p><?php echo $post_content; ?></p>

                        <!-- <hr> -->
                <?php }
                } ?>





                <?php


                //         echo "</div>";
                //         // Blog Sidebar Widgets Column -->
                //         include "includes/sidebar.php";

                //         echo " </div><div>";

                //         echo "<hr>


                // <ul class='pager' id='pager'>";

                //         for ($i = 1; $i <= $count; $i++) {
                //             if ($i == $page) {

                //                 echo " <li'><a class='pagenation' href='index.php?page=$i'> $i </a></li>";
                //             } else {
                //                 echo " <li ><a href='index.php?page=$i'> $i </a></li>";
                //             }
                //         }
                // "</ul>";

                //         echo 

                //         
                ?>