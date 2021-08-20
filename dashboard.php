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
                 <h1 class="page-header">
                    Welcome to Admin  Dashboard
                     <strong> <small><?php echo  strtoupper(get_username_session()); ?> </small></strong>
                 </h1>
             </div>
         </div>

         <!-- /.row -->

         <div class="row">
             <div class="col-lg-3 col-md-6">
                 <div class="panel panel-primary">
                     <div class="panel-heading">
                         <div class="row">
                             <div class="col-xs-3">
                                 <i class="fa fa-file-text fa-5x"></i>
                             </div>
                             <div class="col-xs-9 text-right">
                                 <?php
                                    $sql = "SELECT * FROM posts";
                                    $select_all_post = execute($sql);
                                    $posts_count = mysqli_num_rows($select_all_post);
                                    ?>
                                 <div class='huge'><?php echo $posts_count ?></div>
                                 <div>Posts</div>
                             </div>
                         </div>
                     </div>
                     <a href="posts.php">
                         <div class="panel-footer">
                             <span class="pull-left">View Details</span>
                             <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                             <div class="clearfix"></div>
                         </div>
                     </a>
                 </div>
             </div>
             <div class="col-lg-3 col-md-6">
                 <div class="panel panel-green">
                     <div class="panel-heading">
                         <div class="row">
                             <div class="col-xs-3">
                                 <i class="fa fa-comments fa-5x"></i>
                             </div>
                             <div class="col-xs-9 text-right">
                                 <?php
                                    $sql = "SELECT * FROM comments";
                                    $select_comments = execute($sql);
                                    $comments_count = mysqli_num_rows($select_comments);
                                    ?>
                                 <div class='huge'><?php echo $comments_count; ?></div>
                                 <div>Comments</div>
                             </div>
                         </div>
                     </div>
                     <a href="comments.php">
                         <div class="panel-footer">
                             <span class="pull-left">View Details</span>
                             <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                             <div class="clearfix"></div>
                         </div>
                     </a>
                 </div>
             </div>
             <div class="col-lg-3 col-md-6">
                 <div class="panel panel-yellow">
                     <div class="panel-heading">
                         <div class="row">
                             <div class="col-xs-3">
                                 <i class="fa fa-user fa-5x"></i>
                             </div>
                             <div class="col-xs-9 text-right">
                                 <?php
                                    $sql = "SELECT * FROM users";
                                    $select_users = execute($sql);
                                    $user_count = mysqli_num_rows($select_users);
                                    ?>
                                 <div class='huge'><?php echo $user_count; ?></div>
                                 <div> Users</div>
                             </div>
                         </div>
                     </div>
                     <a href="users.php">
                         <div class="panel-footer">
                             <span class="pull-left">View Details</span>
                             <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                             <div class="clearfix"></div>
                         </div>
                     </a>
                 </div>
             </div>
             <div class="col-lg-3 col-md-6">
                 <div class="panel panel-red">
                     <div class="panel-heading">
                         <div class="row">
                             <div class="col-xs-3">
                                 <i class="fa fa-list fa-5x"></i>
                             </div>
                             <?php
                                $sql = "SELECT * FROM Categories";
                                $select_categories = execute($sql);
                                $categories_count = mysqli_num_rows($select_categories);
                                ?>

                             <div class="col-xs-9 text-right">
                                 <div class='huge'><?php echo $categories_count ?></div>
                                 <div>Categories</div>
                             </div>
                         </div>
                     </div>
                     <a href="categories.php">
                         <div class="panel-footer">
                             <span class="pull-left">View Details</span>
                             <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                             <div class="clearfix"></div>
                         </div>
                     </a>
                 </div>
             </div>
         </div>
         <!-- /.row -->
         <?php

            ////this show the draft post statistic
            $sql = "SELECT * FROM posts WHERE post_status = 'draft' ";
            $select_all_draft_post = execute($sql);
            $post_draft_count = mysqli_num_rows($select_all_draft_post);



            ////this show the published post statistic
            $sql = "SELECT * FROM posts WHERE post_status = 'published' ";
            $select_all_published_post = execute($sql);
            $published_post_count = mysqli_num_rows($select_all_published_post);


            ////this show the  comment Status statistic
            $sql = "SELECT * FROM comments WHERE comment_status = 'unapproved' ";
            $select_unapproved = execute($sql);
            $unapproved_comment = mysqli_num_rows($select_unapproved);


            ////this show the  comment Status statistic
            $sql = "SELECT * FROM comments WHERE comment_status = 'approved' ";
            $select_approved_comments = execute($sql);
            $approved_comment_count = mysqli_num_rows($select_approved_comments);


            ////this show the user roles comment statistic
            $sql = "SELECT * FROM users WHERE user_role = 'subscriber' ";
            $select_subscriber = execute($sql);
            $subscriber_count = mysqli_num_rows($select_subscriber);


            ////this show the user roles comment statistic
            $sql = "SELECT * FROM users WHERE user_role = 'admin' ";
            $select_admin = execute($sql);
            $admin_count = mysqli_num_rows($select_admin);


            ?>

         <div class="row">


             <script type="text/javascript">
                 google.charts.load('visualization', "1.1", {
                     'packages': ['bar']
                 });
                 google.charts.setOnLoadCallback(drawChart);

                 function drawChart() {
                     var data = google.visualization.arrayToDataTable([
                         ['Data', 'Count'],

                         <?php

                            $elements_text  = [
                                'Active Post', 'Draft Post', 'Published Post', 'Comments',
                                'Approved Comment', 'Unapproved Comment', 'Users', 'subscriber',
                                'Categories'
                            ];

                            $elements_count = [
                                $posts_count, $post_draft_count, $published_post_count,
                                $comments_count, $approved_comment_count, $unapproved_comment,
                                $user_count, $subscriber_count, $categories_count
                            ];

                            for ($i = 0; $i < 9; $i++) {
                                echo "['{$elements_text[$i]}'" . "," . "{$elements_count[$i]}],";
                            }
                            ?>

                         // ['POST', 1000 ]
                     ]);

                     var options = {
                         chart: {
                             title: '',
                             subtitle: '',
                         }
                     };

                     var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                     chart.draw(data, google.charts.Bar.convertOptions(options));
                 }
             </script>

             <div id="columnchart_material" style="width: 800px; height: 500px;"></div>

         </div>

         <!-- /.row -->
     </div>
     <!-- /.container-fluid -->

 </div>
 <!-- /#page-wrapper -->

 <!-- /#wrapper -->
 <?php include  'includes/footer.php'; ?>