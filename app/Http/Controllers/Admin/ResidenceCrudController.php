<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ResidenceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ResidenceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ResidenceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Residence::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/residence');
        CRUD::setEntityNameStrings('residence', 'residences');
        $this->crud->setShowView('residences.show');

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
        CRUD::addColumn([
            'name' => 'address.locality',
            'label' => 'Ville',
        ]);
        CRUD::addColumn([
            'name' => 'max_housings',
            'label' => 'Nombre de logements'
        ]);

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
        CRUD::setValidation(ResidenceRequest::class);
        CRUD::field('name');
        CRUD::addfield([
            'name' => 'address',
            'label' => 'Adresse',
            'store_in' => 'address',
            'store_as_json' => true,
            'type'          => 'address_google',
            "wrapper" => [
                "class" => "form-group col-md-6"
            ]
        ]);

        CRUD::addField([
        "name" => 'max_housings',
        "label" => "Nombre de logements",
        "type" => "number",
        "wrapper" => [
            "class" => "form-group col-md-3"
        ]
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

    $this->crud->addField([
        'name' => 'header',
        'label' => 'Image entête',
        'type' => 'image',
        'upload' => true,
        "wrapper" => [
            "class" => "form-group col-md-6"
        ]
    ]);
    CRUD::addField([
        'type'          => "select2_multiple",
        'name'          => 'amenities',
        'label'         => 'Commodités',
        'ajax'          => true,
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
