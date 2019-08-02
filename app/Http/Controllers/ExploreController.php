<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service;
use App\Organization;
use App\Taxonomy;
use App\Detail;
use App\Servicetaxonomy;
use App\Serviceorganization;
use App\Servicelocation;
use App\Servicedetail;
use App\Metafilter;
use App\Serviceaddress;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Geolocation;
use Geocode;
use App\Location;
use App\Map;
use PDF;
use App\Layout;
use App\CSV;
use App\Analytic;
use App\Source_data;

class ExploreController extends Controller
{

    public function geolocation(Request $request)
    {
        $ip= \Request::ip();
        // echo $ip;
        $data = \Geolocation::get($ip);

        $chip_title = "";
        $chip_name = "Search Near Me";
        // $auth = new Location();
        // $locations = $auth->geolocation(40.573414, -73.987818);
        // var_dump($locations);


        $lat =floatval($data->latitude);
        $lng =floatval($data->longitude);

        // $lat =37.3422;
        // $lng = -121.905;

        $locations = Location::with('services', 'organization')->select(DB::raw('*, ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
        ->having('distance', '<', 2)
        ->orderBy('distance');

        $locationids = $locations->pluck('location_recordid')->toArray();

        $location_serviceids = Servicelocation::whereIn('location_recordid', $locationids)->pluck('service_recordid')->toArray();

        $services = Service::whereIn('service_recordid', $location_serviceids)->orderBy('service_name');

        $search_results = $services->count();

        $services = $services->paginate(10);

        $locations = $locations->get();

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
        
        return view('frontEnd.services', compact('services','locations', 'chip_title', 'chip_name', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'search_results'));
    }

    public function geocode(Request $request)
    {
        $chip_service = $request->input('find');
        $chip_address = $request->input('search_address');

        $source_data = Source_data::find(1);

        $location_serviceids = Service::pluck('service_recordid')->toArray();
        $location_locationids = Location::with('services','organization')->pluck('location_recordid')->toArray();

        if($source_data->active == 1)

            $services= Service::with(['organizations', 'taxonomy', 'details'])->where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->orwhereHas('organizations', function ($q)  use($chip_service){
                    $q->where('organization_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('taxonomy', function ($q)  use($chip_service){
                    $q->where('taxonomy_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('details', function ($q)  use($chip_service){
                    $q->where('detail_value', 'like', '%'.$chip_service.'%');
                })->select('services.*');
        else
            $serviceids= Service::where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->pluck('service_recordid')->toArray();

            $organization_recordids = Organization::where('organization_name', 'like', '%'.$chip_service.'%')->pluck('organization_recordid')->toArray();
            $organization_serviceids = Serviceorganization::whereIn('organization_recordid', $organization_recordids)->pluck('service_recordid')->toArray();
            $taxonomy_recordids = Taxonomy::where('taxonomy_name', 'like', '%'.$chip_service.'%')->pluck('taxonomy_recordid')->toArray();
            $taxonomy_serviceids = Servicetaxonomy::whereIn('taxonomy_recordid', $taxonomy_recordids)->pluck('service_recordid')->toArray();


            $service_locationids = Servicelocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid')->toArray();


        if($chip_address != null){
            
            $response = Geocode::make()->address($chip_address);


            $lat =$response->latitude();
            $lng =$response->longitude();

            $locations = Location::with('services','organization')->select(DB::raw('*, ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 2)
            ->orderBy('distance');

            $location_locationids = $locations->pluck('location_recordid')->toArray();

            $location_serviceids = Servicelocation::whereIn('location_recordid', $location_locationids)->pluck('service_recordid')->toArray();
        }   

        if($chip_service != null)
        {

            $service_ids = Service::whereIn('service_recordid', $serviceids)->orWhereIn('service_recordid', $organization_serviceids)->orWhereIn('service_recordid', $taxonomy_serviceids)->pluck('service_recordid')->toArray();

            $services = Service::whereIn('service_recordid', $serviceids)->whereIn('service_recordid', $location_serviceids)->orderBy('service_name');

            $locations = Location::with('services','organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        }
        else{
            $services = Service::WhereIn('service_recordid', $location_serviceids)->orderBy('service_name');
            $locations = Location::with('services','organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        }
        if($chip_service == null && $chip_address == null){
            $services = Service::orderBy('service_name');
            $locations = Location::with('services','organization');
        }

        $search_results = $services->count();

        $services = $services->paginate(10);

        $locations = $locations->get();

        $analytic = Analytic::where('search_term', '=', $chip_service)->orWhere('search_term', '=', $chip_address)->first();
        if(isset($analytic)){
            $analytic->search_term = $chip_service;
            $analytic->search_results = $search_results;
            $analytic->times_searched = $analytic->times_searched + 1;
            $analytic->save();
        }
        else{
            $new_analytic = new Analytic();
            $new_analytic->search_term = $chip_service;
            $new_analytic->search_results = $search_results;
            $new_analytic->times_searched = 1;
            $new_analytic->save();
        }

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
        $checked_transportations = [];
        $checked_hours= [];

        return view('frontEnd.services', compact('services','locations', 'chip_service', 'chip_address', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'search_results'));

    }

    public function filter(Request $request)
    {   
        $parents = $request->input('parents');
        $childs = $request->input('childs');
        $parent_taxonomy = [];
        $child_taxonomy = [];

        $chip_service = $request->input('find');
        $chip_address = $request->input('search_address');

        $source_data = Source_data::find(1);

        $location_serviceids = Service::pluck('service_recordid');
        $location_locationids = Location::with('services','organization')->pluck('location_recordid');

        if($source_data->active == 1)

            $services= Service::with(['organizations', 'taxonomy', 'details'])->where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->orwhereHas('organizations', function ($q)  use($chip_service){
                    $q->where('organization_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('taxonomy', function ($q)  use($chip_service){
                    $q->where('taxonomy_name', 'like', '%'.$chip_service.'%');
                })->orwhereHas('details', function ($q)  use($chip_service){
                    $q->where('detail_value', 'like', '%'.$chip_service.'%');
                })->select('services.*');
        else{
            $serviceids= Service::where('service_name', 'like', '%'.$chip_service.'%')->orwhere('service_description', 'like', '%'.$chip_service.'%')->orwhere('service_airs_taxonomy_x', 'like', '%'.$chip_service.'%')->pluck('service_recordid');

            $organization_recordids = Organization::where('organization_name', 'like', '%'.$chip_service.'%')->pluck('organization_recordid');
            $organization_serviceids = Serviceorganization::whereIn('organization_recordid', $organization_recordids)->pluck('service_recordid');
            $taxonomy_recordids = Taxonomy::where('taxonomy_name', 'like', '%'.$chip_service.'%')->pluck('taxonomy_recordid');
            $taxonomy_serviceids = Servicetaxonomy::whereIn('taxonomy_recordid', $taxonomy_recordids)->pluck('service_recordid');


            $service_locationids = Servicelocation::whereIn('service_recordid', $serviceids)->pluck('location_recordid');
        }

        if($chip_address != null){
            
            $response = Geocode::make()->address($chip_address);


            $lat =$response->latitude();
            $lng =$response->longitude();

            $locations = Location::with('services','organization')->select(DB::raw('*, ( 3959 * acos( cos( radians('.$lat.') ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians('.$lng.') ) + sin( radians('.$lat.') ) * sin( radians( location_latitude ) ) ) ) AS distance'))
            ->having('distance', '<', 2)
            ->orderBy('distance');

            $location_locationids = $locations->pluck('location_recordid');

            $location_serviceids = Servicelocation::whereIn('location_recordid', $location_locationids)->pluck('service_recordid');
        }   

        if($chip_service != null)
        {

            $service_ids = Service::whereIn('service_recordid', $serviceids)->orWhereIn('service_recordid', $organization_serviceids)->orWhereIn('service_recordid', $taxonomy_serviceids)->pluck('service_recordid');

            $services = Service::whereIn('service_recordid', $serviceids)->whereIn('service_recordid', $location_serviceids)->orderBy('service_name');

            $locations = Location::with('services','organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        }
        else{
            $services = Service::WhereIn('service_recordid', $location_serviceids)->orderBy('service_name');
            $locations = Location::with('services','organization')->whereIn('location_recordid', $service_locationids)->whereIn('location_recordid', $location_locationids);
        }
        if($chip_service == null && $chip_address == null){
            $services = Service::orderBy('service_name');
            $locations = Location::with('services','organization');
        }



        if($parents!=null){
            $parent_taxonomy = Taxonomy::whereIn('taxonomy_recordid', $parents)->pluck('taxonomy_recordid');
            $parent_taxonomy = json_decode(json_encode($parent_taxonomy));

            $parent_taxonomy_names = Taxonomy::whereIn('taxonomy_recordid', $parents)->pluck('taxonomy_name')->toArray();

            $taxonomy = Taxonomy::whereIn('taxonomy_parent_name', $parent_taxonomy_names)->pluck('taxonomy_id');

            $service_ids = Servicetaxonomy::whereIn('taxonomy_id', $taxonomy)->groupBy('service_recordid')->pluck('service_recordid');
 
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');

        }
        if($childs!=null){
            $child_taxonomy = Taxonomy::whereIn('taxonomy_recordid', $childs)->pluck('taxonomy_recordid');
            $child_taxonomy = json_decode(json_encode($child_taxonomy));

            $child_taxonomy_names = Taxonomy::whereIn('taxonomy_recordid', $childs)->pluck('taxonomy_name');
            $child_taxonomy_ids = Taxonomy::whereIn('taxonomy_recordid', $childs)->pluck('taxonomy_id');
            
            $service_ids = Servicetaxonomy::whereIn('taxonomy_id', $child_taxonomy_ids)->groupBy('service_recordid')->pluck('service_recordid');
            $location_ids = Servicelocation::whereIn('service_recordid', $service_ids)->groupBy('location_recordid')->pluck('location_recordid');
            $services = $services->whereIn('service_recordid', $service_ids);
            $locations = $locations->whereIn('location_recordid', $location_ids)->with('services','organization');
        }


        // $services = $services->paginate(10);

        // $locations = $locations->get();

        $map = Map::find(1);

        $pdf = $request->input('pdf');
        $csv = $request->input('csv');

        $pagination = strval($request->input('paginate'));

        $sort = $request->input('sort');
        $meta_status = $request->input('meta_status');
        // var_dump($sort);
        // exit();

        $metas = Metafilter::all();
        $count_metas = Metafilter::count();


        if($meta_status == 'On' && $count_metas > 0){
            $address_serviceids = Service::pluck('service_recordid')->toArray();
            $taxonomy_serviceids = Service::pluck('service_recordid')->toArray();

            foreach ($metas as $key => $meta) {
                $values = explode(",", $meta->values);
                if($meta->facet == 'Postal_code'){
                    $address_serviceids = [];
                    if($meta->operations == 'Include')
                        $serviceids = Serviceaddress::whereIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    if($meta->operations == 'Exclude')
                        $serviceids = Serviceaddress::whereNotIn('address_recordid', $values)->pluck('service_recordid')->toArray();
                    $address_serviceids = array_merge($serviceids, $address_serviceids);
                    // var_dump($address_serviceids);
                    // exit();
                }
                if($meta->facet == 'Taxonomy'){

                    if($meta->operations == 'Include')
                        $serviceids = Servicetaxonomy::whereIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                    if($meta->operations == 'Exclude')
                        $serviceids = Servicetaxonomy::whereNotIn('taxonomy_recordid', $values)->pluck('service_recordid')->toArray();
                    $taxonomy_serviceids = array_merge($serviceids, $axonomy_serviceids);
                    var_dump($taxonomy_serviceids);
                    exit();
                }
            }
            
            $services = $services->whereIn('service_recordid', $address_serviceids)->whereIn('service_recordid', $taxonomy_serviceids);
          
            $services_ids = $services->pluck('service_recordid')->toArray();
            $locations_ids = Servicelocation::whereIn('service_recordid', $services_ids)->pluck('location_recordid')->toArray();
            $locations = $locations->whereIn('location_recordid', $locations_ids);

        }

        if($pdf == 'pdf'){

            $layout = Layout::find(1);

            $services = $services->get();

            $pdf = PDF::loadView('frontEnd.services_download', compact('services', 'layout'));

            return $pdf->download('services.pdf');
        }

        if($csv == 'csv'){
            $csvExporter = new \Laracsv\Export();

            $layout = Layout::find(1);

            $services = $services->whereNotNull('service_name')->get();

            foreach ($services as $service) {
                $taxonomies = '';
                $organizations = '';
                $phones = '';
                $address1 ='';
                $contacts ='';
                $details = '';

                foreach($service->taxonomy as $key => $taxonomy){
                    $taxonomies = $taxonomies.$taxonomy->taxonomy_name.',';
                }
                $service['taxonomies'] = $taxonomies;

                foreach ($service->organizations as $organization) {
                    $organizations = $organizations.$organization->organization_name;
                }    
                $service['organizations'] = $organizations;

                foreach($service->phone as $phone1){
                    $phones = $phones.$phone1->phone_number;
                }
                $service['phones'] = $phones;

                foreach($service->address as $address){
                    $address1 = $address1.$address->address_1.' '.$address->address_city.' '.$address->address_state_province.' '.$address->address_postal_code;
                }
                $service['address1'] = $address1;

                foreach($service->contact as $contact){
                    $contacts = $contacts.$contact->contact_name;
                }
                $service['contacts'] = $contacts;



                $show_details = [];
                           
                foreach($service->details as $detail)
                {
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
                                                               
                }  
                foreach($show_details as $detail){
                    $details = $details.$detail['detail_type'].':'.$detail['detail_value'].'; ';
                }
                $service['details'] = $details;           
             } 


            $csv = CSV::find(1);

            $source = $layout->footer_csv;
            $csv->description = $source;
            $csv->save();

            $csv = CSV::find(2);
            $description = '';
            if($child_taxonomy_names != ""){
                $filter_category ='';
                foreach($child_taxonomy_names as $child_taxonomy_name){
                    $filter_category = $filter_category.$child_taxonomy_name.',';
                }

                $description = $description."Category: ".$filter_category;
            }
            if($checked_organization_names != ""){
                $filter_organization ='';
                foreach($checked_organization_names as $checked_organization_name){
                    $filter_organization = $filter_organization.$checked_organization_name.',';
                }

                $description = $description."Organization: ".$filter_organization;
            }
            if($checked_insurance_names != ""){
                $filter_insurance ='';
                foreach($checked_insurance_names as $checked_insurance_name){
                    $filter_insurance = $filter_insurance.$checked_insurance_name.',';
                }

                $description = $description."Insurance: ".$filter_insurance;
            }
            if($checked_age_names != ""){
                $filter_age ='';
                foreach($checked_age_names as $checked_age_name){
                    $filter_age = $filter_age.$checked_age_name.',';
                }

                $description = $description."Age: ".$filter_age;
            }
            if($checked_language_names != ""){
                $filter_language ='';
                foreach($checked_language_names as $checked_language_name){
                    $filter_language = $filter_language.$checked_language_name.',';
                }

                $description = $description."Language: ".$filter_language;
            }
            if($checked_setting_names != ""){
                $filter_setting ='';
                foreach($checked_setting_names as $checked_setting_name){
                    $filter_setting = $filter_setting.$checked_setting_name.',';
                }

                $description = $description."Setting: ".$filter_setting;
            }
            if($checked_cultural_names != ""){
                $filter_cultural ='';
                foreach($checked_cultural_names as $checked_cultural_name){
                    $filter_cultural = $filter_cultural.$checked_cultural_name.',';
                }

                $description = $description."Cultural: ".$filter_cultural;
            }
            if($checked_transportation_names != ""){
                $filter_transportation ='';
                foreach($checked_transportation_names as $checked_transportation_name){
                    $filter_transportation = $filter_cultural.$checked_transportation_name.',';
                }

                $description = $description."Transportation: ".$filter_transportation;
            }
            if($checked_hour_names != ""){
                $filter_hour ='';
                foreach($checked_hour_names as $checked_hour_name){
                    $filter_hour = $filter_hour.$checked_hour_name.',';
                }

                $description = $description."Additional Hour: ".$filter_hour;
            }

            $csv->description = $description;
            $csv->save();

            $csv = CSV::find(3);
            $csv->description = date('m/d/Y H:i:s');
            $csv->save();
            // var_dump($projects);
            // var_dump($collection);
            // exit();
            $csv = CSV::all();


            return $csvExporter->build($services, ['service_name'=>'Service Name', 'service_alternate_name'=>'Service Alternate Name', 'taxonomies'=>'Category', 'organizations'=>'Organization', 'phones'=>'Phone', 'address1'=>'Address', 'contacts'=>'Contact', 'service_description'=>'Service Description', 'service_url'=>'URL','service_application_process'=>'Application Process', 'service_wait_time'=>'Wait Time', 'service_fees'=>'Fees', 'service_accreditations'=>'Accreditations', 'service_licenses'=>'Licenses', 'details'=>'Details'])->build($csv, ['name'=>'', 'description'=>''])->download();
        }

        $search_results = $services->count();

        if($sort == 'Service Name'){
            $services = $services->orderBy('service_name');
        }

        // if($sort == 'Organization Name'){
        //     $services = $services->with(['organizations' => function($q) {
        //         $q->orderBy('organization_name', 'asc');
        //     }])->paginate($pagination);
        // }

        // if($sort == 'Organization Name'){
        //     $services = Service::leftjoin('service_organization', 'service_organization.service_recordid', '=', 'services.service_recordid')->leftjoin('organizations', 'organizations.organization_recordid', 'service_organization.organization_recordid');
        //     $services = $services->whereIn('services.service_recordid', $services_ids)->orderBy('organization_name')->paginate($pagination);
        // }

        if($sort == 'Organization Name'){
            $services = Service::whereIn('services.service_recordid', $services_ids);
            $services = $services->leftjoin('service_organization', 'service_organization.service_recordid', '=', 'services.service_recordid')->leftjoin('organizations', 'organizations.organization_recordid', 'service_organization.organization_recordid')->orderBy('organization_name');
        }

        $services = $services->paginate($pagination);

        $locations = $locations->get();

        $analytic = Analytic::where('search_term', '=', $chip_service)->orWhere('search_term', '=', $chip_address)->first();
        if(isset($analytic)){
            $analytic->search_term = $chip_service;
            $analytic->search_results = $search_results;
            $analytic->times_searched = $analytic->times_searched + 1;
            $analytic->save();
        }
        else{
            $new_analytic = new Analytic();
            $new_analytic->search_term = $chip_service;
            $new_analytic->search_results = $search_results;
            $new_analytic->times_searched = 1;
            $new_analytic->save();
        }  
       
        $map = Map::find(1);

        return view('frontEnd.services', compact('services','locations', 'chip_service', 'chip_address', 'map', 'parent_taxonomy', 'child_taxonomy', 'checked_organizations', 'checked_insurances', 'checked_ages', 'checked_languages', 'checked_settings', 'checked_culturals', 'checked_transportations', 'checked_hours', 'search_results', 'pagination', 'sort', 'meta_status', 'parent_taxonomy_names'));

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
