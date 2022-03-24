<?php

$txt = "Shafiq";
echo "Hello <b>$txt</b><br>";
echo "Would you like to do something, <b>$txt</b>?<br>";

// php is loosely typed - variable type doesnt matter

/*****************************************************/
echo "<br><b>Types:</b><br>";
$int = 3;
$float = 3.14;
$string = "text";
$boolean = true;
$null = null;
var_dump($int, $float, $string, $boolean, $null); //to check variable type
/*****************************************************/
echo "<br><b>Basic Arithmetic:</b><br>";
$a = 12;
$b = 4;
$c = 5.14;
var_dump($a + $b, $a - $b, $a * $c, $a / $c, $a % $c);
/*****************************************************/
echo "<br><b>Strings:</b><br>";
$firstName = "shafiq";
$lastName = "imtiaz";
$age = 26;
echo $firstName . " " . $lastName . " - " . $age;
/*****************************************************/
echo "<br><b>Boolean:</b><br>";
$is_true = true;
$is_false = false;
var_dump($is_true, $is_false);
var_dump(!$is_false);
var_dump($is_true && $is_false);
var_dump($is_true || $is_false);
/*****************************************************/
echo "<br><b>Escape Char:</b><br>";
$message = "Hello";
$name = 'Shafiq\'s';
echo "$message \n {$name} hoobla is k3wlz";
/*****************************************************/
echo "<br><b>Arrays:</b><br>";
$british_array = [
    "oi",
    "bollocks",
    "cunt"
];
$frech_array = array(
    "tu",
    "est",
    "tabanac"
);
$bangla_array = [
    3 => "ami",
    6 => "onek",
    "joss"
];
$movie_array = [
    "marvel" => "spiderman",
    "DC" => "batman",
    "fox" => "logan"
];
var_dump($frech_array);
echo "<br>";
var_dump($british_array[2]);
echo "<br>";
var_dump($bangla_array);
echo "<br>";
var_dump($movie_array["DC"]);
/*****************************************************/
echo "<br><b>Multidimensional Arrays:</b><br>";
$header = "Result";
$ilham = [
    "name" => "Ilham Morsalin",
    "gpa" => 5,
    "result" => true
];
$shafiq = [
    "name" => "Shafiq Imtiaz",
    "gpa" => 3.23,
    "result" => false
];
$values = [$header, $ilham, $shafiq];
var_dump($values);
echo "<br>";
$ilham_gpa = $values[1]["gpa"];
var_dump($ilham_gpa);
/*****************************************************/
echo "<br><b>Foreach Loops:</b><br>";
$articles = [
    "first post",
    "another post",
    "last post",
    "final post"
];
$articles_new = [
    "a" => "first post",
    "b" => "another post",
    "c" => "last post",
    "d" => "final post"
];
foreach ($articles as $location => $post) {
    echo $location, " - ", $post, " ; ";
}
echo "<br>";
foreach ($articles_new as $location => $post) {
    echo $location, " - ", $post, " ; ";
}
/*****************************************************/
echo "<br><b>Conditionals:</b><br>";
$articles = [
    "first post",
    "another post",
    "last post",
    "final post"
];
if (empty($articles) || count($articles) == 0) {
    echo "the array is empty";
} elseif (count($articles) <= 5) {
    echo "the array has " . count($articles) . " items which is less than 5";
} else {
    echo "the array has more than 5 elements";
}
/*****************************************************/
echo "<br><b>Comparisons:</b><br>";
var_dump(3 == 4);
var_dump(3 != 4);
var_dump(3 < 4);
var_dump(3 > 4);
var_dump(4 <= 4);
var_dump(4 >= 4);
/*****************************************************/
echo "<br><b>While Loops:</b><br>";
$month = 1;
while ($month <= 12) {
    echo $month . ", ";
    $month += 1;
}
/*****************************************************/
echo "<br><b>For Loops:</b><br>";
$family = [
    "Abbu",
    "Ammu",
    "Shafiq",
    "Ilham"
];
for ($i = 0; $i < count($family); $i++) {
    echo $family[$i] . ", ";
};
/*****************************************************/
echo "<br><b>Switch:</b><br>";
$week = [
    1 => "sunday",
    2 => "monday",
    3 => "tuesday",
    4 => "wednesday",
    5 => "thursday",
    6 => "friday",
    7 => "saturday"
];
$day = 3;
switch ($day) {
    case 1:
        echo $week[1];
        break;
    case 2:
        echo $week[2];
        break;
    case 3:
        echo $week[3];
        break;
    case 4:
        echo $week[4];
        break;
    case 5:
        echo $week[5];
        break;
    case 6:
        echo $week[6];
        break;
    case 7:
        echo $week[7];
        break;
    default:
        echo "day out of bounds!";
        break;
}
