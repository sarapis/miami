<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Alt_taxonomy extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'alt_taxonomies';
    
	public $timestamps = false;

	public function terms() {
		$primaryKey = 'id';
		return $this->belongsToMany('App\ParentTaxonomies', 'alt_taxonomies_term_relation','alt_taxonomy_id', 'taxonomy_taxonomy_id');
	}

}
