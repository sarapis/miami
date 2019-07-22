@extends('layouts.app')
@section('title')
Home
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
    ul#tree1 {
        column-count: 2;
    }
    .home-category{
        cursor: pointer;
    }
</style>
<link href="{{asset('css/treeview.css')}}" rel="stylesheet">
@section('content')
    <div class="home-sidebar">
        @include('layouts.sidebar')
    </div>
    <!-- start top form content div -->
    <div class="page-register layout-full page-dark">
        <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">
            <div class="page-content vertical-align-middle">
                <div class="brand">
                    <h2 class="brand-text"></h2>
                </div>
                <h3 class="text-white">How can we help you?</h3>
                <form method="post" role="form" autocomplete="off" class="home_serach_form" action="/search">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-group text-left form-material" data-plugin="formMaterial">
                        <label for="inputName"><h4 class="text-white">I'm looking for </h4></label>
                        <input type="text" class="form-control" id="inputName" name="find">
                    </div>
                    <div class="form-group text-left form-material" data-plugin="formMaterial">
                        <label for="inputName"><h4 class="text-white">Near an Address?</h4></label>
                        <div class="form-group">
                            <div class="input-group">
                                <a href="/services_near_me" class="input-search-btn" style="z-index: 100;"><img src="frontend/assets/examples/images/location.png"></a>
                                <input type="text" class="form-control pr-50" id="location1" name="search_address" >
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-lg">Search</button>
                </form>
            </div>
        </div>
        <!-- End Page -->
    </div>
    <!--end top form content div -->

    <!-- start browse_category div -->
    <div class="browse_category">
        <div class="page-content">
            <div class="py-15">
                <div class="text-center">
                    <h3>Browse by Category</h3>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2"></div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-12">
                        <div id="accordion">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-6 col-sm-12">
                                <!-- Example Collapsed -->
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="card-link" data-toggle="collapse" href="#collapseOne"></a>
                                            <a class="card-link" href="#">Assessment</a>
                                        </div>
                                        <div id="collapseOne" class="collapse show" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Psychiatric Assessment</a></a></li>
                                                    <li><a href="#">Behavioral Health Assessment</a></li>
                                                    <li><a href="#">Psycho-Sexual Evaluation</a></li>
                                                    <li><a href="#">Psychological Testin</a>g</li>
                                                    <li><a href="#">Substance Abuse Assessment</a></li>
                                                    <li><a href="#">Trauma Assessment</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo"></a>
                                            <a class="card-link" href="#">Autism Spectrum Disorder</a>
                                        </div>
                                        <div id="collapseTwo" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Applied Behavioral Analysis (ABA)</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree"></a>
                                            <a class="card-link" href="#">Case Management</a>
                                        </div>
                                        <div id="collapseThree" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Behavioral health/Mental health case management</a></li>
                                                    <li><a href="#">Case management; Nursing Services</a></li>
                                                    <li><a href="#">Psychiatric Medication Monitoring</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse5"></a>
                                            <a class="card-link" href="#">Certified Peer Support Specialist for Families</a>
                                        </div>
                                        <div id="collapse5" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Education Advocacy</a></li>
                                                    <li><a href="#">Faith based</a></li>
                                                    <li><a href="#">Family Based Service</a>s</li>
                                                    <li><a href="#">IC3 Services (Wrap-around services)</a></li>
                                                    <li><a href="#">Parent Counseling</a></li>
                                                    <li><a href="#">Parent Peer Support</a></li>
                                                    <li><a href="#">Parent Skills Classe</a>s</li>
                                                    <li><a href="#">Parent or Family Support Group</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse6"></a>
                                            <a class="card-link" href="#">Community Support</a>
                                        </div>
                                        <div id="collapse6" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Aftercare</a></li>
                                                    <li><a href="#">Anti-bullying Program</a>s</li>
                                                    <li><a href="#">Art Therapy</a></li>
                                                    <li><a href="#">Behavior Modificatio</a>n</li>
                                                    <li><a href="#">Developmental Disabilities</a></li>
                                                    <li><a href="#">Emerging Adult Transition Programs</a></li>
                                                    <li><a href="#">Gang Prevention</a></li>
                                                    <li><a href="#">Independent Living Skills</a></li>
                                                    <li><a href="#">Life skills</a></li>
                                                    <li><a href="#">Mentoring</a></li>
                                                    <li><a href="#">Pro-Social programs</a></li>
                                                    <li><a href="#">Recreational program</a>s</li>
                                                    <li><a href="#">School Behavior/IEP Management</a></li>
                                                    <li><a href="#">Self-esteem</a></li>
                                                    <li><a href="#">Supported Employment</a></li>
                                                    <li><a href="#">Youth Leadership/Youth Development</a></li>
                                                    <li><a href="#">Youth Violence Prevention</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse7"></a>
                                            <a class="card-link" href="#">Counseling Specialties</a>
                                        </div>
                                        <div id="collapse7" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Depression</a></li>
                                                    <li><a href="#">Family Violence</a></li>
                                                    <li><a href="#">Trauma</a></li>
                                                    <li><a href="#">Trauma EDMR</a></li>
                                                    <li><a href="#">ADHD</a></li>
                                                    <li><a href="#">ASD/IDD/Behavior Disorders</a></li>
                                                    <li><a href="#">Anger Management</a></li>
                                                    <li><a href="#">Child Sexual Abuse Counseling</a></li>
                                                    <li><a href="#">Child/Adolescent</a></li>
                                                    <li><a href="#">Children&#039;s Yoga</a></li>
                                                    <li><a href="#">Cognitive Behavioral Therapy</a></li>
                                                    <li><a href="#">Core Services</a></li>
                                                    <li><a href="#">DBT</a></li>
                                                    <li><a href="#">Executive Functionin</a>g</li>
                                                    <li><a href="#">Exposure and Response Prevention Therapy</a></li>
                                                    <li><a href="#">Gender Identity</a></li>
                                                    <li><a href="#">Grief</a></li>
                                                    <li><a href="#">Hoarding</a></li>
                                                    <li><a href="#">Holistic Approach</a></li>
                                                    <li><a href="#">Intensive family intervention</a></li>
                                                    <li><a href="#">LGBTQ</a></li>
                                                    <li><a href="#">Military</a></li>
                                                    <li><a href="#">Mood Disorder</a></li>
                                                    <li><a href="#">Motivational Interviewing; CORE services</a></li>
                                                    <li><a href="#">Obsessive Compulsive Disorder (OCD)</a></li>
                                                    <li><a href="#">Offender treatment</a></li>
                                                    <li><a href="#">PTSD</a></li>
                                                    <li><a href="#">Pet Assisted Therapy</a></li>
                                                    <li><a href="#">Phobias</a></li>
                                                    <li><a href="#">Play Therapy</a></li>
                                                    <li><a href="#">Rape/Sexual Assault</a></li>
                                                    <li><a href="#">Relationship Skills</a></li>
                                                    <li><a href="#">Relaxation</a></li>
                                                    <li><a href="#">Self-Awareness</a></li>
                                                    <li><a href="#">Obsessive Compulsive Disorder (OCD)</a></li>
                                                    <li><a href="#">Offender treatment</a></li>
                                                    <li><a href="#">PTSD</a></li>
                                                    <li><a href="#">Pet Assisted Therapy</a></li>
                                                    <li><a href="#">Phobias</a></li>
                                                    <li><a href="#">Play Therapy</a></li>
                                                    <li><a href="#">Rape/Sexual Assault</a></li>
                                                    <li><a href="#">Relationship Skills</a></li>
                                                    <li><a href="#">Relaxation</a></li>
                                                    <li><a href="#">Self-Awareness</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="col-12 col-md-6 col-lg-6 col-sm-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse8"></a>
                                            <a class="card-link" href="#">Counseling/Therapy</a>
                                        </div>
                                        <div id="collapse8" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Substance Abuse Group Counseling</a></li>
                                                    <li><a href="#">Clubhouse Model Substance Use Recovery</a></li>
                                                    <li><a href="#">Group Counseling</a></li>
                                                    <li><a href="#">Individual Counselin</a>g</li>
                                                    <li><a href="#">Life Skills Group Counseling</a></li>
                                                    <li><a href="#">Parent Education Grou</a>p</li>
                                                    <li><a href="#">Seven Challenges</a></li>
                                                    <li><a href="#">Substance Abuse Individual Counseling</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse9"></a>
                                            <a class="card-link" href="#">Developmental Disabilities</a>
                                        </div>
                                        <div id="collapse9" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Developmental Disorde</a>r</li>
                                                    <li><a href="#">Early Intervention</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse10"></a>
                                            <a class="card-link" href="#">Families</a>
                                        </div>
                                        <div id="collapse10" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">At-Risk Families</a></li>
                                                    <li><a href="#">Family Counseling</a></li>
                                                    <li><a href="#">Family Preservation</a></li>
                                                    <li><a href="#">Parenting Education; Psychological</a></li>
                                                    <li><a href="#">Parenting and Family Services</a></li>
                                                    <li><a href="#">Wrap-around</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse11"></a>
                                            <a class="card-link" href="#">Mental Health/Developmental</a>
                                        </div>
                                        <div id="collapse11" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Crisis Hotline</a></li>
                                                    <li><a href="#">Crisis Intervention Counseling</a></li>
                                                    <li><a href="#">Crisis Intervention Stabilization</a></li>
                                                    <li><a href="#">Emergency Behavioral Healthcare</a></li>
                                                    <li><a href="#">Mental Health Counseling</a></li>
                                                    <li><a href="#">Mobile Crisis Unit</a></li>
                                                    <li><a href="#">Outpatient Mental Health Counseling</a></li>
                                                    <li><a href="#">Psychiatric Evaluatio</a>n</li>
                                                    <li><a href="#">Psychological Evaluation</a></li>
                                                    <li><a href="#">Suicide Prevention</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse12"></a>
                                            <a class="card-link" href="#">Psychiatric Services</a>
                                        </div>
                                        <div id="collapse12" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Autism Spectrum Disorder</a></li>
                                                    <li><a href="#">Children&#039;s/Adolescent Psychiatric Hospitals</a></li>
                                                    <li><a href="#">Clubhouse Model Psychiatric Rehabilitation</a></li>
                                                    <li><a href="#">Medication Managemen</a>t</li>
                                                    <li><a href="#">Psychiatric Case Management</a></li>
                                                    <li><a href="#">Psychiatric Services</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <a class="collapsed card-link" data-toggle="collapse" href="#collapse13"></a>
                                            <a class="card-link" href="#">Support Services</a>
                                        </div>
                                        <div id="collapse13" class="collapse" data-parent="#accordion">
                                            <div class="card-body">
                                                <ul>
                                                    <li><a href="#">Family/Youth Support</a></li>
                                                    <li><a href="#">Parent or Family Support Services</a></li>
                                                    <li><a href="#">Youth Support Groups</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end browse_category div -->

    <!-- start below after serching div -->
    <div class="after_serach">
        <div class="container">
            <div class="row">
                <div class="col-lg-1 col-sm-12 col-md-1"></div>
                <div class="col-lg-10 col-sm-12 col-md-10">
                    <div class="inner_search">
                        {!! $home->sidebar_content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
		<!-- end below after serching div -->
    {{-- <div id="content" class="container m-0" style="width: 100%;">
        <div class=" pt-20 pl-15" style="margin-right: 0">
            <div class="col-xl-7 col-md-7">
            <div class="panel mb-10">
                <div class="panel-heading text-center">
                    <h1 class="panel-title" style="font-size: 25px;">I'm looking for ...</h1>
                </div>
                <div class="panel-body text-center">
                    <form action="/search" method="POST" class="hidden-sm hidden-xs col-md-6 col-md-offset-3" style="display: block !important; padding-bottom: 30px;padding: 5px; ">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="input-group pull-right text-white pr-25">

                            <input type="text" class="form-control" placeholder="Search here..." name="find"/ style="z-index: 0;">
                            <div class="input-group-btn pull-right ">
                                <button type="submit" class="btn btn-primary btn-search bg-primary-color"><i class="fa fa-search"></i></button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <div class="panel panel-bordered animation-scale-up">
                <div class="panel-heading text-center">
                    <h3 class="panel-title" style="font-size: 25px;">Browse by Category</h3>
                </div>
                <div class="panel-body">
                    
                </div>
                </div>
            </div>
            <div class="col-xl-5 col-md-5">
                <div class="panel">
                    <div class="panel-body bg-primary-color">
                        <div class="form-group">
                            <h4 class="text-white">Find Services Near an Address?</h4>
                            <form method="post" action="/search" id="search_location">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                
                                    <div class="input-search">
                                        <i class="input-search-icon md-pin" aria-hidden="true"></i>
                                        <input id="location1" type="text" class="form-control text-black" name="search_address" placeholder="Search Address" style="border-radius:0;">
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
        </div>
    </div> --}}


<script src="{{asset('js/treeview.js')}}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>

<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$(document).ready(function(){
    $('.home-category').on('click', function(e){
        var id = $(this).attr('at');
        console.log(id);
        $("#category_" +  id).prop( "checked", true );
        $("#filter").submit();
    });
});
</script>
@endsection