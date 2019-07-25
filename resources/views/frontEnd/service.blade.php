@extends('layouts.app')
@section('title')
Service
@stop
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> --}}


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
#map{
    position: relative !important;
    z-index: 0 !important;
}
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
        <!-- <div id="map" style="height: 30vh;"></div> -->
        <!-- Example Striped Rows -->
        <div class="row m-0">
            <div class="col-md-8 pt-15 pb-15 pl-30">
                <div class="card page-project">
                    <div class="card-block">
                        <h4 class="card-title">
                            <a href="#">{{$service->service_name}}</a>
                        </h4>
                        @if(isset($service->service_alternate_name))
                        <h4><span><b>Alternate Name:</b></span> {{$service->service_alternate_name}}</h4>
                        @endif

                        <h4><span class="badge bg-red pl-0  pr-0 organize_font"><b>Organization:</b></span>
                            @if($service->service_organization!=0)                        
                                @foreach($service->organizations as $organization)
                                    @if($loop->last)
                                    <a class="panel-link" href="/organization/{{$organization->organization_recordid}}" class="notranslate"> {{$organization->organization_name}}</a>
                                    @else
                                    <a class="panel-link" href="/organization/{{$organization->organization_recordid}}" class="notranslate"> {{$organization->organization_name}}</a>,
                                    @endif
                                @endforeach                       
                            @endif
                        </h4>

                        <h4 style="line-height: inherit;">{!! $service->service_description !!}</h4>

                        @if(isset($service->phone()->first()->phone_number))  
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-account font-size-24 vertical-align-top  mr-5 pr-10"></i>
                            @foreach($service->phone as $phone)
                                @if($loop->last)
                                {{$phone->phone_number}}
                                @else
                                {{$phone->phone_number}},
                                @endif
                            @endforeach    
                            </span>                   
                        </h4>
                        @endif

                        @if(isset($service->phone()->first()->phone_extension)) 
                        <h4 style="line-height: inherit;">
                            <span><i class="icon md-account font-size-24 vertical-align-top  mr-5 pr-10"></i>
                            @foreach($service->phone as $phone) {!! $phone->phone_extension !!} @endforeach 
                            </span> 
                        </h4>
                        @endif 
                        <h4 style="line-height: inherit;">
                            <span>  
                            <i class="icon md-globe font-size-24 vertical-align-top  mr-5 pr-10"></i>
                                 @if($service->service_url!=NULL)<a href="{!! $service->service_url !!}">{!! $service->service_url !!}</a> @endif
                            </span>        
                        </h4>

                        @if($service->service_email!=NULL) 
                        <h4 style="line-height: inherit;">
                            <span> 
                            <i class="icon md-email font-size-24 vertical-align-top  mr-5 pr-10"></i>
                                 {{$service->service_email}}
                            </span>
                        </h4>
                        @endif 

                        <h4 style="line-height: inherit;">
                            <span> 
                            <i class="icon fa-language  font-size-24 vertical-align-top  mr-5 pr-10"></i>
                            @if(isset($service->languages))                        
                                @foreach($service->languages as $language)
                                    @if($loop->last)
                                    {{$language->language}}
                                    @else
                                    {{$language->language}},
                                    @endif
                                @endforeach                       
                            @endif
                            </span>
                        </h4>
                        @if($service->service_details!=NULL)
                            @php
                                $show_details = [];
                            @endphp
                            @foreach($service->details->sortBy('detail_type') as $detail)
                            @php
                                for($i = 0; $i < count($show_details); $i ++){
                                    if($show_details[$i]['detail_type'] == $detail->detail_type)
                                        break;
                                }
                                if($i == count($show_details)){
                                    $show_details[$i] = array('detail_type'=> $detail->detail_type, 'detail_value'=> $detail->detail_value);
                                }
                                else{
                                    $show_details[$i]['detail_value'] = $show_details[$i]['detail_value'].', '.$detail->detail_value;
                                }
                            @endphp                                
                            @endforeach
                            @foreach($show_details as $detail)
                            <h4><span class="badge bg-red"><b>{{ $detail['detail_type'] }}:</b></span> {!! $detail['detail_value'] !!}</h4>  
                            @endforeach
                        @endif
                                
                        @if($service->service_application_process)
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Application</b></span><br/>  {!! $service->service_application_process !!}
                        </h4>
                        @endif

                        @if($service->service_wait_time)
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Wait Time:</b></span><br/>  {{$service->service_wait_time}}</h4>
                        @endif

                        @if($service->service_fees)
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Fees:</b></span><br/> {{$service->service_fees}}</h4>
                        @endif

                        @if($service->service_accreditations)
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Accreditations</b></span><br/>{{$service->service_accreditations}}</h4>
                        @endif

                        @if($service->service_licenses)
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Licenses</b></span><br/>{{$service->service_licenses}}</h4>
                        @endif

                            
                        @if(isset($service->schedules()->first()->schedule_days_of_week)) 
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10"><b>Schedules</b></span><br/>
                            @foreach($service->schedules as $schedule)
                                @if($loop->last)
                                {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}} {{$schedule->schedule_closes_at}}
                                @else
                                {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}} {{$schedule->schedule_closes_at}},
                                @endif
                            @endforeach  
                        </h4>
                        @endif
                        <h4 class="py-10" style="line-height: inherit;"><span class="mb-10 pl-0 category_badge"><b>Category:</b>
                            @if($service->service_taxonomy!=0 || $service->service_taxonomy==null)
                                @foreach($service->taxonomy as $key => $taxonomy)
                                    @if($loop->last)
                                    <a class="panel-link" href="/category/{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>                                    @else
                                    <a class="panel-link" href="/category/{{$taxonomy->taxonomy_recordid}}">{{$taxonomy->taxonomy_name}}</a>,
                                    @endif
                                @endforeach
                            @endif  
                        </h4>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 property">
                <div class="pt-10 pb-10 pl-0 btn-download">
                    <a href="/download_service/{{$service->service_recordid}}" class="btn btn-primary ">Download PDF</a>
                    <button type="button" class="btn btn-primary">
                        <i class="icon md-share"></i> Share
                    </button>
                </div>
                <div class="card">
                    <div id="map" style="width:initial;margin: 0;height: 50vh;"></div>
                    <div class="card-block">
                        <div class="p-10">
                            @if(isset($service->locations))
                                @foreach($service->locations as $location)
                                <h4>
                                    <span><i class="icon fas fa-building font-size-24 vertical-align-top  "></i>{{$location->location_name}}</span> 
                                </h4>
                                <h4>
                                    <span><i class="icon md-pin font-size-24 vertical-align-top "></i>@if(isset($location->address))
                                        @foreach($location->address as $address)
                                        {{ $address->address_1 }} {{ $address->address_2 }} {{ $address->address_city }} {{ $address->address_state_province }} {{ $address->address_postal_code }}
                                        @endforeach
                                        @endif
                                    </span>
                                </h4>
                                
                                <h4><span><i class="icon fa-clock-o font-size-24 vertical-align-top "></i> {{$location->location_hours}}</span></h4>
                                <h4><span><i class="icon fa-truck font-size-24 vertical-align-top "></i> {{$location->location_transportation}}</span></h4>
                                <h4><span><i class="icon md-account font-size-24 vertical-align-top "></i>
                                        @foreach($location->phones as $phone)
                                        @php 
                                        $phones ='';
                                        $phones = $phones.$phone->phone_number.','; @endphp
                                        @endforeach
                                        {{ rtrim($phones, ',') }}
                                    </span>
                                </h4>  
                                <h4 style="line-height:inherit">{{$location->location_description}}</h4>
                                <h4><span><b>Accessibility for disabilities:</b></span> <br/>{{$location->accessibilities()->first()->accessibility}}</h4>
                                    @if(isset($location->schedules()->first()->schedule_days_of_week)) 
                                    <h4 class="panel-text"><span class="badge bg-red"><b>Schedules:</b></span>
                                        @foreach($location->schedules as $schedule)
                                            @if($loop->last)
                                            {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}} {{$schedule->schedule_closes_at}}
                                            @else
                                            {{$schedule->schedule_days_of_week}} {{$schedule->schedule_opens_at}} {{$schedule->schedule_closes_at}},
                                            @endif
                                        @endforeach                       
                                    </h4>
                                    @endif
                              @endforeach
                            @endif
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
        var locations = <?php print_r(json_encode($service->locations)) ?>;
        var maplocation = <?php print_r(json_encode($map)) ?>;

        console.log(locations);
        var show = 1;
        if(locations.length == 0){
          show = 0;
        }

        if(maplocation.active == 1){
            avglat = maplocation.lat;
            avglng = maplocation.long;
            zoom = maplocation.zoom;
        }
        else
        {
            avglat = 40.730981;
            avglng = -73.998107;
            zoom = 10
        }

        latitude = locations[0].location_latitude;
        longitude = locations[0].location_longitude;

        if(latitude == null){
            latitude = avglat;
            longitude = avglng;
        }

        var mymap = new GMaps({
          el: '#map',
          lat: latitude,
          lng: longitude,
          zoom: zoom
        });


        
          $.each( locations, function(index, value ){
              mymap.addMarker({
                  lat: value.location_latitude,
                  lng: value.location_longitude,
                  title: value.location_name
                         
                  
              });
         });
        
    }, 2000)
});


</script>
@endsection


