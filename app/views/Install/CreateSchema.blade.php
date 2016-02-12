<!DOCTYPE html>

<html>

  <head>
      <title>MTDb - Installer - Step 1</title>

      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'>
      <link href='http://fonts.googleapis.com/css?family=Ceviche+One' rel='stylesheet' type='text/css'>

      {{ HTML::style('assets/css/styles.css') }}
      
  </head>

  <body id="install">

  		<div class="container">
  			<div class="col-sm-2"></div>
			
			<div class="col-sm-8" id="update-box">
				
				<h2>Welcome to MTDb installer</h2>

				<p>Before proceeding please open up <strong>{{ app_path() . '\config\database.php' }}</strong> and configure your database settings.</p>

        <span class="help-block">* This might take several minutes, please don't close this browser tab while schema creation is not complete.</span>

				{{ Form::open(array('route' => 'install.schema')) }}

					<button type="submit" class="btn btn-warning btn-lg">Create Database Schema and Continue</button>

				{{ Form::close() }}

			</div>

  			<div class="col-sm-2"></div>
  		</div>
    
  </body>
</html>