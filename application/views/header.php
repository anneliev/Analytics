<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>GA</title>
	
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous" >
	<link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
	
	<style type="text/css">
		.row {
			padding-bottom: 15px;
		}
		.thisMonth-circle{
			color: #55ce63;
		} 
		.lastYearMonth-cirlce{
			color: #7460ee;
		} 
		.cust-card{
			border: 3px outset #d3d3d3;
			border-radius: 15px;
		}
		.small-card{
			height: 630px;
		}
		.wide-card{
			height: 530px;
			padding-right: 25px;
		}
		.table_print {
			font-size: 2.2em;
		}
		.square{
			margin: 35px 50px 35px 50px; 
			padding: 40px; 
			background-color: #01c0c8;
			color: #fff;
			border-radius: 30px;
			border: 3px outset #d3d3d3;
			text-align: center;
		}
		.square-input{
			margin: 35px 50px 35px 50px; 
			padding: 40px; 
			border-radius: 30px;
			text-align: center;
		}
		.square h1 {
			font-size: 3.5em;
		}
		.star-list{
			font-size: 2em;
		}
		#overview-month, #overview-year, #adwords-chart{
			padding-right:30px;
		}
	</style>	

	<script
  src="https://code.jquery.com/jquery-3.2.1.min.js"
  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
  crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.7/raphael.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

</head>
<body>
