<li class="dash-item dash-hasmenu {{ (Request::route()->getName() == 'landingpage.index') ? ' active' : '' }}">
    <a href="{{ route('landingpage.index') }}" class="dash-link">
        <span class="dash-micon"><i class="ti ti-access-point"></i></span><span class="dash-mtext">{{__('Landing Page')}}</span>
    </a>
</li>

