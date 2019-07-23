@extends('layouts.app')
@section('title')
About
@stop
<style>
   .navbar-container.container-fluid{
        display: none !important;
    }
    @media (max-width: 991px){
        .page {
            padding-top: 0px !important;
        }
    }
    .pac-logo:after{
      display: none;
	}
	.browse_category h3::after { left: 0 !important;}
</style>
@section('content')
    <div class="home-sidebar">
    @include('layouts.sidebar')
    </div>
    <div id="content" class="container m-0" style="width: 100%;">
		<!-- start aboutus content -->
			<div class="browse_category">
				<div class="page-content">
					<div class="py-15">
						<div class="text-center">
							<h3>About Us</h3>
						</div>
						<div class="row">
							<div class="col-lg-2 col-md-2"></div>
							<div class="col-lg-8 col-md-8 col-sm-12 col-12">
								{!! $about->body !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		<!-- end aboutus content -->

		<!-- start below after serching div -->
        <div class="after_serach">
			<div class="container">
				<div class="row">
					<div class="col-lg-1 col-sm-12 col-md-1"></div>
					<div class="col-lg-10 col-sm-12 col-md-10">
						<div class="inner_search">
							<div class="row">
								<div class="col-sm-12 col-md-4 col-lg-4 col-12">
									<img src="../frontend/assets/examples/images/searchresult.jpg" alt="" title="">
								</div>
								<div class="col-sm-12 col-12 col-md-8 col-lg-8">
									<ul>
										<li><b>Georgia Access and Crisis Line ( GCAL ): </b><br/>  1-800-715-4225</li>
										<li><b>Crisis Text Line for Youth: </b><br/>
										Text “HOME” to 74174 ( 24/7 crisi Support)</li>
										<li><b>National Suicide Prevention Lifeline: </b><br/>
										1-800-273-8255</li>
										<li><b>Trevor Project: </b><br/>
										1-866-488-7386 ( Crisis and Suicide Prevention Support line for LGBTG )</li>
										<li><b>LGBT Youth Talkline: </b><br/>
										1-800-246-7743</li>
										<li><b>Poison Control Hotline: </b><br/>
										1-800-222-1222</li>
										<li><b>For all Emergencies, please call 9-1-1.</b></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- end below after serching div -->


		{{-- <div class="row pt-20 pl-15" style="margin-right: 0">
			<div class="col-xl-7 col-md-7">
				<!-- Panel -->
				<div class="panel">
				<div class="panel-body">
					{!! $about->body !!}
				</div>
				</div>
				<!-- End Panel -->
			</div>
			<div class="col-xl-5 col-md-5">
				<!-- Panel -->
				<div class="panel">
					<div class="panel-body bg-primary-color">
						<div class="form-group">
							<h4 class="text-white">Find Services Near an Address?</h4>
							<form method="post" action="/search_address">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="form-group">
									
									<div class="input-search">
										<i class="input-search-icon md-search" aria-hidden="true"></i>
										<input id="location" type="text" class="form-control text-black" name="search_address" placeholder="Search Address" style="border-radius:0;">
									</div>
									
								</div>
								<button type="submit" class="btn btn_findout"><h4 class="text-white mb-0">Search</h4></button>
								<a href="/services_near_me" class="btn btn_findout pull-right"><h4 class="text-white mb-0">Services Near Me</h4></a>
							</form>
						</div>
					</div>
					<div class="panel-body">
						{!! $home->sidebar_content !!}
					</div>
				</div>
			</div>
				<!-- End Panel -->
		</div> --}}
    </div>
<!--     <div class="fade-in">
      <div class="loader-overlay">
        <div class="loader-content">
          <div class="loader-index">
          </div>
        </div>
      </div>
    </div> -->


<!-- <script>
  function initAutocomplete() {

      var options = {
        types: ['geocode'],
        componentRestrictions: { country: 'us'}
       };

      var input = document.getElementById('location');
      var searchBox = new google.maps.places.Autocomplete(input, options);
  }
  </script> -->

<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-RQR_KenWPqcgUbOtMLreNVWeTV1wcSo&libraries=places&callback=initAutocomplete" async defer></script> -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- <script>
    $(document).ready(function(){
        if(screen.width < 768){
          var text= $('.navbar-header').css('height');
          var height = text.slice(0, -2);
          $('.page').css('padding-top', height);
          $('#content').css('top', height);
        }
        else{
          var text= $('.navbar-header').css('height');
          var height = 0;
          $('.page').css('margin-top', height);
        }
    });
</script> -->
<script>

$(function () {
    var getData = function (request, response) {
        $.getJSON(
            "https://geosearch.planninglabs.nyc/v1/autocomplete?text=" + request.term,
            function (data) {
                response(data.features);
                
                var label = new Object();
                for(i = 0; i < data.features.length; i++)
                    label[i] = data.features[i].properties.label;
                response(label);
            });
    };
 
    var selectItem = function (event, ui) {
        $("#location").val(ui.item.value);
        return false;
    }
 
    $("#location").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {
            console.log(selectItem);

        }
    });
});
</script>
@endsection

