<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>

  <script>
    $(function() {  //datepicker 
        $(".form_datetime").datepicker({format: 'yyyy-mm-dd'});
    });
     </script> 
<html>
<head>
<title> Search Hotels</title>

</head>

<body>

<h1>Search Hotels</h1>
<form action="index.php" method="post">
	Destination Name: <input name="name" type = "text" placeholder='New York' ></br>
	Destination City: <input name="city" type = "text" placeholder='New York'></br>
	Region Id: <input name="region" type = "number" placeholder='178236' ></br>
	Min Trip Start Date:<input name="minTSD" size="10" type="text" value="" readonly class="form_datetime" ></br>
	Max Trip Start Date:<input name="maxTSD" size="10" type="text" value="" readonly class="form_datetime" ></br>
    Length Of Stay: <input name="LOS" type = "number" placeholder='2' ></br>
    Min Star Rating: <input type='number' name="minSR" step='0.50' min="1" max="5" placeholder='3.0' /></br>
	Max Star Rating: <input type='number' name="maxSR" step='0.50' min="1" max="5" placeholder='3.5' /></br>
	Min Guest Rating: <input type='number' name="minGR" step='0.01' min="0.00" placeholder='1.22' /></br>
	Max Guest Rating: <input type='number' name="maxGR" step='0.01' min="0.00" placeholder='4.44' /></br>
   

	<input type = "submit" value="Search>>">
</form>



<?php
if(isset($_POST['name']))
{
	// read all search fields
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


//build the query search dynamically 
$searchq="";
// two dimintion array to create query name+value 
$queryPar = array
  (
  	//encode destination name, city (could have space)
  array("&destinationName=",rawurlencode($nameq)),
  array("&destinationCity=",rawurlencode($cityq)),
  array("&RegionIds=",$region),
  // apply correct format for start/end date, check if null first so that we will not have min date 
  array("&minTripStartDate=",(($minTSD == '') ? '' : date("Y-m-d", strtotime($minTSD) ))),
  array("&maxTripStartDate=",(($maxTSD == '') ? '' : date("Y-m-d", strtotime($maxTSD) ))),
  array("&lengthOfStay=",$LOS),
  array("&minStarRating=",$minSR),
  array("&maxStarRating=",$maxSR),
  array("&minGuestRating=",$minGR),
  array("&maxGuestRating=",$maxGR)
  );


foreach ($queryPar as $query) {
	// for each parameter, add querey if value is not empty
	if (!empty($query[1]))
	{
		$searchq.=$query[0].$query[1];
	}
}


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
