@extends('layouts.app')
@section('title')
Services
@stop
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">


<style type="text/css">
.table a{
    text-decoration:none !important;
    color: rgba(40,53,147,.9);
    white-space: normal;
}
.footable.breakpoint > tbody > tr > td > span.footable-toggle{
    position: absolute;
    right: 25px;
    font-size: 25px;
    color: #000000;
}
.ui-menu .ui-menu-item .ui-state-active {
    padding-left: 0 !important;
}
ul#ui-id-1 {
    width: 260px !important;
}
.btn-group{
    width: 90%;
}
.btn-sort{
    width: 100% !important;
}
/*#map{
    position: fixed !important;
}*/
@media (max-width: 768px) {
    .property{
        padding-left: 30px !important;
    }
    #map{
        display: block !important;
        width: 100% !important;
    }
}
</style>

@section('content')
@include('layouts.filter')
<div class="wrapper">
    
    @include('layouts.sidebar')
    <!-- Page Content Holder -->
    <div id="content" class="container">
        <!-- Example Striped Rows -->
        <div class="row" style="margin-right: 0">
            <div class="col-md-8 pt-15 pr-0 pl-30">
                <div class="col-md-3 p-0 btn-feature">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle btn-sort btn-button" id="exampleSizingDropdown2"
                        data-toggle="dropdown" aria-expanded="false">
                          <b>Download</b>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="exampleSizingDropdown2" role="menu">
                          <a class="dropdown-item" href="javascript:void(0)" role="menuitem" id="download_csv">Download CSV</a>
                          <a class="dropdown-item" href="javascript:void(0)" role="menuitem" id="download_pdf">Download PDF</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 p-0 btn-feature">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle btn-sort btn-button" id="exampleSizingDropdown2"
                        data-toggle="dropdown" aria-expanded="false">
                          <b>Results Per Page</b>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="exampleSizingDropdown2" role="menu">
                          <a @if(isset($pagination) && $pagination == '10') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem" >10</a>
                          <a @if(isset($pagination) && $pagination == '25') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">25</a>
                          <a @if(isset($pagination) && $pagination == '50') class="dropdown-item drop-paginate active" @else class="dropdown-item drop-paginate" @endif href="javascript:void(0)" role="menuitem">50</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 p-0 btn-feature">
                    <div class="btn-group">
                        <button type="button" class="btn btn-info dropdown-toggle btn-sort btn-button" id="exampleSizingDropdown2"
                        data-toggle="dropdown" aria-expanded="false">
                          <b>Sort</b>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="exampleSizingDropdown2" role="menu">
                          <a @if(isset($sort) && $sort == 'Service Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Service Name</a>
                          <a @if(isset($sort) && $sort == 'Organization Name') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Organization Name</a>
                          <a @if(isset($sort) && $sort == 'Distance from Address') class="dropdown-item drop-sort active" @else class="dropdown-item drop-sort" @endif href="javascript:void(0)" role="menuitem">Distance from Address</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 p-0 btn-feature">
                    <div class="btn-group" style="padding: 6px;">
                        @if(isset($search_results))
                          <p class="m-0"><b>Results: {{$search_results}}</b></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-right: 0">
            
            <div class="col-md-8 pt-15 pr-0">
                @if (session('address'))
                    <div class="alert dark alert-danger alert-dismissible ml-15" role="alert">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                      <b>{{ session('address') }}</b>
                    </div>
                @endif

                @if(count($services) != 0)
                    @foreach($services as $service)
                        @if($service->service_name != null)
                        <div class="panel content-panel">
                            <div class="panel-body p-20">
                                
                                <a class="panel-link" href="/service/{{$service->service_recordid}}">{{$service->service_name}}</a>
                                <h4><span class="badge bg-red">Category:</span> 
                                    @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                                        @foreach($service->taxonomy as $key => $taxonomy)
                                            @if($loop->last)
                                            <a class="panel-link" href="/category/{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>                                    @else
                                            <a class="panel-link" href="/category/{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>,
                                            @endif
                                        @endforeach
                                    @endif    
                                </h4>
                                <h4><span class="badge bg-red">Organization:</span>
                                    @if(isset($service->organizations))                        
                                        @foreach($service->organizations as $organization)
                                            @if($loop->last)
                                            <a class="panel-link" href="/organization/{{$organization->organization_recordid}}" class="notranslate"> {{$organization->organization_name}}</a>
                                            @else
                                            <a class="panel-link" href="/organization/{{$organization->organization_recordid}}" class="notranslate"> {{$organization->organization_name}}</a>,
                                            @endif
                                        @endforeach                       
                                    @endif
                                </h4>
                                <h4><span class="badge bg-red">Phone:</span> @foreach($service->phone as $phone) {!! $phone->phone_number !!} @endforeach</h4>
                                <h4><span class="badge bg-blue">Address:</span>
                                    @if(isset($service->address))
                                        @foreach($service->address as $address)
                                          {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                        @endforeach
                                    @endif
                                </h4>
                                <h4><span class="badge bg-blue">Description:</span> {!! str_limit($service->service_description, 200) !!}</h4>
                            </div>
                        </div>
                        @endif
                    @endforeach

                @else
                    <div class="alert dark alert-warning ml-15" role="alert">
                      <p style="color: #ffffff;">
                        <b>There are no results.</b>
                      </p>
                    </div>
                @endif
                <div class="pagination pl-15 pr-15 m-0">
                    {{ $services->appends(\Request::except('page'))->render() }}
                </div>
            </div>
            
            <div class="col-md-4 pt-15 property">
                <div class="panel">
                    <div class="panel-body p-0">
                        <div id="map" style="width: 100%; height: 50vh;">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    
    $(document).ready(function(){

        setTimeout(function(){
            var locations = <?php print_r(json_encode($locations)) ?>;
            var maplocation = <?php print_r(json_encode($map)) ?>;
            // console.log(map);

            var sumlat = 0.0;
            var sumlng = 0.0;
            var length = 0;
            // console.log(locations.length);   

            if(maplocation.active == 1){
                avglat = maplocation.lat;
                avglng = maplocation.long;
                zoom = maplocation.zoom;
            }
            else
            {
                avglat = 40.730981;
                avglng = -73.998107;
                zoom = 14;
            }
            console.log(maplocation.active);
            var mymap = new GMaps({
              el: '#map',
              lat: avglat,
              lng: avglng,
              zoom: zoom
            });

        

            $.each( locations, function(index, value ){
                // console.log(locations);
                var name = value.organization==null?'':value.organization.organization_name;
 

                var content = "";
                for(i = 0; i < value.services.length; i ++){
                    content +=  '<a href="/service/'+value.services[i].service_recordid+'" style="color:#428bca;font-weight:500;font-size:14px;">'+value.services[i].service_name+'</a><br>';
                }
                content += '<p>'+name+'</p>';

                if(value.location_latitude){
                    mymap.addMarker({

                        lat: value.location_latitude,
                        lng: value.location_longitude,
                        title: value.city,
                               
                        infoWindow: {
                            maxWidth: 250,
                            content: (content)
                        }
                    });
                }
            });
        }, 2000)
    });
    
</script>
@endsection


