<?php

# Navbar  function of category
function category()
{
    global $dataBaseConnection;
    $sql = "SELECT  * FROM categories";
    $result = execute($sql);
    confirm($result);

    while ($row = mysqli_fetch_assoc($result,)) {
        $cat_id = $row['cat_id'];
        $cat_title = $row['cat_title'];
        echo "<li class='nav-item'><a href='category.php?category='$cat_id' class='nav-link link-dark px-6'></a></li>";
    }
}
    
#sidebar blog category functions inside include folder
