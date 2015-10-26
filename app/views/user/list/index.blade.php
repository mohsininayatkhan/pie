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
    		<input type="text" name="keyword" id="keyword" value="<?php echo Input::old('filter');?>" class="form-control" placeholder="Search"/>
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
			    </tr>
	  		</thead>
	  		<tbody>
    		@foreach ($users as $user)
    			<tr>
    				<td>{{ $user->fname.' '.$user->lname }}</td>
		      		<td>{{ $user->email }}</td>
		      		<td>{{ $user->phone }}</td>
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

