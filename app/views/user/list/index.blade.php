@extends('layout.main')
@section('page_title')
    Dashboard   
@stop

@section('inner_content')
    @include('section.default_errors')
    
    <div class="row">
    	<div class="col-sm-12">@include('section.default_heading', array('heading' => 'Users'))</div>
	</div>
	
	<div class="row">
    	{{ Form::open(array('url' => 'users','method'=>'GET', 'name'=> 'frmUser', 'id'=>'frmUser')) }}
    	<div class="col-sm-4">
    		<div class="form-group input-group">
                <input id="keyword" name="keyword" type="text" autocomplete="off" value="<?php echo Input::old('keyword') ?>" class="form-control typeahead" placeholder="I am looking for">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="search-listing-btn">
                        <i class="fa fa-search"></i>
                    </button> </span>
            </div>
    		<input type="submit" style="position: absolute; left: -9999px; width: 1px; height: 1px;" tabindex="-1" />
    	</div>
    	<div class="col-sm-2">
    		<span class="btn-group pull-left">
                <button onclick="window.location=base_url+'/users';" type="button" class="btn">Clear</button>
            </span>
    	</div>
    	{{ Form::close() }}
    	<div class="col-sm-6">
    		<span class="btn-group pull-right">
                <button onclick="window.location=base_url+'/create-user';" type="button" class="btn btn-danger">Create User</button>
            </span>
    	</div>
	</div>
    
    @if (!is_null($users) && count($users))
    	<div class = "table-responsive">
    	<table class="table table-striped">
    		<thead>
				<tr>
			        <th>Name</th>
			        <th>Email</th>
			        <th>Phone</th>
			        <th>Action</th>
			    </tr>
	  		</thead>
	  		<tbody>
    		@foreach ($users as $user)
    			<tr>
    				<td>{{ $user->fname.' '.$user->lname }}</td>
		      		<td>{{ $user->email }}</td>
		      		<td>{{ $user->phone }}</td>
		      		<td>
		      			<div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">Action
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <li role="presentation"><a title="<?php echo $user->fname; ?>" id="update-<?php echo Crypt::encrypt($user->slug)?>" role="menuitem" tabindex="-1" class="del-ad" href="<?php echo URL::to('/') . '/update-user/' . $user->slug; ?>"><span class="glyphicon glyphicon-update"></span> Update</a></li>
                                <li role="presentation"><a title="<?php echo $user->fname; ?>" id="user-ads-<?php echo Crypt::encrypt($user->id)?>" role="menuitem" tabindex="-1" class="del-ad" href="<?php echo URL::to('/') . '/user-ads/' . $user->slug; ?>"><span class="glyphicon glyphicon-arrow-up"></span> Ads</a></li>
                                <li role="presentation"><a title="<?php echo $user->fname; ?>" id="delete-<?php echo Crypt::encrypt($user->id)?>" role="menuitem" tabindex="-1" class="del-ad" href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                                <!--li role="presentation"><a role="menuitem" tabindex="-1" href="#"><span class="glyphicon glyphicon-arrow-up"></span> Promote</a></li -->
                            </ul>
                        </div>
		      		</td>
		      	</tr>
        	@endforeach
        	</tbody>
        </table>
        </div>
        {{ $users->appends(Input::all())->links(); }}
	@else
	    <div class="row">
	        <div class="col-sm-12">
	            <div class="alert alert-danger" role="alert" style="text-align: center;">Sorry! We could not find any user against your search.
	            </div>
	        </div>          
	    </div>
	@endif
@stop

