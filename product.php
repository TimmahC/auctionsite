
<?php
include 'nav.php';
?>
<html>

<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js'></script>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title name='name'></title>
	<meta name="description" content="">
	<script type="text/javascript">
		var endtime;
		function getItem()
		{
			$.get(("getItem.php"), {id:<?PHP echo $_GET['id']; ?>}, function(data){
				for( i in document.getElementsByName("DESCRIPTION"))
					document.getElementsByName("DESCRIPTION")[i].innerHTML = data['DESCRIPTION'];
				for( i in document.getElementsByName("POST_PRICE"))
					document.getElementsByName("POST_PRICE")[i].innerHTML = data['POST_PRICE'];
				for( i in document.getElementsByName("POST_DATE"))
					document.getElementsByName("POST_DATE")[i].innerHTML = data['POST_DATE'];
				for( i in document.getElementsByName("POST_TIME"))
					document.getElementsByName("POST_TIME")[i].innerHTML = data['POST_TIME'];
				for( i in document.getElementsByName("POSTER_EMAIL"))
					document.getElementsByName("POSTER_EMAIL")[i].innerHTML = data['POSTER_EMAIL'];
				for( i in document.getElementsByName("NUMBER_OF_BIDS"))
					document.getElementsByName("NUMBER_OF_BIDS")[i].innerHTML = data['NUMBER_OF_BIDS'];
				for( i in document.getElementsByName("CONDITION"))
					document.getElementsByName("CONDITION")[i].innerHTML = data['CONDITION'];
				for( i in document.getElementsByName("IMAGE"))
					document.getElementsByName("IMAGE")[i].src = data['IMAGE'];
				for( i in document.getElementsByName("WINNER_EMAIL"))
					document.getElementsByName("WINNER_EMAIL")[i].innerHTML = data['WINNER_EMAIL'];

				endtime = data['END_DATE'];
				console.log(endtime);
			},"json");
		}


	</script>

<head>


	<style>
	#imagesection{
		margin: 0 0 0 8%;
		float: left;
		min-width: 140px;
		width:30%;
		height: 40%;
		//background-color:lightgrey;
		//border-right: 2px solid grey;
	}

	#productimage{
		border: 3px solid #f2f2f2;
		width: 97%;
		height: 97%;
	}

	#infosection{
		font-family: 'Roboto' sans-serif;
		padding: 5px;
		float:left;
		margin: 0 0 0 10px;
		min-width:140px;
		width: 40%;
		height: 100%;
		//background-color:#ebebe0;		
	}

	#infoTitle{
		text-transform: uppercase;
		font-size: 32px;
	}
	#infoSeller{
		
		font-size: 18px;
	}
	#seller{
		display:inline;
	}
	#price{
		display:inline;
	}
	#bidNumber{
		display:inline;
		padding-left: 20px;
		font-size:16px;
	}
	#infoTimer{
		font-size: 18px;
	}
	#timer{
		font-size: 18px;
		display:inline;
		color: green;
	}
	#bidInput{
		font-size: 16px;
		width: 100px;
		height:40px;
	}
	#bidButton{
		height:40px;
		background-color: #2b78e4;
		color: white;
		font-weight: bold;
		border: 0;
		font-family: 'Roboto', sans-serif;
	}
	#bidCondition{
		display:inline;
		text-transform: uppercase;
	}
</style>	
</head>

<body>
	<div id="imagesection">
	<img id="productImage" name="IMAGE"> 
	</div>


	<div id="infosection">

	<section id="infoTitle">
	<div name="name"></div>
	</section>
	<br>

	<section id="infoSeller">
	Sold by: <a><div id = "seller" name="POSTER_EMAIL"></div></a> <br>
	Current Bid: $<div id="price" name ="POST_PRICE"></div></strong> 
	<a id="bidNumber">[Number of bids: <div style="display:inline;"name="NUMBER_OF_BIDS"></div>]</a>
	</section>

	<section id="infoTimer">
	Time left: <div id = "timer" ></div>
	</section>

	<section id="bidPrice">
		<input id="bidInput">
		<button id="bidButton">Make a bid</button>
	</section>

	<section id="bidDesc">
		<h1>Description</h1>
		Condition: <div id="bidCondition" name="CONDITION"></div>
		<div name="DESCRIPTION"></div>
	</section>
	<div>
</body>



<script type = "text/javascript">
		function getName(){
			$.get(("getItem.php"), {id:<?PHP echo $_GET['id']; ?>}, function(data){
				for( i in document.getElementsByName("name"))
					document.getElementsByName("name")[i].innerHTML = data['NAME'];
			}, "json");
			getItem();
		}
	window.onload = getName;
	</script>

	<script>
	var clock = document.getElementById("timer");
	function getTimeRemaining(end){
		var t =Date.parse(end) - Date.now();
		  var seconds = Math.floor( (t/1000) % 60 );
		  var minutes = Math.floor( (t/1000/60) % 60 );
		  var hours = Math.floor((t/(1000*60*60)%24 ))-8;
		  var days = Math.floor( t/(1000*60*60*24) );
		  return {
		    'total': t,
		    'days': days,
		    'hours': hours,
		    'minutes': minutes,
		    'seconds': seconds
		  };
	}
			setInterval(function(){
		    var t = getTimeRemaining(endtime);
		    clock.innerHTML = t.days + 'd '+ t.hours  + 'h ' + t.minutes + 'm ' + t.seconds + 's ';
		    if(t.days > 0)
		    	clock.innerHTML = t.days + 'd '+ t.hours  + 'h ' + t.minutes + 'm ' + t.seconds + 's ';
		    else
		    {
		    	if(t.hours > 0)
		    		clock.innerHTML = t.hours  + 'h ' + t.minutes + 'm ' + t.seconds + 's ';
		    	else
		    	{
		    		if(t.minutes > 0)
		    			clock.innerHTML = t.minutes + 'm ' + t.seconds + 's ';
		    		else{
		    			if(t.seconds > 0)
		    				clock.innerHTML = t.seconds + 's ';
		    			else
		    				clock.innerHTML = 'Bidding is over.';
		    		}
		    	}
		    }
		  },1000);

	</script>
	</html>