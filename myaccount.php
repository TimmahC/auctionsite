<header>
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ==" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" integrity="sha384-aUGj/X2zp5rLCbBxumKTCw2Z50WgIr1vs/PFN4praOTvYXWlVyh2UtNUU0KAUhAX" crossorigin="anonymous">

</header>
<html>
<body>

   
  <?php
  include ('nav.php');
  include ('config.php');

  $conn = db2_connect( $dbname , $username , $password );
  if ($conn) {
  
     $userName = $_SESSION['username'];

     print ('<h1  style="font-weight: bold;  text-align: center; color: rgb(0,0,255)">Account View</h1>');
	   print ('<h2  style="font-weight: bold;  text-align: center;">Current Bidding</h2>');

     // Current Bid
     $sql = "SELECT ID, IMAGE, DESCRIPTION, CONDITION FROM ".$computerName.".ITEMS"; 
     $stmt = db2_prepare($conn, $sql);
     if ($stmt) {
        $result = db2_execute($stmt);
        if (!$result) {
           echo "exec errormsg: " .db2_stmt_errormsg($stmt);
           die("Failed Query");
        }
		
        while ($row = db2_fetch_array($stmt)) {   // FOR EACH ITEM
		
		  // FOR EACH ITEM
          $itemID = $row[0];
          $image = file_get_contents($row[1]);
		  $condition = $row[3];
          $desc = $row[2].'<br><br>	Condition: '.$row[3].'<br>Item #'.$itemID;

		  // CHECK IF I BID THIS ITEM
          $sql2 = "SELECT ITEM_ID, BIDDER_EMAIL FROM ".$computerName.".BIDHISTORY WHERE ITEM_ID = $itemID AND BIDDER_EMAIL = '$userName'";
          $stmt2 = db2_prepare($conn, $sql2);
          $result2 = db2_execute($stmt2);
          if (!$result2) {
             echo "exec errormsg: " .db2_stmt_errormsg($stmt2);
             die("Failed Query");
          }
		  $bid = db2_fetch_array($stmt2);
		  
		  
		  if (!$bid) {
			continue;  // NOT BIDDING  ITEM
		  }
          // I BID
		  
		  // CHECK IF ENDED
          $sql2 = "SELECT HIGHEST_BID_AMOUNT, END_DATE, END_TIME, HIGHEST_BIDDER FROM ".$computerName.".BIDS WHERE ITEM_ID = $itemID and CURRENT DATE <= END_DATE";
          $stmt2 = db2_prepare($conn, $sql2);
          $result2 = db2_execute($stmt2);
          if (!$result2) {
             echo "exec errormsg: " .db2_stmt_errormsg($stmt2);
             die("Failed Query");
          }
		  $bid = db2_fetch_array($stmt2);
		  
		  if (!$bid) {
             continue;
		  }

		  $endTime = $bid[1].' '.$bid[2];
		  $curTime = date("Y-m-d H:i:s");

		  if ( strcmp($endTime, $curTime) <= 0 ) {
			continue;
		  }
		  
          $endTime = $bid[1] . ' ' . $bid[2];
          $highestBid = $bid[0];
		  $highestBidder = $bid[3];
		  $condition = $row[3];
          $desc = $row[2].'<br><br>	Condition: '.$row[3].'<br>Item #'.$itemID;
		  
		  if ( strcmp($highestBidder, $userName) == 0) {
		     $bidStatus = 'High Bidder';
			 $bidStatusColor = 'color: rgb(0,255,0)';
		  } else {
		     $bidStatus = 'Not High Bidder';
			 $bidStatusColor = 'color: rgb(255,0,0)';
		  }
          print ('<style type="text/css">');
          print ('#a1 {');
          print ('  text-indent: 10px;');
          print ('}');
          print ('</style>');
		  //print ('<h1  style="font-weight: bold;  text-align: center;">Account View</h1><br>');
          print ('<table  style="cellpadding: 10; width: 969px; text-align: left; margin-left: auto; margin-right: auto;" border="1">');
          print ('  <tbody  id="b3">');
          print ('  <tr  align="left">');
          print ('    <td  style="width: 100%; height: 38.4333px;"  colspan="4"  rowspan="1"> <h3  style="text-align: center;">&nbsp;<span  style="font-weight: bold;'.$bidStatusColor.'">'.$bidStatus.'</spa></h3> </td>');
          print ('  </tr>');
          print ('  <tr>');
          print ('    <td  style="font-weight: bold; width: 20%; text-align: center;">Picture</td>');
          print ('    <td  style="font-weight: bold; text-align: center; vertical-align: middle; width: 50%; height: 18px;">Product Description</td>');
          print ('    <td  style="font-weight: bold; text-align: center; width: 10%; vertical-align: middle;">End Time</td>');
          print ('    <td  style="font-weight: bold; text-align: center; width: 20%; vertical-align: middle;">&nbsp;High Bid</td>');
          print ('  </tr>');
          print ('  <tr>');
          print ('    <td  style="width: 20%; text-align: center;">');
          print ('    <img src="data:image/jpeg;base64,' . base64_encode($image) . '" width="140" height="140">');
          print ('    </td>');
          print ('    <td  id="a1" style="width: 50%;">');
          print ($desc);
          print ('</td>');
          print ('    <td  style="width: 10%; text-align: center;">');
          print ($endTime);
          print ('</td>');
          print ('    <td  style="width: 20%; text-align: center;">');
          print ('$'.$highestBid);
          print ('</td>');
          print ('  </tr>');
          print ('  </tbody>');
          print ('</table>');
		  print ('<br>');
        }
      }
	
	 print ('<br><h2  style="font-weight: bold;  text-align: center;">Items Win/Lost</h2><br>');

	 
     // Ended Auctions
     $sql = "SELECT ID, IMAGE, DESCRIPTION, CONDITION FROM ".$computerName.".ITEMS"; 
     $stmt = db2_prepare($conn, $sql);
     if ($stmt) {
        $result = db2_execute($stmt);
        if (!$result) {
           echo "exec errormsg: " .db2_stmt_errormsg($stmt);
           die("Failed Query");
        }
		
        while ($row = db2_fetch_array($stmt)) {   // FOR EACH ITEM
		
		  // FOR EACH ITEM
          $itemID = $row[0];
          $image = file_get_contents($row[1]);
		  $condition = $row[3];
          $desc = $row[2].'<br><br>	Condition: '.$row[3].'<br>Item #'.$itemID;

		  // CHECK IF I BID THIS ITEM
          $sql2 = "SELECT ITEM_ID, BIDDER_EMAIL FROM ".$computerName.".BIDHISTORY WHERE ITEM_ID = $itemID AND BIDDER_EMAIL = '$userName'";
          $stmt2 = db2_prepare($conn, $sql2);
          $result2 = db2_execute($stmt2);
          if (!$result2) {
             echo "exec errormsg: " .db2_stmt_errormsg($stmt2);
             die("Failed Query");
          }
		  $bid = db2_fetch_array($stmt2);
		  
		  
		  if (!$bid) {
			continue;  // NOT BIDDING  ITEM
		  }
          // I BID
		  
		  // CHECK IF ENDED
          $sql2 = "SELECT HIGHEST_BID_AMOUNT, END_DATE, END_TIME, HIGHEST_BIDDER FROM ".$computerName.".BIDS WHERE ITEM_ID = $itemID and CURRENT DATE >= END_DATE";
          $stmt2 = db2_prepare($conn, $sql2);
          $result2 = db2_execute($stmt2);
          if (!$result2) {
             echo "exec errormsg: " .db2_stmt_errormsg($stmt2);
             die("Failed Query");
          }
		  $bid = db2_fetch_array($stmt2);
		  
		  if (!$bid) {
             continue;
		  }

		  $endTime = $bid[1].' '.$bid[2];
		  $curTime = date("Y-m-d H:i:s");

		  if ( strcmp($endTime, $curTime) > 0 ) {
			continue;
		  }
		  
          $endTime = $bid[1] . ' ' . $bid[2];
          $highestBid = $bid[0];
		  $highestBidder = $bid[3];
		  $condition = $row[3];
          $desc = $row[2].'<br><br>	Condition: '.$row[3].'<br>Item #'.$itemID;
		  
		  if ( strcmp($highestBidder, $userName) == 0) {
		     $bidStatus = 'Winning Bidder';
			 $bidStatusColor = 'color: rgb(0,255,0)';
		  } else {
		     $bidStatus = 'Lossing Bidder';
			 $bidStatusColor = 'color: rgb(255,0,0)';
		  }
          print ('<style type="text/css">');
          print ('#a1 {');
          print ('  text-indent: 10px;');
          print ('}');
          print ('</style>');
		  //print ('<h1  style="font-weight: bold;  text-align: center;">Account View</h1><br>');
          print ('<table  style="cellpadding: 10; width: 969px; text-align: left; margin-left: auto; margin-right: auto;" border="1">');
          print ('  <tbody  id="b3">');
          print ('  <tr  align="left">');
          print ('    <td  style="width: 100%; height: 38.4333px;"  colspan="4"  rowspan="1"> <h3  style="text-align: center;">&nbsp;<span  style="font-weight: bold;'.$bidStatusColor.'">'.$bidStatus.'</spa></h3> </td>');
          print ('  </tr>');
          print ('  <tr>');
          print ('    <td  style="font-weight: bold; width: 20%; text-align: center;">Picture</td>');
          print ('    <td  style="font-weight: bold; text-align: center; vertical-align: middle; width: 50%; height: 18px;">Product Description</td>');
          print ('    <td  style="font-weight: bold; text-align: center; width: 10%; vertical-align: middle;">End Time</td>');
          print ('    <td  style="font-weight: bold; text-align: center; width: 20%; vertical-align: middle;">&nbsp;High Bid</td>');
          print ('  </tr>');
          print ('  <tr>');
          print ('    <td  style="width: 20%; text-align: center;">');
          print ('    <img src="data:image/jpeg;base64,' . base64_encode($image) . '" width="140" height="140">');
          print ('    </td>');
          print ('    <td  id="a1" style="width: 50%;">');
          print ($desc);
          print ('</td>');
          print ('    <td  style="width: 10%; text-align: center;">');
          print ($endTime);
          print ('</td>');
          print ('    <td  style="width: 20%; text-align: center;">');
          print ('$'.$highestBid);
          print ('</td>');
          print ('  </tr>');
          print ('  </tbody>');
          print ('</table>');
		  print ('<br>');
        }
      }
  }
  else
  {
      die("Not connect Database");
  }
  ?>
</body>
</html>
