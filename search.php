<?php require_once('./includes/header.php');
require_once('./includes/navbar.php'); ?>






<div class='jumbotron jumbotron-fluid text-center  ' style="background-color: #ecf0f1">

    <div class="container">
        <?php display_message(); ?>
        <p class="text-lead">ICYBERSEC NETWORK NEWS</p>
    </div>
</div>



<!-- Page Content -->
<div class="container">

    <div class="row">

        <!-- Blog Entries Column -->
        <div class="col-md-8">



            <?php





            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $dataBaseConnection;
                $search = $_POST['search'];
                #searching the database for available tags using the keyword LIKE and %
                $sql  = "SELECT * FROM   posts  WHERE post_tags  LIKE  '%$search%'  ";
                $result_search = execute($sql);
                confirm($result_search);
                $count = mysqli_num_rows($result_search);
                if ($count   == 0) {
                    echo "No result found";
                } else {
                    while ($row =  mysqli_fetch_assoc($result_search)) {
                        $post_id = $row['post_id'];
                        $post_title = $row['post_title'];
                        $post_author = $row['post_author'];
                        $post_date = $row['post_date'];
                        $post_image = $row['post_image'];
                        $post_content = $row['post_content'];
                        $post_image = $row['post_image']; ?>



                        <!-- First Blog Post -->
                        <h1>
                            <a href="#"> <?php echo  $post_title ?></a>
                        </h1>
                        <p class="lead">
                            by <a href="index.php"><?php echo $post_author ?></a>
                        </p>
                        <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date ?></p>
                        <!-- <hr> -->
                        <img class="img-responsive" src="images/<?php echo $post_image ?> " alt="">
                        <!-- <hr> -->
                        <p> <?php echo $post_content ?></p>
                        <!-- <a class=" btn btn-primary" href="#">Read More <span class="glyphicon glyphicon-chevron-right"></span></a> -->
                        <!-- <hr> -->


            <?php
                    }
                }
            }

            ?>






        </div>

        <!-- Sidebar start here  -->
        <?php require_once './includes/sidebar.php'; ?>
        <!-- end sidebar -->

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    </body>

    </html>

















    <!-- 
       <div class="row">
        <div class="col-md-6">
            <div class='card card-custom'>


                <div class="card-header card-header-custom text-center " style="background-color:#CCCCFF">
                     <script src="https://partners.getresponse.com/javascripts/promo_materials/text.js" data-abp="mjmywQGqM9" type="text/javascript"></script> 
                     <div data-getresponse-abp="647"></div>  
                </div>
                <div class='card-body'>

                    <div class="row">
                        <div class="col-lg-12" style="margin-top:10px;">
                            <ul class="list-group text-center">
                                <li class="list-group-item">
                                 <script src="https://partners.getresponse.com/javascripts/promo_materials/affiliate.js?id=469&name=mjmywQGqM9" data-affiliate="true" type="text/javascript"></script>
                                <a href="https://partners.getresponse.com/material/hit/469/mjmywQGqM9"><img src="https://partners.getresponse.com/material/view/469/mjmywQGqM9" /></a>
                                </li>
                            </ul>


                        </div>
                    </div>


                </div>
            </div>
        </div>


        <div class="col-md-6 " style="margin-top:15px;">
            <div class='card card-custom'>
                <div class="card-header card-header-custom text-center">
                    <a href="" class="active" id="login-form-link mx-auto">Features [Part - 2]</a>
                </div>
                <div class='card-body'>

                    <div class="row">
                        <div class="col-lg-12">


                            <ul class="list-group text-center">
                                
                                <li class="list-group-item">Deactivate Account</li>
                                
                            </ul>


                        </div>
                    </div>



                </div>


            </div>
        </div>
</div>

    </div>


 



         -->


    <!-ainer-->
        <?php require_once './includes/footer.php'; ?>