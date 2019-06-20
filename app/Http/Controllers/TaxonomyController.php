<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Taxonomy;
use App\Servicetaxonomy;
use App\Airtables;
use App\CSV_Source;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;

class TaxonomyController extends Controller
{

    public function airtable()
    {

        Taxonomy::truncate();
        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'taxonomy' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {

                $taxonomy = new Taxonomy();
                $strtointclass = new Stringtoint();

                $taxonomy->taxonomy_recordid = $strtointclass->string_to_int($record[ 'id' ]);
                 // $taxonomy->taxonomy_recordid = $record[ 'id' ];
                $taxonomy->taxonomy_name = isset($record['fields']['name'])?$record['fields']['name']:null;
                $taxonomy->taxonomy_parent_name = isset($record['fields']['parent_name'])? implode(",", $record['fields']['parent_name']):null;
                if($taxonomy->taxonomy_parent_name!=null){
                    $taxonomy->taxonomy_parent_name = $strtointclass->string_to_int($taxonomy->taxonomy_parent_name);
                }
                $taxonomy->taxonomy_vocabulary = isset($record['fields']['vocabulary'])?$record['fields']['vocabulary']:null;
                $taxonomy->taxonomy_x_description = isset($record['fields']['x-description'])?$record['fields']['x-description']:null;
                $taxonomy->taxonomy_x_notes = isset($record['fields']['x-notes'])?$record['fields']['x-notes']:null;

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $taxonomyservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $taxonomy->taxonomy_services = $taxonomy->taxonomy_services. ','. $taxonomyservice;
                        else
                            $taxonomy->taxonomy_services = $taxonomyservice;
                        $i ++;
                    }
                } 

                $taxonomy ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Taxonomy')->first();
        $airtable->records = Taxonomy::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }

    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='taxonomy.csv') 
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

        if ($csv_header_fields[0]!='id' || $csv_header_fields[1]!='name' || $csv_header_fields[2]!='taxonomy_facet' || $csv_header_fields[3]!='parent_id' || $csv_header_fields[4]!='parent_name' || $csv_header_fields[5]!='vocabulary' ) 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV field is not matched.',
            );
            return $response;
        }

        Taxonomy::truncate();

        $size = '';
        foreach ($csv_data as $key => $row) {

            $taxonomy = new Taxonomy();

            $taxonomy->taxonomy_recordid = $key+1;

            $taxonomy->taxonomy_id =$row[$csv_header_fields[0]]!='NULL'?$row[$csv_header_fields[0]]:null;
            
            $taxonomy->taxonomy_name = $row[$csv_header_fields[1]]!='NULL'?$row[$csv_header_fields[1]]:null;
            $taxonomy->taxonomy_facet = $row[$csv_header_fields[2]]!='NULL'?$row[$csv_header_fields[2]]:null;
            $taxonomy->taxonomy_parent_recordid= $row[$csv_header_fields[3]]!='NULL'?$row[$csv_header_fields[3]]:null;
            $taxonomy->taxonomy_parent_name= $row[$csv_header_fields[4]]!='NULL'?$row[$csv_header_fields[4]]:null;
            $taxonomy->taxonomy_vocabulary= $row[$csv_header_fields[5]]!='NULL'?$row[$csv_header_fields[5]]:null;

            $taxonomy->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Taxonomy')->first();
        $csv_source->records = Taxonomy::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function csv_services_taxonomy(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='services_taxonomy.csv') 
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

        if ($csv_header_fields[0]!='id' || $csv_header_fields[1]!='service_id' || $csv_header_fields[2]!='taxonomy_id' || $csv_header_fields[3]!='taxonomy_detail') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV field is not matched.',
            );
            return $response;
        }

        Servicetaxonomy::truncate();

        $size = '';
        foreach ($csv_data as $key => $row) {

            $service_taxonomy = new Servicetaxonomy();

            $service_taxonomy->taxonomy_recordid = $key+1;
            $service_taxonomy->service_recordid = $row[$csv_header_fields[1]]!='NULL'?$row[$csv_header_fields[1]]:null;
            $service_taxonomy->taxonomy_id =$row[$csv_header_fields[2]]!='NULL'?$row[$csv_header_fields[2]]:null;
            
            $service_taxonomy->taxonomy_detail = $row[$csv_header_fields[3]]!='NULL'?$row[$csv_header_fields[3]]:null;
           
            $service_taxonomy->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Services_taxonomy')->first();
        $csv_source->records = Servicetaxonomy::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxonomies = Taxonomy::orderBy('taxonomy_name')->get();

        return view('backEnd.tables.tb_taxonomy', compact('taxonomies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $taxonomy= Taxonomy::find($id);
        return response()->json($taxonomy);
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
        $taxonomy = Taxonomy::find($id);
        $taxonomy->taxonomy_name = $request->taxonomy_name;
        $taxonomy->taxonomy_vocabulary = $request->taxonomy_vocabulary;
        $taxonomy->taxonomy_x_description = $request->taxonomy_x_description;
        $taxonomy->taxonomy_x_notes = $request->taxonomy_x_notes;
        $taxonomy->flag = 'modified';
        $taxonomy->save();

        return response()->json($taxonomy);
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
