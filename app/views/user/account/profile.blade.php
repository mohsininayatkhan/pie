<div class="row">
    <div class="col-sm-12 list">
        <div id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">My Profile</h4>
                    <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                </div>
                
                <div id="account-info" class="panel-collapse collapse in">
                    <div class="panel-body">
                         {{ Form::open(array('url' => 'update-user-photo','method'=>'POST', 'name' => 'frmUserPhoto', 'id'=>'frmUserPhoto', 'class' => 'form-horizontal', 'role' => 'form')) }}
                        <div class="col-sm-2">
                            <?php $src = Config::get('app.user_img_path') . 'noimg.jpg';
                            if ($user->photo && $user->photo != '') {
                                $src = Config::get('app.user_img_path') . $user->photo;
                            }
                            ?>
                            <div class="thumbnail profile-thumb">
                                <img id="user-profil-photo" class="img-responsive img-hover img-rounded" src="<?php echo Image::url($src,300,300,array('crop'))?>" alt="<?php echo $user->fname . ' ' . $user->lname; ?>">
                            </div>

                            <div class="row top-margin-sm">
                                <div class="col-sm-12">
                                    <button type="button" id="upload_img" name="upload_img" class="btn btn-lg btn-success">
                                        Replace Photo
                                    </button>
                                </div>
                            </div>
                            <input type="file" id="photo" name="photo" class="hide" onchange="profile_ChangePhoto(this)" />
                        </div>
                        <?php echo Form::token(); ?>
                        {{ Form::close() }}
                        
                        {{ Form::open(array('url' => 'update-profile','method'=>'POST', 'name' => 'frmProfile', 'id'=>'frmProfile', 'class' => 'form-horizontal', 'role' => 'form')) }}
                        <div class="col-sm-10">
                            <div class="form-group">
                                <label for="First Name" class="col-sm-2 control-label custom-label">First Name*:</label>
                                <div class="col-sm-10">
                                    {{ Form::text('first_name',$user->fname,array('id' => 'first_name','class'=>'form-control custom-label')) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="Last Name" class="col-sm-2 control-label custom-label">Last Name*:</label>
                                <div class="col-sm-10">
                                    {{ Form::text('last_name',$user->lname,array('id' => 'last_name','class'=>'form-control custom-label')) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="Last Name" class="col-sm-2 control-label custom-label">Email*:</label>
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        {{ Form::text('email',$user->email,array('id' => 'email', 'disabled' => 'disabled', 'class'=>'form-control custom-label')) }}
                                        <span class="input-group-btn">
                                            <button title="locked" class="btn btn-default" type="button">
                                                <i class="fa fa-lock"></i>
                                            </button> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="Phone" class="col-sm-2 control-label custom-label">Phone:</label>
                                <div class="col-sm-10">
                                    {{ Form::text('phone',$user->phone,array('id' => 'phone','class'=>'form-control custom-label','placeholder'=>'XXXXXXXXXXX','maxlength'=>'11')) }}
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="Phone" class="col-sm-2 control-label custom-label">Website:</label>
                                <div class="col-sm-10">
                                    {{ Form::text('website',$user->website,array('id' => 'website','class'=>'form-control custom-label','placeholder'=>'http://example.com')) }}
                                </div>
                            </div>
                            
                            <div id="user-state" class="form-group">
                                <label for="Phone" class="col-sm-2 control-label custom-label">State:</label>
                                <div class="col-sm-10">
                                    {{ Form::select('state', $states, $user->state, array('id' => 'state', 'class'=>'form-control custom-label location')) }}
                                </div>
                            </div>
                            
                            
                            <div id="user-city" class="form-group">
                                <?php 
                                if (count($cities)) {
                                ?>
                                <label for="Phone" class="col-sm-2 control-label custom-label">City:</label>
                                <div class="col-sm-10">
                                    {{ Form::select('city', $cities, $user->city, array('id' => 'city', 'class'=>'form-control custom-label location')) }}
                                </div>
                                <?php 
                                }
                                ?>
                            </div>
                            
                            <div id="user-town" class="form-group">
                                <?php 
                                if (count($towns)) {
                                ?>
                                <label for="Phone" class="col-sm-2 control-label custom-label">Town:</label>
                                <div class="col-sm-10">
                                    {{ Form::select('town', $towns, $user->town, array('id' => 'town', 'class'=>'form-control custom-label location')) }}
                                </div>
                                <?php 
                                }
                                ?>
                            </div>
                            
                            <div class="row pull-right">
                                <div class="col-sm-12">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Save
                                    </button>
                                    <a href="<?php echo URL::to('/').'/timeline'; ?>">
                    					<button type="button" class="btn btn-lg btn-default">Cancel</button>
                					</a>
                                </div>
                                
                            </div>
                            <?php echo Form::token(); ?>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>