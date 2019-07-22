<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<style>
    .pac-logo:after{
      display: none;
    }
    ul, #myUL {
      list-style-type: none;
    }
    #tree2{
        padding-left: 30px;
    }
    .indicator{
        margin-left: -18px;
    }
    .child-ul{
        padding-left: 18px;
    }
    .inputChecked{
        font-size: 1em !important;
        font-weight: 400;
    }
    .branch{
        padding: 5px 0;
    }
    .nobranch{
        padding: 5px 0;
    }
    .regular-checkbox{
        -webkit-appearance: none;
        background-color: #fafafa;
        border: 1px solid #2196F3;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
        padding: 9px !important;
        border-radius: 3px;
        display: inline-block;
        position: relative;
        top: 4px;
    }
    .regular-checkbox:active, .regular-checkbox:checked:active {
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
    }

    .regular-checkbox:checked {
        background-color: #2196F3;
       
      /*  box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1);*/
        color: #ffffff;
    }
    .regular-checkbox:checked:after {
        content: '\2714';
        font-size: 14px;
        position: absolute;
        top: 0px;
        left: 3px;
        color: #ffffff;
    }
    #cityagency{
        padding-left: 12px;
    }
    #insurance{
        padding-left: 12px;
    }
    #ages{
        padding-left: 12px;
    }
    #languages{
        padding-left: 12px;
    }
    #service_settings{
        padding-left: 12px;
    }
    #culturals{
        padding-left: 12px;
    }
    #transportations{
        padding-left: 12px;
    }
    #hours{
        padding-left: 12px;
    }
    .alert{
        padding-left: 15px;
        padding-right: 30px;
    }
    .mobile-btn{
        display: none;
    }
    @media (max-width: 768px) {
        .mobile-btn{
            display: block;
        }
        .btn-feature{
            display: none;
        }
    }
</style>
<nav id="sidebar">
    <ul class="list-unstyled components pt-0 mb-0 sidebar-menu"> 
        <li class="option-side">
            <a href="/services" class="btn btn-block btn-primary waves-effect waves-classic" >Services</a>
        </li>
        <li class="option-side">
            <a href="/organizations" class="btn btn-block btn-primary waves-effect waves-classic" >Organizations</a>
        </li>
        <li class="option-side">
            <a href="/about" class="btn btn-block btn-primary waves-effect waves-classic" >About</a>
        </li>
        <li class="option-side">
            <div class="input-search">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <i class="input-search-icon md-search" aria-hidden="true"></i>
                <input type="text" class="form-control search-form" name="find" placeholder="Search for Services" id="search_address" @if(isset($chip_service)) value="{{$chip_service}}" @endif>
            </div> 
        </li>
        <li class="option-side">
            <div class="input-search">
                <i class="input-search-icon md-pin" aria-hidden="true"></i>
                <input id="location2" type="text" class="form-control search-form" name="search_address" placeholder="Search Address" @if(isset($chip_address)) value="{{$chip_address}}" @endif>
                <button type="button" class="input-search-btn" title="Services Near Me"><a href="/services_near_me"><i class="icon md-gps-dot"></i></a></button>
            </div>
        </li>
        <li class="option-side">
            <button class="btn btn-primary btn-block waves-effect waves-classic " title="Search" style="line-height: 31px;">Search</button>
        </li>
    </ul>

</nav>

<script src="{{asset('js/treeview2.js')}}"></script>
<script>
$(document).ready(function(){
    $('.regular-checkbox').on('click', function(e){
        $('input', $(this).next().next()).prop('checked',0);
        $("#filter").submit();
    });
    $('.drop-paginate').on('click', function(){
        $("#paginate").val($(this).text());
        $("#filter").submit();
    });
    $('.drop-sort').on('click', function(){
        $("#sort").val($(this).text());
        $("#filter").submit();
    });
    $('#download_csv').on('click', function(){
        $("#csv").val('csv');
        $("#filter").submit();
        $("#csv").val('');
    });
    $('#download_pdf').on('click', function(){
        $("#pdf").val('pdf');
        $("#filter").submit();
        $("#pdf").val('');
    });

    if($('input[checked]', $('#projectcategory')).length > 0){
        $('#projectcategory').prev().trigger('click');
    }
    if($('input[checked]', $('#cityagency')).length > 0){
        $('#cityagency').prev().trigger('click');
    }
    if($('input[checked]', $('#insurance')).length > 0){
        $('#insurance').prev().trigger('click');
    }
    if($('input[checked]', $('#ages')).length > 0){
        $('#ages').prev().trigger('click');
    }
    if($('input[checked]', $('#languages')).length > 0){
        $('#languages').prev().trigger('click');
    }
    if($('input[checked]', $('#service_settings')).length > 0){
        $('#service_settings').prev().trigger('click');
    }
    if($('input[checked]', $('#culturals')).length > 0){
        $('#culturals').prev().trigger('click');
    }
    if($('input[checked]', $('#transportations')).length > 0){
        $('#transportations').prev().trigger('click');
    }
    if($('input[checked]', $('#hours')).length > 0){
        $('#hours').prev().trigger('click');
    }
});
</script>


