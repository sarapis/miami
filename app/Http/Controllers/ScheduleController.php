<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Schedule;
use App\Serviceschedule;
use App\Locationschedule;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;

class ScheduleController extends Controller
{

    public function airtable()
    {

        Schedule::truncate();
        $airtable = new Airtable(array(
            'api_key'   => env('AIRTABLE_API_KEY'),
            'base'      => env('AIRTABLE_BASE_URL'),
        ));

        $request = $airtable->getContent( 'schedule' );

        do {


            $response = $request->getResponse();

            $airtable_response = json_decode( $response, TRUE );

            foreach ( $airtable_response['records'] as $record ) {



                $schedule = new Schedule();
                $strtointclass = new Stringtoint();

                $schedule->schedule_recordid = $strtointclass->string_to_int($record[ 'id' ]);

                $schedule->schedule_id = isset($record['fields']['id'])?$record['fields']['id']:null;

                $schedule->schedule_services = isset($record['fields']['services'])? implode(",", $record['fields']['services']):null;

                if(isset($record['fields']['services'])){
                    $i = 0;
                    foreach ($record['fields']['services']  as  $value) {

                        $scheduleservice=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $schedule->schedule_services = $schedule->schedule_services. ','. $scheduleservice;
                        else
                            $schedule->schedule_services = $scheduleservice;
                        $i ++;
                    }
                }

                if(isset($record['fields']['locations'])){
                    $i = 0;
                    foreach ($record['fields']['locations']  as  $value) {

                        $schedulelocation=$strtointclass->string_to_int($value);

                        if($i != 0)
                            $schedule->schedule_locations = $schedule->schedule_locations. ','. $schedulelocation;
                        else
                            $schedule->schedule_locations = $schedulelocation;
                        $i ++;
                    }
                }

                $schedule->schedule_description = isset($record['fields']['description-x'])?$record['fields']['description-x']:null;
                $schedule->schedule_x_phones = isset($record['fields']['phones-x'])? implode(",", $record['fields']['phones-x']):null;
                $schedule->schedule_days_of_week = isset($record['fields']['days_of_week'])?$record['fields']['days_of_week']:null;
                $schedule->schedule_opens_at = isset($record['fields']['opens_at'])?$record['fields']['opens_at']:null;
                $schedule->schedule_closes_at = isset($record['fields']['closes_at'])?$record['fields']['closes_at']:null;
                $schedule->schedule_holiday = isset($record['fields']['holiday'])?$record['fields']['holiday']:null;
                $schedule->schedule_start_date = isset($record['fields']['start_date'])?$record['fields']['start_date']:null;
                $schedule->schedule_end_date = isset($record['fields']['end_date'])?$record['fields']['end_date']:null;
                $schedule->schedule_closed = isset($record['fields']['closed'])?$record['fields']['closed']:null;
                $schedule ->save();

            }
            
        }
        while( $request = $response->next() );

        $date = date("Y/m/d H:i:s");
        $airtable = Airtables::where('name', '=', 'Schedule')->first();
        $airtable->records = Schedule::count();
        $airtable->syncdate = $date;
        $airtable->save();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function csv(Request $request)
    {


        $path = $request->file('csv_file')->getRealPath();

        $data = Excel::load($path)->get();

        $filename =  $request->file('csv_file')->getClientOriginalName();
        $request->file('csv_file')->move(public_path('/csv/'), $filename);

        if ($filename!='regular_schedules.csv') 
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

        if ($csv_header_fields[0]!='id' || $csv_header_fields[1]!='service_id' || $csv_header_fields[2]!='weekday' || $csv_header_fields[3]!='opens_at' || $csv_header_fields[4]!='closes_at' || $csv_header_fields[5]!='original_text' || $csv_header_fields[6]!='location_id' || $csv_header_fields[7]!='service_at_location_id') 
        {
            $response = array(
                'status' => 'error',
                'result' => 'This CSV field is not matched.',
            );
            return $response;
        }

        Schedule::truncate();
        Serviceschedule::truncate();
        Locationschedule::truncate();

        $size = '';
        foreach ($csv_data as $key => $row) {

            $schedule = new Schedule();

            $schedule->schedule_recordid= $key+1;
            $schedule->schedule_services = $row[$csv_header_fields[1]]!='NULL'?$row[$csv_header_fields[1]]:null;

            if($row[$csv_header_fields[1]]){
                $service_schedule = new Serviceschedule();
                $service_schedule->service_recordid = $row[$csv_header_fields[1]]!='NULL'?$row[$csv_header_fields[1]]:null;
                $service_schedule->schedule_recordid = $schedule->schedule_recordid;
                $service_schedule->save();

            }

            $schedule->schedule_days_of_week = $row[$csv_header_fields[2]]!='NULL'?$row[$csv_header_fields[2]]:null;
            $schedule->schedule_opens_at = $row[$csv_header_fields[3]]!='NULL'?$row[$csv_header_fields[3]]:null;
            $schedule->schedule_closes_at = $row[$csv_header_fields[4]]!='NULL'?$row[$csv_header_fields[4]]:null;
            $schedule->schedule_description = $row[$csv_header_fields[5]]!='NULL'?$row[$csv_header_fields[5]]:null;
            $schedule->schedule_locations = $row[$csv_header_fields[6]]!='NULL'?$row[$csv_header_fields[6]]:null;

            if($row[$csv_header_fields[6]]){
                $location_schedule = new Locationschedule();
                $location_schedule->location_recordid = $row[$csv_header_fields[6]]!='NULL'?$row[$csv_header_fields[6]]:null;
                $location_schedule->schedule_recordid = $schedule->schedule_recordid;
                $location_schedule->save();

            }
                                     
            $schedule ->save();
          
        }

        $date = date("Y/m/d H:i:s");
        $csv_source = CSV_Source::where('name', '=', 'Regular_schedules')->first();
        $csv_source->records = Schedule::count();
        $csv_source->syncdate = $date;
        $csv_source->save();
    }

    public function index()
    {
        $schedules = Schedule::orderBy('id')->get();
        $source_data = Source_data::find(1); 

        return view('backEnd.tables.tb_schedule', compact('schedules', 'source_data'));
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
        $schedule= Schedule::find($id);
        return response()->json($schedule);
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
        $schedule = Schedule::find($id);
        $schedule->schedule_days_of_week = $request->schedule_days_of_week;
        $schedule->schedule_opens_at = $request->schedule_opens_at;
        $schedule->schedule_closes_at = $request->schedule_closes_at;

        $schedule->schedule_holiday = $request->schedule_holiday;
        $schedule->schedule_closed = $request->schedule_closed;
        
        $schedule->flag = 'modified';
        $schedule->save();

        return response()->json($schedule);
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
