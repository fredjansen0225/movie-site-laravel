@extends('Main.Boilerplate')

@section('bodytag')
	<body id="dashboard">
@stop

@section('content')

	<section id="dash-container" class="with-filter-bar">

		@include('Dashboard.Partials.Sidebar')

		<div class="content">

			<div id="filter-row" class="row">			
				<button class="col-sm-1 btn btn-primary" data-bind="click: app.paginator.previousPage, enable: app.paginator.hasPrevious">
					<fa class="fa fa-chevron-left"></fa> {{ trans('dash.previous') }}
				</button>
				<button class="col-sm-1 btn btn-primary" data-bind="click: app.paginator.nextPage, enable: app.paginator.hasNext">
					{{ trans('dash.next') }} <fa class="fa fa-chevron-right"></fa>
				</button>
				<section class="col-sm-4 filter-dropdown">
					<select class="form-control" data-bind="value: params.order">
						<option value="">{{ trans('dash.sortBy') }}...</option>
						<option value="created_atDesc">{{ trans('dash.createdAsc') }}</option>
						<option value="created_atAsc">{{ trans('dash.createdDesc') }}</option>
						<option value="last_loginDesc">Last Seen Descending</option>
						<option value="last_loginAsc">Last Seen Ascending</option>
					</select>
				</section>
				<section class="col-sm-4">
					<i class="fa fa-search"></i>
					<input type="text" autocomplete="off" class="strip-input-styles" placeholder="{{ trans('main.search') }}..." data-bind="value: params.query, valueUpdate: 'keyup'">
				</section>
				<div class="col-sm-1"></div>
				<button class="col-sm-1 btn btn-primary" data-toggle="modal" data-target="#new-user-modal"><i class="fa fa-pencil"></i> {{ trans('dash.createNew') }}</button>
			</div>	


			<section class="dash-padding">
				<table class="table table-striped table-centered table-responsive">
			    	<thead>
			        	<tr>
			        		<th>{{ trans('main.image') }}</th>
			          		<th>{{ trans('users.username') }}</th>
			          		<th>{{ trans('main.email') }}</th>
			          		<th>Activated</th> 
			          		<th>{{ trans('users.gender') }}</th>		
		          			<th>{{ trans('main.member since') }}</th>
		          			<th>Last Seen</th>
			          		<th>{{ trans('dash.actions') }}</th>
			        	</tr>
			      	</thead>
			    	<tbody data-bind="foreach: sourceItems">
						<tr>
							<td class="col-sm-1"><img class="img-responsive col-sm-12" data-bind="attr: { src: avatar ? avatar : vars.urls.baseUrl+'/assets/images/no_user_icon_big.jpg', alt: username }"></td>
							<td class="col-sm-1" data-bind="text: username"></td>
							<td class="col-sm-1" data-bind="text: email"></td>
							<td class="col-sm-1" data-bind="text: activated ? 'Yes' : 'No'"></td>
							<td class="col-sm-1" data-bind="text: gender"></td>
							<td class="col-sm-1" data-bind="text: created_at"></td>
							<td class="col-sm-1" data-bind="text: last_login"></td>
							
							<td class="col-sm-1">
								<button class="btn btn-danger btn-sm" data-bind="click: app.paginator.deleteItem"><i class="fa fa-trash-o"></i> </button>
								<a class="btn btn-primary btn-sm" data-bind="click: $root.populateModal.bind($data, id)"><i class="fa fa-wrench"></i> </a>
							</td>
						</tr>				       		
			    	</tbody>
			    </table>
			</section>

			<div class="modal fade" id="new-user-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil"></i> {{ trans('dash.createNew') }}</h4>
			            </div>
			            <div class="modal-body">
			            	{{ Form::open(array('route' => 'users.createNew', 'data-bind' => 'submit: create')) }}
			            		<div class="form-group">
			            			<label for="username">{{ trans('users.username') }}</label>
			            			<input type="text" name="username" class="form-control">
			            		</div>

			            		<div class="form-group">
			            			<label for="email">{{ trans('users.email') }}</label>
			            			<input type="text" name="email" class="form-control">
			            		</div>

			            		<div class="form-group">
			            			<label for="password">{{ trans('users.gender') }}</label>
			            			<select name="gender" class="form-control">
			            				<option value="male">{{ trans('main.male') }}</option>
			            				<option value="female">{{ trans('main.female') }}</option>
			            			</select>
			            		</div>

			            		<div class="form-group">
			            			<label for="avatar">{{ trans('users.avatar') }}</label>
			            			<input type="text" name="avatar" class="form-control">
			            		</div>

			            		<!-- ko ifnot: username -->
			            		<div class="form-group">
			            			<label for="password">{{ trans('users.password') }}</label>
			            			<input type="password" name="password" class="form-control">
			            		</div>

			            		<div class="form-group">
			            			<label for="password_confirmation">{{ trans('users.confirm password') }}</label>
			            			<input type="password" name="password_confirmation" class="form-control">
			            		</div>
			            		<!-- /ko -->

			            		<button type="submit" class="btn btn-success">{{ trans('main.submit') }}</button>
			            	{{ Form::close() }}
			            </div>
			        </div>
			    </div>
			</div>

		</div>

	</section>

@stop

@section('ads')	
@stop

@section('scripts')
	<script>
		vars.trans.users = '<?php echo strtolower(trans("main.users")); ?>';
		app.paginator.start(app.viewModels.users, '.content', 15);
		app.viewModels.users.registerEvents();

	</script>
@stop