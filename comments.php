
 <?php require_once'./includes/header.php'; ?>
        <!-- Navigation -->
<?php require_once'./includes/navbar.php';?>

            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
<?php require_once'./includes/sidebar.php';?>    

<?php 
 if(isset($_SESSION['user_role'])){
    if(!is_admin()){
        redirect('../index.php');   
    } 
 }else{
   logged_in();
 }
?>



<!-- <div id="wrapper"> -->

<!-- Navigation -->

<?php//  include 'includes/admin_navigation.inc.php'?>


<div id="page-wrapper">
<div class="container-fluid">
<!-- Page Heading -->
<div class="row">

<div class="col-lg-12">
    <h1 class="page-header">
        Welcome to  admin
        <small>Author</small>
    </h1>

<?php
//check condition to bring in the all_post view
if(isset($_GET['source'])){
    $source = $_GET['source'];
}else{
    $source ='';
}
switch ($source) {
    case 'add_post';
        include 'includes/add_post.php';
        break;
    case 'edit_post';
        include 'includes/edit_post.php';
        break;
    case '300';
       echo 'NICE 300';
        break;
    
    default:
        include 'includes/view_all_comments.php';
        break;
}

?>



</div>
</div>
<!-- /.row -->

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->

<!-- /#wrapper -->
<?php include  'includes/footer.php';?>
