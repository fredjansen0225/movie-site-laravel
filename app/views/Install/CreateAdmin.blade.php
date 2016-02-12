<!DOCTYPE html>

<html>

  <head>
      <title>MTDb - Installer - Step 2</title>

      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <link href='http://fonts.googleapis.com/css?family=Ubuntu:400,700' rel='stylesheet' type='text/css'>
      <link href='http://fonts.googleapis.com/css?family=Ceviche+One' rel='stylesheet' type='text/css'>

      {{ HTML::style('assets/css/styles.css') }}
      
  </head>

  <body id="install" class="create-admin">

  		<div class="container">
  			<div class="col-sm-2"></div>
			
			<div class="col-sm-8" id="update-box">
				
				<h2>Create your super user account</h2>

				{{ Form::open(array('route' => 'install.admin')) }}

					<div class="form-group">          
			          <label for="username"><i class="fa fa-user"></i> {{ trans('users.username') }}</label>
			          {{ Form::text('username', Input::old('username'), array('class' => 'form-control')) }}
			          {{ $errors->first('username', '<span class="help-block alert alert-danger">:message</span>') }}
			        </div>
			         

			        <div class="form-group">
			          <label for="email"><i class="fa fa-envelope-o"></i> {{ trans('users.email') }}</label>
			          {{ Form::text('email', Input::old('email'), array('class' => 'form-control')) }}
			          {{ $errors->first('email', '<span class="help-block alert alert-danger">:message</span>') }}
			        </div>
			            
			          
			        <div class="form-group">
			          <label for="password"><i class="fa fa-lock"></i> {{ trans('users.password') }}</label>
			          {{ Form::password('password', array('class' => 'form-control')) }}
			          {{ $errors->first('password', '<span class="help-block alert alert-danger">:message</span>') }}
			        </div>

			        <div class="form-group">
			          <label for="password_confirmation"><i class="fa fa-lock"></i> {{ trans('users.confirm password') }}</label>
			          {{ Form::password('password_confirmation', array('class' => 'form-control')) }}
			        </div>

			        <button type="submit" class="btn btn-default">{{ trans('users.create account') }}</button>

				{{ Form::close() }}

			</div>

  			<div class="col-sm-2"></div>
  		</div>
    
  </body>
</html>