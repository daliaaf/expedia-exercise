<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

  <script>
    $(function() {  //<--this is shorthand of document.ready function
        $(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
    });

     </script> 
<html>
<head>
<title> Search Hotels</title>

</head>

<body>

<form action="index.php" method="post">
	Destination Name: <input name="name" type = "text" placeholder="search hotels"></br>
	Destination City: <input name="city" type = "text" placeholder="search hotels"></br>
	Region Id: <input name="region" type = "number" ></br>
	Min Trip Start Date:<input name="minTSD" size="10" type="text" value="" readonly class="form_datetime" ></br>
	Max Trip Start Date:<input name="maxTSD" size="10" type="text" value="" readonly class="form_datetime" ></br>
    Length Of Stay: <input name="LOS" type = "number" ></br>
    Min Star Rating: <input type='number' name="minSR" step='0.50' min="1" max="5" placeholder='0.0' /></br>
	Max Star Rating: <input type='number' name="maxSR" step='0.50' min="1" max="5" placeholder='0.0' /></br>
	Min Guest Rating: <input type='number' name="minGR" step='0.01' min="0.00" placeholder='0.00' /></br>
	Max Guest Rating: <input type='number' name="maxGR" step='0.01' min="0.00" placeholder='0.00' /></br>
   

	<input type = "submit" value="Search>>">
</form>



<?php
if(isset($_POST['name']))
{
	
	$nameq= $_POST['name'];
	$cityq= $_POST['city'];
	$region= $_POST['region'];
	$minTSD = $_POST['minTSD'];
    $maxTSD = $_POST['maxTSD'];
    $LOS = $_POST['LOS'];
    $minSR = $_POST['minSR'];
    $maxSR = $_POST['maxSR'];
    $minGR = $_POST['minGR'];
    $maxGR = $_POST['maxGR'];

// Validate filed
//$nameq= preg_replace("#[^0-9a-z]#i", "", $nameq);
echo "daliaaaa".$region;

//create complex query search
    $searchq=(($nameq == '') ? '' : "&destinationName=".urlencode($nameq)).(($cityq == '') ? '' : "&destinationCity=".urlencode($cityq)).(($region == '') ? '' : "&"."regionIds=".$region).(($minTSD == '') ? '' : "&minTripStartDate=".date("Y-m-d", strtotime($minTSD) )).(($maxTSD == '') ? '' : "&maxTripStartDate=".date("Y-m-d", strtotime($maxTSD))).(($LOS == '') ? '' : "&lengthOfStay=".$LOS).(($minSR == '') ? '' : "&minStarRating=".$minSR).(($maxSR == '') ? '' : "&maxStarRating=".$maxSR).(($minGR == '') ? '' : "&minGuestRating=".$minGR).(($maxGR == '') ? '' : "&maxGuestRating=".$maxGR);

//($user['permissions'] == 'admin') ? true : false;

// domain should be configured 
	$url="https://offersvc.expedia.com/offers/v2/getOffers?scenario=deal-finder&page=foo&uid=foo&productType=Hotel".$searchq;

// Send request to resource
	$client=curl_init($url);
    curl_setopt($client,CURLOPT_RETURNTRANSFER,1);
// get response from resource
	$response=curl_exec($client);

//decode
	$result=json_decode($response);
// validate response first
   $hotels= $result->offers->Hotel;

	$count=count($hotels);
	if ($count == 0)
	{
		$output="<i>There is no search results...</i>";
		print ($output);
		print ("</br></br>Check the result using the same API URL...</br><i>".$url."</i>");
	}else
	{
		$output="";
		foreach ($hotels as $hotel) {
			
			$name=$hotel->hotelInfo->hotelName;
			$destination=$hotel->hotelInfo->hotelDestination;
			$price=$hotel->hotelPricingInfo->originalPricePerNight;
			$regionId=$hotel->hotelInfo->hotelDestinationRegionID;
			$starRating=$hotel->hotelInfo->hotelStarRating;
			$lengthOfStay=$hotel->offerDateRange->lengthOfStay;
			$guestRating=$hotel->hotelInfo->hotelGuestReviewRating;

			$output .='<tr><td>'.$name.'</td><td>'.$destination.'</td><td>'.$regionId.'</td><td>'.$starRating.'</td><td>'.$lengthOfStay.'</td><td>'.$guestRating.'</td><td>'.$price.'</td></tr>' ;
		}

		print ("<table><tr><th>Name</th><th>Destination</th><th>Region Id</th><th>Star Rating</th><th>Length Of Stay</th><th>Guest Rating</th><th>Price</th></tr>");
		print ($output);
		print ("</table>");
		print ("</br></br>Check the result using the same API URL...</br><i>".$url."</i>");


	}
}

?>
</body>

</html>
