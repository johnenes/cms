 
 
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
       Welcome to Comments
        <small>Author</small>
    </h1>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Id</th>
            <th>Author</th>
            <th>Comment</th>
            <th>Email</th>
            <th>Status</th>
            <th>In Response to </th>
            <th>Date</th>
            <th>Approve</th>
            <th>Unapprove</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
<?php
       if(isset($_GET['id'])){
        $post_id_relate_comment_post_id = $_GET['id'];
       
            $sql = "SELECT * FROM comments WHERE comment_post_id =$post_id_relate_comment_post_id";
            $query_comment = execute($sql);
            confirm($query_comment);
            while($row = mysqli_fetch_assoc($query_comment)){
                    $comment_id        = $row['comment_id'];
                    $comment_post_id   = $row['comment_post_id'];
                    $comment_author    = $row['comment_author'];
                    $comment_content   = $row['comment_content'];
                    $comment_email     = $row['comment_email'];           
                    $comment_status    = $row['comment_status'];
                    $comment_date      = $row['comment_date'];
                ?>     
        <tr >      
                <td><?php echo $comment_id ?></td>
                <td><?php echo $comment_author ?></td>
                <td><?php echo $comment_content ?></td>
                <td><?php echo $comment_email ?></td>
                <td><?php echo $comment_status ?></td>
                <?php 
                /////Relating the comment to post using comment status
                $sql = "SELECT * FROM posts WHERE post_id = $comment_post_id ";//relating post to comment
                $select_post_id_query = execute($sql);
                confirm($select_post_id_query);
                    while ($row = mysqli_fetch_assoc($select_post_id_query)) {
                        $post_id = $row ['post_id'];
                        $post_title = $row['post_title'];
                    ?>
                <td><a href="../post.php?p_id=<?php echo $post_id ?> "><?php echo $post_title ?></td>
                    <?php } ?>
                <td><?php echo $comment_date ?></td>
                <td><a href="comments.php?approve=<?php   echo    $comment_id?>&id=<?php echo $_GET['id'];?>">Approve</a></td>
                <td><a href="comments.php?unapprove=<?php echo $comment_id?>&id=<?php echo $_GET['id']; ?>">Unapprove</a></td> 
                <td><a href="post_comment.php?delete=<?php echo $comment_id ?>&id=<?php echo $_GET['id'];?>">Delete</a></td> 
        </tr>
<?php } } //else{header('location: posts.php');}?> 

    </tbody> 
     
</table>        
    
<?php


//Approve   comment
 if(isset($_GET['approve'])){
    $the_comment_id = $_GET['approve'];
    $sql = " UPDATE  comments SET comment_status ='approved' WHERE comment_id= $the_comment_id";
    $query =execute($sql);
    confirm($query);
    header("location: post_comment.php?id=".$_GET['id']."");
     
}




//Unapprove   comment
 if(isset($_GET['unapprove'])){
    $the_comment_id = $_GET['unapprove'];
    $sql = " UPDATE  comments SET comment_status ='unapproved' WHERE comment_id= $the_comment_id";
    $query = execute($sql);
    confirm($query);

    header("location: post_comment.php?id=".$_GET['id']."");
     
}
///DELETING POST FROM POST
if(isset($_GET['delete'])){
    $comment_post_id = $_GET['delete'];
    $sql = "DELETE FROM  comments WHERE comment_id = $comment_post_id ";
    $delete_posts = execute($sql);
    confirm($delete_posts);
    header("location: post_comment.php?id=".$_GET['id']."");

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


