<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class ParentTaxonomiesTaxonomies extends Model
{
    use Sortable;

    protected $primaryKey = 'id';

    protected $table = 'parent_taxonomies_taxonomies';
    
	public $timestamps = false;
}
