 <div class="collapse navbar-collapse navbar-ex1-collapse">
     <ul class="nav navbar-nav side-nav">
         <li>
             <a href="index.php"><i class="fa fa-fw fa-dashboard"></i> My Data</a>
         </li>
    <li>
             <a href="dashboard.php"><i class="fa fa-fw fa-dashboard"></i>Dashboard </a>
         </li>
         <li>
             <a href="categories.php"><i class="fa fa-fw fa-wrench"></i> Categories</a>
         </li>


         <li>
             <a href="javascript:;" data-toggle="collapse" data-target="#posts-dropdown"><i
                     class="fa fa-fw fa-arrows-v"></i> Posts <i class="fa fa-fw fa-caret-down"></i></a>
             <ul id="posts-dropdown" class="collapse">
                 <li>
                     <a href="posts.php"> View All Posts</a>
                 </li>
                 <li>
                     <a href="posts.php?source=add_post"> Add Posts</a>
                 </li>
             </ul>
         </li>
         <li>
             <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i>
                 Users <i class="fa fa-fw fa-caret-down"></i></a>
             <ul id="demo" class="collapse">
                 <li>
                     <a href="users.php"> View Users</a>
                 </li>
                 <li>
                     <a href="users.php?source=add_user"> Add User</a>
                 </li>
             </ul>
         </li>
         <li class="active">
             <a href="comments.php"><i class="fa fa-fw fa-file"></i>Comment</a>
         </li>


         <li>
             <a href="profile.php?email"><i class="fa fa-fw fa-dashboard"></i> Profile</a>
         </li>
     </ul>
 </div>
 <!-- /.navbar-collapse -->
 </nav>