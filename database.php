

<?php

define("DB_HOST", 'localhost');
define('DB_USER', 'root');
define("DB_PASS", '');
define("DB_NAME", 'icyber_');
$dataBaseConnection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$dataBaseConnection) {
    die('Fail  Connection ' . mysqli_connect_error());
}


function fetchRecord($result)
{
    return mysqli_fetch_array($result);
}
/***********************ROW COUNT IN DATABASE FUNCTION*****************/
function row_count($result)
{
    // global $dataBaseConnection;
    $result =  mysqli_num_rows($result);
    return  $result;
}
/**********************End the row count function**************************** */




/*****************QUERY DATABASE FUNCTIONS******************************/
function execute($sql)
{
    global $dataBaseConnection;
    $result = mysqli_query($dataBaseConnection, $sql);
    // confirm($result);
    return $result;
}
/*************End of query functions***************************************** */





/************TO CHECK IF ERROR EXISTS IN    DATADASE **********************/
function confirm($result)
{
    global $dataBaseConnection;
    if (!$result) {
        die("QUERY FAILED" . mysqli_error($dataBaseConnection));
    }
}
/**************End of function confirm error functions */




/*****************CLEANING DATA BEDFORE SUBMISSION******************** */
function escape($result)
{
    global $dataBaseConnection;
    return mysqli_real_escape_string($dataBaseConnection, $result);
}
/************************End of escape function******************************* */


//***************FETCHIN ALL DATA FROM DATABASE*********************** */
function fetch_array($result)
{
    // global $dataBaseConnection;
    return mysqli_fetch_array($result);
}
/******************end of fetch array*************************************** */

function fetch_all_assoc($result)
{
    return    mysqli_fetch_assoc($result);
    # AUTHENTICATIONS 
}

function logged_in()
{
    if (isset($_SESSION['email']) || isset($_COOKIE['email'])) { // checking for session and cookies
        return true;
    } else {
        return false;
    }
}



function is_admin($email)
{
    if (logged_in()) {
        $sql = "SELECT user_role FROM users where email='$email' ";
        $result = execute($sql);
        $row = mysqli_fetch_array($result);
        if ($row['user_role']='admin') {
            return true;
        } else {
            return false;
        }
    }
    return false;
}



function userLikePost($post_id = '')
{
    $result = execute("SELECT  *FROM  likes WHERE  id='" . loggedInUserId() . " '  AND    post_id='$post_id' ");
    confirm($result);
    return  row_count($result) >= 1 ? true : false;
}

function loggedInUserId()
{
    if (logged_in()) {
        $result = execute("SELECT * FROM users WHERE email='email' ");
        confirm($result);
        $users = mysqli_fetch_assoc($result);
        return  row_count($result) >= 1 ? $users['id'] : false;
    }
    return false;
}




// ############ USER SPECIFIC FUNCTION#############
// function get_all_user_post()
// {
//     $sql = "SELECT  * FROM posts WHERE id='" . loggedInUserId() . "' ";
//     $query_comment_count = execute($sql);
//     $count_comment =  mysqli_num_rows($query_comment_count);
//     return $count_comment;
// }


// function get_all_posts_user_comment()
// {
//     $sql = "SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE id='" . loggedInUserId() . "' ";
//     $query_post_count =  execute($sql);
//     $count_comment_post = mysqli_num_rows($query_post_count);
//     return $count_comment_post;
// }
// function get_user_category()
// {
//     $sql = "SELECT * FROM categories WHERE id='" . loggedInUserId() . "' ";
//     $results = execute($sql);
//     $count_category =  mysqli_num_rows($results);
//     return $count_category;
// }

// function get_all_user_draft_posts()
// {
//     $sql = "SELECT  * FROM posts WHERE id='" . loggedInUserId() . "'  AND  post_status = 'draft' ";
//     $select_all_draft_post = execute($sql);
//    return mysqli_num_rows($select_all_draft_post);
// }

// function get_all_user_published_posts()
// {
//     $sql = "SELECT  * FROM posts WHERE id='" . loggedInUserId() . "'  AND  post_status = 'published' ";
//     $select_all_published_post = execute($sql);
//    return mysqli_num_rows($select_all_published_post);
// }

// function get_all_unproved_comment()
// {
//     $sql = "SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE id='" . loggedInUserId() . "' AND  comment_status = 'unaproved' ";
//     $select_all_unaproved_post = execute($sql);
//     return  mysqli_num_rows($select_all_unaproved_post);
// }

// function  get_all_proved_comment()
// {
//     $sql = "SELECT * FROM posts INNER JOIN comments ON posts.post_id = comments.comment_post_id WHERE id='" . loggedInUserId() . "' AND  comment_status = 'approved' ";
//     $select_aproved_post = execute($sql);
//     return mysqli_num_rows($select_aproved_post);
// }




function get_username_session()
{
    return isset($_SESSION['email']) ? $_SESSION['email'] : '';
}


