<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Area extends Model
{
    use Sortable;

    protected $table = 'areas';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
