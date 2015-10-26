<div class="row">
    <div class="col-sm-12 list">
        <div id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Change Password</h4>
                    <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                </div>
                {{ Form::open(array('url' => 'change-password','method'=>'POST', 'name' => 'frmChangePassword', 'id'=>'frmChangePassword', 'class' => 'form-horizontal', 'role' => 'form')) }}
                <div id="change-password" class="panel-collapse collapse in">
                    <div class="panel-body">
                        
                        <div class="form-group">
                            <label for="First Name" class="col-sm-2 control-label custom-label">Password*:</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="old_password" name="old_password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Last Name" class="col-sm-2 control-label custom-label">New Password*:</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="Last Name" class="col-sm-2 control-label custom-label">Confirm Password*:</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>


                        <div class="row pull-right">
                            <div class="col-sm-12">
                                <button class="btn btn-lg btn-primary" type="submit">
                                    Change
                                </button>
                                <a href="<?php echo URL::to('/').'/timeline'; ?>">
                					<button type="button" class="btn btn-lg btn-default">Cancel</button>
            					</a>
                            </div>
                        </div>
                        <?php echo Form::token(); ?>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>