<?php 
	$companies = [
		['companyName' => 'Bluescreen AB', 'id' => '***'],
		['companyName' => 'Byggahusguide', 'id' => '***']
	];
?>

<div class="col-12">
	<div class="row">
		<header class="col-12">
			<h1>Månadsrapport Google Analytics</h1>
		</header>
	</div>
	<div class="row">
		<div class="col-4">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Välj företag och månad</h5>

					<form action="https://test3.testserver.se/index.php/ga/start_form" method="post">
						<div class="form-group" >
		      		<label for="company">Företag</label>
		      		<select id="company" class="form-control" name="company">
		      			<option selected> </option>
		      			<?php
		      			foreach($companies as $key){
		      				echo '
										<option value="'.$key['id'].'-'.$key['companyName'].'" >'.$key['companyName'].'</option>
		      				';
		      			} 			        		
		        		?>
		      		</select>			      		
			      	<label for="month">Månad </label>
							<input type="text" id="month" name="month" class="monthPicker form-control" />
			    	</div>
		    	  <button type="submit" class="btn btn-primary">Hämta rapport</button>
		    	</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

$(function() {   
  $(".monthPicker").datepicker({
    dateFormat: "MM yy",
    changeMonth: true,
    changeYear: true,
    showButtonPanel: true,
    onClose: function(dateText, inst) {
      var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
      var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
      $(this).val($.datepicker.formatDate("MM yy", new Date(year, month, 1)));
    }
  });

  $(".monthPicker").focus(function () {
    $(".ui-datepicker-calendar").hide();
    $("#ui-datepicker-div").position({
      my: "center top",
      at: "center bottom",
      of: $(this)
      });
    });
  });

</script>
