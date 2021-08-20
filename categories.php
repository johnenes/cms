<?php require_once'./includes/header.php'; ?>
<!-- Navigation -->
<?php require_once'./includes/navbar.php';?>

<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<?php require_once'./includes/sidebar.php';?>



<div id="page-wrapper">

    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    CATEGORY FORM
                    
                </h1>

<?php 
 if(isset($_SESSION['user_role'])){
    if(!is_admin()){
        redirect('../index.php');   
    } 
 }else{
   logged_in();
 }
?>


                <div class="col-xs-6">
                <?php
                ######## FUNCTION INSERT CATEGORIES DATA FROM CONFIG/FUNCTIONS##################
                insert_categories_data();
                ?>  

                    <form action="" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <label for='categorie'> ADD CATEGORY</label>
                            <input text="text" name='cat_title' class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit" value="ADD CATEGORY"
                                class="form-control btn btn-primary">
                        </div>

                    </form>
                </div>
                <!---- end of  add category -->

<?php 

############## THIS IS COMING FROM  INCLUDES  Including Update category  from includes folder######################
if(isset($_GET['edit']) ){
    $cat_id  =  $_GET['edit'];
    include 'includes/update_categories.php';
    
}



?>




                <div class="col-xl-12 ">
                    <table class="table table-bordered table-hover table-center">
                        <thead>
                            
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>DELETE</th>
                                <th> EDIT </th>
                            </tr>

                        </thead>
                        <tbody>
                                <tr>
                            <?php 
                            #DISPLAY THE TABLE FUNCTION HERE FROM functions.php#
                              categories_table();
                            ?>


                             <?php  deleting_categories();      # ID deleteing functions ?>
                        </tbody>
                    </table>
                </div><!-- end of table-->

            </div>
        </div>
        <!-- /.row -->

    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

</body>

</html>