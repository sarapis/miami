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

}
