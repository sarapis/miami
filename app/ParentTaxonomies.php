<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ParentTaxonomies extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'parent_taxonomies';
    
	public $timestamps = false;

	public function taxonomies() {
		$primaryKey = 'id';
		return $this->belongsToMany('App\Taxonomy', 'parent_taxonomies_taxonomies','parent_taxonomy_id', 'taxonomy_id');
	}
	public function grandparents() {
		$primaryKey = 'id';
		return $this->belongsToMany('App\Alt_taxonomy', 'alt_taxonomies_term_relation','taxonomy_taxonomy_id', 'alt_taxonomy_id');
	}
}
