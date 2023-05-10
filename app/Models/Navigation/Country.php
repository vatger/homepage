<?php

namespace App\Models\Navigation;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The name of the database table the model is stored in
     *
     * @var string
     */
    protected $table = 'navigation_countries';

    /**
     * the primary key of the table
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the index is automatically incrementing
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Does the table has created_at and updated_at fields
     *
     * @var bool
     */
    public $timestamps = false;
}
