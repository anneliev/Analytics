<?php

echo'
	<div class="col-lg-12 col-md-12">

		<div class="row">
			<div class="col-8">
				<h1>Månadsrapport: '.$month.'</h1>
				<h1>Kund: '.$companyName.'</h1>
			</div>
			<div class="col-4 text-middle">
					<img class=" float-right" src="../../images/logo.png" alt="bluescreen logo" height="120px"/>
			</div>		
		</div>
		<hr>
		<br />
		
';
	if(!empty($e_commerce)){
		echo '
			<div class="row">
				<div class="col-lg-12">
					<h1>E-handel</h1>
				</div>		
			</div>
			';
			foreach($e_commerce['e_overview'] as $key){
			echo '
			<div class="row">
			
		  	<div class="col-lg-12 col-md-12">					
					<div class="row justify-content-center">
					  <div class="col-2 e_square">
					    <h2>Transaktioner</h2>
					    <h2 class="invisible" style="margin-bottom: 0">"</h2>
					    <h1 class="font-weight-bold">'.$key['transactions'].'</h1>
					  </div>
					  <div class="col-2 e_square">
					    <h2>Intäkter</h2>
					    <h2 class="invisible" style="margin-bottom: 0">"</h2>
					    <h1 class="font-weight-bold">'.number_format($key['revenue'],1,',',' ').' kr</h1>
					  </div>
				  
		  	    <div class="col-2 e_square">
				      <h2>Medelvärde/köp</h2>
				      <h2 class="invisible" style="margin-bottom: 0">"</h2>
				      <h1 class="font-weight-bold">'.number_format($key['avgRevenue'],1,',',' ').' kr</h1>
				    </div>
				    <div class="col-2 e_square">
				      <h2>Sålda artiklar</h2>
				      <h2 class="invisible" style="margin-bottom: 0">"</h2>
				      <h1 class="font-weight-bold">'.$key['quantity'].'</h1>
				    </div>
				    <div class="col-2 e_square">
				      <h2>Handlande besökare</h2>
				      <h1 class="font-weight-bold">'.round($key['converts'], 2).'%</h1>
				    </div>
				  </div>
				</div>

		  </div> ';
		};
		echo '

		<div class="row">
	
			<div class="col-12 ">
				<h1>Kanaler</h1>
			</div>
		
			<div class="col-lg-5 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">	
						<h1 class="card-title text-center">Intäkter</h1>
						<table class="table table-striped table_print">
						  <thead>
						    <tr>
						      <th scope="col">Sökväg</th>
						      <th scope="col" class="text-right">Intäkt</th>
						    </tr>
						  </thead>
						  <tbody>';	 
						  $totalEChannelsRevenue = 0;
							foreach($e_commerce['e_channels'] as $key){
								$totalEChannelsRevenue += $key['revenue'];
							}	
						  foreach($e_commerce['e_channels'] as $key => $value){
						  	echo '
									<tr >';
									if(isset($e_commerce['e_channels'])){
										echo '
											<td>'.$value['source'].'</td>
							      	<td class="text-right">'.number_format($value['revenue'],0,',',' ').' kr</td>
										';
										}; echo '
								    </tr>
						  		';
						  	};
						    echo '
						    <tr>
						      <td class="font-weight-bold">Totalt</td>
						      <td class="font-weight-bold text-right"">'.number_format($totalEChannelsRevenue,0,',',' ').' kr</td>
							  </tr>
						  </tbody>
						</table>
					</div>
				</div>
		  </div>

		  <div class="col-lg-7 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">
						<h1 class="card-title text-center">Transaktioner</h1>
						<hr>
						<div class="row">
							<div class="col-10">
								<canvas id="e-channels-doughnut"></canvas>
							</div>
							<div class="col-2">
								<ul style="list-style: none">	
								';
								$totalEChannelsTrans = 0;
								foreach($e_commerce['e_channels'] as $key){
									$totalEChannelsTrans += $key['transactions'];
								}	
								foreach($e_commerce['e_channels'] as $key){
									echo '
										<li>
											<h1 class="text-right font-weight-bold">'.round($key['transactions']/$totalEChannelsTrans * 100).' % </h1>
										</li>	
									';
									}
								echo '
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="row">
	
			<div class="col-12 ">
				<h1>Enheter</h1>
			</div>
		
			<div class="col-lg-5 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">	
						<h1 class="card-title text-center">Intäkter</h1>
						<table class="table table-striped table_print">
						  <thead>
						    <tr>
						      <th scope="col">Enhet</th>
						      <th scope="col" class="text-right">Intäkt</th>
						    </tr>
						  </thead>
						  <tbody>';	 
						  $totalEDevicesRevenue = 0;
							foreach($e_commerce['e_devices'] as $key){
								$totalEDevicesRevenue += $key['revenue'];
							}	
						  foreach($e_commerce['e_devices'] as $key => $value){
						  	echo '
									<tr >';
									if(isset($e_commerce['e_devices'])){
										echo '
											<td>'.$value['device'].'</td>
							      	<td class="text-right">'.number_format($value['revenue'],0,',',' ').' kr</td>
										';
										}; echo '
								    </tr>
						  		';
						  	};
						    echo '
						    <tr>
						      <td class="font-weight-bold">Totalt</td>
						      <td class="font-weight-bold text-right"">'.number_format($totalEDevicesRevenue,0,',',' ').' kr</td>
							  </tr>
						  </tbody>
						</table>
					</div>
				</div>
		  </div>

		  <div class="col-lg-7 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">
						<h1 class="card-title text-center">Transaktioner</h1>
						<hr>
						<div class="row">
							<div class="col-10">
								<canvas id="e-devices-doughnut"></canvas>
							</div>
							<div class="col-2">
								<ul style="list-style: none">	
								';
								$totalEDevicesTrans = 0;
								foreach($e_commerce['e_devices'] as $key){
									$totalEDevicesTrans += $key['transactions'];
								}	
								foreach($e_commerce['e_devices'] as $key){
									echo '
										<li>
											<h1 class="text-right font-weight-bold">'.round($key['transactions']/$totalEDevicesTrans * 100).' % </h1>
										</li>	
									';
									}
								echo '
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		';
		if(isset($e_cities)){
			echo '
		
		<div class="row  e-cards">
	
			<div class="col-12 ">
				<h1>Topp '.sizeof($e_cities).' orter</h1>
			</div>
		
			<div class="col-lg-5 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">	
						<h1 class="card-title text-center">Intäkter</h1>
						<table class="table table-striped table_print">
						  <thead>
						    <tr>
						      <th scope="col">Ort</th>
						      <th scope="col" class="text-right">Intäkt</th>
						    </tr>
						  </thead>
						  <tbody>';	 
						  $totalECitiesRevenue = 0;
							foreach($e_cities as $key){
								$totalECitiesRevenue += $key['revenue'];
							}	
						  foreach($e_cities as $key => $value){
						  	echo '
									<tr >';
									if(isset($e_cities)){
										echo '
											<td>'.$value['city'].'</td>
							      	<td class="text-right">'.number_format($value['revenue'],0,',',' ').' kr</td>
										';
										}; echo '
								    </tr>
						  		';
						  	};
						    echo '
						    <tr>
						      <td class="font-weight-bold">Totalt</td>
						      <td class="font-weight-bold text-right"">'.number_format($totalECitiesRevenue,0,',',' ').' kr</td>
							  </tr>
						  </tbody>
						</table>
					</div>
				</div>
		  </div>

		  <div class="col-lg-7 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">
						<h1 class="card-title text-center">Transaktioner</h1>
						<hr>
						<div class="row">
							<div class="col-10">
								<canvas id="e-cities-doughnut"></canvas>
							</div>
							<div class="col-2">
								<ul style="list-style: none">	
								';
								$totalECitiesTrans = 0;
								foreach($e_cities as $key){
									$totalECitiesTrans += $key['transactions'];
								}	
								foreach($e_cities as $key){
									echo '
										<li>
											<h1 class="text-right font-weight-bold">'.round($key['transactions']/$totalECitiesTrans * 100).' % </h1>
										</li>	
									';
									}
								echo '
								</ul>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>
		';
		};
		echo '
	'; 
  }

	if(!empty($month_traffic)){
		echo '
		<div class="row">
			<div class="col-lg-12 col-md-12">
				<div class="card cust-card wide-card" >
					<div class="card-body">
						<h1 class="card-title text-center">Trafik till webbplatsen dag för dag</h1>
						<div class="col-12">
							<div id="overview-month">
								 
							</div>
							<div class=" col-12 list-labels">
								<ul class="list-inline text-center">      
							    <li class="list-inline-item">
							      <h3 class="thisMonth-circle"> &#11044;</h3>
							    </li>
							    <li class="list-inline-item">
							      <h2>Aktuell månad</h2>
							    </li>
							    <li class="list-inline-item">
							      <h3 class ="lastYearMonth-cirlce"> &#11044;</h3>
							    </li>
							    <li class="list-inline-item">
							      <h2>Föregående år</h2>
							    </li>
						    </ul>
						  </div>
						</div>
				  </div>
				</div>
		  </div>	
		</div>
		'; 
	} 
	if(!empty($visitors) || !empty($pagePaths)){
	echo '
		<div class="row">
	';
	}
	if(!empty($visitors)){
		echo '
			<div class="col-lg-7 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">
						<h1 class="card-title text-center">Besökare</h1>
						<hr>
						<div class="row">
							<div class="col-10">
								<canvas id="visitors_pie"></canvas>
							</div>
							<div class="col-2">
								<ul style="list-style: none">	
								';
								$totalVisitors = 0;
								foreach($visitors as $key){
									$totalVisitors += $key['quantity'];
								}	
								foreach($visitors as $key){
									echo '
										<li>
											<h1 class="text-right font-weight-bold">'.round($key['quantity']/$totalVisitors * 100).' % </h1>
										</li>	
									';
									}
								echo '
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		'; 
	}
	if(!empty($pagePaths)){
		echo '
			<div class="col-lg-5 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">	
						<h1 class="card-title text-center">Topp '.sizeof($pagePaths).' besökta sidor</h1>
						<table class="table table-striped table_print">
						  <thead>
						    <tr>
						      <th scope="col">Sökväg</th>
						      <th scope="col" class="text-right">Visningar</th>
						    </tr>
						  </thead>
						  <tbody>';	 
						  foreach($pagePaths as $key => $value){
						  	echo '
									<tr >';
									if(isset($value['pagePath'])){
										echo '
											<td>'.$value['pagePath'].'</td>
							      	<td class="text-right">'.$value['views'].'</td>
										';
									}; echo '
							    </tr>
						  	';
						  };
						  echo '
						  </tbody>
						</table>
					</div>
				</div>
		  </div>
		'; 
	}
	if(!empty($visitors) || !empty($pagePaths)){
	echo '
		</div>
	';
	}
	if(!empty($cities) || !empty($devices)){
	echo '
		<div class="row">
	';
  }
	if(!empty($cities)){
	  echo'
		  <div class="col-lg-5 col-md-6">
			  <div class="card cust-card small-card">
				  <div class="card-body">	
						<h1 class="card-title text-center">Topp '.sizeof($cities).' orter</h1>
						<table class="table table-striped table_print">
						  <thead>
						    <tr>
						      <th scope="col">Ort</th>
						      <th scope="col" class="text-right">Besökare</th>
						    </tr>
						  </thead>
						  <tbody> ';	  
						  	foreach($cities as $key => $value){
						  		echo '
										<tr>';
										if(isset($value['city'])){
											echo '
												<td>'.$value['city'].'</td>
								      	<td class="text-right">'.round($value['views'], 2).' %</td>
											';
										}; echo '
								    </tr>
						  		';
						  	}; 
						    echo '
						  </tbody>
						</table>
					</div>
			  </div> 
		  </div>
		'; 
	}
	if(!empty($devices)){
		echo '
			<div class="col-lg-7 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">
						<h1 class="card-title text-center">Enheter</h1>
						<hr>
						<div class="row">
							<div class="col-10">
								<canvas id="devices_pie"></canvas>
							</div>
							<div class="col-2">
								<ul style="list-style: none">	
								';
								$totalDevices = 0;
								foreach($devices as $key){
									$totalDevices += $key['usage'];
								}	
								foreach($devices as $key){
									echo '
										<li>
											<h1 class="text-right font-weight-bold">'.round($key['usage']/$totalDevices * 100).' % </h1>
										</li>	
									';
									}
								echo '
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		'; 
	}	
	if(!empty($cities) || !empty($devices)){
	echo '
		</div>
	';
  }
  if(!empty($channels_pie) || !empty($channels_table)){
  echo '
		<div class="row">
	';
  }
	if(!empty($channels_pie)){
		echo '
			<div class="col-lg-7 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body">
						<h1 class="card-title text-center">Bästa kanaler</h1>
						<hr>
						<div class="row">
							<div class="col-10">
								<canvas id="channels_pie"></canvas>
							</div>
							<div class="col-2">
								<ul style="list-style: none">	
								';
								$totalChannels_pie = 0;
								foreach($channels_pie as $key){
									$totalChannels_pie += $key['sessions'];
								}	
								foreach($channels_pie as $key){
									echo '
										<li>
											<h1 class="text-right font-weight-bold">'.round($key['sessions']/$totalChannels_pie * 100).' %</h1>
										</li>	
									';
									}
								echo '
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		'; 
	}
	if(!empty($channels_table)){
		echo '
			<div class="col-lg-5 col-md-6">
				<div class="card cust-card small-card">
					<div class="card-body" style="height: 635px">	
						<h1 class="card-title text-center">Kanaler</h1>
						<table class="table table-striped table_print">
						  <thead>
						    <tr>
						      <th scope="col">Typ</th>
						      <th scope="col" class="text-right">Besök</th>
						    </tr>
						  </thead>
						  <tbody> ';	  
						  	$totalChannels = 0;
								foreach($channels_table as $key){
									$totalChannels += $key['sessions'];
								}	
						  	foreach($channels_table as $key){
						  		echo '
										<tr>';
										if(isset($key['source'])){
											echo '
												<td>'.$key['source'].'</td>
								      	<td class="text-right">'.$key['sessions'].'</td>
											';
										}; echo '
								    </tr>
						  		';
						  	};
						    echo '
						    <tr>
						      <td class="font-weight-bold">Totalt</td>
						      <td class="font-weight-bold text-right"">'.number_format($totalChannels,0,',',' ').'</td>
							  </tr>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
		'; 
	}
	if(!empty($channels_pie) || !empty($channels_table)){
	echo '
		</div>
	';
  }
	if(!empty($year_traffic)){
		echo '
			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="card cust-card wide-card">
						<div class="card-body">
							<h1 class="card-title text-center">Trafik till webbplatsen månad för månad</h1>
							<div class="col-12">
								<div id="overview-year">
										 
								</div>
								<div class="list-labels">
									<ul class="list-inline text-center">      
								    <li class="list-inline-item">
								      <h3 class="thisMonth-circle"> &#11044;</h3>
								    </li>
								    <li class="list-inline-item">
								      <h2>Aktuellt år</h2>
								    </li>
								    <li class="list-inline-item">
								      <h3 class ="lastYearMonth-cirlce"> &#11044;</h3>
								    </li>
								    <li class="list-inline-item">
								      <h2>Föregående år</h2>
								    </li>
							    </ul>
						    </div>
						  </div>
						</div>
				  </div>
		  	</div>
		  </div>
		'; 
	}
	if(!empty($adsArr)){
		foreach ($adsArr as $key => $value) {
			$numOfClicks[$key] = $value['adClicks'];
		}
		array_multisort($numOfClicks, SORT_DESC, $adsArr);
		echo '
			<div class="row">
				<div class="col-lg-12">
				<hr>
					<h1>Google Adwords</h1>
				</div>		
			</div>

			<div class="row">
				<div class="col-lg-12 col-md-12">
					<div class="card cust-card  wide-card">
						<div class="card-body">
							<h1 class="card-title text-center">Topp 5 sökord</h1>
							<div class="col-12">
								<div> 
									<canvas id="adwords-chart" height= "70"></canvas>
								</div>
						  </div>
						</div>
				  </div>
		  	</div>
		  </div>
			<div class="row">
		  	<div class="col-lg-12 col-md-12">
					<div class="card cust-card small-card">
						<div class="card-body">
							<h1 class="card-title text-center">Topp 5 sökord detaljerat</h1>
							<table class="table table-bordered table_print">
						  <thead>
						    <tr>
						      <th scope="col">Sökord</th>
									<th scope="col">Annonsgrupp</th>
									<th scope="col" class="text-right">Klick</th>
									<th scope="col" class="text-right">Visningar</th>
									<th scope="col" class="text-right">Position</th>
						    </tr>
						  </thead>
						  <tbody> ';	  
						  	foreach($adsArr as $key){
						  		echo '
										<tr>
											<td>'.$key['adSearch'].'</td>
								      <td>'.$key['adGroup'].'</td>
								      <td class="text-right">'.number_format($key['adClicks'],0,',',' ').'</td>
								      <td class="text-right">'.number_format($key['adViews'],0,',',' ').'</td>
								      <td class="text-right">'.$key['adPosition'].'</td>
								    </tr>
						  		';
						  	};
						    echo '
						  </tbody>
						</table>
						</div>
				  </div>
		  	</div>
			</div>
			<div class="row">
			
		  	<div class="col-lg-12 col-md-12">
					<div class="col-12 ">
				    <h1 class="font-weight-bold float-right">*</h1>
				  </div>
					<div class="row justify-content-center">
					  <div class="col-2 square">
					    <h2>Klick</h2>
					    <h1 class="font-weight-bold">'.number_format($totalAdClicks,0,',',' ').'</h1>
					  </div>
					  <div class="col-2 square">
					    <h2>Visningar</h2>
					    <h1 class="font-weight-bold">'.number_format($totalAdViews,0,',',' ').'</h1>
					  </div>
				  
		  	    <div class="col-2 square">
				      <h2>Position</h2>
				      <h1 class="font-weight-bold">'.$totalAdPosition.'</h1>
				    </div>
				    <div class="col-2 square">
				      <h2>Överst</h2>
				      <h1 class="font-weight-bold">'.$totalAdTop.' %</h1>
				    </div>
				    
				  </div>
				</div>

		  </div>
		  <div class="row">

				<ul class="list-unstyled star-list">
					<li><h1 class="font-weight-bold">*</h1></li>
					<li><b>Klick: </b>Totalt antal klick för alla annonser under vald period</li>
					<li><b>Visningar: </b>Totalt antal visningar för alla annonser under vald period</li>
					<li><b>Position: </b>Din annonsrankning jämfört med andra annonser</li>
					<li><b>Överst: </b>Hur ofta din annons visades högst upp på sidan med sökresultat</li>
					
					<li><small>Annonsrankning avgör din annonsposition. Faktorer som spelar in är budget och annonsens kvalité samt målsidans relevans och upplevelse</small></li>
					
				</ul>
		  </div>
		</div>
	'; 
  }
	echo '		
	<footer>
		<div class="col-12 text-center">
			<h4>www.bluescreen.se | 021 - 475 00 00</h4>
		</div>
	</footer>	
	  </div>
	';
?>

<script type="text/javascript">
	
	<?php if(!empty($month_traffic)){ ?>
		Morris.Area({
	    element: 'overview-month',
	    data: [<?php foreach($month_traffic as $key => $value){
	    	echo json_encode($value).","; } ?> ],
	    xkey: 'date',
	    ykeys: ['sessions', 'sessionsLastYear'],
	    labels: ['Aktuell månad', 'Föregående år'],
	    pointSize: 5,
	    fillOpacity: 0,
	    pointStrokeColors:['#55ce63', '#7460ee'],
	    behaveLikeLine: true,
	    gridLineColor: '#455a64',
	    gridTextSize: 25,
	    gridTextColor: '#455a64',
	    lineWidth: 5,
	    hideHover: 'always',
	    lineColors: ['#55ce63', '#7460ee'],
	    parseTime: false,
	    resize: true    
	  });
	<?php } ?>

	<?php if(!empty($year_traffic)){ ?>
		 Morris.Bar({
        element: 'overview-year',
        data: [<?php foreach($year_traffic as $key => $value){
        	echo json_encode($value).","; }?> ],
        xkey: 'month',
        ykeys: ['sessions', 'sessionsLastYear'],
        labels: ['Aktuellt år', 'Föregående år'],
        barColors:['#55ce63', '#7460ee'],
        hideHover: 'always',
        gridLineColor: '#455a64',
        gridTextSize: 30,
        gridTextColor: '#455a64',
        parseTime: 'false',
        resize: true
    });
	<?php } ?>

	<?php if(!empty($visitors)){ ?>
		new Chart(document.getElementById("visitors_pie").getContext("2d"), {
      type: 'doughnut',
      data: {
	      datasets: [{
	        data: [
	        	<?php foreach($visitors as $key => $value){
	        		echo json_encode($value['quantity']) .",";
	        	} ?>
	        ],
	        borderWidth: [3, 3, 3, 3, 3],
	        backgroundColor: ['#01c0c8', '#f62d51'],
	        hoverBackgroundColor: ['#01c0c8', '#f62d51']
	      }],
	      labels: [
	        <?php foreach($visitors as $key => $value){
	        	echo json_encode($value['userType']) .",";
	        } ?>
	      ]
	    },
      options: {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 0,
        animationSteps: 75,
        tooltipCornerRadius: 2,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        arc: {
        	backgroundColor: '#000',
        	borderColor: '#D3D3D3',
        	borderWidth: 2,
        },
        legend: {
        	position: 'right',
        	labels: {
        		boxWidth: 40,
        		fontSize: 44,
        		fontStyle: 'normal',
        		fontColor: 'black',
        	}
      	},
      	cutoutPercentage: 0,
    	}
  	});
	<?php } ?>

	<?php if(!empty($devices)){ ?>
		new Chart(document.getElementById("devices_pie").getContext("2d"), {
      type: 'doughnut',
      data: {
	      datasets: [{
	        data: [
	        	<?php foreach($devices as $key => $value){
	        		echo json_encode($value['usage']) .",";
	        	} ?>
	        ],
	        borderWidth: [3, 3, 3, 3, 3],
	        backgroundColor: ['#55ce63', '#7460ee', '#01c0c8'],
	        hoverBackgroundColor: ['#55ce63', '#7460ee', '#01c0c8']
	      }],
	      labels: [
	        <?php foreach($devices as $key => $value){
	        		echo json_encode($value['device']) .",";
	        	} ?>      
	      ]
	    },
      options: {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 0,
        animationSteps: 75,
        tooltipCornerRadius: 2,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        legend: {
        	position: 'right',
        	labels: {
        		boxWidth: 40,
        		fontSize: 44,
        		fontStyle: 'normal',
        		fontColor: 'black',
        	}
      	},
      	cutoutPercentage: 0,
    	}
  	});
	<?php } ?>
	
	<?php if(!empty($channels_pie)){ ?>
		new Chart(document.getElementById("channels_pie").getContext("2d"), {
      type: 'doughnut',
      data: {
	      datasets: [{
	        data: [
	        	<?php foreach($channels_pie as $key => $value){
	        		echo json_encode($value['sessions']) .",";
	        	} ?>
	        ],
	        borderWidth: [3, 3, 3, 3, 3],
	        backgroundColor: ['#f62d51', '#7460ee', '#55ce63', '#01c0c8', '#455a64', '#1e88e5'],
	        shadowColor: '#000',
	        shadowBlur: 10,
	        hoverBackgroundColor: ['#f62d51', '#7460ee', '#55ce63', '#01c0c8', '#455a64', '#1e88e5']
	      }],
	      labels: [
	        <?php foreach($channels_pie as $key => $value){
	        	echo json_encode($value['source']) .",";
	        } ?>     
	      ]
	    },
      options: {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 0,
        animationSteps: 75,
        tooltipCornerRadius: 2,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        legend: {
        	position: 'right',
        	labels: {
        		boxWidth: 40,
        		fontSize: 44,
        		fontStyle: 'normal',
        		fontColor: 'black',
        	}
      	},
      	cutoutPercentage: 0,
    	}
  	});
	<?php } ?>

	<?php if(!empty($adsArr)){ ?>
		new Chart(document.getElementById("adwords-chart"), {
    type: 'horizontalBar',
    data: {
      labels: [
	      <?php foreach($adsArr as $key => $value){
	        echo json_encode($value['adSearch']) .",";
	       } ?>
	    ],
      datasets: [
        {
          label: "Topp 5",
          backgroundColor: ['#f62d51', '#7460ee', '#55ce63', '#01c0c8', '#455a64'],
          data: [
	        	<?php foreach($adsArr as $key => $value){
	        		echo json_encode($value['adClicks']) .",";
	        	} ?>
	        ],
        }
      ]
    },
    options: {
      legend: { 
      	display: false 
      },

      scales: {     	
      	xAxes: [{
      		ticks: {
      			fontSize: 30,
      			fontColor: '#455a64',
      		},
      		gridLines: {
      			color: '#455a64',
      		}
      	}],
      	yAxes: [{
      		ticks: {
      			fontSize: 30,
      			fontColor: '#455a64',
      		},
      		gridLines: {
      			display: false,
      		},
      		barThickness: 50,
      		categoryPercentage: 0.9,
      	}],
      }
    },
    
	});
	<?php } ?>

	<?php if(!empty($e_commerce['e_channels'])){ ?>
		new Chart(document.getElementById("e-channels-doughnut").getContext("2d"), {
      type: 'doughnut',
      data: {
	      datasets: [{
	        data: [
	        	<?php foreach($e_commerce['e_channels'] as $key => $value){
	        		echo json_encode($value['transactions']) .",";
	        	} ?>
	        ],
	        borderWidth: [3, 3, 3, 3, 3],
	        backgroundColor: ['#f62d51', '#7460ee', '#55ce63', '#01c0c8', '#455a64', '#1e88e5'],
	        shadowColor: '#000',
	        shadowBlur: 10,
	        hoverBackgroundColor: ['#f62d51', '#7460ee', '#55ce63', '#01c0c8', '#455a64', '#1e88e5']
	      }],
	      labels: [
	        <?php foreach($e_commerce['e_channels'] as $key => $value){
	        	echo json_encode($value['source']) .",";
	        } ?>     
	      ]
	    },
      options: {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 0,
        animationSteps: 75,
        tooltipCornerRadius: 2,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        legend: {
        	position: 'right',
        	labels: {
        		boxWidth: 40,
        		fontSize: 44,
        		fontStyle: 'normal',
        		fontColor: 'black',
        	}
      	},
      	cutoutPercentage: 50,
    	}
  	});
	<?php } ?>

	<?php if(!empty($e_commerce['e_devices'])){ ?>
		new Chart(document.getElementById("e-devices-doughnut").getContext("2d"), {
      type: 'doughnut',
      data: {
	      datasets: [{
	        data: [
	        	<?php foreach($e_commerce['e_devices'] as $key => $value){
	        		echo json_encode($value['transactions']) .",";
	        	} ?>
	        ],
	        borderWidth: [3, 3, 3, 3, 3],
	        backgroundColor: ['#55ce63', '#7460ee', '#01c0c8'],
	        shadowColor: '#000',
	        shadowBlur: 10,
	        hoverBackgroundColor: ['#55ce63', '#7460ee', '#01c0c8']
	      }],
	      labels: [
	        <?php foreach($e_commerce['e_devices'] as $key => $value){
	        	echo json_encode($value['device']) .",";
	        } ?>     
	      ]
	    },
      options: {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 0,
        animationSteps: 75,
        tooltipCornerRadius: 2,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        legend: {
        	position: 'right',
        	labels: {
        		boxWidth: 40,
        		fontSize: 44,
        		fontStyle: 'normal',
        		fontColor: 'black',
        	}
      	},
      	cutoutPercentage: 50,
    	}
  	});
	<?php } ?>

	<?php if(!empty($e_cities)){ ?>
		new Chart(document.getElementById("e-cities-doughnut").getContext("2d"), {
      type: 'doughnut',
      data: {
	      datasets: [{
	        data: [
	        	<?php foreach($e_cities as $key => $value){
	        		echo json_encode($value['transactions']) .",";
	        	} ?>
	        ],
	        borderWidth: [3, 3, 3, 3, 3],
	        backgroundColor: ['#01c0c8', '#f62d51', '#7460ee', '#55ce63', '#455a64', '#1e88e5'],
	        shadowColor: '#000',
	        shadowBlur: 10,
	        hoverBackgroundColor: ['#01c0c8', '#f62d51', '#7460ee', '#55ce63', '#455a64', '#1e88e5']
	      }],
	      labels: [
	        <?php foreach($e_cities as $key => $value){
	        	echo json_encode($value['city']) .",";
	        } ?>     
	      ]
	    },
      options: {
        segmentShowStroke: true,
        segmentStrokeColor: "#fff",
        segmentStrokeWidth: 0,
        animationSteps: 75,
        tooltipCornerRadius: 2,
        animationEasing: "easeOutBounce",
        animateRotate: true,
        animateScale: false,
        responsive: true,
        legend: {
        	position: 'right',
        	labels: {
        		boxWidth: 40,
        		fontSize: 44,
        		fontStyle: 'normal',
        		fontColor: 'black',
        	}
      	},
      	cutoutPercentage: 50,
    	}
  	});
	<?php } ?>

</script>
