<div class="filter-bar container-fluid bg-secondary" style="padding-top: 14px;">
    <div class="col-md-8 col-md-offset-2">
        <form action="/search" method="POST">
        <div class="col-md-5">
          <div class="input-search">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <i class="input-search-icon md-search" aria-hidden="true"></i>
              <input type="text" class="form-control search-form" name="find" placeholder="Search for Services" id="search_address" @if(isset($chip_service)) value="{{$chip_service}}" @endif>
          </div>    
        </div>
        <div class="col-md-5">

            <div class="input-search">
              <i class="input-search-icon md-pin" aria-hidden="true"></i>
              <input id="location2" type="text" class="form-control search-form" name="search_address" placeholder="Search Address" @if(isset($chip_address)) value="{{$chip_address}}" @endif>
              <button type="button" class="input-search-btn" title="Services Near Me"><a href="/services_near_me"><i class="icon md-gps-dot"></i></a></button>
            </div>
 
        </div>
        
        <div class="col-md-2">
           <button class="btn btn-block waves-effect waves-classic btn_findout" style="padding: 0;margin-bottom: 14px;font-size: 22px;" title="Search"><a class="search-near" style="display: block;font-size: 18px;padding: 8px;color: #000;">Search</a></button>
        </div>
        </form>
    </div>  
</div>
<style>
@media (max-width: 768px){
  .filter-bar{
    display: none;
  }
}
</style>