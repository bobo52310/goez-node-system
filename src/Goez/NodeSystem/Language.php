<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;
use ModelValidatingTrait;

class Language extends Eloquent
{
    use ModelValidatingTrait;

    public static $tableName = 'goez_languages';
    protected $rules = [
        'name' => ['required', 'regex:/^[a-z]{2}(-[a-z]{2})?$/', 'max:10'],
        'display_name' => ['required', 'max:50'],
        'is_display' => ['required', 'in:y,n']
    ];
    public $timestamps = false;
    protected $table = 'goez_languages';
    protected $guarded = array();

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->string('name', 10);
            $table->string('display_name', 50);

            $table->index('name');
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

