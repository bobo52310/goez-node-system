<?php

namespace Goez\NodeSystem;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Schema\Blueprint as Table;
use ModelValidatingTrait;

class FieldType extends Eloquent
{
    use ModelValidatingTrait;

    public static $tableName = 'goez_field_types';
    public static $types = array(
        array(
            'id' => 1,
            'type' => 'flexible',
            'name' => 'Flexible',
            'description' => 'Flexible wrapper for mixed contents.',
        ),
        array(
            'id' => 2,
            'type' => 'text',
            'name' => 'Text',
            'description' => 'For single line text.',
        ),
        array(
            'id' => 3,
            'type' => 'textarea',
            'name' => 'TextArea',
            'description' => 'For multiple lines text.',
        ),
        array(
            'id' => 4,
            'type' => 'image',
            'name' => 'Image',
            'description' => 'Image upload.',
        ),
        array(
            'id' => 5,
            'type' => 'link',
            'name' => 'Link',
            'description' => 'For url link.',
        ),
        array(
            'id' => 6,
            'type' => 'html',
            'name' => 'HTML',
            'description' => 'For rich content.',
        ),
        array(
            'id' => 7,
            'type' => 'calendar',
            'name' => 'Calendar',
            'description' => 'Date and time picker.',
        ),
    );
    protected $rules = [
        'type' => ['required', 'max:100'],
        'name' => ['required', 'max:100'],
        'description' => ['max:200']
    ];
    public $timestamps = false;
    protected $table = 'goez_field_types';
    protected $guarded = false;

    /**
     * @return callable
     */
    public static function getBlueprint()
    {
        return function (Table $table) {
            $table->increments('id');
            $table->string('type', 100);
            $table->string('name', 100);
            $table->string('description', 200)->nullable()->default(null);

            $table->index('type');
        };
    }
}
