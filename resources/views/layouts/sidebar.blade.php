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
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/services" style="display: block;padding-left: 10px;">Services</a></button>
        </li>
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/organizations" style="display: block;padding-left: 10px;">Organizations</a></button>
        </li>
        <li class="option-side">
            <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/about" style="display: block;padding-left: 10px;">About</a></button>
        </li>
    </ul>

       <ul class="list-unstyled components pt-0"> 
            <li class="option-side sidebar-menu">
                <form action="/find" method="POST" class="mb-5">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-search">
                        <i class="input-search-icon md-search" aria-hidden="true"></i>
                        <input type="text" class="form-control search-form" name="find" placeholder="Search for Services" id="search_address" @if(isset($chip_service)) value="{{$chip_service}}" @endif>
                    </div>
                </form>
            </li>

            <li class="option-side sidebar-menu">
                <!--begin::Form-->
                <form method="post" action="/search_address" class="mb-5" id="search_location">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="input-search">
                        <i class="input-search-icon md-pin" aria-hidden="true"></i>
                        <input id="location" type="text" class="form-control search-form" name="search_address" placeholder="Search Address" @if(isset($chip_address)) value="{{$chip_address}}" @endif>
                    </div>
                </form>
            </li>

            <li class="option-side sidebar-menu">
                <button class="btn btn-block waves-effect waves-classic" style="padding: 0;background: #A2E9FF;"><a href="/services_near_me" style="display: block;padding-left: 10px;">Services Near Me</a></button>
            </li> 

            <form action="/filter" method="POST" id="filter">
            {{ csrf_field() }}      
            <li class="option-side">
                <a href="#projectcategory" class="text-side" data-toggle="collapse" aria-expanded="false">Services</a>
                <ul class="collapse list-unstyled option-ul" id="projectcategory">
                    <ul id="tree2">
                        @foreach($taxonomies as $taxonomy)
                            @if($taxonomy->taxonomy_name)
                                                                
                                <li class="nobranch">
                                    
                                        <input type="checkbox" id="category_{{$taxonomy->taxonomy_recordid}}" @if(count($taxonomy->childs)) name="parents[]" @else name="childs[]" @endif value="{{$taxonomy->taxonomy_recordid}}"  class="regular-checkbox" @if(in_array($taxonomy->taxonomy_recordid, $parent_taxonomy)) checked @elseif(in_array($taxonomy->taxonomy_recordid, $child_taxonomy)) checked @endif/>
                                        <span class="inputChecked">{{$taxonomy->taxonomy_name}}</span>
                                    
                                    @if(count($taxonomy->childs))
                                        @include('layouts.manageChild1',['childs' => $taxonomy->childs])
                                    @endif
                                </li>
                                    
                            @endif
                        @endforeach
                    </ul>
                </ul>
            </li>
            <!-- <li class="option-side">
                <a href="#cityagency" class="text-side" data-toggle="collapse" aria-expanded="false">Organization</a>
                <ul class="collapse list-unstyled option-ul" id="cityagency">
                    @foreach($organizations as $organization)
                        @if($organization->organization_services)
                        <li class="nobranch">
                            <input type="checkbox" name="organizations[]" value="{{$organization->organization_recordid}}"  class="regular-checkbox" @if(in_array($organization->organization_recordid, $checked_organizations)) checked @endif/>
                            <span class="inputChecked">{{$organization->organization_name}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li> -->

            <li class="option-side">
                <a href="#ages" class="text-side" data-toggle="collapse" aria-expanded="false">Ages Served</a>
                <ul class="collapse list-unstyled option-ul" id="ages">
                    @foreach($ages as $age)
                        @if($age->detail_value)
                        <li class="nobranch">
                            <input type="checkbox" name="ages[]" value="{{$age->detail_recordid}}"  class="regular-checkbox" @if(in_array($age->detail_recordid, $checked_ages)) checked @endif/>
                            <span class="inputChecked">{{$age->detail_value}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="option-side">
                <a href="#languages" class="text-side" data-toggle="collapse" aria-expanded="false">Languages</a>
                <ul class="collapse list-unstyled option-ul" id="languages">
                    @foreach($languages as $language)
                        @if($language->detail_value)
                        <li class="nobranch">
                            <input type="checkbox" name="languages[]" value="{{$language->detail_recordid}}"  class="regular-checkbox" @if(in_array($language->detail_recordid, $checked_languages)) checked @endif/>
                            <span class="inputChecked">{{$language->detail_value}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="option-side">
                <a href="#service_settings" class="text-side" data-toggle="collapse" aria-expanded="false">Service Setting</a>
                <ul class="collapse list-unstyled option-ul" id="service_settings">
                    @foreach($service_settings as $service_setting)
                        @if($service_setting->detail_value)
                        <li class="nobranch">
                            <input type="checkbox" name="service_settings[]" value="{{$service_setting->detail_recordid}}"  class="regular-checkbox" @if(in_array($service_setting->detail_recordid, $checked_settings)) checked @endif/>
                            <span class="inputChecked">{{$service_setting->detail_value}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="option-side">
                <a href="#insurance" class="text-side" data-toggle="collapse" aria-expanded="false">Insurance</a>
                <ul class="collapse list-unstyled option-ul" id="insurance">
                    @foreach($insurances as $insurance)
                        @if($insurance->detail_value)
                        <li class="nobranch">
                            <input type="checkbox" name="insurances[]" value="{{$insurance->detail_recordid}}"  class="regular-checkbox" @if(in_array($insurance->detail_recordid, $checked_insurances)) checked @endif/>
                            <span class="inputChecked">{{$insurance->detail_value}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="option-side">
                <a href="#culturals" class="text-side" data-toggle="collapse" aria-expanded="false">Cultural Competency</a>
                <ul class="collapse list-unstyled option-ul" id="culturals">
                    @foreach($culturals as $cultural)
                        @if($cultural->detail_value)
                        <li class="nobranch">
                            <input type="checkbox" name="culturals[]" value="{{$cultural->detail_recordid}}"  class="regular-checkbox" @if(in_array($cultural->detail_recordid, $checked_culturals)) checked @endif/>
                            <span class="inputChecked">{{$cultural->detail_value}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="option-side">
                <a href="#transportations" class="text-side" data-toggle="collapse" aria-expanded="false">Transportation</a>
                <ul class="collapse list-unstyled option-ul" id="transportations">
                    @foreach($transportations as $transportation)
                        @if($transportation->detail_value)
                        <li class="nobranch">
                            <input type="checkbox" name="transportations[]" value="{{$transportation->detail_recordid}}"  class="regular-checkbox" @if(in_array($transportation->detail_recordid, $checked_transportations)) checked @endif/>
                            <span class="inputChecked">{{$transportation->detail_value}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="option-side">
                <a href="#hours" class="text-side" data-toggle="collapse" aria-expanded="false">Additional Hours</a>
                <ul class="collapse list-unstyled option-ul" id="hours">
                    @foreach($hours as $hour)
                        @if($hour->detail_value)
                        <li class="nobranch">
                            <input type="checkbox" name="hours[]" value="{{$hour->detail_recordid}}"  class="regular-checkbox" @if(in_array($hour->detail_recordid, $checked_hours)) checked @endif/>
                            <span class="inputChecked">{{$hour->detail_value}}</span>
                        </li>   
                        @endif
                    @endforeach
                </ul>
            </li>

            <li class="option-side mobile-btn">
                <a href="#export" class="text-side" data-toggle="collapse" aria-expanded="false">Print/Export</a>
                <ul class="collapse list-unstyled option-ul" id="export">
                    <li class="nobranch">
                        <a class="dropdown-item" href="javascript:void(0)" role="menuitem">Expert CSV</a>
                        <a class="dropdown-item" href="javascript:void(0)" role="menuitem">Print PDF action</a>
                    </li>   
                </ul>
            </li>
            <li class="option-side mobile-btn">
                <a href="#perpage" class="text-side" data-toggle="collapse" aria-expanded="false">Results Per Page</a>
                <ul class="collapse list-unstyled option-ul" id="perpage">
                    <li class="nobranch">
                        <a @if(isset($pagination) && $pagination == '10') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem" >10</a>
                        <a @if(isset($pagination) && $pagination == '25') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">25</a>
                        <a @if(isset($pagination) && $pagination == '50') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">50</a>
                    </li>   
                </ul>
            </li>
            <li class="option-side mobile-btn">
                <a href="#sort1" class="text-side" data-toggle="collapse" aria-expanded="false">Sort</a>
                <ul class="collapse list-unstyled option-ul" id="sort1">
                    <li class="nobranch">
                        <a @if(isset($sort) && $sort == 'Service Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Service Name</a>
                        <a @if(isset($sort) && $sort == 'Organization Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Organization Name</a>
                        <a @if(isset($sort) && $sort == 'Distance from Address') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Distance from Address</a>
                    </li>   
                </ul>
            </li>
            <input type="hidden" name="paginate" id="paginate" @if(isset($pagination)) value="{{$pagination}}" @else value="10" @endif>
            <input type="hidden" name="sort" id="sort" @if(isset($sort)) value="{{$sort}}" else value="" @endif>

            <input type="hidden" name="pdf" id="pdf">

            <input type="hidden" name="csv" id="csv">

            </form>
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


