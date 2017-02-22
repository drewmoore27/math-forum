<html>
    <head>
   <style type="text/css">
    .container {
        width: 200px;
        clear: both;
    }
    .container input {
        width: 100%;
        clear: both;
    }

    </style>
        
    <link href='https://fonts.googleapis.com/css?family=Roboto:400italic,400' rel='stylesheet'                   type='text/css'>
        
    <title>Sign Up</title>
    <link rel='shortcut icon' href='../favicon.ico'/>

    
<?php
$config_array = include '../config.php';
$mysqli = $config_array['conn'];
$header = $config_array['header'];
$stylesheet = $config_array['stylesheet'];
$icon = $config_array['icon'];
$javascript = $config_array['javascript'];

session_start();

echo "<!--Stylesheet-->
        <link rel='stylesheet' type='text/css' href='" . $stylesheet . "'/>";


 ?>
    </head>
    
 <main>
 <body>
     <h3>Signup</h3>
     <div class="container">
     <form method='post' action = 'process_new_user.php'>
       <label>Username :</label><input type="text" name = "user_name"><br /><br />
       <label>Email :</label><input type="text" name = "user_email"><br /><br />
         <label>Password :</label><input type="password" name = "user_pass"><br /><br />
         <label>Confirm Password :</label><input type="password" name = "confirm_pass"><br /><br />
         <label>Key :</label><input type="text" name = "key"><br /><br />
         <input type='submit'>
     </form></div>
     <?php
     if (isset($_SESSION['name_taken'])) {
       echo "<p>Username taken. Try another name.</p>";
     }

      if (isset($_SESSION['wrong_confirm'])) {
        echo "<p>Passwords did not match. Try again.</p>";
      }
    if (isset($_SESSION['wrong_key'])) {
        echo "<p>Incorrect key. Try again.</p>";
      }
        unset($_SESSION['name_taken']);
        unset($_SESSION['wrong_confirm']);
        unset($_SESSION['wrong_key']);
       ?>
     

 </body>
 </main>

 </html>
