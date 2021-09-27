
<!doctype html>
<html>
<head>
<meta charset="utf-8"><title>Blackjack 1on1</title>
<link rel="stylesheet" href="styles.css">
<meta description="Section made with CSS" />
</head>
<body>
<header><div id="h"><H1 style="color: red; text-align:center;">Blackjack!</H1></div></header>
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

$bj = FALSE;
$bust = FALSE;
//echo "Round is".$_POST["round"];
if ($_POST["choice"]=="hit" or $_POST["choice"]=="stand")
{
    $test1 = json_decode(htmlspecialchars_decode($_POST["ph"]));
    $test2 = json_decode(htmlspecialchars_decode($_POST["dh"]));

    
    $phand = $test1;
    $dhand = $test2;
    $hide = json_decode(htmlspecialchars_decode($_POST["hh"]));
    $Deck = json_decode(htmlspecialchars_decode($_POST["deck"]));
    $msg = json_decode(htmlspecialchars_decode($_POST["mb"]));
    if ($_POST["choice"]=="hit") 
        { 
            moveCard(0,$Deck,$phand);
            
            if (getTotal($phand)==21) {
                if ($bj != TRUE) array_push($msg,"You get Blackjack!");
                moveCard(0,$hide,$dhand);
                $bj = TRUE;
            }
            elseif (getTotal($phand)>21) {
                array_push($msg,"You bust!");
                moveCard(0,$hide,$dhand);
                $bust=TRUE;
                
            }
            else {
                array_push($msg,"You're safe.");
            }
        }
    elseif ($_POST["choice"]=="stand")
    {
        moveCard(0,$hide,$dhand);
        while (getTotal($dhand)<17) {
            moveCard(0,$Deck,$dhand);
        }
        
        
        if (getTotal($dhand)==21) {
            if ($bust != TRUE) array_push($msg, "Dealer gets Blackjack!");
            $bust = TRUE;
        }
        elseif (getTotal($dhand) > 21)
        {
                array_push($msg, "Dealer busts!");
                $bust = TRUE;
        }
        else {
                if (getTotal($dhand) >= (getTotal($phand))) {
                    array_push($msg, "You lose!");
                    $bust = TRUE;
                }
                elseif (getTotal($dhand) < getTotal($phand)) {
                array_push($msg,"You win!");
                $bj = TRUE;
            
                }
            }
    }

}
if (($_POST["choice"]=="new") or ($_POST["choice"]==""))
{

    $Vallist = ["2","3","4","5","6","7","8", "9","10", "J", "Q","K", "A"];
    $Vallist2 = [2,3,4,5,6,7,8, 9,10, "J", "Q","K", "A"];
    $CardSuits = ["Clu","Spa", "Hearts", "Dia"];
    $Deck = [];
    $msg = [];
    $bust = FALSE;
    $bj = FALSE;

    //Create whole deck
    foreach ($Vallist2 as $x){
        foreach($CardSuits as $y) {
            $val = $x.$y;
            array_push($Deck,$val);
        }
    }

    //Shuffle deck 100 times
    for ($i=0;$i<520;$i++) {
        $r1 = rand(0,count($Deck)-1);
        $r2 = rand(0,count($Deck)-1);
        flip($Deck,$r1,$r2);
    }


    //print_r($Deck); 
    
    $phand = [];
    //tableArray($Deck);
    moveCard(0,$Deck,$phand);
    moveCard(0,$Deck,$phand);

    $dhand = [];
    $hide = [];
    moveCard(0,$Deck,$dhand);
    moveCard(0,$Deck,$hide);
}

tableArray($dhand,$hide);
echo "<br><div id=\"lt\"> Dealer has ".getTotal($dhand)."</div><br>";
if (getTotal($dhand)==21) {array_push($msg, "Dealer has Blackjack!"); $bust=TRUE; }
//echo "<br>Val is ".getTotal($hide)."<br>";
tableArray($phand,[]);
echo "<br><div id=\"lt\"> You have ".getTotal($phand)."</div>";
if (getTotal($phand)==21) {array_push($msg, "You have Blackjack!"); $bj= TRUE; }


?>

<div>
<form style="text-align: center;" action="index.php" method="POST">
<div id="lt" style="text-align:center;">
<?php
    echo "<input type=\"hidden\" name=\"ph\" value=\"".htmlspecialchars(json_encode($phand))."\">";
    echo "<input type=\"hidden\" name=\"dh\" value=\"".htmlspecialchars(json_encode($dhand))."\">";
    echo "<input type=\"hidden\" name=\"hh\" value=\"".htmlspecialchars(json_encode($hide))."\">";
    echo "<input type=\"hidden\" name=\"deck\" value=\"".htmlspecialchars(json_encode($Deck))."\">";
    echo "<input type=\"hidden\" name=\"mb\" value=\"".htmlspecialchars(json_encode($msg))."\">";
    echo "<input type=\"hidden\" name=\"round\" value=\"true\">";

    if (($bust)or($bj)) {
        echo "<input type=\"hidden\" name=\"choice\" value=\"new\">Again?";
        echo "<input type=\"submit\">";
    
    }
    else {
        echo "<input type=\"radio\" name=\"choice\" value=\"hit\">Hit";
        echo "<input type=\"submit\">Stand";
        echo "<input type=\"radio\" name=\"choice\" value=\"stand\">";
        
    }
?>
</div>
</form>
</div>
<footer id="f"><?php showMsg($msg);?></footer>
</body></html>
