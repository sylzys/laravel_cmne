<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
        $this->crud->setShowView('users.show');
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
        CRUD::column('email');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        CRUD::column('firstname');
        CRUD::column('lastname');
        CRUD::column('email');


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
        CRUD::setValidation(UserRequest::class);
        CRUD::field('name')->type('hidden');
        CRUD::addField([
            'name' => 'firstname',
            'label' => 'Prénom',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ]);
        CRUD::addField([
            'name' => 'lastname',
            'label' => 'Nom',
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
        ]);
        CRUD::addField([   // Upload
            'name'      => 'picture',
            'label'     => 'Image',
            'type'      => 'image',
            'upload' => true,
            'wrapper' => [
                'class' => 'form-group col-md-4',
            ],
            // 'upload'    => true,
            // optional:
        ]);
        CRUD::addField([
                'name' => 'email',
                'wrapper' => [
                    'class' => 'form-group col-md-4',
                ],
            ]
        );
        // CRUD::addField([
        //     'name'  => 'password',
        //     'label' => 'Mot de passe',
        //     'type'  => 'password',
        //     'validationRules' => 'required|confirmed|size:6',
        //     'validationMessages' => [
        //         'required' => 'The "Password" field is required',
        //         'confirmed' => 'The "Password" confirmation does not match.',
        //         'size' => 'The "Password" must be at least 6 characters long.',
        //     ],
        //     'wrapper' => [
        //         'class' => 'form-group col-md-4',
        //     ],

        // ]);
        // CRUD::addField([
        //     'name'  => 'password_confirmation',
        //     'label' => 'Confirmation du mot de passe',
        //     'type'  => 'password',
        //     'false' => 'true',
        //     'validationRules' => 'required|same:password|size:6',
        //     'validationMessages' => [
        //         'required' => 'The "Password" field is required',
        //         'size' => 'The "Password" must be at least 6 characters long.',
        //     ],
        //     'wrapper' => [
        //         'class' => 'form-group col-md-4',
        //     ],
        // ]);

        // CRUD::addField([
        //     'name'     => 'lease',
        //     'type'     => 'upload',
        //     'upload'   => true,
        //     'label'    => 'Lease',
        //     'wrapper' => [
        //         'class' => 'form-group col-md-6',
        //     ],
        // ]);
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
