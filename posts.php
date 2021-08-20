<?php 

# Navbar  function of category
function category(){
    global $dataBaseConnection;
    $sql = "SELECT  * FROM categories";
    $result = execute($sql);
    confirm($result);
 
    while($row = mysqli_fetch_assoc($result,)){
        $cat_title = $row['cat_title'];    
        echo "<li class='nav-item'><a href='#' class='nav-link link-dark px-6'>$cat_title</a></li>";
    }
     
    }
    
#sidebar blog category functions inside include folder
function  blogs_category(){
    global $dataBaseConnection;
    $sql = "SELECT  * FROM categories";
    $result = execute($sql);
    confirm($result);
 
    while($row = mysqli_fetch_assoc($result,)){
        $cat_title = $row['cat_title'];    
        echo "
        <ul class='list-unstyled'>              
         <li><a href=''>$cat_title</a>
        </li>
        </ul>
        ";
        
    }
     
    }
 
     

