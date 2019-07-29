  <div class="filter-bar container-fluid bg-secondary" style="padding-top: 14px;    background-color: #abcae9 !important;">
	<div class="row">
		<div class="col-md-2 col-sm-2"></div>
		<div class="col-md-8 col-sm-8 col-xs-12">
			<form action="/search" method="POST">
				<div class="row">
		          	<div class="col-md-2">
		              	@if($layout->meta_filter_activate == 1)
		                <button type="button" class="btn btn-primary btn-block waves-effect waves-classic dropdown-toggle  btn-button" id="exampleBulletDropdown1" data-toggle="dropdown" aria-expanded="false" style="line-height: 31px;">
		                    Metafilter
		                </button>
		                <div class="dropdown-menu bullet" aria-labelledby="exampleBulletDropdown1" role="menu">
		                    <a class="dropdown-item" href="javascript:void(0)" role="menuitem" id="toggle1">{{$layout->meta_filter_on_label}}</a>
		                    <a class="dropdown-item" href="javascript:void(0)" role="menuitem" id="toggle2">{{$layout->meta_filter_off_label}}</a>
		                </div>
		              	@endif
		          	</div>
					<div class="col-md-4">
						<div class="input-search">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<i class="input-search-icon md-search" aria-hidden="true"></i>
							<input type="text" class="form-control search-form" name="find" placeholder="Search for Services" id="search_address" @if(isset($chip_service)) value="{{$chip_service}}" @endif>
						</div>    
					</div>
					<div class="col-md-4">
						<div class="input-search">
							<i class="input-search-icon md-pin" aria-hidden="true"></i>
							<input id="location2" type="text" class="form-control search-form" name="search_address" placeholder="Search Address" @if(isset($chip_address)) value="{{$chip_address}}" @endif>
							<button type="button" class="input-search-btn" title="Services Near Me"><a href="/services_near_me"><i class="icon md-gps-dot"></i></a></button>
						</div>
					</div>
					<div class="col-md-2">
						<button class="btn btn-primary btn-block waves-effect waves-classic btn-button" title="Search" style="line-height: 31px;">Search</button>
					</div>
				</div>
			</form>
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