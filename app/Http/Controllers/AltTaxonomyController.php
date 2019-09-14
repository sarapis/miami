<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\Alt_taxonomy;
use App\Taxonomy;
use App\Servicetaxonomy;
use App\Airtables;
use App\CSV_Source;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;

class AltTaxonomyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alt_taxonomies = Alt_taxonomy::orderBy('id')->paginate(20);
        $counts = [];
        foreach ($alt_taxonomies as $key => $alt_taxonomy) {
            $alt_taxonomy_name = $alt_taxonomy->alt_taxonomy_name;
            $count = Taxonomy::where('taxonomy_grandparent_name', 'like', $alt_taxonomy_name)
                            ->orWhere('taxonomy_parent_name', 'like', $alt_taxonomy_name)
                            ->orWhere('taxonomy_name', 'like', $alt_taxonomy_name)->count();
            array_push($counts, $count);
        }

        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_alt_taxonomy', compact('alt_taxonomies', 'counts', 'source_data'));
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
        $alt_taxonomy= new Alt_taxonomy();
        $alt_taxonomy->alt_taxonomy_name = $request->alt_taxonomy_name;
        $alt_taxonomy->alt_taxonomy_vocabulary = $request->alt_taxonomy_vocabulary;
        $alt_taxonomy->save();

        return response()->json($alt_taxonomy);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $alt_taxonomy= Alt_taxonomy::find($id);
        return response()->json($alt_taxonomy);
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
        $alt_taxonomy = Alt_taxonomy::find($id);
        $alt_taxonomy->alt_taxonomy_name = $request->alt_taxonomy_name;
        $alt_taxonomy->alt_taxonomy_vocabulary = $request->alt_taxonomy_vocabulary;
        $alt_taxonomy->save();

        return response()->json($alt_taxonomy);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $alt_taxonomy = Alt_taxonomy::destroy($id);
        return response()->json($alt_taxonomy);
    }
}
