 <?php require'./includes/header.php';require'./includes/navbar.php';  ?>
<?php 

if($_SERVER['REQUEST_METHOD']=='POST'){
  $to            ="johneneojo1@gmail.com";
  $subject   = wordwrap( $_POST['subject'],70);
  $body       = $_POST['body'];
  $header    = "From: ". $_POST['email'];
  mail( $to, $subject,$body, $header);  
}



?>
    <div class='container'>
    <div class="row"> 
       <div class="col-lg-6 col-lg-offset-3 mx-auto">
          <h3>Contact </h3>
        <form method="POST">
          <input type="email" name="email" placeholder="Email" class="form-control"><p></p>

            <input type="text"  placeholder ="Subject"  name = "subject" class="form-control"><br/>

          <textarea  name="body" placeholder="Message"  cols="10" rows="10" class="form-control"></textarea><br/>

          <button type ="submit" class="btn btn-default bg-secondary text-white form-control" value="submit">  Send</button>
        </form>
        </div>
      </div>
      </div>
      

      <?php require'./includes/footer.php';?>   
  
  

