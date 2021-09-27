<?php session_start(); ?>
<!doctype html>
<html>
<head>
<meta charset="utf-8"><title>Blackjack 1on1</title>
<link rel="stylesheet" href="styles.css">
<div id="h"><H1 style="color: red; text-align:center;">Blackjack!</H1></div>
<meta description="Section made with CSS" />
</head>
<body>

<?php


//Return int val of string
function getVal($i) {
    if ($i[0]=="J") return 10;
    elseif ($i[0]=="Q") return 10;
    elseif ($i[0]=="K") return 10;
    elseif ($i[0]=="1") return 10;
    elseif ($i[0]=="A") return 11;
    else return (int)$i[0];
    
}

//Return int val of an array
function getTotal($a) {
    $total = 0;
    foreach ($a as $x){
        $v = getVal($x);
        if (($v==11)) {
            $foundAce = TRUE;
            if ($v+$total > 21) $v =1;
            else $v = 11;
        }
        
        
        $total+=$v;
    }
    return $total;
}

//Flip indices x and y of array a
function flip(&$a,$x,$y){
    $t = $a[$x];
    $a[$x] = $a[$y];
    $a[$y] = $t; 
}

//Move card at i from array a to array b
function moveCard($i, &$a, &$b){
    array_push($b, $a[$i]);
    array_splice($a,$i,1);
}

//Display array as a single row of images in table
function tableArray($a,$h) {
    echo "<br><table><tr>";
    foreach ($a as $x){
        echo "<td  id=\"data\" ><img src=\"".$x.".jpg\"></td>\n";

    }
    if ($h != []) {echo "<td  id=\"data\"><img src=\"hidden.jpg\"></td>\n";}
    echo "</tr></table><br><br>";
}

function showMsg($a) {
    echo "<div id=\"m\">";
    echo $a[count($a)-1];
    /*foreach ($a as $m) {
        echo $m."<br>";
    }*/
    echo "</div>\n";
}

$_SESSION["bj"] = FALSE;
$_SESSION["bust"] = FALSE;

//echo "Round is".$_POST["round"];
$_SESSION["choice"] = $_GET["choice"];
if ($_GET["choice"]=="hit" or $_GET["choice"]=="stand")
{
    $test1 = json_decode(htmlspecialchars_decode($_SESSION["phand"]));
    $test2 = json_decode(htmlspecialchars_decode($_SESSION["dhand"]));

    
    /*$_SESSION["phand"] = $test1;
    $_SESSION["dhand"] = $test2;
    $_SESSION["hide"] = json_decode(htmlspecialchars_decode($_SESSION["hide"]));
    $_SESSION["Deck"] = json_decode(htmlspecialchars_decode($_SESSION["Deck"]));*/
    //$_SESSION["msg"] = json_decode(htmlspecialchars_decode($_SESSION["msg"]));
    if ($_GET["choice"]=="hit") 
        { 
            moveCard(0,$_SESSION["Deck"],$_SESSION["phand"]);
            
            if (getTotal($_SESSION["phand"])==21) {
                if ($_SESSION["bj"] != TRUE) array_push($_SESSION["msg"],"You get Blackjack!");
                moveCard(0,$_SESSION["hide"],$_SESSION["dhand"]);
                $_SESSION["bust"] = TRUE;
            }
            elseif (getTotal($_SESSION["phand"])>21) {
                array_push($_SESSION["msg"],"You bust!");
                moveCard(0,$_SESSION["hide"],$_SESSION["dhand"]);
                $_SESSION["bust"]=TRUE;
                
            }
            else {
                array_push($_SESSION["msg"],"You're safe.");
            }
        }
    elseif ($_GET["choice"]=="stand")
    {
        moveCard(0,$_SESSION["hide"],$_SESSION["dhand"]);
        while (getTotal($_SESSION["dhand"])<17) {
            moveCard(0,$_SESSION["Deck"],$_SESSION["dhand"]);
        }
        
        
        if (getTotal($_SESSION["dhand"])==21) {
            if ($_SESSION["bust"] != TRUE) array_push($_SESSION["msg"], "Dealer gets Blackjack!");
            $_SESSION["bust"] = TRUE;
        }
        elseif (getTotal($_SESSION["dhand"]) > 21)
        {
                array_push($_SESSION["msg"], "Dealer busts!");
                $_SESSION["bust"] = TRUE;
        }
        else {
                if (getTotal($_SESSION["dhand"]) >= (getTotal($_SESSION["phand"]))) {
                    array_push($_SESSION["msg"], "You lose!");
                    $_SESSION["bust"] = TRUE;
                }
                elseif (getTotal($_SESSION["dhand"]) < getTotal($_SESSION["phand"])) {
                array_push($_SESSION["msg"],"You win!");
                $_SESSION["bj"] = TRUE;
            
                }
            }
    }

}
if (($_GET["choice"]=="new") or ($_GET["choice"]==""))
{

    $Vallist = ["2","3","4","5","6","7","8", "9","10", "J", "Q","K", "A"];
    $Vallist2 = [2,3,4,5,6,7,8, 9,10, "J", "Q","K", "A"];
    $CardSuits = ["Clu","Spa", "Hearts", "Dia"];
    $_SESSION["Deck"] = [];
    $_SESSION["msg"] = [];
    $_SESSION["bust"] = FALSE;
    $_SESSION["bj"] = FALSE;
    
    //Create whole deck
    foreach ($Vallist2 as $x){
        foreach($CardSuits as $y) {
            $val = $x.$y;
            array_push($_SESSION["Deck"],$val);
        }
    }

    //Shuffle deck 100 times
    for ($i=0;$i<520;$i++) {
        $r1 = rand(0,count($_SESSION["Deck"])-1);
        $r2 = rand(0,count($_SESSION["Deck"])-1);
        flip($_SESSION["Deck"],$r1,$r2);
    }


    //print_r($_SESSION["Deck"]); 
    
    $_SESSION["phand"] = [];
    //tableArray($_SESSION["Deck"]);
    moveCard(0,$_SESSION["Deck"],$_SESSION["phand"]);
    moveCard(0,$_SESSION["Deck"],$_SESSION["phand"]);

    $_SESSION["dhand"] = [];
    $_SESSION["hide"] = [];
    moveCard(0,$_SESSION["Deck"],$_SESSION["dhand"]);
    moveCard(0,$_SESSION["Deck"],$_SESSION["hide"]);
}

tableArray($_SESSION["dhand"],$_SESSION["hide"]);
echo "<br><div id=\"lt\"> Dealer has ".getTotal($_SESSION["dhand"])."</div><br>";

//echo "<br>Val is ".getTotal($_SESSION["hide"])."<br>";
tableArray($_SESSION["phand"],[]);
echo "<br><div id=\"lt\"> You have ".getTotal($_SESSION["phand"])."</div>";
if (getTotal($_SESSION["phand"])==21) {
    array_push($_SESSION["msg"], "You have Blackjack!");
    $_SESSION["bj"]= TRUE;
}


?>

<div>
<div id="lt" style="text-align:center;">
<?php
    /*$_SESSION["phand"] = htmlspecialchars(json_encode($_SESSION["phand"]));
    $_SESSION["dhand"] = htmlspecialchars(json_encode($_SESSION["dhand"]));
    $_SESSION["hide"] = htmlspecialchars(json_encode($_SESSION["hide"]));
    $_SESSION["Deck"] = htmlspecialchars(json_encode($_SESSION["Deck"]));*/
    
    $round = TRUE;

    if (($_SESSION["bust"])or($_SESSION["bj"])) {
        echo "<a href=\"i2.php?choice=\"new\">Again?</a>";
        
    
    }
    else {
        echo "<a href=\"i2.php?choice=hit\">Hit</a><br>";
        echo "<a href=\"i2.php?choice=stand\">Stand</a>";
        
        
        
    }
?>
</div>
</form>
</div>
<footer id="f"><?php showMsg($_SESSION["msg"]);?></footer>
</body></html>
