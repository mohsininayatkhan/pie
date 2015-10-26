<div class="well light-green-box">
    <div class="row">
    {{ Form::open(array('url' => '/users/search','name'=> 'frmUserSearch','id'=>'frmUserSearch', 'method' => 'GET')) }}
        <div class="col-sm-12">
            <div class="input-group">
                <input type="text" name="keyword" id="keyword" value="<?php echo Request::input('keyword');?>" class="form-control" placeholder="Search by name or email">
                <span class="input-group-btn">
                    <button class="btn btn-danger search-bar-btn pull-right" type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                        Go!
                    </button>
                </span>
            </div>
        </div>
    {{ Form::close() }}
    </div>    
</div>