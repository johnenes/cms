<?php
require_once './vendor/autoload.php';



//   Validate User Registration against errors in submission
function validate_user_registrations()
{
  $min = 3;
  $max = 35;
  $error = [];

  if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $first_name                =   trim(clean($_POST['firstname']));
    $last_name                 =   trim(clean($_POST['lastname']));
    $username                  =   trim(clean($_POST['username']));
    $email                          =  trim(clean($_POST['email']));
    $password                   =  trim(clean($_POST['password']));
    $confirm_password   =  trim(clean($_POST['confirm_password']));
    $subscriber                  = 'subscriber';



    //***************************First name Validation***************************************** */  
    if (strlen($first_name) < $min) {
      $errors[]  =  "Sorry first name can not be less then {$min} characters";
    }


    if (strlen($first_name) > $max) {
      $errors[] = "Sorry first name canot be more {$max} characters ";
    }


    if (!preg_match("/^[a-zA-Z][A-Za-z0-9]*$/", $first_name)) {
      $errors[] = "Sorry first name must start with a character not a number ";
    }
    /**************************END OF  FIRST NAME VALIDATIONS*********************** */




    //***********************Last Name validations****************************************/
    if (strlen($last_name) < $min) {
      $errors[]  =  "Sorry last name can not be less then {$min} characters";
    }

    if (strlen($last_name) > $max) {
      $rrors[] = "Sorry last name canot be more {$max} characters ";
    }

    if (!preg_match("/^[a-zA-Z][A-Za-z0-9]*$/", $last_name)) {
      $errors[] = "Sorry last name must start with a character not a number ";
    }
    /**************************END OF LAST NAME VALIDATIONS**************************** */



    //****************************** Username validations*********************************************/

    if (strlen($username) < $min) {
      $errors[]  =  "Sorry username can not be less then {$min} characters";
    }

    if (strlen($username) > $max) {
      $errors[] = "Sorry username canot be more {$max} characters ";
    }

    if (!preg_match("/^[a-zA-Z][A-Za-z0-9]*$/", $username)) {
      $errors[] = "Sorry username    must start with a character not a number";
    }
    /********************END OF USERNAME VALIDATIONS************************************* */




    //******************************************Password validations********************************/

    if ($password !== $confirm_password) {
      $errors[]  = "Sorry password don't match";
    }
    /************************END PASSWORD VALIDATION**************************************** */


    /********************Valiadate Email  and Username if exists ************************************/
    if ($password < $min) {
      $errors[] = "Sorry password can not be less then {$min} characters";
    }



    if (email_exists($email)) {
      $errors[] = "Sorry that email already registered";
    }

    if (username_exists($username)) {
      $errors[] = "Sorry that username is already taken";
    }
    /***************************END OF EMAIL AND USERNAME CHECK IF EXISTS */







    //***************************************Check for errors ************************************/

    if (!empty($errors)) {
      foreach ($errors as $error) {
        /***************************************Diplay Errors Here************************************/
        echo validation_error_display($error);
      }
    } else {
      if (register_user($first_name, $last_name, $username, $email, $password, $subscriber)) {
        set_message("<p class='bg-dark  text-center text-white'>Please check your email or spam folder for activation link</p>");
        redirect("index.php");
      } else {
        set_message("<p class='bg-danger text-center'>Sorry We could not register the user</p>");
        redirect("index.php");
      }
    }
  }
  /******************END OF POST REQUEST*********************************************** */
}
/****************END OF VALIDATION_USER_REGISTRATIONS********************************** */



//************************Check if Username exists in the database***********************/
function  username_exists($username)
{
  $sql = "SELECT  id  FROM users WHERE  username='$username'   ";
  $result =  execute($sql);
  confirm($result);
  if (row_count($result) == 1) {
    return true;
  } else {
    return false;
  }
}
/*************END OF CHECK IF USERNAME EXIST */




//******************Check if email exist in the database*****************************/
function email_exists($email)
{
  $sql = "SELECT  id  FROM users WHERE  email='$email'   ";
  $result =  execute($sql);
  confirm($result);
  if (row_count($result) == 1) {
    return true;
  } else {
    return false;
  }
}
/**********************END CHECK IF USEER EAMIL EXISTS*******************/


//**********************************Sending email activation code************************************** */
function  send_email($email, $subject, $message, $headers = null)
{
  $mail = new  PHPMailer\PHPMailer\PHPMailer();
  $mail->isSMTP();
  $mail->Host              =   Config::SMTP_HOST;
  $mail->Username     =  Config::SMTP_USER;
  $mail->Password      =  Config::SMTP_PASSWORD;
  $mail->Port               =  Config::SMTP_PORT;
  $mail->SMTPAuth    = true;
  $mail->SMTPSecure  = 'tls';
  $mail->isHTML(true);
  $mail->CharSet = 'UTF-8';


  $mail->setFrom('info@icybersec.org ', 'John Nelson');
  $mail->addAddress($email);

  $mail->Subject      = $subject;
  $mail->Body          = $message;
  $mail->AltBody     = $message;
  #finally send email
  if (!$mail->Send()) {
    echo 'failed sanding'  .   $mail->ErrorInfo;
    //return false;
  } else {
    echo "message send  ";
    #return true;
  }
  $mail->smtpClose(); //close the smtp connection

  return  mail($email, $subject, $message, $headers);
}



//**********************************Sending email activation code************************************** */
// function  send_email($email, $subject, $message, $headers,){     
//      return  mail ($email, $subject ,$message,$headers);   
//}




//Function to generate OTP
function validation_codes($n)
{

  // Take a generator string which consist of
  // all numeric digits
  $generator = "1N3LD57O902AUH46M8";
  $generate2 =  md5($generator . microtime(true));

  $result = "";

  for ($i = 1; $i <= $n; $i++) {
    $result .= substr($generator, (rand() % (strlen($generator))), 1);
  }

  // Return result
  return $result;
}

// Main program


//***************************** Register Users function************************** */
function register_user($first_name, $last_name, $username, $email, $password, $subscriber)
{

  $first_name                =   trim(escape($first_name));
  $last_name                 =  trim(escape($last_name));
  $username                  =  trim(escape($username));
  $email                          =  trim(escape($email));
  $password                   =  trim(escape($password));
  $subscriber                 = 'subscriber';

  if (email_exists($email)) {

    return false;
  } else if (username_exists($username)) {

    return false;
  } else {
    $password_hashed = password_hash($password,  PASSWORD_BCRYPT, array('cost' => 12));



    $n = 30;
    $validation_code  =  validation_codes($n);
    // $validation_code =  md5($username .microtime (true));
    //$session->token_generator();

    $sql = "INSERT  INTO users (firstname,  lastname, username, email,user_role, passwd ,validation_code, active )";
    $sql .= "VALUES ('$first_name', '$last_name','$username','$email','$subscriber', '$password_hashed','$validation_code','0')";
    execute($sql);

    /**********************Send activation link after registering accounts */
    $subject  = 'Activate Account';

    $message = "
        
         
        
         <div class=''><div class='aHl'></div><div id=':nz' tabindex='-1'></div><div id=':no' class='ii gt'><div id=':nn' class='a3s aiL msg-4962985035510753523'><div class='adM'> 

            </div><u></u>


                
            <div style='padding:0;margin:0;background-color:#f6f6f6'>
                
                <table dir='ltr' width='100%' cellspacing='0' cellpadding='0' border='0' bgcolor='#f6f6f6'>
                    <tbody><tr>
                    <td class='m_-4962985035510753523mlTemplateContainer' align='center'>
                    
                    
                            
                <table style='width:860px;min-width:860px' class='m_-4962985035510753523mobileHide' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                  <tbody><tr>
                    <td align='center'>
                      <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                        <tbody><tr>
                          <td colspan='2' height='20'></td>
                        </tr>
                        <tr>
                                                        <td style='font-family:'Open Sans',Arial,Helvetica,sans-serif;font-size:0px;color:#f6f6f6;line-height:0px;max-height:0px;max-width:0px;opacity:0;overflow:hidden'>
                                <div style='display:none;max-height:0px;overflow:hidden'>
                                Complete your signup.
                                </div>
                                <div style='display:none;max-height:0px;overflow:hidden'>
                                    ‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                    &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                    &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                    &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                </div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='2' height='20'></td>
                        </tr>
                      </tbody></table>
                    </td>
                  </tr>
                </tbody></table>
                <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                  <tbody><tr>
                    <td>
                      
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#673DE6' align='center'>
                        <tbody><tr>
                          <td>
                            <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#673DE6' align='center'>
                              <tbody><tr>
                                <td>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td align='center'>
                                        <table style='border-top:11px solid #673de6;border-collapse:initial' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                          
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                        <tbody><tr>
                          <td>
                            <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                              <tbody><tr>
                                <td>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-30' style='line-height:30px;min-height:30px' height='21'></td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                                        <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                          <tbody><tr>
											<td align='left'>
                                              
                                            </td>
                                          </tr>
                                        </tbody></table>
                                      
                                        <img src='company Logo'
                                       id='m_-4962985035510753523logoBlock-6' alt='' style='display:block' class='CToWUd' width='130' border='0'></td>
                      
                                  
                                       </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='20'></td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                      
                      
                        <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                          <tbody><tr>
                            <td>
                              <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                                <tbody><tr>
                                  <td>
                                    <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                      <tbody><tr>
                                        <td class='m_-4962985035510753523mlContentOuter' align='center'>
                                          <table style='border-top:1px solid #dadce0;border-collapse:initial' class='m_-4962985035510753523mlContentTable' width='620' cellspacing='0' cellpadding='0' border='0' align='center'>
                                            <tbody><tr>
                                              <td style='line-height:10px;min-height:10px' height='20'>
                                                 <img src='https://ci5.googleusercontent.com/proxy/BFWENKPKa5rpWxb3WQg_EEMZRszPGOVy6hOHfJdaNDMvFPJ8ffo214JW3x7hc2jEE5bJdbbYuXhLlT7UZhRiLw=s0-d-e1-ft#https://cdn.hostinger.com/mailer/spacerv2.gif' alt='' style='display:block' class='CToWUd' width='1' height='1' border='0'>
                                              </td>
                                            </tr>
                                          </tbody></table>
                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                      
                      
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                        <tbody><tr>
                          <td>
                            <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                              <tbody><tr>
                                <td>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                        <td style='line-height:10px;min-height:10px' height='10'></td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                                        <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                          <tbody><tr>

                                            <td id='m_-4962985035510753523bodyText-10' style='font-family:Helvetica,sans-serif;font-size:14px;line-height:24px;color:#727586'>
                                              
                                            <p style='margin-top:0px;margin-bottom:0px;line-height:32px;font-weight:400px;font-size:16px'>Hello  $first_name </p>
                                              <p style='margin-top:0px;margin-bottom:0px;line-height:32px;font-weight:400px;font-size:16px'>
                                               Thank you for joining Icybersec. Click the button below to confirm that this is correct email to reach you.
                                                </p>



                                            </td>
                                          </tr>
                                        </tbody></table>
                                      </td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='17'></td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                      
                      
                      
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
             <tbody><tr>
               <td>
                 <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                   <tbody><tr>
                     <td>
                       <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                         <tbody><tr>
                           <td style='line-height:10px;min-height:10px' height='10'></td>
                         </tr>
                       </tbody></table>
                       <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                         <tbody><tr>
                           <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                             <table style='width:100%;min-width:100%' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                               <tbody><tr>
                                   <td align='left'>
                                     <table style='width:250px;min-width:250px' width='' cellspacing='0' cellpadding='0' border='0'>
                                       <tbody><tr>
                                         <td class='m_-4962985035510753523mlContentButton' style='font-family:Helvetica,sans-serif;padding-bottom:10px;padding-top:10px' align='left'>


                                           <a class='m_-4962985035510753523mlContentButton'
                                             href=\"http://localhost/authemail-/activate.php?email={$email}&code=$validation_code\"
                                             style='font-family:Helvetica,sans-serif;background-color:#673de6;border-radius:4px;color:#ffffff;display:inline-block;font-size:16px;
                                             font-weight:700;line-height:24px;padding:17px 0 17px 0;text-align:center;text-decoration:none;width:220px' >  Verify Email </a>





                                         </td>
                                       </tr>
                                     </tbody></table>
                                   </td>
                               </tr>
                             </tbody></table>
                           </td>
                         </tr>
                       </tbody></table>
                       <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                         <tbody><tr>
                           <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='20'></td>
                         </tr>
                       </tbody></table>
                     </td>
                   </tr>
                 </tbody></table>
               </td>
             </tr>
           </tbody></table>
           
            
            <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
              <tbody><tr>
                <td>
                  <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody><tr>
                      <td>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='20'></td>
                          </tr>
                        </tbody></table>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td class='m_-4962985035510753523mlContentOuter' align='center'>
                              <table style='border-top:1px solid #dadce0;border-collapse:initial' class='m_-4962985035510753523mlContentTable' width='620' cellspacing='0' cellpadding='0' border='0' align='center'>
                                <tbody><tr>
                                  <td style='line-height:10px;min-height:10px' height='10'>
                                    <img src='https://ci5.googleusercontent.com/proxy/BFWENKPKa5rpWxb3WQg_EEMZRszPGOVy6hOHfJdaNDMvFPJ8ffo214JW3x7hc2jEE5bJdbbYuXhLlT7UZhRiLw=s0-d-e1-ft#https://cdn.hostinger.com/mailer/spacerv2.gif' alt='' style='display:block' class='CToWUd' width='1' height='1' border='0'>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
<table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
  <tbody><tr>
    <td>
      <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
        <tbody><tr>
          <td>
            <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
              <tbody><tr>
                <td style='line-height:10px;min-height:10px' height='10'></td>
              </tr>
            </tbody></table>
            <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
              <tbody><tr>
                <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                  <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                    <tbody><tr>
                      <td id='m_-4962985035510753523bodyText-16' style='font-family:Helvetica,sans-serif;font-size:14px;line-height:13px;color:#6f6f6f'>
                        <p style='margin-top:0px;margin-bottom:0px;line-height:24px'><span style='color:rgb(114,117,134)'><a href='https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e=eNrLKCkpKLbS1y8vL9dLS0xOTcrPz9ZLzs%2FV98gvLsnMS08tAgDe3Qzo&amp;s=6aecfa9c307d93f27d8df56fc6119a8591dc6cc369a4ef8d999419fb320ce4ba' style='word-break:break-word;font-family:Helvetica,sans-serif;color:#727586;font-weight:400px;text-decoration:underline' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e%3DeNrLKCkpKLbS1y8vL9dLS0xOTcrPz9ZLzs%252FV98gvLsnMS08tAgDe3Qzo%26s%3D6aecfa9c307d93f27d8df56fc6119a8591dc6cc369a4ef8d999419fb320ce4ba&amp;source=gmail&amp;ust=1619277879482000&amp;usg=AFQjCNGB-kubmze9VxcyXM-sL5XcftIZ9g'>Facebook</a>&nbsp;·&nbsp;<a href='https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e=eNrLKCkpKLbS1y8vL9fLzCsuSUwvSszVS87P1c%2FILy7JzEtPLYpPz8lPSszRBwBrpRCT&amp;s=4fa28b408fe4c2f53e1b1c7581e1ed9e420f90b254071d72b48f4334b5b31cd3' style='word-break:break-word;font-family:Helvetica,sans-serif;color:#727586;font-weight:400px;text-decoration:underline' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e%3DeNrLKCkpKLbS1y8vL9fLzCsuSUwvSszVS87P1c%252FILy7JzEtPLYpPz8lPSszRBwBrpRCT%26s%3D4fa28b408fe4c2f53e1b1c7581e1ed9e420f90b254071d72b48f4334b5b31cd3&amp;source=gmail&amp;ust=1619277879482000&amp;usg=AFQjCNHHKoiUwe3YL2NYgrzchiYbUeFrKw'>Instagram</a>&nbsp;·&nbsp;<a href='https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e=eNrLKCkpKLbS1y8pzywpSS3SS87P1c%2FILy7JzEtPLQIApwsLTg%3D%3D&amp;s=491b611e32dc70e1c3b943e89bcf6393211d2939feba8b113400311483cbec0d' style='word-break:break-word;font-family:Helvetica,sans-serif;color:#727586;font-weight:400px;text-decoration:underline' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e%3DeNrLKCkpKLbS1y8pzywpSS3SS87P1c%252FILy7JzEtPLQIApwsLTg%253D%253D%26s%3D491b611e32dc70e1c3b943e89bcf6393211d2939feba8b113400311483cbec0d&amp;source=gmail&amp;ust=1619277879482000&amp;usg=AFQjCNFXuNc8vZRORlaCqUr8hBlaKPV6gQ'>Twitter</a></span>
                                              </p>
                                            </td>
                                          </tr>
                                        </tbody></table>
                                      </td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-20' style='line-height:15px;min-height:15px' height='15'></td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
            
            
            <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
              <tbody><tr>
                <td>
                  <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody><tr>
                      <td>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                              <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                <tbody><tr>
                                  <td id='m_-4962985035510753523bodyText-18' style='font-family:Helvetica,sans-serif;font-size:14px;line-height:24px;color:#727586'>
                                    <p style='margin-top:0px;margin-bottom:0px;line-height:24px'><span style='font-size:14px'><span style='font-size:14px'><span style='font-size:14px'><span style='color:#727586'>© 2004⁠–⁠2021 Hostinger International Ltd.</span></span>
                                      </span>
                                      </span>
                                    </p>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='35'></td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
            
          </td>
        </tr>
      </tbody></table>
      <table class='m_-4962985035510753523mobileHide'>
<tbody><tr>
<td colspan='2' height='20'></td>
</tr>
</tbody></table>
      
      

</td>
</tr>
</tbody></table>

<img alt='pixel' src='https://ci4.googleusercontent.com/proxy/3NE1Cz2PYPyrZm9-55hrhFPrqSXiibkSfEfXw0JO_L_ufCbjYJ4GTDqtCAUn3PWcdHLJvQWmMIwjKscPT8nCe3FFMeU4X5Ay34FK01F6gW3rWoHUCt32wMWFQRfkYgCC=s0-d-e1-ft#https://mailer.hostinger.io/o/249588157/a46881d816a14022b18c92235f00ac82/2303' class='CToWUd'></div><div class='yj6qo'></div><div class='adL'>


</div></div></div><div id=':o2' class='ii gt' style='display:none'><div id=':o3' class='a3s aiL '></div></div><div class='hi'></div></div>
        
        
              
        
        
        ";

    // $message = "<p> Hello  $first_name,  </p>
    // <p>Thank you for joining  Icybersec. Click the button below to confirm that this is correct email to reach you. </p><p></p>
    // <a href=\"http://localhost/authemail/activate.php?email=$email&code=$validation_code \" 
    // style=\"font-family:Helvetica,sans-serif;background-color:#673de6;border-radius:4px;    text-align:center;      
    // color:#ffffff;display:inline-block;font-size:16px;font-weight:700;line-height:24px;padding:17px 0 17px 0;tealign:center;text-decoration:none;width:220px\">
    // Verify Email </a>";
    // $headers =  'MIME-Version: 1.0' . "\r\n";

    $headers = "From:<info@icybersec.org>" . "\n\r";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    send_email($email, $subject, $message, $headers);

    return true;
  }
}

//****************************************Activate User email link**************************************************/

function activate_user()
{
  if ($_SERVER['REQUEST_METHOD'] == "GET") {

    if (isset($_GET['email'])) {

      $email                     =     clean($_GET["email"]);
      $validation_code    =    clean($_GET["code"]);
      $email1                     =   escape($_GET["email"]);
      $validation_code1   =    escape($_GET["code"]);

      #check in database if user exist
      $sql = "SELECT  id  FROM users   WHERE email='$email'  AND validation_code='$validation_code' ";
      $result = execute($sql);
      confirm($result);

      #check here if the user is found  
      if (row_count($result) == 1) {
        #then if found update the user active to 1 showin the user is registered.
        $sql2 = "UPDATE users  SET  active=1 , validation_code=0  WHERE email='$email1' AND validation_code='$validation_code1' ";
        $result2 = execute($sql2);
        confirm($result2);

        set_message("<p class='bg-success'>Your account has been activated ,Please login</p>");
        redirect('login.php');
      } else {
        set_message("<p class='bg-danger'>Your account could not be activated</p>");
        redirect('  login.php');
      }
    }
  }
}














#***************************Validate User Login Functions************************ */
function validate_user_login()
{
  $error = [];

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email                          =  clean($_POST['email']);
    $password                   =  clean($_POST['password']);
    $remember                  = isset($_POST['remember']);

    if (empty($email)) {
      $errors[] = "Email field cannot be empty";
    }

    if (empty($password)) {
      $errors[] = "Password filed cannot be empty";
    } #***************************END OF EMAIL AND USERNAME CHECK IF EXISTS */   
    #             Check for error      
    if (!empty($errors)) {
      foreach ($errors as $error) {
        #***************************************Diplay Errors Here************************************/
        echo validation_error_display($error);
      }
    } else {
      #taking user login functions
      if (login_user($email, $password, $remember)) {
        redirect('index.php');
      } else {
        echo validation_error_display('Your credentials are incorrect');
      }
    }
  }
} // Functions





#*************************** User Login Functions************************ */
function login_user($email, $password, $remember)
{
  $sql = "SELECT passwd, id  FROM users  WHERE email='" . escape($email) . "'  AND  active=1   "; #use only this if not encrypted
  $result = execute($sql);
  confirm($result);
  if (row_count($result) == 1) {
    #once password is found the fetch the password then decrypt it.
    $row = fetch_array($result);
    $db_password = $row['passwd'];
    if (password_verify($password, $db_password)) {
      if ($remember = "on") {
        setcookie('email', $email, time() + 86400);
      }
      // session();
      return true;
    } else {
      return false;
    }
    return true;
  } else {
    return false; #if no result  found return false.
  }
} #function login user  function





//*************************** Logged  in Functions************************ */
// function logged_in()
// {
//   if (isset($_SESSION['email']) || isset($_COOKIE['email'])) { // checking for session and cookies
//     return true;
//   } else {
//     return false;
//   }
// }



// Hello John,

// Thank you for joining Hostinger. Click the button below to confirm that this is correct email to reach you. 




function recovery_password()
{ #recovery password function
  if ($_SERVER["REQUEST_METHOD"] == 'POST') {

    if (isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']) {
      #Verify  if email exissts in  the database and pull it out.
      $email  = escape($_POST['email']);

      if (email_exists($email)) {


        $n = 30;
        $validation_code  =  validation_codes($n);
        // $validation_code = md5($email.microtime());    #generating code that will late be submitted to db.

        setcookie('temp_access_code', $validation_code,  time() + 900); #setting cookies 

        #updating the db before sending email varifications
        $sql = "UPDATE users SET validation_code='$validation_code'  WHERE  email='$email'  ";
        $result = execute($sql);
        confirm($result);
        #mail subject , message and headers
        $subject    = "Please reset your password";




        $message = "
         <div class=''><div class='aHl'></div><div id=':nz' tabindex='-1'></div><div id=':no' class='ii gt'><div id=':nn' class='a3s aiL msg-4962985035510753523'><div class='adM'> 

            </div><u></u>


                
            <div style='padding:0;margin:0;background-color:#f6f6f6'>
                
                <table dir='ltr' width='100%' cellspacing='0' cellpadding='0' border='0' bgcolor='#f6f6f6'>
                    <tbody><tr>
                    <td class='m_-4962985035510753523mlTemplateContainer' align='center'>
                    
                    
                            
                <table style='width:860px;min-width:860px' class='m_-4962985035510753523mobileHide' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                  <tbody><tr>
                    <td align='center'>
                      <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                        <tbody><tr>
                          <td colspan='2' height='20'></td>
                        </tr>
                        <tr>
                                                        <td style='font-family:'Open Sans',Arial,Helvetica,sans-serif;font-size:0px;color:#f6f6f6;line-height:0px;max-height:0px;max-width:0px;opacity:0;overflow:hidden'>
                                <div style='display:none;max-height:0px;overflow:hidden'>
                                Complete your signup.
                                </div>
                                <div style='display:none;max-height:0px;overflow:hidden'>
                                    ‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                    &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                    &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                    &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp; &nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌&nbsp;‌
                                </div>
                          </td>
                        </tr>
                        <tr>
                          <td colspan='2' height='20'></td>
                        </tr>
                      </tbody></table>
                    </td>
                  </tr>
                </tbody></table>
                <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                  <tbody><tr>
                    <td>
                      
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#673DE6' align='center'>
                        <tbody><tr>
                          <td>
                            <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#673DE6' align='center'>
                              <tbody><tr>
                                <td>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td align='center'>
                                        <table style='border-top:11px solid #673de6;border-collapse:initial' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                        </table>
                                      </td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                          
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                        <tbody><tr>
                          <td>
                            <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                              <tbody><tr>
                                <td>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-30' style='line-height:30px;min-height:30px' height='21'></td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                                        <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                          <tbody><tr>
											<td align='left'>
                                              
                                            </td>
                                          </tr>
                                        </tbody></table>
                                      
                                        <img src='company Logo'
                                       id='m_-4962985035510753523logoBlock-6' alt='' style='display:block' class='CToWUd' width='130' border='0'></td>
                      
                                  
                                       </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='20'></td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                      
                      
                        <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                          <tbody><tr>
                            <td>
                              <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                                <tbody><tr>
                                  <td>
                                    <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                      <tbody><tr>
                                        <td class='m_-4962985035510753523mlContentOuter' align='center'>
                                          <table style='border-top:1px solid #dadce0;border-collapse:initial' class='m_-4962985035510753523mlContentTable' width='620' cellspacing='0' cellpadding='0' border='0' align='center'>
                                            <tbody><tr>
                                              <td style='line-height:10px;min-height:10px' height='20'>
                                                 <img src='https://ci5.googleusercontent.com/proxy/BFWENKPKa5rpWxb3WQg_EEMZRszPGOVy6hOHfJdaNDMvFPJ8ffo214JW3x7hc2jEE5bJdbbYuXhLlT7UZhRiLw=s0-d-e1-ft#https://cdn.hostinger.com/mailer/spacerv2.gif' alt='' style='display:block' class='CToWUd' width='1' height='1' border='0'>
                                              </td>
                                            </tr>
                                          </tbody></table>
                                        </td>
                                      </tr>
                                    </tbody></table>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                      
                      
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                        <tbody><tr>
                          <td>
                            <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                              <tbody><tr>
                                <td>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                        <td style='line-height:10px;min-height:10px' height='10'></td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                                        <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                          <tbody><tr>

                                            <td id='m_-4962985035510753523bodyText-10' style='font-family:Helvetica,sans-serif;font-size:14px;line-height:24px;color:#727586'>
                                              
                                            <p style='margin-top:0px;margin-bottom:0px;line-height:32px;font-weight:400px;font-size:16px'> </p>
                                              <p style='margin-top:0px;margin-bottom:0px;line-height:32px;font-weight:400px;font-size:16px'>

                                                Here is your password reset code <b> $validation_code</b>


                                                </p>



                                            </td>
                                          </tr>
                                        </tbody></table>
                                      </td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='17'></td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
                      
                      
                      
                      <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
             <tbody><tr>
               <td>
                 <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                   <tbody><tr>
                     <td>
                       <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                         <tbody><tr>
                           <td style='line-height:10px;min-height:10px' height='10'></td>
                         </tr>
                       </tbody></table>
                       <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                         <tbody><tr>
                           <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                             <table style='width:100%;min-width:100%' width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                               <tbody><tr>
                                   <td align='left'>
                                     <table style='width:250px;min-width:250px' width='' cellspacing='0' cellpadding='0' border='0'>
                                       <tbody><tr>
                                         <td class='m_-4962985035510753523mlContentButton' style='font-family:Helvetica,sans-serif;padding-bottom:10px;padding-top:10px' align='left'>


                                           <a class='m_-4962985035510753523mlContentButton'
                                             href=\"http://localhost/authemail/code.php?email=$email&code=$validation_code \" 
                                              style='font-family:Helvetica,sans-serif;background-color:#673de6;border-radius:4px;color:#ffffff;display:inline-block;font-size:16px;
                                              font-weight:700;line-height:24px;padding:17px 0 17px 0;text-align:center;text-decoration:none;width:220px' >   reset your password </a>





                                         </td>
                                       </tr>
                                     </tbody></table>
                                   </td>
                               </tr>
                             </tbody></table>
                           </td>
                         </tr>
                       </tbody></table>
                       <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                         <tbody><tr>
                           <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='20'></td>
                         </tr>
                       </tbody></table>
                     </td>
                   </tr>
                 </tbody></table>
               </td>
             </tr>
           </tbody></table>
           
            
            <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
              <tbody><tr>
                <td>
                  <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody><tr>
                      <td>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='20'></td>
                          </tr>
                        </tbody></table>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td class='m_-4962985035510753523mlContentOuter' align='center'>
                              <table style='border-top:1px solid #dadce0;border-collapse:initial' class='m_-4962985035510753523mlContentTable' width='620' cellspacing='0' cellpadding='0' border='0' align='center'>
                                <tbody><tr>
                                  <td style='line-height:10px;min-height:10px' height='10'>
                                    <img src='https://ci5.googleusercontent.com/proxy/BFWENKPKa5rpWxb3WQg_EEMZRszPGOVy6hOHfJdaNDMvFPJ8ffo214JW3x7hc2jEE5bJdbbYuXhLlT7UZhRiLw=s0-d-e1-ft#https://cdn.hostinger.com/mailer/spacerv2.gif' alt='' style='display:block' class='CToWUd' width='1' height='1' border='0'>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
<table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
  <tbody><tr>
    <td>
      <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
        <tbody><tr>
          <td>
            <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
              <tbody><tr>
                <td style='line-height:10px;min-height:10px' height='10'></td>
              </tr>
            </tbody></table>
            <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
              <tbody><tr>
                <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                  <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                    <tbody><tr>
                      <td id='m_-4962985035510753523bodyText-16' style='font-family:Helvetica,sans-serif;font-size:14px;line-height:13px;color:#6f6f6f'>
                        <p style='margin-top:0px;margin-bottom:0px;line-height:24px'><span style='color:rgb(114,117,134)'><a href='https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e=eNrLKCkpKLbS1y8vL9dLS0xOTcrPz9ZLzs%2FV98gvLsnMS08tAgDe3Qzo&amp;s=6aecfa9c307d93f27d8df56fc6119a8591dc6cc369a4ef8d999419fb320ce4ba' style='word-break:break-word;font-family:Helvetica,sans-serif;color:#727586;font-weight:400px;text-decoration:underline' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e%3DeNrLKCkpKLbS1y8vL9dLS0xOTcrPz9ZLzs%252FV98gvLsnMS08tAgDe3Qzo%26s%3D6aecfa9c307d93f27d8df56fc6119a8591dc6cc369a4ef8d999419fb320ce4ba&amp;source=gmail&amp;ust=1619277879482000&amp;usg=AFQjCNGB-kubmze9VxcyXM-sL5XcftIZ9g'>Facebook</a>&nbsp;·&nbsp;<a href='https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e=eNrLKCkpKLbS1y8vL9fLzCsuSUwvSszVS87P1c%2FILy7JzEtPLYpPz8lPSszRBwBrpRCT&amp;s=4fa28b408fe4c2f53e1b1c7581e1ed9e420f90b254071d72b48f4334b5b31cd3' style='word-break:break-word;font-family:Helvetica,sans-serif;color:#727586;font-weight:400px;text-decoration:underline' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e%3DeNrLKCkpKLbS1y8vL9fLzCsuSUwvSszVS87P1c%252FILy7JzEtPLYpPz8lPSszRBwBrpRCT%26s%3D4fa28b408fe4c2f53e1b1c7581e1ed9e420f90b254071d72b48f4334b5b31cd3&amp;source=gmail&amp;ust=1619277879482000&amp;usg=AFQjCNHHKoiUwe3YL2NYgrzchiYbUeFrKw'>Instagram</a>&nbsp;·&nbsp;<a href='https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e=eNrLKCkpKLbS1y8pzywpSS3SS87P1c%2FILy7JzEtPLQIApwsLTg%3D%3D&amp;s=491b611e32dc70e1c3b943e89bcf6393211d2939feba8b113400311483cbec0d' style='word-break:break-word;font-family:Helvetica,sans-serif;color:#727586;font-weight:400px;text-decoration:underline' target='_blank' data-saferedirecturl='https://www.google.com/url?q=https://mailer.hostinger.io/249588157/a46881d816a14022b18c92235f00ac82/2303?e%3DeNrLKCkpKLbS1y8pzywpSS3SS87P1c%252FILy7JzEtPLQIApwsLTg%253D%253D%26s%3D491b611e32dc70e1c3b943e89bcf6393211d2939feba8b113400311483cbec0d&amp;source=gmail&amp;ust=1619277879482000&amp;usg=AFQjCNFXuNc8vZRORlaCqUr8hBlaKPV6gQ'>Twitter</a></span>
                                              </p>
                                            </td>
                                          </tr>
                                        </tbody></table>
                                      </td>
                                    </tr>
                                  </tbody></table>
                                  <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                                    <tbody><tr>
                                      <td class='m_-4962985035510753523spacingHeight-20' style='line-height:15px;min-height:15px' height='15'></td>
                                    </tr>
                                  </tbody></table>
                                </td>
                              </tr>
                            </tbody></table>
                          </td>
                        </tr>
                      </tbody></table>
            
            
            <table class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
              <tbody><tr>
                <td>
                  <table class='m_-4962985035510753523mlContentTable' style='width:860px;min-width:860px' width='860' cellspacing='0' cellpadding='0' border='0' bgcolor='#ffffff' align='center'>
                    <tbody><tr>
                      <td>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td style='padding:0px 120px' class='m_-4962985035510753523mlContentOuter' align='center'>
                              <table width='100%' cellspacing='0' cellpadding='0' border='0' align='center'>
                                <tbody><tr>
                                  <td id='m_-4962985035510753523bodyText-18' style='font-family:Helvetica,sans-serif;font-size:14px;line-height:24px;color:#727586'>
                                    <p style='margin-top:0px;margin-bottom:0px;line-height:24px'><span style='font-size:14px'><span style='font-size:14px'><span style='font-size:14px'><span style='color:#727586'>© 2004⁠–⁠2021 Hostinger International Ltd.</span></span>
                                      </span>
                                      </span>
                                    </p>
                                  </td>
                                </tr>
                              </tbody></table>
                            </td>
                          </tr>
                        </tbody></table>
                        <table style='width:860px;min-width:860px' class='m_-4962985035510753523mlContentTable' width='860' cellspacing='0' cellpadding='0' border='0' align='center'>
                          <tbody><tr>
                            <td class='m_-4962985035510753523spacingHeight-20' style='line-height:20px;min-height:20px' height='35'></td>
                          </tr>
                        </tbody></table>
                      </td>
                    </tr>
                  </tbody></table>
                </td>
              </tr>
            </tbody></table>
            
          </td>
        </tr>
      </tbody></table>
      <table class='m_-4962985035510753523mobileHide'>
<tbody><tr>
<td colspan='2' height='20'></td>
</tr>
</tbody></table>
      
      

</td>
</tr>
</tbody></table>

<img alt='pixel' src='https://ci4.googleusercontent.com/proxy/3NE1Cz2PYPyrZm9-55hrhFPrqSXiibkSfEfXw0JO_L_ufCbjYJ4GTDqtCAUn3PWcdHLJvQWmMIwjKscPT8nCe3FFMeU4X5Ay34FK01F6gW3rWoHUCt32wMWFQRfkYgCC=s0-d-e1-ft#https://mailer.hostinger.io/o/249588157/a46881d816a14022b18c92235f00ac82/2303' class='CToWUd'></div><div class='yj6qo'></div><div class='adL'>


</div></div></div><div id=':o2' class='ii gt' style='display:none'><div id=':o3' class='a3s aiL '></div></div><div class='hi'></div></div>
        ";





        // $message = " Here is your password reset code <b> {$validation_code} </b><br>
        // Click here 
        // <a href=\"http://localhost/authemail/activate.php?email=johneneojo@aol.com&code=4U41538AL70UL568905N4880M226U9 \" >  to reset your password</a>";

        $headers = "From:<info@icybersec.org>" . "\n\r";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        send_email($email, $subject, $message, $headers);
        set_message("<p class='bg-dark text-center text-white'> Please check your email  or spam folder for a password reset code </p>");
        redirect('index.php');
      } else {
        echo validation_error_display('This email does not exists');
      } #end of email exists

    } else {
      # if token and session email not set redirect user to index
      redirect('index.php');
    } #end of token session

    if (isset($_POST['cancel_submit'])) {
      redirect("login.php");
    }
  } #Post request
  // http://localhost/authemail/code.php?email=Henryjohn@aol.com&code=011ca1b1e648773a7b69963861b862bd   
  // http://localhost/authemail/code.php?email=henryjohn@aol.com&code=0d7176e770a09adf2132b0bf9afa7a8d

}

#***************************Code Validation************************ */
function validation_code()
{
  if (isset($_COOKIE['temp_access_code'])) {

    if (!isset($_GET['email'])  &&  !isset($_GET['code'])) {
      redirect('index.php');
    } else if (empty($_GET['email'])  || empty($_GET['code'])) {
      redirect('index.php');
    } #end else if 
    else {
      if (isset($_POST["code"])) { #This post code is coming from code.php form
        $email                   =  clean($_GET['email']);
        $validation_code = clean($_POST['code']);
        $email                   = escape($email);
        $validation_code = escape($validation_code);
        #findout if the code exist in db
        $sql = "SELECT id FROM users WHERE validation_code='$validation_code' AND email='$email' ";
        $result = execute($sql);
        confirm($result);
        #check if we have the validation code.
        if (row_count($result) == 1) {
          setcookie('temp_access_code', $validation_code,  time() + 900); #setting cookies  before redirecting 



          redirect("reset.php?email=$email&code=$validation_code");
        } else {
          echo  validation_error_display("Worng validation code");
        }
      } # nexted if post isset
    } # end of else 
  } #end of Check if !get in email and code from the url 
  else {
    set_message("<p class='bg-danger text-center' > Sorry your validation cookie  was expired</p>");
    redirect('recover.php');
  } //Cookie isset functions

} # end of validation code 


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['code_cancel'])) {
    redirect("recover.php");
  }
}

#*************************** Password Reset  functions************************ */
function password_reset()
{

  if (isset($_COOKIE['temp_access_code'])) { #check 4 expirations

    if (isset($_GET['email']) && isset($_GET['code'])) {
      #checking to make sure data is gotten from the form.
      if (isset($_SESSION['token']) &&  isset($_POST['token'])) {

        if ($_POST['token'] === $_SESSION['token']) {
          # if  session token is not same with post token then check for get email  and code. 
          if ($_POST['password'] === $_POST['confirm_password']) { #check for password if the same

            $update_password = password_hash($_POST['password'], PASSWORD_BCRYPT, array("cost" => 12));

            $sql = "UPDATE  users SET passwd = '" . escape($update_password) . "', validation_code = 0, active=1  WHERE email='" . escape($_GET['email']) . "'  ";
            $result = execute($sql);
            confirm($sql);
            set_message("<p class='bg-success text-center' >Your password has be updated, please login</p>");
            redirect('login.php');
          }
        }
      }
    }
  } else {
    set_message("<p class='bg-danger text-center'>Sorry your time has expired </p>");
    redirect('recover.php');
  }
}#function password reset           