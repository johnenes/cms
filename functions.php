<?php


/*************************URL REDIRECTING FUNCTION*********** */
function redirect($location){
    header("Location: {$location}");

}

/**********************FILTER UNWANTED STRING **************/
function  clean($string){
    return htmlentities($string);
}/********************End of  cleaning string like , % ^ etc*/

/*********************FOR SETTING MESSAGE VARIABLE *********** */
function set_message($message){
    if(!empty($message)){
            $_SESSION['message'] = $message;
    }else{
        $message = "";
    }
}

/*****************DISPLAY THE MESSAE ONCE IT SET THEN UNSET IT TO AVOID REMAINING THERE PERMANTlY */
function  display_message(){
     if(isset($_SESSION['message'])){
         echo $_SESSION['message'];
         unset($_SESSION['message']);
     }
}

/*************TOKEN  GENERATOR*******************************/
function token_generator(){
  $token  = $_SESSION[ 'token'] = md5( uniqid(mt_rand(), true));
  return $token;
}

/*************************END OF HELPER FUNCTIONS  */

    


/******************************Displaying Error Validations Functions************************/

    function validation_error_display($error_message){
        $error_message = <<<DELIMITER
                        <div class="alert alert-danger alert-dismissible  " role="alert">
                                <strong> Warning!</strong> $error_message
                                <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
        DELIMITER;
            return $error_message;
                            
        }/**********************END OF VALIDATION ERROR DISPLAY************** ********************/ 

##################################################################################################
##                                      WORKING WITH CATEGORY TABLE IN THE  categories.php file                     ########
function insert_categories_data(){
                    if (isset($_POST['submit'])){

                    $cat_title  = $_POST['cat_title'];

                    if($cat_title == "" || empty($cat_title)){
                        echo  validation_error_display("Field can not be empty");
                    }else{
                            
                        $sql   = "INSERT INTO categories (cat_title )  VALUES ('$cat_title')";
                        $result = execute($sql);
                        confirm($result);
                    }
                    
                }

}

############### DELETION FUNCTION OF DATA FROM THE TABLE #################
function deleting_categories (){
            if(isset($_GET['delete'])){
                $delete_cat_id_get  = $_GET['delete'];
                $delete_cat_id_get = escape($delete_cat_id_get) ;
                $sql = "DELETE FROM categories WHERE cat_id ='$delete_cat_id_get' ";
                $result = execute($sql);
                confirm($sql);  
                redirect('categories.php');
            }
            
}


function categories_table(){
                                    #FIND ALL  CATEGORIY FROM DB TO TABLE HTML
                                    
                                    $sql ="SELECT  * FROM categories";
                                    $result = execute($sql);
                                    confirm($result);
                                    while($row = mysqli_fetch_assoc($result)){
                                        $cat_id  = $row['cat_id'];
                                        $cat_title =$row['cat_title'];
            ?>
                     <td> <?php echo $cat_id ?> </td>
                            <td> <?php echo $cat_title?></td>
                            <td><a href="categories.php?delete=<?php echo $cat_id?>">Delete</a></td>
                            <td><a href="categories.php?edit=<?php echo $cat_id?>">Edit</a></td>

                            </tr>
                
            <?php  }   }  ###### END OF THE WORK IN CATEGORY TABLE ################ ?>


<?php


function individual_author(){
        /////reading posts from databases

        
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
    <?php }}}  ?>













<?php
function deletePostVIew(){

        // global $link;
    if(isset($_SESSION['user_role'])) {
                /////this permit any user_role to delete record/////
        if($_SESSION['user_role'] =='admin'){        
           
            $get_post_id = $_POST['post_id'];
            $get_post_id =   escape($get_post_id);
            $sql_delete_post = "DELETE FROM posts WHERE post_id ='$get_post_id' ";
            $result = execute($sql_delete_post);
            confirm($result);
                header("Location: posts.php");
        }
    }else{
                $get_post_id = '';
        }
}





function editPost(){
    // global $link;
    if(isset($_GET['edit'])){
        $get_post = $_GET['edit'];
        $sql_update_post = "UPDATE  posts SET post_id = '$get_post' ";
       $result  = execute($sql);
       confirm($result);
        // header("Location: posts.php");
      
    }   else{
        $get_post_id = '';
    }
}



function resetView(){
    if(isset($_GET['reset'])){
        // global $link;
        $post_id = $_GET['reset'];
        $sql = "UPDATE posts SET post_views_count = 0 WHERE post_id =".escape($_GET['reset'])." ";
        $reset_posts = execute($sql);
        confirm($reset_posts);
        header("Location: posts.php"); 
    }           
}
  



// function is_admin(){
//     if(logged_in()) {
//         $sql = "SELECT user_role FROM users where id='".$_SESSION['id']."' ";
//         $result = execute($sql);
//         $row = fetchRecord($result);
//         if ($row['user_role'] == 'admin') {
//             return true;
//         } else {
//             return false;
//         }
//     }
// }












 




