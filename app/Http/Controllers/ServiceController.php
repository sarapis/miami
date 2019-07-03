<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Service;
use App\Servicelocation;
use App\Servicephone;
use App\Servicedetail;
use App\Serviceaddress;
use App\Serviceorganization;
use App\Servicecontact;
use App\Servicetaxonomy;
use App\Serviceschedule;
use App\Location;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Taxonomy;
use App\Map;
use App\Layout;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    

    public function airtable()
    {

        Service::truncate();
        Servicelocation::truncate();
        Serviceaddress::truncate();
        Servicephone::truncate();
        Servicedetail::truncate();
        Serviceorganization::truncate();
        Servicecontact::truncate();
        Servicetaxonomy::truncate();
        Serviceschedule::truncate();

        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'services' );
        $size = '';
        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {


                $service = new Service();
                $strtointclass = new Stringtoint();
                $service->service_recordid= $strtointclass->string_to_int($record[ 'id' ]);
                $service->service_name = isset($record['fields']['Name'])?$record['fields']['Name']:null;

                if(isset($record['fields']['Organization'])){
                    $i = 0;
                    foreach ($record['fields']['Organization']  as  $value) {
                        $service_organization = new Serviceorganization();
                        $service_organization->service_recordid=$service->service_recordid;
                        $service_organization->organization_recordid=$strtointclass->string_to_int($value);
                        $service_organization->save();
                        $serviceorganization=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_organization = $service->service_organization. ','. $serviceorganization;
                        else
                            $service->service_organization = $serviceorganization;
                        $i ++;
                    }
                }

                $service->service_alternate_name = isset($record['fields']['Alternate Name'])?$record['fields']['Alternate Name']:null;
                $service->service_description = isset($record['fields']['Description'])?$record['fields']['Description']:null;

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {
                        $service_location = new Servicelocation();
                        $service_location->service_recordid=$service->service_recordid;
                        $service_location->location_recordid=$strtointclass->string_to_int($value);
                        $service_location->save();
                        $servicelocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_locations = $service->service_locations. ','. $servicelocation;
                        else
                            $service->service_locations = $servicelocation;
                        $i ++;
                    }
                }
                
                $service->service_url = isset($record['fields']['url'])?$record['fields']['url']:null;
                $service->service_email = isset($record['fields']['email'])?$record['fields']['email']:null;
                $service->service_status = isset($record['fields']['status'])?$record['fields']['status']:null;

                if(isset($record['fields']['taxonomy'])){
                    $i = 0;
                    foreach ($record['fields']['taxonomy']  as  $value) {
                        $service_taxonomy = new Servicetaxonomy();
                        $service_taxonomy->service_recordid=$service->service_recordid;
                        $service_taxonomy->taxonomy_recordid=$strtointclass->string_to_int($value);
                        $service_taxonomy->save();
                        $servicetaxonomy=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_taxonomy = $service->service_taxonomy. ','. $servicetaxonomy;
                        else
                            $service->service_taxonomy = $servicetaxonomy;
                        $i ++;
                    }
                }

                $service->service_application_process = isset($record['fields']['application_process'])?$record['fields']['application_process']:null;
                $service->service_wait_time = isset($record['fields']['wait_time'])?$record['fields']['wait_time']:null;
                $service->service_fees = isset($record['fields']['fees'])?$record['fields']['fees']:null;
                $service->service_accreditations = isset($record['fields']['accreditations'])?$record['fields']['accreditations']:null;
                $service->service_licenses = isset($record['fields']['licenses'])?$record['fields']['licenses']:null;


                if(isset($record['fields']['phones'])){
                    $i = 0;
                    foreach ($record['fields']['phones']  as  $value) {
                        $service_phone = new Servicephone();
                        $service_phone->service_recordid=$service->service_recordid;
                        $service_phone->phone_recordid=$strtointclass->string_to_int($value);
                        $service_phone->save();
                        $servicephone=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_phones = $service->service_phones. ','. $servicephone;
                        else
                            $service->service_phones = $servicephone;
                        $i ++;
                    }
                }


                if(isset($record['fields']['schedule'])){
                    $i = 0;
                    foreach ($record['fields']['schedule']  as  $value) {
                        $service_schedule = new Serviceschedule();
                        $service_schedule->service_recordid=$service->service_recordid;
                        $service_schedule->schedule_recordid=$strtointclass->string_to_int($value);
                        $service_schedule->save();
                        $serviceschedule=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_schedule = $service->service_schedule. ','. $serviceschedule;
                        else
                            $service->service_schedule = $serviceschedule;
                        $i ++;
                    }
                }

                if(isset($record['fields']['contacts'])){
                    $i = 0;
                    foreach ($record['fields']['contacts']  as  $value) {
                        $service_contact = new Servicecontact();
                        $service_contact->service_recordid=$service->service_recordid;
                        $service_contact->contact_recordid=$strtointclass->string_to_int($value);
                        $service_contact->save();
                        $servicecontact=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_contacts = $service->service_contacts. ','. $servicecontact;
                        else
                            $service->service_contacts = $servicecontact;
                        $i ++;
                    }
                }

                if(isset($record['fields']['details'])){
                    $i = 0;
                    foreach ($record['fields']['details']  as  $value) {
                        $service_detail = new Servicedetail();
                        $service_detail->service_recordid=$service->service_recordid;
                        $service_detail->detail_recordid=$strtointclass->string_to_int($value);
                        $service_detail->save();
                        $servicedetail=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_details = $service->service_details. ','. $servicedetail;
                        else
                            $service->service_details = $servicedetail;
                        $i ++;
                    }
                }

                if(isset($record['fields']['address'])){
                    $i = 0;
                    foreach ($record['fields']['address']  as  $value) {
                        $service_addresses = new Serviceaddress();
                        $service_addresses->service_recordid=$service->service_recordid;
                        $service_addresses->address_recordid=$strtointclass->string_to_int($value);
                        $service_addresses->save();
                        $serviceaddress=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $service->service_address = $service->service_address. ','. $serviceaddress;
                        else
                            $service->service_address = $serviceaddress;
                        $i ++;
                    }
                }

                $service->service_metadata = isset($record['fields']['metadata'])? $record['fields']['metadata']:null;
       

                $service->service_airs_taxonomy_x = isset($record['fields']['AIRS Taxonomy-x'])? implode(",", $record['fields']['AIRS Taxonomy-x']):null;          
                
                $service ->save();

            }
           
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Services')->first();
        $airtable->records = Service::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }

    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='services.csv') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV is not correct.',
            );
            return $response;
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }


        Service::truncate();
        Serviceorganization::truncate();

        $size = '';
        foreach ($csv_data as $row) {
            
       
            $service = new Service();

            $service->service_recordid= $row['id'];
            $service->service_name = $row['name']!='NULL'?$row['name']:null;

            if($row['organization_id']){

                    $service_organization = new Serviceorganization();
                    $service_organization->service_recordid=$service->service_recordid;
                    $service_organization->organization_recordid=$row['organization_id'];
                    $service_organization->save();

                    $service->service_organization = $row['organization_id'];

            }

            $service->service_alternate_name = $row['alternate_name']!='NULL'?$row['alternate_name']:null;
            $service->service_description = $row['description']!='NULL'?$row['description']:null;
            $service->service_application_process = $row['application_process']!='NULL'?$row['application_process']:null;
            $service->service_url = $row['url']!='NULL'?$row['url']:null;
            $service->service_program = $row['program_id']!='NULL'?$row['program_id']:null;

            $service->service_email = $row['email']!='NULL'?$row['email']:null;
            $service->service_status = $row['status']!='NULL'?$row['status']:null;

            $service->service_wait_time = $row['wait_time']!='NULL'?$row['wait_time']:null;
            $service->service_fees = $row['fees']!='NULL'?$row['fees']:null;
            $service->service_accreditations = $row['accreditations']!='NULL'?$row['accreditations']:null;
            $service->service_licenses = $row['licenses']!='NULL'?$row['licenses']:null;
        
            
            $service ->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services')->first();
        $csv_source->records = Service::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function csv_services_location(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='services_at_location.csv') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV is not correct.',
            );
            return $response;
        }

        if (count($data) > 0) {
            $csv_header_fields = [];
            foreach ($data[0] as $key => $value) {
                $csv_header_fields[] = $key;
            }
            $csv_data = $data;
        }


        Servicelocation::truncate();

        foreach ($csv_data as $key => $row) {

            $service_location = new Servicelocation();

            $service_location->location_recordid = $row['location_id']!='NULL'?$row['location_id']:null;
            $service_location->service_recordid =$row['service_id']!='NULL'?$row['service_id']:null;
                      
            $service_location->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services_location')->first();
        $csv_source->records = Servicelocation::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index()
    {
        $services = Service::with('locations', 'organizations', 'locations', 'taxonomy', 'phone', 'schedules', 'contact', 'details', 'address')->orderBy('service_recordid', 'asc')->paginate(20);
        $source_data = Source_data::find(1);      

        return view('backEnd.tables.tb_services', compact('services', 'source_data'));
    }


    public function services()
    {
        $services = Service::with('locations')->orderBy('service_name')->paginate(10);
        $locations = Location::with('services','organization')->get();
        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        return view('frontEnd.services', compact('services', 'locations', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours'));
    }

    public function service($id)
    {
        $service = Service::where('service_recordid', '=', $id)->first();
        $location = Location::with('organization', 'address')->where('location_services', 'like', '%'.$id.'%')->get();

        $map = Map::find(1);
        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        return view('frontEnd.service', compact('service', 'location', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours'));
    }

    public function taxonomy($id)
    {
        $chip_name = Taxonomy::where('taxonomy_recordid', '=', $id)->first()->taxonomy_name;
        $chip_title = 'Category:';

        $serviceids = Servicetaxonomy::where('taxonomy_recordid', '=', $id)->pluck('service_recordid')->toArray();
        $services = Service::whereIn('service_recordid', $serviceids)->orderBy('service_name')->paginate(10);

        $locationids = Servicelocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid')->toArray();

        $locations = Location::whereIn('location_recordid', $locationids)->with('services','organization')->get();

        $map = Map::find(1);

        $parent_taxonomy = [];
        $child_taxonomy = [];
        $checked_organizations = [];
        $checked_insurances = [];
        $checked_ages = [];
        $checked_languages = [];
        $checked_settings = [];
        $checked_culturals = [];
        $checked_transportations = [];
        $checked_hours= [];

        return view('frontEnd.services', compact('services', 'locations', 'chip_title', 'chip_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        $service = Service::where('service_recordid', '=', $id)->first();
        $service_name= $service->service_name;

        $layout = Layout::find(1);

        $pdf = PDF::loadView('frontEnd.service_download', compact('service', 'layout'));

        return $pdf->download($service_name.'.pdf');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::find($id);
        return response()->json($service);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $service = Service::find($id);
        // $project = Project::where('id', '=', $id)->first();
        $service->service_name = $request->service_name;
        $service->service_alternate_name = $request->service_alternate_name;
        $service->service_description = $request->service_description;
        $service->service_url = $request->service_url;
        $service->service_email = $request->service_email;
        $service->service_status = $request->service_status;
        $service->service_application_process = $request->service_application_process;
        $service->service_wait_time = $request->service_wait_time;
        $service->service_fees = $request->service_fees;
        $service->service_accreditations = $request->service_accreditations;
        $service->service_metadata = $request->service_metadata;

        $service->flag = 'modified';
        $service->save();
        // var_dump($project);
        // exit();
        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
