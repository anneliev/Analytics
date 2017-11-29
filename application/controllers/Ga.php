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
		/*If oauth2 doesn't work, use this link*/
		 // header('***');
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
	 				'companyName' => $_SESSION['companyName'],
	 				'month' => $_SESSION['month'],
	    	));
	    	$this->load->view('footer.php');

			}catch(apiException $e){
				$msg = $e->getMessage;
				if((strpos($msg, 'invalid authentication credentials') !== false)){
					header('Location: ***');
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

		if(isset($_POST['month-traffic-table'])){
			$postArr = str_replace("'", '"', $_POST['month-traffic-table']);
			$postArr = json_decode($postArr, true);
			foreach($postArr as $key => $value){
				array_push($month_traffic, $value);
			}
			foreach($month_traffic[0]['thisYear'] as $key => $value){
				$month_traffic[1]['lastYear'][$key]['sessionsThisMonth'] = $value['sessions'];
			}
			$month_traffic = $month_traffic[1]['lastYear'];
			foreach($month_traffic as $key => $value){
				if(!isset($month_traffic[$key]['sessionsThisMonth'])){
					$month_traffic[$key]['sessionsThisMonth'] = 0;
				}
				$month_traffic[$key]['date'] = substr($month_traffic[$key]['date'], -2);
			}
		}
		
		if(isset($_POST['visitors-pie'])){
			$postArr = str_replace("'", '"', $_POST['visitors-pie']);
			$postArr = json_decode($postArr, true);
			foreach($postArr as $key => $value){
			  array_push($visitors, $value);
			}
		}

		if(isset($_POST['devices-pie'])){
			$postArr = str_replace("'", '"', $_POST['devices-pie']);
			$postArr = json_decode($postArr, true);
			foreach ($postArr as $key => $value) {
				array_push($devices, $value);
			}
		}

		if(isset($_POST['channels-table'])){
			$postArr = str_replace("'", '"', $_POST['channels-table']);
			$postArr = json_decode($postArr, true);
			foreach ($postArr as $key => $value) {
				array_push($channels_table, $value);
			}
		}

		if(isset($_POST['channels-pie'])){
			$postArr = str_replace("'", '"', $_POST['channels-pie']);
			$postArr = json_decode($postArr, true);
			foreach ($postArr as $key => $value) {
				array_push($channels_pie, $value);
			}
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

		if(isset($_POST['year-traffic-table'])){
			$postArr = str_replace("'", '"', $_POST['year-traffic-table']);
			$postArr = json_decode($postArr, true);
			foreach($postArr as $key => $value){
				array_push($year_traffic, $value);
			}
			foreach($year_traffic[0]['thisYear'] as $key => $value){
				$year_traffic[1]['lastYear'][$key]['sessionsThisYear'] = $value['sessions'];
			}
			$year_traffic = $year_traffic[1]['lastYear'];
			foreach($year_traffic as $key => $value){
				if(!isset($year_traffic[$key]['sessionsThisYear'])){
					$year_traffic[$key]['sessionsThisYear'] = 0;
				}
			}
		}
		if(isset($_POST['adWords-card'])){
			$searchArr = [];
			$groupArr = [];
			$clickArr = [];
			$viewsArr = [];
			$positionArr = [];
			$adsArr = [];

			for($i = 1; $i <= 5; $i++){
				array_push($searchArr, $_POST["adword-search$i"]);
			}
			for($i = 1; $i <= 5; $i++){
				array_push($groupArr, $_POST["adword-group$i"]);
			}
			for($i = 1; $i <= 5; $i++){
				array_push($clickArr, $_POST["adword-click$i"]);
			}
			for($i = 1; $i <= 5; $i++){
				array_push($viewsArr, $_POST["adword-views$i"]);
			}
			for($i = 1; $i <= 5; $i++){
				array_push($positionArr, $_POST["adword-position$i"]);
			}
			
			foreach ($searchArr as $key => $value) {
				array_push($adsArr, ['adSearch' => $value]);
			}
			foreach($groupArr as $key => $value){
				$adsArr[$key]['adGroup'] = $value;
			}
			foreach($clickArr as $key => $value){
				$adsArr[$key]['adClicks'] = $value;
			}
			foreach($viewsArr as $key => $value){
				$adsArr[$key]['adViews'] = $value;
			}
			foreach($positionArr as $key => $value){
				$adsArr[$key]['adPosition'] = $value;
			}
			
			$totalAdClicks = "";
			$totalAdViews = "";
			$totalAdPosition = "";
			$totalAdTop = "";
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

	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$dateRange = [];
			$sessionRange = [];
			foreach ($rows as $key) {
	  		array_unshift($dateRange, $key->dimensions[0]);
	  		array_unshift($sessionRange, intval($key->metrics[0]->values[0]));
	  	}	
		}

		$dates = [];
		for($i = 0; $i < sizeof($dateRange); $i++){
			array_push($dates, $dateRange[$i]);
		}
		$sessions = [];
		for($i = 0; $i < sizeof($dates); $i++){
			array_push($sessions, $sessionRange[$i]);
		}

		$mergedArr = [];
		$merged2 = [];

		foreach ($dates as $key => $value) {
			array_push($mergedArr, ['date' => $value]);
		}
		foreach ($sessions as $key => $value) {
			array_push($merged2, ['sessions' => $value]);
		}
		
		foreach ($merged2 as $key => $value) {
			$mergedArr[$key]['sessions'] = $value['sessions'];
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
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$dateRange = [];
			$sessionRange = [];
			foreach ($rows as $key) {
	  		array_unshift($dateRange, $key->dimensions[0]);
	  		array_unshift($sessionRange, intval($key->metrics[0]->values[0]));
	  	}	
		}
	
		$dates = [];
		for($i = 0; $i < sizeof($dateRange); $i++){
			array_push($dates, $dateRange[$i]);
		}
		$sessions = [];
		for($i = 0; $i < sizeof($dates); $i++){
			array_push($sessions, $sessionRange[$i]);
		}
		$mergedArr = [];
		$merged2 = [];
		foreach ($dates as $key => $value) {
			array_push($mergedArr, ['month' => $value]);
		}
		foreach ($sessions as $key => $value) {
			array_push($merged2, ['sessions' => $value]);
		}
		foreach ($merged2 as $key => $value) {
			$mergedArr[$key]['sessions'] = $value['sessions'];
		}
	  return $mergedArr;
	}

/*------Creates report for the channels uses to find the website. 
Doesn't use get_analytics() because it has two dimension values.
Gets analytics object from $channelResults in get_results. 
Merges the different arrays into one and translates the channels to swedish.
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

	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$source = [];
			$social = [];
			$sessions = [];
			foreach ($rows as $key) {
	  		array_push($source, $key->dimensions[0]);
	  		array_push($social, $key->dimensions[1]);
	  		array_push($sessions, $key->metrics[0]->values[0]);
	  	}	
		}
		/*used during development to see all values in array
		$arr = ["1" => "1"];
		foreach($arr as $key){
			if($source !== "cpc"){
				array_unshift($source, "cpc");
				array_unshift($social, "No");
				array_unshift($sessions, "300");
			}
		}
		*/
		$mergedArr = [];
		$merged2 = [];
		$merged3 = [];
		foreach ($source as $key => $value) {
			array_push($mergedArr, ['source' => $value]);
			
		}
		foreach ($social as $key => $value) {
			array_push($merged2, ['hasSocial' => $value]);
		}
		foreach ($sessions as $key => $value) {
			array_push($merged3, ['sessions' => $value]);
		}
		foreach ($merged2 as $key => $value) {
			$mergedArr[$key]['hasSocial'] = $value['hasSocial'];
		}
		foreach ($merged3 as $key => $value) {
			$mergedArr[$key]['sessions'] = $value['sessions'];
		}
		$merged2 = [];
		$merged3 = [];

		foreach ($mergedArr as $key => $value) {
			if($value['hasSocial'] === "Yes" && $value['source'] === "referral"){
				$mergedArr[$key]['source'] = "Socialt Nätverk";
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
		}
    return $mergedArr;
	}
/*------Creates report for the type of users visiting the website. 
Gets analytics object from $userResults in get_results(). 
Calls get_analytics() with more arguments.
Merges the different arrays into one and translates the channels to swedish.
Returns array data to $userResults-----*/
	function _get_users($analytics) 
	{
	  $gaResults = $this->_get_analytics($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth'], "ga:sessions", "ga:userType", "ga:sessions");
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$userType = [];
			$quantity = [];
			foreach ($rows as $key) {
	  		array_push($userType, $key->dimensions[0]);
	  		array_push($quantity, $key->metrics[0]->values[0]);
	  	}	
		}
		$mergedArr = [];
		$merged2 = [];
		foreach ($userType as $key => $value) {
			if($value === "New Visitor"){
				$value = "Nya";
			} else if($value === "Returning Visitor"){
				$value = "Återkommande";
			}
			array_push($mergedArr, ['userType' => $value]);
		}
		foreach ($quantity as $key => $value) {
			array_push($merged2, ['quantity' => $value]);
		}
		foreach ($merged2 as $key => $value) {
			$mergedArr[$key]['quantity'] = $value['quantity'];
		}	
		return $mergedArr;
	}
/*------Creates report for the type of devices used to visit the website. 
Gets analytics object from $deviceResults in get_results(). 
Calls get_analytics() with more arguments.
Merges the different arrays into one and translates the devices to swedish.
Returns array of data to $deviceResults-----*/
	function _get_devices($analytics) 
	{
	  $gaResults = $this->_get_analytics($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth'], "ga:sessions", "ga:deviceCategory", "ga:sessions");
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$devices = [];
			$quantity = [];
			foreach ($rows as $key) {
	  		array_push($devices, $key->dimensions[0]);
	  		array_push($quantity, $key->metrics[0]->values[0]);
	  	}	
		}
		$topDevice = [];
		for($i = 0; $i < sizeof($devices); $i++){
			array_push($topDevice, $devices[$i]);
		}
		$topUsage = [];
		for($i = 0; $i < sizeof($topDevice); $i++){
			array_push($topUsage, $quantity[$i]);
		}
		$mergedArr = [];
		$merged2 = [];
		foreach ($topDevice as $key => $value) {
			if($value === "desktop"){
				$value = "Dator";
			} else if($value === "mobile"){
				$value = "Mobil";
			}else if($value === "tablet"){
				$value = "Surfplatta";
			}
			array_push($mergedArr, ['device' => $value]);
		}
		foreach ($topUsage as $key => $value) {
			array_push($merged2, ['usage' => $value]);
		}
		foreach ($merged2 as $key => $value) {
			$mergedArr[$key]['usage'] = $value['usage'];
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
			$cities = [];
			$viewsPerCity = [];
			foreach ($rows as $key) {
	  		array_push($cities, $key->dimensions[0]);
	  		array_push($viewsPerCity, $key->metrics[0]->values[0]);
	  	}	
		}
		if(sizeof($cities) > 10){
			$len = 10;
		}else {
			$len = sizeof($cities);
		}
		$topCities = [];
		for($i = 0; $i < $len; $i++){
			array_push($topCities, $cities[$i]);
		}
		$topViews = [];
		for($i = 0; $i < sizeof($topCities); $i++){
			array_push($topViews, $viewsPerCity[$i]);
		}
		$mergedArr = [];
		$merged2 = [];
		foreach ($topCities as $key => $value) {
			array_push($mergedArr, ['city' => $value]);
		}
		foreach ($topViews as $key => $value) {
			array_push($merged2, ['views' => $value]);
		}
		foreach ($merged2 as $key => $value) {
			$mergedArr[$key]['views'] = $value['views'];
		}
		return $mergedArr;
	}
/*------Creates report for which pages on the websites users look at the most. 
Gets analytics object from $pagePathResults in get_results(). 
Calls get_analytics() with more arguments.
Returns array of data with top 10 pages to $pagePathResults-----*/	
	function _get_pageviews($analytics) 
	{
	  $gaResults = $this->_get_analytics($analytics, $_SESSION['startThisMonth'], $_SESSION['endThisMonth'], "ga:pageviews", "ga:pagePath", "ga:pageviews");
	  foreach($gaResults->reports as $key){
			$data = $key->data;
			$rows = $data->rows;
			$pagePath = [];
			$viewsPerPage = [];
			foreach ($rows as $key) {
	  		array_push($pagePath, $key->dimensions[0]);
	  		array_push($viewsPerPage, $key->metrics[0]->values[0]);
	  	}	
		}
		if(sizeof($pagePath) > 10){
			$len = 10;
		}else {
			$len = sizeof($pagePath);
		}
		$topPages = [];
		for($i = 0; $i < $len; $i++){
			array_push($topPages, $pagePath[$i]);
		}
		$topViews = [];
		for($i = 0; $i < sizeof($topPages); $i++){
			array_push($topViews, $viewsPerPage[$i]);
		}
		$mergedArr = [];
		$merged2 = [];
		foreach ($topPages as $key => $value) {
			array_push($mergedArr, ['pagePath' => $value]);
		}
		foreach ($topViews as $key => $value) {
			array_push($merged2, ['views' => $value]);
		}
		foreach ($merged2 as $key => $value) {
			$mergedArr[$key]['views'] = $value['views'];
		}
		return $mergedArr;
	}

}
