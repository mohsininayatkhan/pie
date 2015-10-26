<?php
$currRoute = Route::currentRouteName();
?>
<div class="row" id="account-panel">
    <div class="col-sm-12">
        <ul role="tablist" class="nav nav-tabs">
            <li class="<?php echo ($currRoute=='manage-ads')?'active':'' ?>" role="presentation"><a href="{{ URL::route('manage-ads') }}">Manage My Ads</a></li>
            <li class="<?php echo ($currRoute=='message' || $currRoute=='message-detail' || $currRoute=='message-archiv-list')?'active':'' ?>" role="presentation"><a href="{{ URL::route('message') }}">Messages <span class="badge">{{ $unread }}</span></a></li>
            <li class="<?php echo ($currRoute=='my-account')?'active':''?> " role="presentation"><a href="{{ URL::route('my-account') }}">My Details</a></li>
        </ul> 
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <br>
        
    </div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirmation Delete
            </div>
            <div class="modal-body">
                Are you sure you want to delete ad?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger danger">Delete</a>
            </div>
        </div>
    </div>
</div>