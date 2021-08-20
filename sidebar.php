 <div class="col-md-4  " style="padding:25px;">
     <!-- Blog Categories Well -->
     <div class="well">
         <?php
            //use limit to limit caregory of the number of database loop.
            $sql = "SELECT * FROM categories ";
            $query_categories_sidebar = execute($sql);
            confirm($query_categories_sidebar);

            ?>

         <h4>Blog Categories</h4>
         <div class="row">
             <div class="col-lg-12">
                 <ul class="list-unstyled">
                     <?php

                        while ($row = mysqli_fetch_assoc($query_categories_sidebar)) {
                            $cat_title = $row['cat_title'];
                            $cat_id = $row['cat_id'];
                            echo "<li><a href='category.php?category=$cat_id'>$cat_title</a></li>";
                        }
                        ?>
                 </ul>
             </div>
         </div>
         <!-- /.row -->
     </div>
     <!-- Side Widget Well -->
     <?php include 'includes/widget.php'; ?>
 </div>