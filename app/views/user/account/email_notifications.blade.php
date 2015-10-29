<div class="row">
    <div class="col-sm-12 list">
        <div id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Email Notifications</h4>
                    <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                </div>
                {{ Form::open(array('url' => 'email-notification','method'=>'POST', 'name' => 'frmNotification', 'id'=>'frmNotification', 'class' => 'form-horizontal', 'role' => 'form')) }}
                <input type="hidden" name="id" id="id" value="<?php echo $user->id; ?>"/>
                <div id="email-notifications" class="panel-collapse collapse in">
                    <div class="panel-body">

                        <div class="checkbox">
                            <input <?php echo ($user->receive_newsletter=='Yes') ? 'checked="checked"' : ''; ?> type="checkbox" id="chk_newsletter" name="chk_newsletter" value="1">
                            <label for="chk_newsletter"><b>Yes</b>, I want to receive newsletter.</label>
                        </div>

                        <div class="checkbox">
                            <input <?php echo ($user->email_messages_notification=='Yes') ? 'checked="checked"' : ''; ?> type="checkbox" id="chk_messages" name="chk_messages" value="1">
                            <label for="chk_messages"><b>Yes</b>, I want to receive e-mail notifications for messages.</label>
                        </div>

                        <div class="checkbox">
                            <input <?php echo ($user->new_listing_alert=='Yes') ? 'checked="checked"' : ''; ?> type="checkbox" id="chk_listings" name="chk_listings" value="1">
                            <label for="chk_listings"><b>Yes</b>, I want to receive e-mail alerts about new listings.</label>
                        </div>

                        <div class="row pull-right">
                            <div class="col-sm-12">
                                <button class="btn btn-lg btn-primary" type="submit">
                                    Save
                                </button>
                                <a href="<?php echo URL::to('/').'/users'; ?>">
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