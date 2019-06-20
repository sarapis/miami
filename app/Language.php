<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Language extends Model
{
    use Sortable;

    protected $table = 'languages';

    protected $primaryKey = 'id';
    
	public $timestamps = false;

}
