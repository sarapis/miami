<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Area;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;

class AreaController extends Controller
{


    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='service_areas.csv') 
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

        Area::truncate();

        foreach ($csv_data as $key => $row) {

            $area = new Area();

            $area->area_recordid =$row['service_area']!='NULL'?$row['service_area']:null;
            $area->area_service = $row['service_id']!='NULL'?$row['service_id']:null;
            $area->area_description =$row['description']!='NULL'?$row['description']:null; 
            $area->area_date_added =$row['date_added']!='NULL'?$row['date_added']:null;
            $area->area_multiple_counties =$row['multiple_counties']!='NULL'?$row['multiple_counties']:null;
            $area->save();

           
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Service_areas')->first();
        $csv_source->records = Area::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index()
    {
        $accessibilities = Area::orderBy('accessibility_recordid')->paginate(20);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_accessibility', compact('accessibilities', 'source_data'));
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
        $process= Area::find($id);
        return response()->json($process);
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
        $location = Area::find($id);
        // $project = Project::where('id', '=', $id)->first();
        $location->location_name = $request->location_name;
        $location->location_alternate_name = $request->location_alternate_name;
        $location->location_transportation = $request->location_transportation;
        $location->location_latitude = $request->location_latitude;
        $location->location_longitude = $request->location_longitude;
        $location->location_description = $request->location_description;
        $location->flag = 'modified';
        $location->save();
        // var_dump($project);
        // exit();
        return response()->json($location);
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
