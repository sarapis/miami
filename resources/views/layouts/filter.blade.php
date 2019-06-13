<div class="filter-bar container-fluid bg-secondary" style="padding-top: 14px;">
    <div class="col-md-8 col-md-offset-2">
        <div class="col-md-5">
          <form action="/find" method="POST" class="input-search">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <i class="input-search-icon md-search" aria-hidden="true"></i>
              <input type="text" class="form-control search-form" name="find" placeholder="Search for Services" id="search_address" @if(isset($chip_service)) value="{{$chip_service}}" @endif>
              
          </form>
        </div>
        <div class="col-md-5">
          <form method="post" action="/search_address" class="input-search" id="search_location">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              
                  <i class="input-search-icon md-pin" aria-hidden="true"></i>
                  <input id="location2" type="text" class="form-control search-form" name="search_address" placeholder="Search Address" @if(isset($chip_address)) value="{{$chip_address}}" @endif>
           
          </form>
        </div>
        <div class="col-md-2">
           <button class="btn btn-block waves-effect waves-classic btn_findout" style="padding: 0;margin-bottom: 14px;" title="Services Near Me"><a href="/services_near_me" class="search-near" style="display: block;font-size: 22px;padding: 5px;"><i class="input-search-icon md-pin-drop" aria-hidden="true"></i></a></button>
        </div>
    </div>  
</div>
<style>
@media (max-width: 768px){
  .filter-bar{
    display: none;
  }
}
</style>