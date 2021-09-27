function getVal($i) {
    if ($i[0]=="J") return 10;
    else if ($i[0]=="Q") { return 10;}
    else if ($i[0]=="K") return 10;
    else if ($i[0]=="1") return 10;
    else if ($i[0]=="A") return 11;
    else 
    {
        //$n = parseInt($i[0]);
        
        return parseInt($i[0]);
    }
}

function getTotal($a) {
    var $total = 0;
    for ($x of $a){
        var $v = getVal($x);
        if ($v==11) {
            //var $foundAce = TRUE;
            if (($v+$total) > 21) $v =1; else $v = 11;
        }
        
        
        $total+=$v;
    }
    return $total;
}

function tableArray($a,$h) {
    document.write( "<br><table><tr>");
    for ($x of $a){
        document.write( "<td  id='data' ><img src='"+$x+".jpg'></td>");

    }
    if ($h != []) {document.write( "<td  id='data'><img src='hidden.jpg'></td>");}
    document.write( "</tr></table><br><br>");
}


function moveCard($i, $a, $b){
    $b.push($a[$i]);
    $a.splice($i,1);
}

function printr($a){
    for ($i of $a) document.write($i+"<br>");
}

function rand($min,$max){
    $v = Math.floor(Math.random()*$max)+$min;
    return $v;
}


function flip($a,$x,$y){
    $t = $a[$x];
    $a[$x] = $a[$y];
    $a[$y] = $t; 
}

var $Vallist = Array("2","3","4","5","6","7","8", "9","10", "J", "Q","K", "A");
var $CardSuits = Array("Clu","Spa", "Hearts", "Dia");
var $Deck = Array();
var $phand = Array();
var $dhand = Array();
var $hide = Array();
var $msg = Array();
var $bust = false;
var $bj = false;



//Create whole deck
//var $x, $y, $val;
for ($x of $Vallist){
    for ($y of $CardSuits) {
        $val = $x+$y;
        $Deck.push($val);
    }
}




//Shuffle deck 100 times
for ($i=0;$i<520;$i++) {
    
    $r1 = rand(0,$Deck.length-1);
    $r2 = rand(0,$Deck.length-1);
    flip($Deck,$r1,$r2);
}

moveCard(0,$Deck,$phand);
moveCard(0,$Deck,$phand);

moveCard(0,$Deck,$dhand);
moveCard(0,$Deck,$hide);

tableArray($dhand,$hide);
//document.write("<br><div id='lt'> Dealer has "+getTotal($dhand)+"</div><br>");
//if (getTotal($dhand)==21) {array_push($msg, "Dealer has Blackjack!"); $bust=TRUE; }
//echo "<br>Val is ".getTotal($hide)."<br>";
tableArray($phand,"");
//document.write("<br><div id='lt'> You have "+getTotal($phand)+"</div>";
//if (getTotal($phand)==21) {array_push($msg, "You have Blackjack!"); $bj= TRUE; }
