@extends('backLayout.app')
@section('title')
Export
@stop
<style>
  .color-pick{
    padding: 0 !important;
  }
</style>
@section('content')

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2>Export</h2>
            <ul class="nav navbar-right panel_toolbox">
              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
              </li>
              <li><a class="close-link"><i class="fa fa-close"></i></a>
              </li>
            </ul>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <div class="form-horizontal form-label-left">
              <div class="item form-group">
                <a class="btn btn-primary" href="/export_hsds_zip_file">Download HSDS Zip File</a>
           <!--      <button class="btn btn-primary"><a href="/zip_export/datapackage.zip" style="color: white;" download>Download HSDS Zip File</a></button> -->          
              </div>
            </div>

            <div class="form-horizontal form-label-left">
              <div class="item form-group">
                <h5>
                  The downloaded HSDS Zip file will be available to use as a import file for other project with API format like below:
                </h5>
                <p for="datapackages_url_example" style="font-style: italic; color: grey;"> 
                  <a href="http://23.96.85.224/datapackages?auth_key=aksflak601KKKSS1050A0A" style="color: #027bff;"> http://23.96.85.224/datapackages?auth_key=aksflak601KKKSS1050A0A</a>
                  </p>
                <h5>
                  The request method is "GET".
                </h5>        
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection
@section('scripts')

@endsection