<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $table = 'taxonomy';

    protected $primaryKey = 'taxonomy_recordid';

    public $fillable = ['name','parent_name'];

    public $timestamps = false;

    /**
     * Get the index name for the model.
     *
     * @return string
    */
    public function childs() {
        return $this->hasMany('App\Taxonomy','taxonomy_parent_name','taxonomy_recordid') ;
    }

    public function parent()
    {
        return $this->belongsTo('App\Taxonomy', 'taxonomy_parent_name', 'taxonomy_recordid');
    }

    public function service()
    {
        $this->primaryKey='taxonomy_recordid';
        
        return $this->belongsToMany('App\Service', 'service_taxonomy', 'taxonomy_recordid', 'service_recordid');
    }
}
