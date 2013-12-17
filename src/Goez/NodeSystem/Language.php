<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;

class Language extends Eloquent
{
    public static $tableName = 'goez_languages';
    protected $table = 'goez_languages';
    protected $guarded = array();

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->text('name', 10);
            $table->text('display_name', 50);
        };
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function nodes()
    {
        return $this->belongsToMany(
            'Goez\NodeSystem\Node',
            'goez_node_languages',
            'language_name', 'name');
    }
}

