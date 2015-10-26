@extends('layout.profile')

@section('page_title')
    User Profile  
@stop

@section('inner_content')
    <div class="summary-box well light-blue-box">
        <div class="row">
            <div class="col-sm-12">
                <p><?php echo $summary; ?></p>    
            </div>
        </div>
    </div>
    @include('user.profile.record')
    
    {{ $ads->appends(GeneralPurpose::getQuerystringParams())->links() }}
@stop

@section('additional_scripts')
    {{ HTML::script('js/profile.js') }}
    {{ HTML::script('js/followers.js') }}
@stop
