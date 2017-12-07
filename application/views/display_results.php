<?php

echo'

<div class="col-lg-12 style="padding: 50px">
	<div class="row">
		<header>
			<h4>Månadsrapport: '.$month.'</h4>
			<h4>Kund: '.$companyName.'</h4>
		</header>
	</div>

	<form action="https://test3.testserver.se/index.php/ga/print_results" method="post">
	
	<div class="row">
		<div class="col-lg-12">
			<div class="card" id="eCommerce-group-card">
				<div class="card-body">	
					<div class="row">
						<div class="col-lg-12">
							<h4 class="card-title">E-handel</h4>
						</div>
					</div>
					
					<div class="row">

						<div class="col-lg-12">
							<div class="card" id="e-overview">
								<div class="card-body">	
									<h4 class="card-title">Översikt totalt denna månad (rutor)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									      <th scope="col">Transaktioner</th>
									      <th scope="col">Intäkter</th>
									      <th scope="col">Medelvärde/köp</th>
									      <th scope="col">Sålda artiklar</th>
									      <th scope="col">Handlande besökare</th>
									    </tr>
									  </thead>
									  <tbody> ';	  	
									  		echo '
													<tr> ';
													foreach($e_overview as $key => $value){
														echo '
															<td>'.number_format($value['transactions'],0,',',' ').'</td>
											      	<td>'.number_format($value['revenue'],1,',',' ').' kr</td>
											      	<td>'.number_format($value['avgRevenue'],1,',',' ').' kr</td>
											      	<td>'.number_format($value['quantity'],0,',',' ').'</td>
											      	<td>'.number_format($value['converts'],2,',',' ').' %</td>
											      ';
											    };
											    echo '
											    </tr>
									  </tbody>
									</table>
								</div>
							</div>
						</div>
					
					</div>
					<div class="row">

						<div class="col-lg-5">
							<div class="card" id="e-channels-transactions">
								<div class="card-body">	
									<h4 class="card-title">Intäkter/Kanal (tabell)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									      <th scope="col">Kanal</th>
									      <th scope="col">Intäkt</th>
									    </tr>
									  </thead>
									  <tbody> ';	  
									  	$totalChannelRevenue = 0;
											foreach($e_channels as $key){
												$totalChannelRevenue += $key['revenue'];
											}	
									  	foreach($e_channels as $key){
									  		echo '
													<tr>
														<td>'.$key['source'].'</td>
										      	<td>'.number_format($key['revenue'],0,',',' ').' kr</td>	
											    </tr>
									  		';
									  	};
									    echo '
									    <tr>
									      <td>Totalt</td>
									      <td>'.number_format($totalChannelRevenue,0,',',' ').' kr</td>
									    </tr>
									  </tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="col-lg-7">
							<div class="card" id="e-channels-pie">
								<div class="card-body">	
									<h4 class="card-title">Transaktioner/kanal (Doughnut chart)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									      <th scope="col">Kanal</th>
									      <th scope="col">Antal</th>
									    </tr>
									  </thead>
									  <tbody> ';	  
									  	foreach($e_channels as $key){
									  		echo '
													<tr>
														<td>'.$key['source'].'</td>
											      <td>'.$key['transactions'].'</td>
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

						<div class="col-lg-5">
							<div class="card" id="e-devices-table">
								<div class="card-body">	
									<h4 class="card-title">Intäkter/Enhet (tabell)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									      <th scope="col">Enhet</th>
									      <th scope="col">Intäkt</th>
									    </tr>
									  </thead>
									  <tbody> ';	  
									  	$totalDevicesRevenue = 0;
											foreach($e_devices as $key){
												$totalDevicesRevenue += $key['revenue'];
											}	
									  	foreach($e_devices as $key){
									  	echo '
												<tr>
													<td>'.$key['device'].'</td>
										     	<td>'.number_format($key['revenue'],0,',',' ').' kr</td>
											  </tr>
									  	';
									    };
									    echo '   
									    <tr>
									      <td>Totalt</td>
									      <td>'.number_format($totalDevicesRevenue,0,',',' ').' kr</td>
									    </tr>
									  </tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="col-lg-7">
							<div class="card" id="e-devices-pie">
								<div class="card-body">	
									<h4 class="card-title">Transaktioner/Enhet (Doughnut chart)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									      <th scope="col">Enhet</th>
									      <th scope="col">Antal</th>
									    </tr>
									  </thead>
									  <tbody> ';	  
									  	foreach($e_devices as $key){
									  	echo '
								  			<tr>
													<td>'.$key['device'].'</td>
										     	<td>'.$key['transactions'].'</td>
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
										
						<div class="col-lg-5">
							<div class="card" id="e-cities-table">
							  <div class="card-body">	
									<h4 class="card-title">Intäkter/Ort (tabell)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									      <th scope="col">Stad</th>
									      <th scope="col">Intäkt</th>
									      <th scope="col">Välj 3</th>
									    </tr>
									  </thead>
									  <tbody> ';	  
									  	$totalCitiesRevenue = 0;
												foreach($e_cities as $key){
													$totalCitiesRevenue += $key['revenue'];
												}
									  	foreach($e_cities as $key => $value){
									  	echo '
												<tr>
														<td>'.$value['city'].'</td>
										      	<td>'.number_format($value['revenue'],0,',',' ').' kr</td>
										      	<td>
										      		<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
												        <input type="checkbox" class="custom-control-input" name="e-cities-table'.$key.'" value="'.str_replace('"', "'", json_encode($value)).'">
												        <span class="custom-control-indicator"></span>
												        <span class="custom-control-description">Lägg till</span>
												      </label>
										      	</td>
													
											    </tr>
									  		';
									  	};
									    echo '
									  </tbody>
									</table>
								</div>
						  </div>
						</div> 

						<div class="col-lg-7">
							<div class="card" id="e-cities-pie">
								<div class="card-body">	
									<h4 class="card-title">Trasaktioner/Ort (Doughnut chart)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									      <th scope="col">Ort</th>
									      <th scope="col">Antal</th>
									    </tr>
									  </thead>
									  <tbody> ';	  
									  	foreach($e_cities as $key => $value){
									  	  echo '
												<tr>
													<td>'.$value['city'].'</td>
										     	<td>'.$value['transactions'].'</td>
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
						';
						$e_devAndChannels = [
							'e_overview' => $e_overview,
							'e_devices' => $e_devices,
							'e_channels' => $e_channels,
						];
						$e_devAndChannelsArray = json_encode($e_devAndChannels);
						$e_devAndChannelsArray = str_replace('"', "'", $e_devAndChannelsArray);
						echo '
						<div class="col-12">
							<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
							  <input type="checkbox" class="custom-control-input" name="eCommerce-card" value="'.$e_devAndChannelsArray.'">
							  <span class="custom-control-indicator"></span>
							  <span class="custom-control-description">Lägg till</span>
						  </label>
				    </div>
					</div>

				</div>
			</div>
		</div>
	</div>
	
	<div class="row">	
		<div class="col-12">
			<div class="card" id="overview-month">
				<div class="card-body">
					<div class=" row">
						<div class="col-6">
						<h4 class="card-title">Trafik/dag denna månad valt år (Line chart (tillsammans med året innan) )</h4>
							<table class="table table-sm">
						  <thead>
						    <tr>
						      <th scope="col">Datum</th>
						      <th scope="col">Besök</th>
						    </tr>
						  </thead>
						  <tbody> ';
							  $totalMonth = 0;
								foreach($thisMonth as $key){
									$totalMonth += $key['sessions'];
								}	
						    $bothMonths = [
						    	'thisYear' => $thisMonth,
						    	'lastYear' => $lastYearMonth,
						    ];
						  	$bothMonths_table = json_encode($bothMonths);
					  	  $bothMonths_table = str_replace('"', "'", $bothMonths_table);
						  /*	foreach($thisMonth as $key){   		
						  		echo '
										<tr>';
										if(isset($key['date'])){
											echo '
												<td>'.$key['date'].'</td>
								      	<td>'.$key['sessions'].'</td>
											';
										}; echo '
								    </tr>
						  		';
						  	} ; */
						    echo '
						    <tr>
						      <td>Totalt</td>
						      <td>'.$totalMonth.'</td>
						    </tr>
						  </tbody>
						</table>
						</div>
						
						<div class="col-lg-6">
							<h4 class="card-title">Trafik/dag denna månad året innan (Line chart (tillsammans med valt år) )</h4>
							<table class="table table-sm">
						  <thead>
						    <tr>
						      <th scope="col">Datum</th>
						      <th scope="col">Besök</th>
						    </tr>
						  </thead>
						  <tbody>
						    ';	  
						    $totalLastMonth = 0;
								foreach($lastYearMonth as $key){
									$totalLastMonth += $key['sessions'];
								}	
						  /*foreach($lastYearMonth as $key){ 
						  		echo '
										<tr>';
										if(isset($key['date'])){
											echo '
												<td>'.$key['date'].'</td>
								      	<td>'.$key['sessions'].'</td>
											';
										}; echo '
								    </tr>
						  		';
						  	}; */
							    echo '
							    <tr>
							      <td>Totalt</td>
							      <td>'.$totalLastMonth.'</td>
							    </tr>
							  </tbody>
							</table>
						</div>
					</div>
					<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
		        <input type="checkbox" class="custom-control-input" checked="checked" name="month-traffic-table" value="'.$bothMonths_table.'">
		        <span class="custom-control-indicator"></span>
		        <span class="custom-control-description">Lägg till</span>
		      </label>
				</div>
			</div>
		</div>

	</div>
	<div class="row">

		<div class="col-lg-7">
			<div class="card" id="visitors">
				<div class="card-body">	
					<h4 class="card-title">Besökare (Pie chart)</h4>
					<table class="table table-sm">
					  <thead>
					    <tr>
					      <th scope="col">Typ</th>
					      <th scope="col">Antal</th>
					    </tr>
					  </thead>
					  <tbody> ';	  
					  	$totalUsers = 0;
							foreach($userType as $key){
								$totalUsers += $key['quantity'];
							}	
					  	$users_pie = json_encode($userType);
					  	$users_pie = str_replace('"', "'", $users_pie);
					  	foreach($userType as $key){
					  		echo '
								<tr>
									<td>'.$key['userType'].'</td>
						     	<td>'.round($key['quantity']/$totalUsers * 100, 2).' %</td>
							  </tr>
					  		';
					  	};
					    echo '
					    <tr>
					      <td>Totalt</td>
					      <td>'.$totalUsers.'</td>
					    </tr>
					  </tbody>
					</table>
		      <label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
		        <input type="checkbox" class="custom-control-input" checked="checked" name="visitors-pie" value=" '.$users_pie.' ">
		        <span class="custom-control-indicator"></span>
		        <span class="custom-control-description">Lägg till</span>
		      </label>
				</div>
			</div>
		</div>
		
		<div class="col-lg-5">
			<div class="card" id="pagePath">
				<div class="card-body">	
					<h4 class="card-title">Topp 10 besökta sidor (tabell)</h4>
					<table class="table table-sm">
					  <thead>
					    <tr>
					      <th scope="col">Sökväg</th>
					      <th scope="col">Visningar</th>
					      <th scope="col">Välj 3</th>
					    </tr>
					  </thead>
					  <tbody>';	  
					  	foreach($pagePaths as $key => $value){
					  		echo '
								<tr>
									<td>'.$value['pagePath'].'</td>
							    <td>'.$value['views'].'</td>
							    <td>
						      	<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
								      <input type="checkbox" class="custom-control-input" name="pagePath-table'.$key.'" value="'.str_replace('"', "'", json_encode($value)).'">
								      <span class="custom-control-indicator"></span>
								      <span class="custom-control-description">Lägg till</span>
								    </label>
						      </td>
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
		
		<div class="col-lg-5">
			<div class="card" id="cities">
			  <div class="card-body">	
					<h4 class="card-title">Topp 10 orter (tabell)</h4>
					<table class="table table-sm">
					  <thead>
					    <tr>
					      <th scope="col">Stad</th>
					      <th scope="col">Besökare</th>
					      <th scope="col">Välj 3</th>
					    </tr>
					  </thead>
					  <tbody> ';	  
					  	foreach($cities as $key => $value){
					  	echo '
								<tr>										
								  <td>'.$value['city'].'</td>
						      <td>'.round($value['views'], 2).' %</td>
						      <td>
						      	<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
								      <input type="checkbox" class="custom-control-input" name="cities-table'.$key.'" value="'.str_replace('"', "'", json_encode($value)).'">
								      <span class="custom-control-indicator"></span>
								      <span class="custom-control-description">Lägg till</span>
								    </label>
						      </td>
							  </tr>
					  		';
					  	};
					    echo '
					  </tbody>
					</table>
				</div>
		  </div>
		</div>

		<div class="col-lg-7">
			<div class="card" id="devices">
				<div class="card-body">	
					<h4 class="card-title">Enheter (Pie chart)</h4>
					<table class="table table-sm">
					  <thead>
					    <tr>
					      <th scope="col">Typ</th>
					      <th scope="col">Användning</th>
					    </tr>
					  </thead>
					  <tbody> ';	  
					  	$totalDevices = 0;
							foreach($devices as $key){
								$totalDevices += $key['usage'];
							}	
					  	$devices_table = json_encode($devices);
					  	$devices_table = str_replace('"', "'", $devices_table);
					  	foreach($devices as $key){
					  	echo '
								<tr>
									<td>'.$key['device'].'</td>
						     	<td>'.round($key['usage']/$totalDevices * 100, 2).' %</td>
							  </tr>
					  	';
					    };
					    echo '   
					    <tr>
					      <td>Totalt</td>
					      <td>'.$totalDevices.'</td>
					    </tr>
					  </tbody>
					</table>
					<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
		        <input type="checkbox" class="custom-control-input" checked="checked" name="devices-pie" value="'.$devices_table.'">
		        <span class="custom-control-indicator"></span>
		        <span class="custom-control-description">Lägg till</span>
		      </label>
				</div>
			</div>
		</div>

	</div>
	<div class="row">

		<div class="col-lg-7">
			<div class="card" id="best-channels">
				<div class="card-body">	
					<h4 class="card-title">Bästa kanaler (Pie chart)</h4>
					<table class="table table-sm">
					  <thead>
					    <tr>
					      <th scope="col">Typ</th>
					      <th scope="col">Besök</th>
					    </tr>
					  </thead>
					  <tbody> ';	  
					  	$totalChannels = 0;
							foreach($channels as $key){
								$totalChannels += $key['sessions'];
							}	
					  	$channels_pie = json_encode($channels);
					  	$channels_pie = str_replace('"', "'", $channels_pie);
					  	foreach($channels as $key){
					  		echo '
									<tr>
										<td>'.$key['source'].'</td>
		  			      	<td>'.round($key['sessions']/$totalChannels * 100, 2).' %</td>
							    </tr>
					  		';
					  	};
					     echo '
					     <tr>
					      <td>Totalt</td>
					      <td>'.$totalChannels.'</td>
					    </tr>  
					  </tbody>
					</table>
					<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
		        <input type="checkbox" class="custom-control-input" checked="checked" name="channels-pie" value="'.$channels_pie.'">
		        <span class="custom-control-indicator"></span>
		        <span class="custom-control-description">Lägg till</span>
		      </label>
				</div>
			</div>
		</div>

		<div class="col-lg-5">
			<div class="card" id="channels">
				<div class="card-body">	
					<h4 class="card-title">Kanaler (tabell)</h4>
					<table class="table table-sm">
					  <thead>
					    <tr>
					      <th scope="col">Typ</th>
					      <th scope="col">Besök</th>
					    </tr>
					  </thead>
					  <tbody> ';	  
					  	$channels_table = json_encode($channels);
					  	$channels_table = str_replace('"', "'", $channels_table);
					  	foreach($channels as $key){
					  		echo '
									<tr>
										<td>'.$key['source'].'</td>
						      	<td>'.$key['sessions'].'</td>
							    </tr>
					  		';
					  	};
					    echo '
					    <tr>
					      <td>Totalt</td>
					      <td>'.$totalChannels.'</td>
					    </tr>
					  </tbody>
					</table>
					<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
		        <input type="checkbox" class="custom-control-input" checked="checked" name="channels-table" value="'.$channels_table.'">
		        <span class="custom-control-indicator"></span>
		        <span class="custom-control-description">Lägg till</span>
		      </label>
				</div>
			</div>
		</div>

	</div>

	<div class="row">		

		<div class="col-lg-12">
			<div class="card" id="overview-year">
				<div class="card-body">
					<div class=" row">
						<div class="col-6">
						<h4 class="card-title">Trafik/månad valt år (Bar chart (tillsammans med året innan) )</h4>
							<table class="table table-sm">
						  <thead>
						    <tr>
						      <th scope="col">Datum</th>
						      <th scope="col">Besök</th>
						    </tr>
						  </thead>
						  <tbody> ';	
						  	$totalThisYear = 0;
								foreach($allMonthsThisYear as $key){
									$totalThisYear += $key['sessions'];
								}	
								$bothYears = [
						    	'thisYear' => $allMonthsThisYear,
						    	'lastYear' => $allMonthsLastYear,
						    ];
						  	$bothYears_table = json_encode($bothYears);
					  	  $bothYears_table = str_replace('"', "'", $bothYears_table);
						  /*	foreach($allMonthsThisYear as $key){
						  		echo '
										<tr>';
										if(isset($key['month'])){
											echo '
												<td>'.$key['month'].'</td>
								      	<td>'.$key['sessions'].'</td>
											';
										}; echo '
								    </tr>
						  		';
						  	}; */
						    echo '
						    <tr>
						      <td>Totalt</td>
						      <td>'.$totalThisYear.'</td>
						    </tr>
						  </tbody>
						</table>
						</div>
						
						<div class="col-lg-6">
							<h4 class="card-title">Trafik/månad året innan (Bar chart (tillsammans med valt år) )</h4>
							<table class="table table-sm">
						  <thead>
						    <tr>
						      <th scope="col">Datum</th>
						      <th scope="col">Besök</th>
						    </tr>
						  </thead>
						  <tbody>
						    ';	  
						    $totalLastYear = 0;
								foreach($allMonthsLastYear as $key){
									$totalLastYear += $key['sessions'];
								}
						  /*	foreach($allMonthsLastYear as $key){
						  	echo '
									<tr>';
									if(isset($key['month'])){
										echo '
											<td>'.$key['month'].'</td>
								     	<td>'.$key['sessions'].'</td>
										';
									}; 
								echo '
							    </tr>
						 		';
						  	}; */
						    echo '
						    <tr>
						      <td>Totalt</td>
						      <td>'.$totalLastYear.'</td>
						    </tr>
						  </tbody>
						</table>
						</div>
					</div>
					<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
		        <input type="checkbox" class="custom-control-input" checked="checked" name="year-traffic-table" value="'.$bothYears_table.'">
		        <span class="custom-control-indicator"></span>
		        <span class="custom-control-description">Lägg till</span>
		      </label>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="card" id="adWords-group-card">
				<div class="card-body">	
					<div class="row">

						<div class="col-lg-12">
							<h4 class="card-title">Google Adwords</h4>
						</div>

						<div class="col-lg-12">
							<div class="card" id="adWords-chart">
							  <div class="card-body">	
									<h4 class="card-title">Topp 5 sökord (chart)</h4>
									<p class="card-text">Liggande chart med topp 5 sökord</p>
								</div>
						  </div>
						</div>

					</div>
					<div class="row">

						<div class="col-lg-12">
							<div class="card" id="adWords-table">
							  <div class="card-body">	
									<h4 class="card-title">Topp 5 sökord (tabell)</h4>
									<table class="table table-sm">
									  <thead>
									    <tr>
									    	<th scope="row">#</th>
									      <th scope="col">Sökord</th>
									      <th scope="col">Annonsgrupp</th>
									      <th scope="col">Antal klick</th>
									      <th scope="col">Antal visningar</th>
									      <th scope="col">Genomsnittlig position</th>
									    </tr>
									  </thead>
									  <tbody> 
									  	<tr>
									  		<th scope="row">1</th>
									  		<td><input type="text" class="form-control" name="adword-search1" /></td>
									  		<td><input type="text" class="form-control" name="adword-group1" /></td>
									  		<td><input type="text" class="form-control" name="adword-click1" /></td>
									  		<td><input type="text" class="form-control" name="adword-views1" /></td>
									  		<td><input type="text" class="form-control" name="adword-position1" /></td>
									  	</tr>  
									  	<tr>
									  		<th scope="row">2</th>
									  		<td><input type="text" class="form-control" name="adword-search2" /></td>
									  		<td><input type="text" class="form-control" name="adword-group2" /></td>
									  		<td><input type="text" class="form-control" name="adword-click2" /></td>
									  		<td><input type="text" class="form-control" name="adword-views2" /></td>
									  		<td><input type="text" class="form-control" name="adword-position2" /></td>
									  	</tr> 
									  	<tr>
									  		<th scope="row">3</th>
									  		<td><input type="text" class="form-control" name="adword-search3" /></td>
									  		<td><input type="text" class="form-control" name="adword-group3" /></td>
									  		<td><input type="text" class="form-control" name="adword-click3" /></td>
									  		<td><input type="text" class="form-control" name="adword-views3" /></td>
									  		<td><input type="text" class="form-control" name="adword-position3" /></td>
									  	</tr> 
									  	<tr>
									  		<th scope="row">4</th>
									  		<td><input type="text" class="form-control" name="adword-search4" /></td>
									  		<td><input type="text" class="form-control" name="adword-group4" /></td>
									  		<td><input type="text" class="form-control" name="adword-click4" /></td>
									  		<td><input type="text" class="form-control" name="adword-views4" /></td>
									  		<td><input type="text" class="form-control" name="adword-position4" /></td>
									  	</tr> 
									  	<tr>
									  		<th scope="row">5</th>
									  		<td><input type="text" class="form-control" name="adword-search5" /></td>
									  		<td><input type="text" class="form-control" name="adword-group5" /></td>
									  		<td><input type="text" class="form-control" name="adword-click5" /></td>
									  		<td><input type="text" class="form-control" name="adword-views5" /></td>
									  		<td><input type="text" class="form-control" name="adword-position5" /></td>
									  	</tr> 
									  </tbody>
									</table>
								</div>
						  </div>
						</div>

					</div>
					<div class="row">
						<div class="col-lg-12" id="adWords-squares">
							<div class="row justify-content-center">
								<div class="col-2 card square-input">
									<div class="card-body">	
										<h3 class="card-title">Klick</h3>
										<input type="text" class="form-control" name="adword-total-click" />
									</div>
								</div>
								<div class="col-2 card square-input">
									<div class="card-body">	
										<h3 class="card-title">Visningar</h3>
										<input type="text" class="form-control" name="adword-total-views" />
									</div>
								</div>
								<div class="col-2 card square-input">
									<div class="card-body">	
										<h3 class="card-title">Position</h3>
										<input type="text" class="form-control" name="adword-total-position" />
									</div>
								</div>
								<div class="col-2 card square-input">
									<div class="card-body">	
										<h3 class="card-title">Överst</h3>
										<input type="text" class="form-control" name="adword-total-top" />
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
					<div class="col-12">
						<label class="custom-control custom-checkbox mb-2 mr-sm-2 mb-sm-0">
				      <input type="checkbox" class="custom-control-input" name="adWords-card" value="adWords-card">
				      <span class="custom-control-indicator"></span>
				      <span class="custom-control-description">Lägg till</span>
				    </label>
				    </div>
					</div>
		    </div>
		  </div>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<button type="submit" class="btn btn-md btn-primary">Hämta anpassad rapport</button>
		</div>
	</div>
	</form>
</div>
';
