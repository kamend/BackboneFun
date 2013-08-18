<div class="container">

	<h1>Hey, Bootstrap and Backbone!</h1>

	<h2>Weather App</h2>
	<div class="row">
		<div class="span5 round-background" id="weather-app">
			Loading..
		</div>
	</div>


	
	<script type="text/x-handlebars-template" id="index-template">
		
		<div class="cities">
		</div>
	</script>

	<script type="text/x-handlebars-template" id="index-weather-template">
	
		<span class="span3 pull-left">
			<span id="cityname"><h5>{{ city }}</h5></span> <input type="text" id="city" value="{{ city }}" class="hidden">
		</span>
		<span class="span2 update-item hidden">
			<button class="btn btn-primary btn-mini btn-save">Save</button>
			<button class="btn btn-danger btn-mini btn-cancel">Cancel</button>		
		</span>
		<span class="span2 edit-item">
			<button class="btn btn-primary btn-mini btn-edit">Edit</button>
			<button class="btn btn-danger btn-mini btn-delete">Remove</button>
		</span>
		
	</script>

	<script type="text/x-handlebars-template" id="index-form-template">
			<div class="input-append">
				<input type="text" class="input-medium" id="city">
				<button type="submit" class="btn "><i class="icon-ok"></i> Add City</button>
			</div>
	</script>

</div>