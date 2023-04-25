<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HousingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HousingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HousingCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Housing::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/housing');
        CRUD::setEntityNameStrings('logement', 'logements');
        $this->crud->setShowView('housings.show');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(HousingRequest::class);
        CRUD::addField([
            'name' => 'name',
            'type' => 'text',
            'label' => 'Name'
        ]);
        CRUD::addField([
            'name' => 'residence',
            'type' => 'relationship',
            'label' => 'Résidence',
            'entity' => 'residence',
            "wrapper" => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        CRUD::addField([
            'name' => 'surface',
            'type' => 'number',
            'suffix' => 'm²',
            'default' => 0,
            "wrapper" => [
                'class' => 'form-group col-md-3'
            ]
        ]);
        CRUD::addField([
            'name' => 'type',
            'type' => 'select2_from_array',
            'options'     => [
                't1' => 'T1',
                't2' => 'T2',
                't3' => 'T3',
                't4' => 'T4'
            ],
            'allows_null' => false,
            'default'     => 'one',
            "wrapper" => [
                'class' => 'form-group col-md-3'
            ]

        ]);
        CRUD::addField([
            'name' => 'floor',
            'label' => 'Etage',
            'type' => 'select2_from_array',
            'options'     => [
                'rdc' => 'RDC',
                '1' => '1',
                '2' => '2',
                '3' => '3',
                '4' => '4'
            ],
            'allows_null' => false,
            'default'     => '1',
            'ajax'          => true,
            "wrapper" => [
                'class' => 'form-group col-md-3'
            ]

        ]);
        CRUD::addField([
            'name' => 'orientation',
            'label' => 'Orientation',
            'type' => 'select2_from_array',
            'options'     => [
                'N' => 'Nord',
                'S' => 'Sud',
                'E' => 'Est',
                'W' => 'Ouest',
            ],
            'allows_null' => false,
            'default'     => 'one',
            'name'          => 'orientation', // the method on your model that defines the relationship
            'ajax'          => true,
            "wrapper" => [
                'class' => 'form-group col-md-4'
            ]
        ]);
        CRUD::addField([
            'name' => 'bedrooms',
            'type' => 'number',
            'label' => 'Nb chambres',
            'default' => 1,
        ]);
        CRUD::addField([
            'name' => 'bathrooms',
            'type' => 'number',
            'label' => 'Nb SdB',
            'default' => 1,
        ]);
        $this->crud->addField([
            'name' => 'galery',
            'label' => 'Photos',
            'type' => 'upload_multiple',
            'multiple' => true,
            'upload' => true,
            "wrapper" => [
                "class" => "form-group col-md-6"
            ]
        ]);
        CRUD::addField([
            'name' => 'header',
            'type' => 'image',
            'upload' => true,
            'label' => 'Image d\'entête'
        ]);
        CRUD::addField([
            'type'          => "select2_multiple",
            'name'          => 'amenities',
            'label'         => 'Commodités',
            // 'ajax'          => true,
            'data_source' => url("api/amenities"),
            'inline_create' => [ 'entity' => 'amenity' ],
            'wrapper' => [
                'class' => 'form-group col-md-12',
            ],
        ]);
        $this->crud->addField([
            'name' => 'description',
            'label' => 'Description',
            "wrapper" => [
                "class" => "form-group col-md-12"
            ]
        ]);
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
