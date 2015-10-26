<div class="well light-blue-box">
    <h3 class="margin-bottom-0">
        Contact <?php echo ucfirst(strtolower($ad->seller_name)) ?>    
    </h3>
    <div class="clear"></div>
    <div class="page-header margin-top-0">
        Member <?php echo GeneralPurpose::timeAgo($ad->membership_date); ?>
        <a href="<?php echo URL::to('/').'/user-profile/'.$ad->user_slug?>" class="pull-right">View Profile</a>
    </div>
    
    
    <div class="row">
        @if ($ad->seller_phone_public=='1')  
        <div class="col-sm-6">
            <a title="Contact Number" href="javascript:void(0)" onclick="showContactNumber();" class="btn-phone btn-block btn btn-default"><span class="glyphicon glyphicon-earphone"></span> <span id="seller-contact-number"><?php echo str_limit($ad->seller_phone, 7, '...');?></span></a>
        </div>
        @endif
        <div class="col-sm-6">
            @if (Auth::check())    
                <a data-toggle="modal" data-target="#exampleModal" onclick="$('#ajax-error-report').html('');resetForm('frmAdMessage', false);" class="btn-message btn btn-default btn-block"><span class="glyphicon glyphicon-envelope"></span> Send Message</a>
            @else
                <a onclick="save_url('<?php echo Request::url();?>');window.location.href=base_url+'/login';" href="javascript:void(0);" class="btn-message btn-block btn btn-default"><span class="glyphicon glyphicon-envelope"></span> Send Message</a>
            @endif
        </div>
    </div>
    <div class="page-header margin-top-0"></div>
    
    <div class="row">
        <div class="col-sm-6">
            @if (Auth::check())    
                <a data-toggle="modal" data-target="#reportModal" onclick="$('#ajax-error-report').html('');resetForm('frmAdReport', false);" class="btn-reportad btn btn-default btn-block"><span class="glyphicon glyphicon-flag"></span> Report Ad</a>
            @else
                <a onclick="save_url('<?php echo Request::url();?>');window.location.href=base_url+'/login';" href="javascript:void(0);" class="btn-reportad btn btn-default btn-block"><span class="glyphicon glyphicon-flag"></span> Report Ad</a>
            @endif
        </div>
        
        <div class="col-sm-6">
            @if (Auth::check())
                <?php 
                $user = Auth::user();
                $count = Favouriteads::where('ad_id', '=', $ad->id)->where('user_id', '=', $user->id)->count();
                ?>
                <a class="btn-savead btn btn-default btn-block"><span class="glyphicon glyphicon-heart"></span> <span id="btn-save-txt"><?php echo ($count) ? 'Saved' : 'Save Ad'; ?></span></a>    
            @else
                <a class="btn-savead btn btn-default btn-block" onclick="save_url('<?php echo Request::url();?>');window.location.href=base_url+'/login';" href="javascript:void(0);" class=""><span class="glyphicon glyphicon-heart"></span> Save Ad</a>
            @endif
        </div>
    </div>
</div>
<?php 
echo "<script>function showContactNumber(){ $('#seller-contact-number').text('".$ad->seller_phone."');}</script>";
//echo "{('#seller-contact-number').html();}";
?>

