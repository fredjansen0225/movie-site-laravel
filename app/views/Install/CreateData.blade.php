<!DOCTYPE html>

<html>

  <head>
      <title>MTDb - Installer - Step 3</title>

      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'>
      <link href='http://fonts.googleapis.com/css?family=Ceviche+One' rel='stylesheet' type='text/css'>

      {{ HTML::style('assets/css/styles.css') }}
      
  </head>

  <body id="install">

  		<div class="container">
  			<div class="col-sm-2"></div>
			
			<div class="col-sm-8" id="update-box">
				
				<h3>Almost done, should I get you started with some Movies/Series?</h3>

				{{ Form::open(array('route' => 'install.data')) }}

			        <div class="form-group">          
			          {{ Form::label('news', 'Fetch some news') }}  
			          {{ Form::checkbox('news', 1, array('class' => 'form-control')) }}
			        </div>

			        <div class="form-group">
			          {{ Form::label('movies', 'Fetch some movies and series') }}       
			          {{ Form::checkbox('movies', 1, array('class' => 'form-control')) }}
			        </div>
			            			          
			        <span class="help-block">*Note that depending on your internet speed and what options you have chosen above it might take several minutes or more to fetch everything, so please don't close this browser tab while app is in progress of fetching data.</span>

			        <button type="submit" class="btn btn-default">Finish Installation</button>

				{{ Form::close() }}

			</div>

  			<div class="col-sm-2"></div>
  		</div>
    
  </body>
</html>