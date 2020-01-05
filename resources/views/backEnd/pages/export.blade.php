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
              </div>
            </div>

            <div class="form-horizontal form-label-left">
              <form class="edit-hsds-api-key" action="/update_hsds_api_key" method="POST">
                {{ csrf_field() }}
                <div class="item form-group">
                  <label for="import_hsds_api_key_input">Authorization Key</label>
                  <p for="import_hsds_api_key_example" style="font-style: italic; color: grey;">   Example: aksflak601KKKSS1050A0A</p>
                  <input class="form-control" type="text" name="import_hsds_api_key" id="import_csv_api_key" value="{{$hsds_api_key->hsds_api_key}}" />
                  <p id="validation-hsds-api-key" style="font-style: italic; color: red;">Authorization Key is required.</p>
                </div>
                <button type="submit" class="btn btn-danger save-api-key">Save</button>
                <!-- <button type="button" class="btn btn-primary refresh-export-page">Refresh</button> -->
              </form>
              <div class="item form-group">
                <h6>
                  The downloaded HSDS Zip file will be available to use as a import file for other project with API format like below:
                </h6>
                <p for="datapackages_url_example" style="font-style: italic; color: grey;"> 
                  <a href="{{$url_path}}" style="color: #027bff;"> {{$url_path}}</a>
                  </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_content">
            <div class="form-horizontal form-label-left">
              <div class="item form-group">
                <h6>
                  Request Method: GET
                </h6>
                <h6>
                  URL Path:  <a href="{{$url_path}}" style="color: #027bff;"> {{$url_path}}</a>
                </h6>
                <h6>
                  Username: NA - leave this field blank
                </h6>  
                <h6>
                  Key: aksflak601KKKSS1050A0A
                </h6>      
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

@endsection
@section('scripts')

<script type="text/javascript">
  $(document).ready(function() {
    $('#validation-hsds-api-key').hide();
    if ($('#import_csv_api_key').val() == '') {
      $('#validation-hsds-api-key').show();
    }
  });

  $('button.refresh-export-page').on('click', function(e) {
        e.preventDefault();
        window.location.reload(true);
    });

</script>

@endsection