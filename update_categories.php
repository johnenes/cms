<?php include'./includes/header.php';?>
                    
        
                <?php
##### GETTING THE GET GLOBAL FROM URL categories.php?edit=1 the convet to varaible###########
## the sql here only allowed editing on the edit field######################
                    if(isset($_GET['edit'])){        
                    $edit_cat =   clean($_GET['edit']);
                    $sql ="SELECT  * FROM categories WHERE  cat_id=$edit_cat";
                    $result = execute($sql);
                    confirm($result);
                    while($row = mysqli_fetch_assoc($result)){
                        $cat_id  = $row['cat_id'];
                        $cat_title =$row['cat_title'];
###############   END OF EDITING DBTABLE ###############################
                        ?>
                        

                        <div  class="col-xs-6">
                         <form action="" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <label for='categorie'> EDIT CATEGORY</label>
                            <input text="text" name="title" value="<?php if(isset( $cat_title )) { echo $cat_title ;}     ?>" class="form-control">

                        </div>
                        <div class="form-group">
                            <input type="submit" name="update_category" value="UPDATE CATEGORY"
                                class="form-control btn btn-primary">
                        </div>

                    </form>
                </div>
                <!---- end of form-->              
               <?php }}?>


               <?php
            ##################### UPDATE CATEGORY########################
            ####### ONCE DISPLAY ON THE EDIT FIELD THEN EDIT AND UPDATE IT .############
               
                if(isset($_POST['update_category'])){
                    $cat_title = $_POST['title'] ;
                    if($cat_title == "" || empty($cat_title)){
                        echo validation_error_display("You can not update blank filed")          ;              
                    }else{   
                        if($cat_title !="" && !empty($cat_title)){      
                        // $the_cat_tile  = escape($the_cat_tile) ;
                        $sql = "UPDATE categories SET cat_title ='$cat_title'   WHERE cat_id ='$cat_id  ' ";
                        $update_result = execute($sql);
                        confirm($update_result );
                        redirect('categories.php');
                        }
                    }
            }##### END  OF UPDATING THE CATEGORY FIELD ##########################
               ?>

              


