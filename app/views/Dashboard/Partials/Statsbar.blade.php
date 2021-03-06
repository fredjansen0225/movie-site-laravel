<div class="row" id="stats-bar">
				
	<div class="col-sm-2 stats-box">
		<div class="col-sm-6 stats-icon">
			<i class="fa fa-gamepad"></i>
		</div>
		<div class="col-sm-6 stats-text">
			<strong>{{ $count['games'] }}</strong>
			<span>{{ trans('main.games') .' '. trans('dash.inDB') }}</span>
		</div>
	</div>

	<div class="col-sm-2 stats-box">
		<div class="col-sm-6 stats-icon">
			<i class="fa fa-bullhorn"></i>
		</div>
		<div class="col-sm-6 stats-text">
			<strong class="stats-num">{{ $count['news'] }}</strong>
			<span class="stats-text">{{ trans('main.news') .' '. trans('dash.inDB') }}</span>
		</div>
	</div>

	<div class="col-sm-2 stats-box">
		<div class="col-sm-6 stats-icon">
			<i class="fa fa-video-camera"></i>
		</div>
		<div class="col-sm-6 stats-text">
			<strong class="stats-num">{{ $count['videos'] }}</strong>
			<span class="stats-text">{{ trans('main.videos') .' '. trans('dash.inDB') }}</span>
		</div>
	</div>

	<div class="col-sm-2 stats-box">
		<div class="col-sm-6 stats-icon">
			<i class="fa fa-users"></i>
		</div>
		<div class="col-sm-6 stats-text">
			<strong class="stats-num">{{ $count['users'] }}</strong>
			<span class="stats-text">{{ trans('main.users') .' '. trans('dash.inDB') }}</span>
		</div>
	</div>

	<div class="col-sm-2 stats-box">
		<div class="col-sm-6 stats-icon">
			<i class="fa fa-thumbs-down"></i>
		</div>
		<div class="col-sm-6 stats-text">
			<strong class="stats-num">{{ $count['reviews'] }}</strong>
			<span class="stats-text">{{ trans('main.reviews') .' '. trans('dash.inDB') }}</span>
		</div>
	</div>

</div>