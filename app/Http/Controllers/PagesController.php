<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Page;
use App\Source_data;
use App\Airtables;
use App\CSV_Source;
use App\Layout;
use App\Taxonomy;
use App\Address;
use App\Metafilter;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Session;
use Validator;
use Sentinel;
use Route;

class PagesController extends Controller
{
    protected function validator(Request $request,$id='')
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'title' => 'required',            
            'body' => 'required',
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $pages = Page::all();

        return view('backEnd.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('backEnd.pages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        if ($this->validator($request,Sentinel::getUser()->id)->fails()) {
            
            return redirect()->back()
                    ->withErrors($this->validator($request))
                    ->withInput();
        }
        
        Page::create($request->all());

        Session::flash('message', 'Page added!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('backEnd.pages.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        if ($this->validator($request,Sentinel::getUser()->id)->fails()) {
            
            return redirect()->back()
                    ->withErrors($this->validator($request))
                    ->withInput();
        }
        
        $page = Page::findOrFail($id);
        $page->update($request->all());

        Session::flash('message', 'Page updated!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        $page->delete();

        Session::flash('message', 'Page deleted!');
        Session::flash('status', 'success');

        return redirect('pages');
    }

    public function datasync()
    {
        $airtables = Airtables::all();
        $csvs = CSV_Source::all();
        $source_data = Source_data::find(1);

        return view('backEnd.datasync', compact('airtables', 'csvs', 'source_data'));
    }

    public function export()
    {
        return view('backEnd.pages.export');
    }

    public function metafilter()
    {
        $meta = Layout::find(1);
        $source_data = Source_data::find(1);
        $metafilters = Metafilter::all();

        return view('backEnd.pages.metafilter', compact('meta', 'source_data', 'metafilters'));
    }

    public function metafilter_save($id, Request $request)
    {
        $meta = Layout::find($id);

        if ($request->input('meta_filter_activate') == 'checked')
        {
            $meta->meta_filter_activate = 1;
        }
        else
            $meta->meta_filter_activate = 0;

        $meta->meta_filter_on_label = $request->meta_filter_on_label;
        $meta->meta_filter_off_label = $request->meta_filter_off_label;
        $meta->save();

        return redirect('meta_filter');
    }

    public function taxonomy_filter()
    {
        $taxonomies = Taxonomy::all();
        $source_data = Source_data::find(1);
        $checked_taxonomies = [];

        return view('backEnd.pages.metafilter_taxonomy', compact('taxonomies', 'source_data', 'checked_taxonomies'))->render();
    }

    public function metafilter_edit($id, Request $request)
    {   
        $source_data = Source_data::find(1);
        $metafilter = Metafilter::find($id);
        if($metafilter->facet = 'Taxonomy'){

            $taxonomies = Taxonomy::all();
            $checked_taxonomies = explode(",",$metafilter->values);

            return view('backEnd.pages.metafilter_taxonomy', compact('taxonomies', 'source_data', 'checked_taxonomies'))->render();
        }
        else{

            $addresses = Address::orderBy('id')->get();
            $checked_addresses = explode(",",$metafilter->values);

            return view('backEnd.pages.metafilter_address', compact('addresses', 'source_data', 'checked_addresses'))->render();
        }
    }

    public function postal_filter()
    {
        $addresses = Address::orderBy('id')->get();
        $source_data = Source_data::find(1);

        return view('backEnd.pages.metafilter_address', compact('addresses', 'source_data'))->render();
    }

    public function operation(Request $request){

        

        $id = $request->input('status');
        if($id == 0)
        {
            $metafilter = new Metafilter;
            $metafilter->operations = $request->input('operation');
            $metafilter->facet = $request->input('facet');
            $metafilter->method = $request->input('method');
            if($request->input('table_records') != null)
                $metafilter->values = implode(",", $request->input('table_records'));
            $metafilter->save();
        }
        else{
            $metafilter = Metafilter::where('id', '=', $id)->first();
            $metafilter->operations = $request->input('operation');
            $metafilter->facet = $request->input('facet');
            $metafilter->method = $request->input('method');
            if($request->input('table_records') != null)
                $metafilter->values = implode(",", $request->input('table_records'));
            $metafilter->save();
        }


        return redirect('meta_filter');

    }

    public function delete_operation(Request $request){
        $id = $request->input('id');

        $metafilter = Metafilter::find($id);
        $metafilter->delete();

        return redirect('meta_filter');
    }

}
