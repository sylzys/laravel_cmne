<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Housing extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'housings';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'type',
        'floor',
        'orientation',
        'bedrooms',
        'bathrooms',
        'surface',
        'galery',
        'header',
        'description',
        'residence_id'
    ];
    protected $casts = [
        'galery' => 'array'
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

    public function residence()
    {
        return $this->belongsTo(Residence::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'housings_amenities');
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
        $disk = "housings";
        $destination_path = "pictures";

        $this->uploadMultipleFilesToDisk($value, $attribute_name, $disk, $destination_path);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    // public function setHeaderAttribute($value)
    // {
    //     $attribute_name = "header";
    //     $disk = "housings";
    //     $destination_path = "pictures";

    //     $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $filename = null);

    // // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    // }
}
