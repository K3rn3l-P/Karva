<?php
include_once($_SERVER['DOCUMENT_ROOT'] . "/config/config.php");
?>

<?
function anteprimaTit($testo, $lunghezza, $puntini, $id) {
     $ellipses = $puntini;
    $testo = strip_tags($testo);
    if (strlen($testo) <= $lunghezza) {
        return $testo;
    }
    $javaid = strval($id);
    $ultimo_spazio = strrpos(substr($testo, 0, $lunghezza), ' ');
    
    $ant = substr($testo, 0, $ultimo_spazio);
    if ($ellipses) {
        $ant .= ' ...';
    }
    return $ant;
}
function anteprima($testo, $lunghezza, $puntini, $id) {
     $ellipses = $puntini;
    $testo = strip_tags($testo);
    if (strlen($testo) <= $lunghezza) {
        return $testo;
    }
    $javaid = strval($id);
    $ultimo_spazio = strrpos(substr($testo, 0, $lunghezza), ' ');
    
    $ant = substr($testo, 0, $ultimo_spazio);
    if ($ellipses) {
        $ant .= ' ...';
    }
    return $ant.'';
}
?>

			
				



<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Shaiya Note</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Language" content="en" />
</head>


<style type="text/css">
html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video{margin:0;padding:0;border:0;font-size:100%;font: inherit;vertical-align:baseline;}
body{margin:0;overflow:hidden;}
a{outline:none;text-decoration:none}

#contLeft{height:466px;width:332px;float:left;}

#contRight{
	height:300px;
	width:300px;
	float:left;
	margin:0 0 0 0px; 
	height:300px;
	width:300px;
	background-color:#000000;
	background-repeat:no-repeat;
	font-family:Arial;
	position:absolute;
			}



#title-majorEvent{height:40px;width:300px;display:block;position:absolute;top:110px;left:30px;}

#majorEvent{height:255px;width:304px;position:relative;top:107px;left:27px;display:block;border:1px solid transparent;background-image:url(../images/notice/sy-launcher-06232011-thanks-IE-for-being-stupid.png);background-repeat:repeat;}

#majorEvent:hover{border:1px solid #c1dde0;}

/*
.row{width:300px;height:80px;float:left;position:relative;}
.row-link{width:300px;height:80px;display:block;position:absolute;z-index:1;border:1px solid transparent;background:url("../images/notice/sy-launcher-06232011-thanks-IE-for-being-stupid.png") repeat;}
.row-link:hover{border:1px solid white;}
.link-01{top:151px;left:30px;}
.link-02{top:229px;left:30px;}
.link-03{top:311px;left:30px;}
.row-item-img{width:60px;height:80px;float:left;}
.row-content{width:180px;height:80px;background:url("images/text-bg.jpg") 0 0 no-repeat;float:left;position:relative;}
.item-name{font:bold 12px "Arial", sans-serif;color:#FFF;margin:5px 5px 0 10px;}
.item-descr{font:11px "Arial", sans-serif;color:#FFF;margin:5px 5px 0 10px;}
.timer{font:italic 11px "Arial", sans-serif;color:#FFF;margin:9px 0 0 10px;text-transform:uppercase;}
.timer span{font-weight:bold;}
.timer-box{width:178px;height:20px;background-color:rgba(0, 0, 0, 0.5);bottom:0;position:absolute;}
.row-currency{font:bold 14px "Arial", sans-serif;color:#FFF;text-align:center;letter-spacing:1px;width:60px;height:35px;float:left;padding:45px 0 0 0;}
.old-price{color:#404040;text-decoration:line-through;}
*/

#homeNwebmall{height:70px;width:300px;}
#home, #webmall{height:70px;width:150px;float:left;}
#home a, #webmall a{height:70px;width:150px;display:block;}
#home a:hover, #webmall a:hover{background-image:url(../images/notice/sy-launcher_034.jpg);background-repeat:no-repeat;}
#home a:hover{background-position: -364px -476px;}




#newsNevents{height:35px;width:270px;margin:-5px 0 0 0;}

#news a, #events a{
	height:17px;
	
	margin:0;
	padding:5px 0 5px 0;
	float:left;
	text-align:center;
	text-decoration:none;
	border-radius:5px 5px 0 0;
	-moz-border-radius:5px 5px 0 0;}



#news a{color:#ccecff;}
#events a{background-color:none;color:#1d6798;}

#moInfo a{
	height:19px;
	width:100px;
	margin:0 0 0 45px;
	padding:11px 5px 5px 5px;
	float:left;
	text-align:right;
	font-size:11px;
	color:#ffa500;
	text-decoration:none;}

#moInfo a:hover{color:#FFF;}

p.newsTab{background-color:#4a4941;color:#ffce0b;}
p.eventsTab{color:#9e9e9d;}
p.smallTxt{font-family:Arial;font-size:10px;color:#ffdc73;}

#contentBox{
	height:145px;
	width:270px;
	border:1px solid #b97311;
	border-left:none;
	border-right:none;
	margin:-8px 0 0 0;
	padding:0;
	font-size:11px;}

#contentBox li{margin:3px;list-style:none}


.content {display:inline-block;zoom:1;*display:inline;color:#011a21;font:bold 10px Arial ;margin:0; padding:0 0 0 5px;width:70%;}
.date {display:inline-block;*display:inline;zoom:1;font:normal 9px Arial; color:#1D6798;margin:0;padding:0;width:20%;text-align:right;vertical-align:bottom}
.content a:hover{color:#1D6798;}
.content a{color:#BBBBBB}
/*#newsContent{ display:none;}*/
#eventsContent{display:none;}


a {
  color: red;
  font-weight: bold;
  
}

li {
  color: white;
  font-size:11px;
}
</style>






<body>

	<div id="contRight">
		
		
		
		
		<div id="newsNevents">
			<div id="news">
				<a id="hideNews">Latest News</a>
			</div>
			
			<div id="moInfo">
				<a href="https://s.pinto-lime.ts.net/?p=news" target='_blank'>More News</a>
			</div>
		</div>
		<div id="contentBox">
		
		
			<div id="newsContent">				
<?php
$queryNews=$conn->prepare("SELECT top 2 * FROM PS_WebSite.dbo.NewsEN WHERE Del=0 ORDER BY Row DESC");
$queryNews->execute();
$article = 0;
 while ($rowNews = $queryNews->fetch(PDO::FETCH_NUM)){
	echo '<li>';
    echo '<dl class="article" id="article-'.$article.'">';    
    echo '<dt class="subject" ><a href="../community/index.php?row='.$rowNews[0].' " target="_blank">'; 
    echo anteprimaTit($rowNews[1], '60', true, $rowNews[0]);
    echo '</a></dt>';
    
    echo '<dd class="teaser">';
    echo anteprima($rowNews[2], '200', true, $rowNews[0]);
    echo '</dd>';
    echo "</dl>";
	echo '</li>';
	
     $article = $article+1;
}
$queryNews = null;
$conn  = null;   
?>			
				
					
			    	
				
			</div>	


			
		</div>

</div>
</body>
</html>	















