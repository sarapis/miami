<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Functions\Airtable;
use App\ParentTaxonomies;
use App\Taxonomy;
use App\Servicetaxonomy;
use App\AltTaxonomiesTermRelation;
use App\Source_data;
use App\Services\Stringtoint;
use Maatwebsite\Excel\Facades\Excel;
use Redirect;

class ParentTaxonomiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parent_taxonomies = ParentTaxonomies::orderBy('id')->paginate(20);
        $counts = [];
        // $terms = $alt_taxonomies->terms();
        foreach ($parent_taxonomies as $key => $parent_taxonomy) {
            // $id = $alt_taxonomy->id;
            // $tmp_alt_taxonomy = AltTaxonomiesTermRelation::where('alt_taxonomy_id','=',$id)->get();
            //exit();
            // var_dump($tmp_alt_taxonomy->terms()->allRelatedIds());
            // exit;
            $count = $parent_taxonomy->taxonomies()->count();
            // var_dump($terms);    
            array_push($counts, $count);
        }
        // var_dump($alt_taxonomies);
        $source_data = Source_data::find(1);

        return view('backEnd.tables.tb_parent_taxonomy', compact('parent_taxonomies', 'counts', 'source_data'));
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
        $parent_taxonomy= new ParentTaxonomies();
        $parent_taxonomy->parent_taxonomy_name = $request->parent_taxonomy_name;
        $parent_taxonomy->parent_taxonomy_vocabulary = $request->parent_taxonomy_vocabulary;
        $parent_taxonomy->save();

        return response()->json($parent_taxonomy);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $parent_taxonomy= ParentTaxonomies::find($id);
        return response()->json($parent_taxonomy);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function open_taxonomies($id)
    {
        $parent_taxonomy = ParentTaxonomies::find($id);
        $parent_taxonomy_name = $parent_taxonomy->parent_taxonomy_name;
        $taxonomies = $parent_taxonomy->taxonomies;
        $all_taxonomies = Taxonomy::all()->toArray();
        return response()->json(array('all_taxonomies' => $all_taxonomies, 'taxonomies' => $taxonomies, 'parent_taxonomy_name' => $parent_taxonomy_name));

    }

    public function operation(Request $request){
        $checked_taxonomies_list = $request->input("checked_taxonomies");
        $id = $request->input("parent_taxonomy_id");             
        $parent_taxonomy = ParentTaxonomies::find($id);
        $parent_taxonomy->taxonomies()->sync($checked_taxonomies_list);
        return redirect('tb_parent_taxonomy');
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
        $parent_taxonomy = ParentTaxonomies::find($id);
        $parent_taxonomy->parent_taxonomy_name = $request->parent_taxonomy_name;
        $parent_taxonomy->parent_taxonomy_vocabulary = $request->parent_taxonomy_vocabulary;
        $parent_taxonomy->save();

        return response()->json($parent_taxonomy);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $parent_taxonomy = ParentTaxonomies::destroy($id);
        return response()->json($parent_taxonomy);
    }
}
