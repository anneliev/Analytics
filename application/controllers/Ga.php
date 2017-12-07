<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once './vendor/autoload.php';

class Ga extends CI_Controller {

/*-----Loads form to choose Company and Month-----*/
	public function index()
	{
		session_start();

    $this->load->view('header');
	  $this->load->view('first_page');
    $this->load->view('footer');
	}

/*-----When form is submitted gets the values 'company' which holds the company name and id and 'month'. 
Sets the date intervals for months and years and changes the month names to swedish.
Stores date data and company data in session.
Calls the function to get the Google Analytics report-----*/
	public function start_form()
	{
		session_start();
	
		$company = $_POST['company'];
		$month = $_POST['month'];
		$companyArr = (explode("-", $company));
		$companyId = $companyArr[0];
		$companyName = $companyArr[1];

		$startThisMonth = date("Y-m-d", strtotime($month));
		$endThisMonth = strtotime("last day of ".$month);
		$endThisMonth = date("Y-m-d", $endThisMonth);

		$thisYear = date("Y", strtotime($month));
		$firstThisYear = strtotime("first day of January" . $thisYear);
		$firstThisYear = date("Y-m-d", $firstThisYear);
	
		$intervalDay = new DateInterval('P1D');
		$intervalYear = new DateInterval('P1Y');
		$start = new DateTime($startThisMonth);

		$startOfMonthLastYear = $start->sub($intervalYear);
		$startOfMonthLastYear = date_format($startOfMonthLastYear, "Y-m-d");
		$endOfMonthLastYear = strtotime("last day of ".$startOfMonthLastYear);
		$endOfMonthLastYear = date("Y-m-d", $endOfMonthLastYear);

		$startThisYear = new DateTime($firstThisYear);
		$startOfLastYear = $startThisYear->sub($intervalYear);
		$startOfLastYear = date_format($startOfLastYear, "Y-m-d");

		$end = date("Y", strtotime($startOfMonthLastYear));
		$endOfLastYear = strtotime("last day of December ".$end);
		$endOfLastYear = date("Y-m-d", $endOfLastYear);
		
		if(strpos($month, 'January') !== false){
			$month = str_replace('January', 'Januari', $month);
		}else if(strpos($month, 'February') !== false){
			$month = str_replace('February', 'Februari', $month);
		}else if(strpos($month, 'March') !== false){
			$month = str_replace('March', 'Mars', $month);
		}else if(strpos($month, 'May') !== false){
			$month = str_replace('May', 'Maj', $month);
		}else if(strpos($month, 'June') !== false){
			$month = str_replace('June', 'Juni', $month);
		}else if(strpos($month, 'July') !== false){
			$month = str_replace('July', 'Juli', $month);
		}else if(strpos($month, 'August') !== false){
			$month = str_replace('August', 'Augusti', $month);
		}else if(strpos($month, 'October') !== false){
			$month = str_replace('October', 'Oktober', $month);
		}

		$_SESSION['startThisMonth'] = $startThisMonth;
		$_SESSION['endThisMonth'] = $endThisMonth;
		$_SESSION['startPrevYearMonth'] = $startOfMonthLastYear;
		$_SESSION['endPrevYearMonth'] = $endOfMonthLastYear;
		$_SESSION['firstThisYear'] = $firstThisYear;
		$_SESSION['startOfLastYear'] = $startOfLastYear;
		$_SESSION['endOfLastYear'] = $endOfLastYear;
		$_SESSION['month'] = $month;
		$_SESSION['companyId'] = $companyId;
		$_SESSION['companyName'] = $companyName;

		$this->get_results();
	}
/*-----If the access token in session isn't set, this callback function is called-----*/
	public function oauth2callback()
	{
		session_start();

	/*Create the client object and set the authorization configuration from client_secrets.json*/
		$client = new Google_Client();
		$client->setAuthConfig('/home/test/www3/client_secrets.json');
		$client->setRedirectUri('https://test3.testserver.se/index.php/ga/oauth2callback');
		$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);

	/*Handle authorization flow from the server*/
		if (! isset($_GET['code'])) {
		  $auth_url = $client->createAuthUrl();
		  header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
		} else {
		  $client->authenticate($_GET['code']);
		  $_SESSION['access_token'] = $client->getAccessToken();
		  $redirect_uri = 'https://test3.testserver.se';
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		/* If oauth2 doesn't work, use this link. Replace the client_id with the client_id from client_secrets.json*/
		 // ****');
		}
	}
/*-----Creates the Google client and when auhtorized, calls the functions to get the specific reports from Google Analytics and displays them in the view display_results-----*/
	public function get_results()
	{
		
		$client = new Google_Client();
		$client->setAuthConfig('/home/test/www3/client_secrets.json');
		$client->addScope(Google_Service_Analytics::ANALYTICS_READONLY);
	

		/* If the user has already authorized this app then get an access token else redirect to ask the user to authorize access to Google Analytics */
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {	
		  try{
			  $client->setAccessToken($_SESSION['access_token']);
			  $analytics = new Google_Service_AnalyticsReporting($client);

			  $cityResults = $this->_get_cities($analytics);
			  $pagePathResults = $this->_get_pageviews($analytics);
			  $deviceCategory = $this->_get_devices($analytics);
			  $userResults = $this->_get_users($analytics);
			  $channelResults = $this->_get_channels($analytics);
			  $monthThisYear = $this->_get_monthData($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth']);
			  $monthLastYear = $this->_get_monthData($analytics, $_SESSION['startPrevYearMonth'], $_SESSION['endPrevYearMonth']);
			  $allMonthsThisYear = $this->_get_yearData($analytics, $_SESSION['firstThisYear'], $_SESSION['endThisMonth']);
			  $allMonthsLastYear = $this->_get_yearData($analytics, $_SESSION['startOfLastYear'], $_SESSION['endOfLastYear']);
			  $e_channelResults = $this->_get_e_channels($analytics);
			  $e_deviceResults = $this->_get_e_devices($analytics);
			  $e_cityResults = $this->_get_e_cities($analytics);
			  $e_overview = $this->_get_e_overview($analytics);
			  $get_ads = $this->_get_ads($analytics);

			  $this->load->view('header.php');
				$this->load->view('display_results', array(
	 				'pagePaths' => $pagePathResults,
	 				'cities' => $cityResults,
	 				'devices' => $deviceCategory,
	 				'userType' => $userResults,
	 				'channels' => $channelResults,
	 				'thisMonth' => $monthThisYear,
	 				'lastYearMonth' => $monthLastYear,
	 				'allMonthsThisYear' => $allMonthsThisYear,
	 				'allMonthsLastYear' => $allMonthsLastYear,
	 				'e_channels' => $e_channelResults,
	 				'e_devices' => $e_deviceResults,
	 				'e_cities' => $e_cityResults,
	 				'e_overview' => $e_overview,
	 				'companyName' => $_SESSION['companyName'],
	 				'month' => $_SESSION['month'],
	    	));
	    	$this->load->view('footer.php');

			}catch(apiException $e){
				$msg = $e->getMessage;
				if((strpos($msg, 'invalid authentication credentials') !== false)){
					header($auth_url);
					/* If oauth2 doesn't work, use this link. Replace the client_id with the client_id from client_secrets.json*/
					//***');
			  }else {
			  	var_dump($e->getCode() .': '. $e->getMessage);
			  }
			}
		} else {
		  $redirect_uri = 'https://test3.testserver.se/index.php/ga/oauth2callback';
		  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
		}
	}
/*-----Checks if the user has checked checkboxes in display_results view. 
If set, decodes the string and adds in the matching empty array or takes the value from input field and stores in arrays and variables. 
Loads the view print_results with all the arrays and variables, even the empty ones. 
In the view file, checks if the array/variable is not empty and in that case passes the values into tables and JavaScript charts-----*/
	public function print_results()
	{
		session_start();

		$month_traffic = [];
		$visitors = [];
		$devices = [];
		$channels_table = [];
		$channels_pie = [];
		$pagePaths = [];
		$cities = [];
		$year_traffic = [];
		$adsArr = [];
		$totalAdClicks = "";
		$totalAdViews = "";
		$totalAdPosition = "";
		$totalAdTop = "";
		$e_commerce = [];
		$e_cities = [];

		if (isset($_POST['month-traffic-table'])) {
	    $postArr = str_replace("'", '"', $_POST['month-traffic-table']);
	    $month_traffic = json_decode($postArr, true);
	   
	   	foreach ($month_traffic['lastYear'] as $key => $value) {
	   		$month_traffic['lastYear'][$key]['date'] = substr($month_traffic['lastYear'][$key]['date'], -2);
	   	}
			foreach ($month_traffic['thisYear'] as $key => $value) {
				$month_traffic['thisYear'][$key]['date'] = substr($month_traffic['thisYear'][$key]['date'], -2);
				if(!isset($month_traffic['lastYear'][$key]['date'])){
					$month_traffic['lastYear'][$key]['date'] = strval($key);
					$month_traffic['lastYear'][$key]['sessions'] = 0;
				}
				if($month_traffic['thisYear'][$key]['date'] === $month_traffic['lastYear'][$key]['date']){
					$month_traffic['thisYear'][$key]['sessionsLastYear'] = $month_traffic['lastYear'][$key]['sessions'];
			  }
			}
			$days = array();
			foreach($month_traffic['thisYear'] as $key => $value){
				$days[$key] = $month_traffic['thisYear'][$key]['date'];
			}
			array_multisort($days, SORT_ASC, $month_traffic['thisYear']);
			$month_traffic = $month_traffic['thisYear'];
	  }
		
		if(isset($_POST['visitors-pie'])){
			$postArr = str_replace("'", '"', $_POST['visitors-pie']);
			$visitors = json_decode($postArr, true);
		}

		if(isset($_POST['devices-pie'])){
			$postArr = str_replace("'", '"', $_POST['devices-pie']);
			$devices = json_decode($postArr, true);
		}

		if(isset($_POST['channels-table'])){
			$postArr = str_replace("'", '"', $_POST['channels-table']);
			$channels_table = json_decode($postArr, true);
		}

		if(isset($_POST['channels-pie'])){
			$postArr = str_replace("'", '"', $_POST['channels-pie']);
			$channels_pie = json_decode($postArr, true);
		}

		$pageArr = [];
		for($i = 0; $i < 10; $i++){
			if(isset($_POST["pagePath-table$i"])){
				$postArr = str_replace("'", '"', $_POST["pagePath-table$i"]);
				$postArr = json_decode($postArr, true);
				array_push($pageArr, $postArr);
			}
		}
		if(!empty($pageArr)){
			foreach ($pageArr as $key => $value) {
				array_push($pagePaths, $value);
			}
		}

		$cityArr = [];
		for($i = 0; $i < 10; $i++){
			if(isset($_POST["cities-table$i"])){
				$postArr = str_replace("'", '"', $_POST["cities-table$i"]);
				$postArr = json_decode($postArr, true);
				array_push($cityArr, $postArr);
			}
		}
		if(!empty($cityArr)){
			foreach ($cityArr as $key => $value) {
				array_push($cities, $value);
			}
		}

		if (isset($_POST['year-traffic-table'])) {
	    $postArr = str_replace("'", '"', $_POST['year-traffic-table']);
	    $year_traffic = json_decode($postArr, true);

	    foreach($year_traffic['lastYear'] as $key => $value){
				if(!isset($year_traffic['thisYear'][$key]['month'])){
					$year_traffic['thisYear'][$key]['month'] = strval($key);
					$year_traffic['thisYear'][$key]['sessions'] = 0;
				}
			}
	    foreach ($year_traffic['thisYear'] as $key => $value) {
				if(!isset($year_traffic['lastYear'][$key]['month'])){
					$year_traffic['lastYear'][$key]['month'] = strval($key);
					$year_traffic['lastYear'][$key]['sessions'] = 0;
				}
				if($year_traffic['thisYear'][$key]['month'] == $year_traffic['lastYear'][$key]['month']){
				$year_traffic['thisYear'][$key]['sessionsLastYear'] = $year_traffic['lastYear'][$key]['sessions'];
			  }
			}
			$months = array();
			foreach($year_traffic['thisYear'] as $key => $value){
				$months[$key] = $year_traffic['thisYear'][$key]['month'];
			}
			array_multisort($months, SORT_ASC, $year_traffic['thisYear']);
			$year_traffic = $year_traffic['thisYear'];
    }

		if(isset($_POST['eCommerce-card'])){
			$postArr = str_replace("'", '"', $_POST['eCommerce-card']);
			$e_commerce = json_decode($postArr, true);
			
			$e_cityArr = [];
			for($i = 0; $i < 10; $i++){
				if(isset($_POST["e-cities-table$i"])){
					$cityPostArr = str_replace("'", '"', $_POST["e-cities-table$i"]);
					$cityPostArr = json_decode($cityPostArr, true);
					array_push($e_cityArr, $cityPostArr);
				}
			}
			if(!empty($e_cityArr)){
				foreach ($e_cityArr as $key => $value) {
					array_push($e_cities, $value);
				}
			}
		}

		if(isset($_POST['adWords-card'])){
			$searchArr = [];
			$groupArr = [];
			$clickArr = [];
			$viewsArr = [];
			$positionArr = [];

			for($i = 1; $i <= 5; $i++){
				array_push($searchArr, $_POST["adword-search$i"]);
				array_push($groupArr, $_POST["adword-group$i"]);
				array_push($clickArr, $_POST["adword-click$i"]);
				array_push($viewsArr, $_POST["adword-views$i"]);
				array_push($positionArr, $_POST["adword-position$i"]);
			}
			
			foreach ($searchArr as $key => $value) {
				$adsArr[] = [
					'adSearch' => $value,
					'adGroup' => $groupArr[$key],
					'adClicks' => $clickArr[$key],
					'adViews' => $viewsArr[$key],
					'adPosition' => $positionArr[$key],

				];
			}
			
			$totalAdClicks = $_POST['adword-total-click'];
			$totalAdViews = $_POST['adword-total-views'];
			$totalAdPosition = $_POST['adword-total-position'];
			$totalAdTop = $_POST['adword-total-top'];
		}

		$this->load->view('header');
		$this->load->view('print_results', array(
			'month_traffic' => $month_traffic,
			'visitors' => $visitors,
		  'devices' => $devices,
		  'channels_table' => $channels_table,
		  'channels_pie' => $channels_pie,
		  'pagePaths' => $pagePaths,
		  'cities' => $cities,
		  'year_traffic' => $year_traffic,
	 		'adsArr' => $adsArr,
	 		'totalAdClicks' => $totalAdClicks,
	 		'totalAdViews' => $totalAdViews,
	 		'totalAdPosition' => $totalAdPosition,
	 		'totalAdTop' => $totalAdTop,
	 		'e_commerce' => $e_commerce,
	 		'e_cities' => $e_cities,
	 		'companyName' => $_SESSION['companyName'],
	 		'month' => $_SESSION['month'],
		));
		$this->load->view('footer');

	}

/*-----Creates report with one metric value and one dimension value. 
Gets data from different functions first called in get_results().
Returns the report to the function that made this call-----*/
	function _get_analytics($analytics, $startDate, $endDate, $metric, $dimension, $order)
	{
		$dateRange = new Google_Service_AnalyticsReporting_DateRange();
	  $dateRange->setStartDate($startDate);
	  $dateRange->setEndDate($endDate);

	  $metric_values = [
	  	 ["expression" => $metric, "alias" => $metric], 
	  ];
	  json_encode($metric_values);

	  $dimensions = [
	  	["name" => $dimension],
	  ];
	  json_encode($dimensions);

		$ordering = new Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName($order);
		$ordering->setOrderType("VALUE");   
    $ordering->setSortOrder("DESCENDING");

	  $request = new Google_Service_AnalyticsReporting_ReportRequest();
	  $request->setViewId($_SESSION['companyId']);
	  $request->setDateRanges($dateRange);
	  $request->setOrderBys($ordering);
	  $request->setDimensions(array($dimensions));
	  $request->setMetrics(array($metric_values));

	  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
	  
	  try{
	  	$body->setReportRequests( array( $request) );
	  	$gaResults = $analytics->reports->batchGet( $body );
	  	return $gaResults;
	  }catch(Google_Exception $e){
			header('***');
		}
	}

/*------Creates report day by day for a specific month. 
Gets analytics object, startDate and endDate from $monthThisYear and $monthLastYear in get_results() and calls get_analytics() with more arguments.
Returns data to the function that made this call*/
	function _get_monthData($analytics, $startDate, $endDate) 
	{
		$gaResults = $this->_get_analytics($analytics, $startDate, $endDate, "ga:sessions", "ga:date", "ga:date");

		$mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$mergedArr = [];
			foreach ($rows as $key) {
				$mergedArr[substr($key->dimensions[0], -2)] = [
					'date' => $key->dimensions[0],
					'sessions' => intval($key->metrics[0]->values[0]),
				];
	  	}	
		}
		return $mergedArr;
	}

/*------Creates report month by month for a specific year. 
Gets analytics object, startDate and enDate from $allMonthsThisYear and $allMonthsLastYear in get_results()
and calls get_analytics() with more arguments. 
Returns data to the function that made this call*/
	function _get_yearData($analytics, $startDate, $endDate) 
	{
		$gaResults = $this->_get_analytics($analytics, $startDate, $endDate, "ga:sessions", "ga:month", "ga:month");
		$mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
				$mergedArr[$key->dimensions[0]] = [
					'month' => $key->dimensions[0],
					'sessions' => intval($key->metrics[0]->values[0]),
				];
	  	}	
		}
	  return $mergedArr;
	}

/*------Creates report for the channels uses to find the website. 
Doesn't use get_analytics() because it has two dimension values.
Gets analytics object from $channelResults in get_results. 
Merges the data into one array and translates the channels to swedish.
Returns array of data to $channelResults-----*/
	function _get_channels($analytics) 
	{
	  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
	  $dateRange->setStartDate($_SESSION['startThisMonth']);
	  $dateRange->setEndDate($_SESSION['endThisMonth']);

	  $metric_values = [
	  	 ["expression" => "ga:sessions", "alias" => "sessions"],
	  ];
	  json_encode($metric_values);

	  $dimensions = [
	  	["name" => "ga:medium"],
	  	["name" => "ga:hasSocialSourceReferral"],
	  ];
	  json_encode($dimensions);
	  
		$ordering = new Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName("ga:sessions");
		$ordering->setOrderType("VALUE");   
    $ordering->setSortOrder("DESCENDING");

	  $request = new Google_Service_AnalyticsReporting_ReportRequest();
	  $request->setViewId($_SESSION['companyId']);
	  $request->setDateRanges($dateRange);
	  $request->setOrderBys($ordering);
	  $request->setDimensions(array($dimensions));
	  $request->setMetrics(array($metric_values));

	  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
	  $body->setReportRequests( array( $request) );

	  $gaResults = $analytics->reports->batchGet( $body );

	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
	  		$mergedArr[$key->dimensions[0] . $key->dimensions[1]] = [
	  			'source' => $key->dimensions[0],
	  			'hasSocial' => $key->dimensions[1],
	  			'sessions' => $key->metrics[0]->values[0],
	  		];
	  	}	
		}
		if(!empty($mergedArr["emailNo"]) && !empty($mergedArr['referralYes'])) {
			$mergedArr['referralYes']['sessions'] += $mergedArr['emailNo']['sessions'];
			unset($mergedArr['emailNo']);
		}
		foreach ($mergedArr as $key => $value) {
			if($value['hasSocial'] === "Yes" && $value['source'] === "referral"){
				$mergedArr[$key]['source'] = "Socialt & E-post";
			}
			if($value['source'] === "(none)"){
				$mergedArr[$key]['source'] = "Direkt";
			}
			if($value['source'] === "organic"){
				$mergedArr[$key]['source'] = "Organisk";
			}
			if($value['hasSocial'] === "No" && $value['source'] === "referral"){
				$mergedArr[$key]['source'] = "Hänvisning";
			}
			if($value['source'] === "cpc"){
				$mergedArr[$key]['source'] = "Annons";
			}
			if(!in_array($key, ["referralNo", "cpcNo", "organicNo", "(none)No", "referralYes"])){
				$mergedArr['referralNo']['sessions'] += $mergedArr[$key]['sessions'];
				unset($mergedArr[$key]);
			}
		}
    return $mergedArr;
	}

/*------Creates report for the type of users visiting the website. 
Gets analytics object from $userResults in get_results(). 
Calls get_analytics() with more arguments.
Merges the data into one array and translates the channels to swedish.
Returns array data to $userResults-----*/
	function _get_users($analytics) 
	{
	  $gaResults = $this->_get_analytics($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth'], "ga:sessions", "ga:userType", "ga:sessions");

	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$userType = [];
			$quantity = [];
			foreach ($rows as $key) {
				$mergedArr[] = [
					'userType' => $key->dimensions[0],
					'quantity' => $key->metrics[0]->values[0]
				];
	  	}	
		}
		foreach ($mergedArr as $key => $value) {
			if($value['userType'] === "New Visitor"){
				$mergedArr[$key]['userType'] = "Nya";
			} else if($value['userType'] === "Returning Visitor"){
				$mergedArr[$key]['userType'] = "Återkommande";
			}
		}
		return $mergedArr;
	}

/*------Creates report for the type of devices used to visit the website. 
Gets analytics object from $deviceResults in get_results(). 
Calls get_analytics() with more arguments.
Merges the data into one array and translates the devices to swedish.
Returns array of data to $deviceResults-----*/
	function _get_devices($analytics) 
	{
	  $gaResults = $this->_get_analytics($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth'], "ga:sessions", "ga:deviceCategory", "ga:sessions");

	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
				$mergedArr[] = [
					'device' => $key->dimensions[0],
					'usage' => $key->metrics[0]->values[0],
				];
	  	}	
		}
		foreach ($mergedArr as $key => $value) {
			if($value['device'] === "desktop"){
				$mergedArr[$key]['device'] = "Dator";
			}else if($value['device'] === "mobile"){
				$mergedArr[$key]['device'] = "Mobil";
			}else if($value['device'] === "tablet"){
				$mergedArr[$key]['device'] = "Surfplatta";
			}
		}
		return $mergedArr;
	}

/*------Creates report for what city the visitors the website comes from. 
Gets analytics object from $cityResults in get_results(). 
Calls get_analytics() with more arguments.
Returns array of data with top 10 cities to $cityResults-----*/
	function _get_cities($analytics) 
	{
	  $gaResults = $this->_get_analytics($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth'], "ga:sessions", "ga:city", "ga:sessions");
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$totals = $data->totals[0]->values[0];
			foreach ($rows as $key) {
				$mergedArr[] = [
					'city' => $key->dimensions[0],
					'views' => $key->metrics[0]->values[0]/$totals * 100,
				];
	  	}	
		}
		if(!isset($mergedArr)){
			$mergedArr = [];
		}
		if(sizeof($mergedArr) > 10){
			$len = 10;
		}else {
			$len = sizeof($mergedArr);
		}

		$topCities = [];
		for($i = 0; $i < $len; $i++){
			array_push($topCities, $mergedArr[$i]);
		}
		return $topCities;
	}

/*------Creates report for which pages on the websites users look at the most. 
Gets analytics object from $pagePathResults in get_results(). 
Calls get_analytics() with more arguments.
Returns array of data with top 10 pages to $pagePathResults-----*/	
	function _get_pageviews($analytics) 
	{
	  $gaResults = $this->_get_analytics($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth'], "ga:pageviews", "ga:pagePath", "ga:pageviews");

	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
				$mergedArr[] = [
					'pagePath' => $key->dimensions[0],
					'views' => $key->metrics[0]->values[0],
				];
	  	}	
		}
		if(sizeof($mergedArr) > 10){
			$len = 10;
		}else {
			$len = sizeof($mergedArr);
		}
		$topPages = [];
		for($i = 0; $i < $len; $i++){
			array_push($topPages, $mergedArr[$i]);
		}
		return $topPages;
	}

/*------Creates report for the channels uses to make a purcahse. 
Doesn't use get_analytics() because it has two dimension and two metric values.
Gets analytics object from $e_channelResults in get_results. 
Merges the data into one array and translates the channels to swedish.
Returns array of data to $e_channelResults-----*/
	function _get_e_channels($analytics) 
	{
	  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
	  $dateRange->setStartDate($_SESSION['startThisMonth']);
	  $dateRange->setEndDate($_SESSION['endThisMonth']);

	  $metric_values = [
	  	["expression" => "ga:transactions", "alias" => "transactions"],
	  	["expression" => "ga:localTransactionRevenue", "alias" => "localTransactionRevenue"],
	  ];
	  json_encode($metric_values);

	  $dimensions = [
	  	["name" => "ga:medium"],
	  	["name" => "ga:hasSocialSourceReferral"],
	  ];
	  json_encode($dimensions);
	  
		$ordering = new Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName("ga:localTransactionRevenue");
		$ordering->setOrderType("VALUE");   
    $ordering->setSortOrder("DESCENDING");

	  $request = new Google_Service_AnalyticsReporting_ReportRequest();
	  $request->setViewId($_SESSION['companyId']);
	  $request->setDateRanges($dateRange);
	  $request->setOrderBys($ordering);
	  $request->setDimensions(array($dimensions));
	  $request->setMetrics(array($metric_values));

	  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
	  $body->setReportRequests( array( $request) );

	  $gaResults = $analytics->reports->batchGet( $body );

	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
	  		$mergedArr[$key->dimensions[0] . $key->dimensions[1]] = [
	  			'source' => $key->dimensions[0],
	  			'hasSocial' => $key->dimensions[1],
	  			'transactions' => $key->metrics[0]->values[0],
	  			'revenue' => $key->metrics[0]->values[1],
	  		];
	  	}	
		}
		if(isset($mergedArr['emailNo']) && !empty($mergedArr['referralYes'])) {
			$mergedArr['referralYes']['transactions'] += $mergedArr['emailNo']['transactions'];
			$mergedArr['referralYes']['revenue'] += $mergedArr['emailNo']['revenue'];
			unset($mergedArr['emailNo']);
		}
		foreach ($mergedArr as $key => $value) {
			if($value['hasSocial'] === "Yes" && $value['source'] === "referral"){
				$mergedArr[$key]['source'] = "Socialt & E-post";
			}
			if($value['source'] === "(none)"){
				$mergedArr[$key]['source'] = "Direkt";
			}
			if($value['source'] === "organic"){
				$mergedArr[$key]['source'] = "Organisk";
			}
			if($value['hasSocial'] === "No" && $value['source'] === "referral"){
				$mergedArr[$key]['source'] = "Hänvisning";
			}
			if($value['source'] === "cpc"){
				$mergedArr[$key]['source'] = "Annons";
			}
			if(!in_array($key, ["referralNo", "cpcNo", "organicNo", "(none)No", "referralYes"])){
				$mergedArr['referralNo']['transactions'] += $mergedArr[$key]['transactions'];
				$mergedArr['referralNo']['revenue'] += $mergedArr[$key]['revenue'];
				unset($mergedArr[$key]);
			}
		}
    return $mergedArr;
	}

/*------Creates report for the devices uses to make a purcahse. 
Doesn't use get_analytics() because it has two metric values.
Gets analytics object from $e_deviceResults in get_results. 
Merges the data into one array and translates the devices to swedish.
Returns array of data to $e_deviceResults-----*/
	function _get_e_devices($analytics) 
	{
	  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
	  $dateRange->setStartDate($_SESSION['startThisMonth']);
	  $dateRange->setEndDate($_SESSION['endThisMonth']);

	  $metric_values = [
	  	["expression" => "ga:transactions", "alias" => "transactions"],
	  	["expression" => "ga:localTransactionRevenue", "alias" => "localTransactionRevenue"],
	  ];
	  json_encode($metric_values);

	  $dimensions = [
	  	["name" => "ga:deviceCategory"],
	  ];
	  json_encode($dimensions);
	  
		$ordering = new Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName("ga:localTransactionRevenue");
		$ordering->setOrderType("VALUE");   
    $ordering->setSortOrder("DESCENDING");

	  $request = new Google_Service_AnalyticsReporting_ReportRequest();
	  $request->setViewId($_SESSION['companyId']);
	  $request->setDateRanges($dateRange);
	  $request->setOrderBys($ordering);
	  $request->setDimensions(array($dimensions));
	  $request->setMetrics(array($metric_values));

	  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
	  $body->setReportRequests( array( $request) );

	  $gaResults = $analytics->reports->batchGet( $body );

	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
				$mergedArr[] = [
					'device' => $key->dimensions[0],
					'transactions' => $key->metrics[0]->values[0],
					'revenue' => $key->metrics[0]->values[1],
				];
	  	}	
		}

		foreach ($mergedArr as $key => $value) {
			if($value['device'] === "desktop"){
				$mergedArr[$key]['device'] = "Dator";
			}else if($value['device'] === "mobile"){
				$mergedArr[$key]['device'] = "Mobil";
			}else if($value['device'] === "tablet"){
				$mergedArr[$key]['device'] = "Surfplatta";
			}
		}
		return $mergedArr; 
	}

/*------Creates report for the cities where the user who makes a purcahse is from. 
Doesn't use get_analytics() because it has two metric values.
Gets analytics object from $e_cityResults in get_results. 
Merges the data into one array.
Returns array of data to $e_cityResults-----*/
	function _get_e_cities($analytics) 
	{
	  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
	  $dateRange->setStartDate($_SESSION['startThisMonth']);
	  $dateRange->setEndDate($_SESSION['endThisMonth']);

	  $metric_values = [
	  	["expression" => "ga:transactions", "alias" => "transactions"],
	  	["expression" => "ga:localTransactionRevenue", "alias" => "localTransactionRevenue"],
	  ];
	  json_encode($metric_values);

	  $dimensions = [
	  	["name" => "ga:city"],
	  ];
	  json_encode($dimensions);
	  
		$ordering = new Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName("ga:localTransactionRevenue");
		$ordering->setOrderType("VALUE");   
    $ordering->setSortOrder("DESCENDING");

	  $request = new Google_Service_AnalyticsReporting_ReportRequest();
	  $request->setViewId($_SESSION['companyId']);
	  $request->setDateRanges($dateRange);
	  $request->setOrderBys($ordering);
	  $request->setDimensions(array($dimensions));
	  $request->setMetrics(array($metric_values));

	  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
	  $body->setReportRequests( array( $request) );

	  $gaResults = $analytics->reports->batchGet( $body );

	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
				$mergedArr[] = [
					'city' => $key->dimensions[0],
					'transactions' => $key->metrics[0]->values[0],
					'revenue' => $key->metrics[0]->values[1],
				];
	  	}	
		}
		if(sizeof($mergedArr) > 10){
			$len = 10;
		}else {
			$len = sizeof($mergedArr);
		}
		$topCities = [];
		for($i = 0; $i < $len; $i++){
			array_push($topCities, $mergedArr[$i]);
		}
		return $topCities;
	}

/*------Creates report for an overview of eCommerce. 
Doesn't use get_analytics() because it has four metric and no dimension values.
Gets analytics object from $e_overview in get_results. 
Merges the data into one array.
Returns array of data to $e_overview-----*/
	function _get_e_overview($analytics) 
	{
	  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
	  $dateRange->setStartDate($_SESSION['startThisMonth']);
	  $dateRange->setEndDate($_SESSION['endThisMonth']);

	  $metric_values = [
	  	["expression" => "ga:sessions", "alias" => "sessions"],
	  	["expression" => "ga:transactions", "alias" => "transactions"],
	  	["expression" => "ga:localTransactionRevenue", "alias" => "localTransactionRevenue"],
	  	["expression" => "ga:itemQuantity", "alias" => "itemQuantity"],
	  ];
	  json_encode($metric_values);
 
		$ordering = new Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName("ga:localTransactionRevenue");
		$ordering->setOrderType("VALUE");   
    $ordering->setSortOrder("DESCENDING");

	  $request = new Google_Service_AnalyticsReporting_ReportRequest();
	  $request->setViewId($_SESSION['companyId']);
	  $request->setDateRanges($dateRange);
	  $request->setOrderBys($ordering);
	  $request->setMetrics(array($metric_values));

	  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
	  $body->setReportRequests( array( $request) );

	  $gaResults = $analytics->reports->batchGet( $body );
	  
	  $mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			foreach ($rows as $key) {
				if($key->metrics[0]->values[1] === "0"){
					$key->metrics[0]->values[1] = 1;
				}
				$mergedArr[] = [
					'transactions' => $key->metrics[0]->values[1],
					'revenue' => $key->metrics[0]->values[2],
					'avgRevenue' => $key->metrics[0]->values[2]/$key->metrics[0]->values[1],
					'quantity' => $key->metrics[0]->values[3],
					'converts' => $key->metrics[0]->values[1]/$key->metrics[0]->values[0] * 100,
				];
	  	}	
		}
		return $mergedArr; 
	}

	function _get_ads($analytics) 
	{
	  $dateRange = new Google_Service_AnalyticsReporting_DateRange();
	  $dateRange->setStartDate('2016-11-01');
	  $dateRange->setEndDate('2016-11-30');

	  $metric_values = [
	  	["expression" => "ga:adClicks", "alias" => "adClicks"],
	  	["expression" => "ga:impressions", "alias" => "impressions"],
	  ];
	  json_encode($metric_values);

	  $dimensions = [
	  	["name" => "ga:keyword"],
	  	["name" => "ga:adGroup"],
	  ];
	  json_encode($dimensions);
	  
		$ordering = new Google_Service_AnalyticsReporting_OrderBy();
		$ordering->setFieldName("ga:adClicks");
		$ordering->setOrderType("VALUE");   
    $ordering->setSortOrder("DESCENDING");

	  $request = new Google_Service_AnalyticsReporting_ReportRequest();
	  $request->setViewId($_SESSION['companyId']);
	  $request->setDateRanges($dateRange);
	  $request->setOrderBys($ordering);
	  $request->setDimensions(array($dimensions));
	  $request->setMetrics(array($metric_values));

	  $body = new Google_Service_AnalyticsReporting_GetReportsRequest();
	  $body->setReportRequests( array( $request) );

	  $gaResults = $analytics->reports->batchGet( $body );

		$mergedArr = [];
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$totalClicks = $data->totals[0]->values[0];
			$totalImpressions = $data->totals[0]->values[1];
			foreach ($rows as $key) {
				$mergedArr[] = [
					'keyword' => $key->dimensions[0],
					'adGroup' => $key->dimensions[1],
					'adClicks' => $key->metrics[0]->values[0],
					'impressions' => $key->metrics[0]->values[1],
				];
	  	}	
		}
		if(sizeof($mergedArr) > 10){
			$len = 10;
		}else {
			$len = sizeof($mergedArr);
		}
		$topAds = [];
		for($i = 0; $i < $len; $i++){
			array_push($topAds, $mergedArr[$i]);
		}
		$topAds['totals'] = ['totalClicks' => $totalClicks, 'totalImpressions' => $totalImpressions];
		return $topAds;
	}


}
