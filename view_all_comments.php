
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
        $sql = "SELECT * FROM comments";
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
            <td><a href="comments.php?approve=<?php   echo    $comment_id?>">Approve</a></td>
            <td><a href="comments.php?unapprove=<?php echo    $comment_id?> ">Unapprove</a></td> 
            <td><a href="comments.php?delete=<?php    echo    $comment_id ?>">Delete</a></td> 
        </tr>
        <?php } ?> 

    </tbody> 
     
</table>        
    
<?php


//Approve   comment
 if(isset($_GET['approve'])){
    $the_comment_id = $_GET['approve'];
    $sql = " UPDATE  comments SET comment_status ='approved' WHERE comment_id= $the_comment_id";
    $query =  execute($sql);
    confirm($query);
    header('location: comments.php');
     
}




//Unapprove   comment
 if(isset($_GET['unapprove'])){
    $the_comment_id = $_GET['unapprove'];
    $sql = " UPDATE  comments SET comment_status ='unapproved' WHERE comment_id= $the_comment_id";
    $query = execute($sql);
    confirm($query);
    // header('location: comments.php');
    
}






///DELETING POST FROM POST
if(isset($_GET['delete'])){
    $comment_post_id = $_GET['delete'];
    $sql = "DELETE FROM  comments WHERE comment_id = $comment_post_id ";
    $delet_posts = execute($sql);
    confirm($delet_posts);
    header('location: comments.php');
}
?>

