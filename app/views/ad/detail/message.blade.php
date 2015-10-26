<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-success">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="exampleModalLabel">Send Message</h4>
            </div>
            <div class="modal-body">
                <div id="ajax-error-messages"></div>
                    {{ Form::open(array('url' => 'sendmessage', 'id'=>'frmAdMessage', 'name'=>'frmAdMessage', 'action' =>'POST')) }}
                    <input type="hidden" name="reciever_id" id="reciever_id" value="{{ Crypt::encrypt($ad->user_id) }}"/>
                    <input type="hidden" name="ad_id" id="ad_id" value="{{ Crypt::encrypt($ad->id) }}"/>
                    <input type="hidden" name="subject" id="subject" value="{{ $ad->title.' in '.$ad->state_name.', '.$ad->city_name }}"/>
                    <div class="form-group">
                        <label for="message-text" class="control-label">Message:</label>
                        <textarea class="form-control" id="messagetext" name="messagetext"></textarea>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-default">
                     Send Message
                </button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>