<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Map;
use Geolocation;
use Geocode;
use Spatie\Geocoder\Geocoder;
use App\Location;
use Image;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Validator;
use Sentinel;
use Route;
use Cornford\Googlmapper\Facades\MapperFacade as Mapper;

class MapController extends Controller
{
    /**
     * Post Repository
     *
     * @var Post
    //  */
    // protected $about;

    // public function __construct(About $about)
    // {
    //     $this->about = $about;
    // }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $map = Map::find(1);
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        $geocoding_status = 'Not Started';

        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'geocoding_status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {   
        $map=Map::find(1);
        $map->active=$request->active;
        $map->api_key=$request->api_key;
        $map->state=$request->state;
        $map->lat=$request->lat;
        $map->long=$request->long;
        $map->zoom=$request->zoom;

        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('apis');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('admin.posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $post = $this->post->find($id);

        if (is_null($post))
        {
            return Redirect::route('posts.index');
        }

        return view('admin.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, Request $request)
    {
    	$map=Map::find(1);
        // var_dump($request->input('active'));
        // exit();
        if ($request->input('active') == 'checked')
        {
            $map->active = 1;
            $map->api_key=$request->input('api_key');
            $map->state=$request->input('state');
            $map->lat=$request->input('lat');
            $map->long=$request->input('long');
            $map->zoom=$request->input('zoom');
        }
        else {
            $map->active = 0;
        }
        
        $map->save();

        Session::flash('message', 'Map updated!');
        Session::flash('status', 'success');

        return redirect('map');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->post->find($id)->delete();

        return Redirect::route('admin.posts.index');
    }

    public function upload()
    {
        $file = Input::file('file');
        $input = array('image' => $file);
        $rules = array(
            'image' => 'image'
        );
        $validator = Validator::make($input, $rules);
        if ( $validator->fails()) {
            return Response::json(array('success' => false, 'errors' => $validator->getMessageBag()->toArray()));
        }

        $fileName = time() . '-' . $file->getClientOriginalName();
        $destination = public_path() . '/uploads/';
        $file->move($destination, $fileName);

        echo url('/uploads/'. $fileName);
    }

    public function scan_ungeocoded_location(Request $request) {
        $map = Map::find(1);
        $geocoding_status = 'Not Started';
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'geocoding_status'));
    }

    public function apply_geocode(Request $request) {
        $map = Map::find(1);
        $geocoding_status = 'Completed';
        $ungeocoded_location_numbers = Location::whereNull('location_latitude')->count();
        $ungeocoded_location_info_list = Location::whereNull('location_latitude')->get();

        $client = new \GuzzleHttp\Client();
        $geocoder = new Geocoder($client);
        
        $geocode_api_key = env('GEOCODE_GOOGLE_APIKEY');
        $geocoder->setApiKey($geocode_api_key);

        foreach ($ungeocoded_location_info_list as $key => $ungeocoded_location_info) {
            $location_name = $ungeocoded_location_info->location_name;
            $response = $geocoder->getCoordinatesForAddress($location_name);
            $longitude = $response['lng'];
            $latitude = $response['lat'];
            var_dump($longitude);
            var_dump($latitude);
            exit;
        }
        return view('backEnd.pages.map', compact('map', 'ungeocoded_location_numbers', 'geocoding_status'));
    }
}
