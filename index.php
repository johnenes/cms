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
                     <small>Role: Adamin
                         <?php strtoupper(get_username_session()) ?>

                 </h1>
             </div>
         </div>

         <!-- /.row -->

         <div class="row">
             <div class="col-lg-4 col-md-6">
                 <div class="panel panel-primary">
                     <div class="panel-heading">
                         <div class="row">
                             <div class="col-xs-3">
                                 <i class="fa fa-file-text fa-5x"></i>
                             </div>
                             <div class="col-xs-9 text-right">

                                 <div class='huge'><?//php echo  get_all_posts_user_comment(); ?></div>
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
             <div class="col-lg-4 col-md-6">
                 <div class="panel panel-green">
                     <div class="panel-heading">
                         <div class="row">
                             <div class="col-xs-3">
                                 <i class="fa fa-comments fa-5x"></i>
                             </div>
                             <div class="col-xs-9 text-right">
                                 <?php
                                    // $sql = "SELECT * FROM comments";
                                    // $select_comments = execute($sql);
                                    // $comments_count = mysqli_num_rows($select_comments);
                                    ?>
                                 <div class='huge'><?//php echo  get_all_posts_user_comment(); ?></div>
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
             
             <div class="col-lg-4 col-md-6">
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
                                 <div class='huge'><?php //echo get_user_category(); ?></div>
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
           //s $post_draft_count =  get_all_user_draft_posts();


            ////this show the published post statistic
          //  $published_post_count = get_all_user_published_posts();


            ////this show the  comment Status statistic            
          //  $unapproved_comment = get_all_unproved_comment();   

        
          ////this show the  comment Status statistic
            //$approved_comment_count = get_all_proved_comment();

            ////this show the user roles comment statistic
            

             

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
                                'Approved Comment', 'Unapproved Comment',   'subscriber',
                                'Categories'
                            ];

                            $elements_count = [
                                $posts_count, $post_draft_count, $published_post_count,
                                $comments_count, $approved_comment_count, $unapproved_comment,
                                $subscriber_count, $categories_count
                            ];

                            for ($i = 0; $i < 8; $i++) {
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