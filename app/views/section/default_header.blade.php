<nav class="navbar navbar-inverse navbar-static-top" role="navigation">

    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand header-logo" href="{{ URL::to('/')}}">
            	<img class="responsive" src="{{ URL::to('/'); }}/img/logo.png">
            </a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <?php $unread = Message::getUnreadMessages(Auth::user()->id); ?>
                    <li>
                        <a href="{{ URL::to('/')}}/inbox-messages"><span <?php echo ($unread>0) ? 'data-notifications="'.$unread.'"' : '';?> class="top-nav-icon glyphicon glyphicon-envelope"></span></a>
                        <!--button data-notifications="10" onclick="window.location=base_url+'/inbox-messages';" id="top-right-message" type="button" class="btn navbar-btn"><span class="glyphicon glyphicon-envelope"></span> Message <span class="badge"><?php echo $unread;?></span</span></button -->
                    </li>
                    <li>
                        <a href="{{ URL::to('/')}}/timeline"><span class="fa fa-user fa-lg"></span> <?php echo Auth::user()->fname;?></a>
                    </li>
                    
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><b class="caret"></b></a>
                        
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ URL::to('/')}}/users"><span class="fa fa-user fa-cog"></span> User Management</a>
                            </li>
                            <li role="presentation" class="divider"></li>
                            <li>
                                <a href="{{ URL::to('/')}}/my-account"><span class="fa fa-user fa-cog"></span> Ad Management</a>
                            </li>
                            <li role="presentation" class="divider"></li>
                            <li>
                                <a href="{{ URL::to('/')}}/logout"><span class="fa fa-sign-out fa-lg"></span> Sign Out</a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ URL::route('login') }}"> <span class="fa fa-sign-in fa-lg"></span> Login </a>
                    </li>
                @endif               
                
            </ul>
        </div>
    </div>
</nav>