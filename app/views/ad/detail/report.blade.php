<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header modal-header-warning">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="reportModalLabel"><strong>Report</strong></h4>
            </div>
            <div class="modal-body">
                <div id="ajax-error-report"></div>
                    {{ Form::open(array('url' => 'report', 'id'=>'frmAdReport', 'name'=>'frmAdReport', 'action' =>'POST')) }}
                    <input type="hidden" name="reciever_id" id="reciever_id" value="{{ Crypt::encrypt($ad->user_id) }}"/>
                    <input type="hidden" name="ad_id" id="ad_id" value="{{ Crypt::encrypt($ad->id) }}"/>
                    <input type="hidden" name="subject" id="subject" value="{{ $ad->title }}"/>
                    
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="radio radio-inline">
                                <input id="report_sold" type="radio" name="report" value="sold">
                                <label class="custom-label" for="report_sold">Product already sold</label>
                             </div>   
                        </li>
                        <li class="list-group-item">
                            <div class="radio radio-inline">
                                <input id="report_unreachable" type="radio" name="report" value="unreachable">
                                <label class="custom-label" for="report_unreachable">Seller not responding/phone unreachable</label>
                             </div>   
                        </li>
                        <li class="list-group-item">
                            <div class="radio radio-inline">
                                <input id="report_duplicate" type="radio" name="report" value="duplicate">
                                <label class="custom-label" for="report_duplicate">Ad is duplicate</label>
                             </div>   
                        </li>
                        <li class="list-group-item">
                            <div class="radio radio-inline">
                                <input id="report_wrong" type="radio" name="report" value="wrong">
                                <label class="custom-label" for="report_wrong">Wrong category</label>
                             </div>   
                        </li>
                        <li class="list-group-item">
                            <div class="radio radio-inline">
                                <input id="report_offensive" type="radio" name="report" value="offensive">
                                <label class="custom-label" for="report_offensive">Offensive content</label>
                             </div>   
                        </li>
                        <li class="list-group-item">
                            <div class="radio radio-inline">
                                <input id="report_fraud" type="radio" name="report" value="fraud">
                                <label class="custom-label" for="report_fraud">Fraud reason</label>
                             </div>   
                        </li>
                    </ul> 
                    <div class="form-group">
                        <textarea class="form-control" id="messagetext" name="messagetext" placeholder="Please provide more informations..."></textarea>
                    </div>                    
                 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
                <button type="submit" class="btn btn-default">
                     Report
                </button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>