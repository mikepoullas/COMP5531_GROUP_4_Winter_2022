<html>
<head>
<title>Country</title>
</head>
<body>
<form method="POST" action="">
    Select Your Country 
    <select name="country" onchange="this.form.submit()">
        <option value="" disabled selected>--select--</option>
        <option value="india">India</option>
        <option value="us">Us</option>
        <option value="europe">Europe</option>
    </select>
</form>
<?php
   if(isset($_POST["country"])){
       $country=$_POST["country"];
       echo "select country is => ".$country;
   }
?>
</body>
</html>