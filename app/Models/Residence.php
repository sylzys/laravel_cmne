<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Residence extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'residences';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'address',
        'city',
        'state',
        'zip',
        'max_housings',
        'surface',
        'galery',
        'header',
        'description'
    ];
    protected $casts = [
        // 'galery' => 'array',
        'address' => 'array'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    function housings() {
    	return $this->hasMany(Housing::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'residences_amenities');
    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setGaleryAttribute($value)
    {
        $attribute_name = "galery";
        $disk = "residences";
        $destination_path = "pictures";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    public function setHeaderAttribute($value)
    {
        $attribute_name = "header";
        $disk = "residences";
        $destination_path = "pictures";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $filename = null);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }
}
