<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest as StoreRequest;
use Backpack\PermissionManager\app\Http\Requests\UserUpdateCrudRequest as UpdateRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Hash;
use \Backpack\CRUD\app\Library\Widget;
use Mail;
use App\Mail\UserCreated;
use App\Models\Role;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->crud->setModel(config('backpack.permissionmanager.models.user'));
        $this->crud->setEntityNameStrings(trans('backpack::permissionmanager.user'), trans('backpack::permissionmanager.users'));
        $this->crud->setRoute(backpack_url('user'));
    }
    // public function impersonate ($id) {
    //     return $User::find($id);
    // }
    public function setupListOperation()
    {
        // $this->crud->addButtonFromView('line', 'impersonate', 'Impersonate', 'beginning'); //!! TODO add current_company_id to user model to replace IMPERSONATE
        if(!isAdmin() && canManageCompany()) {
            $this->crud->addClause('whereHas', 'company', function($query) {
                $query->where('id', [backpack_user()->company_id]);
            });
        }
        $this->crud->addColumns([
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
                'validationRules' => 'required',
                'validationMessages' => [
                    'required' => 'The "Name" field is required',
                ],
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
            ],
            [
                'name' => 'managed',
                'label' => "Managed entities",
                'type'  => 'model_function',
                'function_name' => 'getManagedEntities'
            ],
            [
                'label'     => trans('backpack::permissionmanager.roles'),
                'type'      => 'select_multiple',
                'name'      => 'roles',
                'entity'    => 'roles',
                'attribute' => 'name',
                'model'     => config('backpack.permissionmanager.models.roles'),
            ],
            [
                'label'     => trans('backpack::permissionmanager.extra_permissions'),
                'type'      => 'select_multiple',
                'name'      => 'permissions',
                'entity'    => 'permissions',
                'attribute' => 'name',
                'model'     => config('permission.models.permission'),
            ],
        ]);

        if (backpack_pro() && isAdmin()) {
            // Role Filter
            $this->crud->addFilter(
                [
                    'name'  => 'role',
                    'type'  => 'dropdown',
                    'label' => trans('backpack::permissionmanager.role'),
                    'wrapper' => [
                        'class' => 'form-group roles',
                    ],
                ],
                config('permission.models.role')::all()->pluck('name', 'id')->toArray(),
                function ($value) { // if the filter is active
                    $this->crud->addClause('whereHas', 'roles', function ($query) use ($value) {
                        $query->where('role_id', '=', $value);
                    });
                }
            );

            // Extra Permission Filter
            $this->crud->addFilter(
                [
                    'name'  => 'permissions',
                    'type'  => 'select2',
                    'label' => trans('backpack::permissionmanager.extra_permissions'),
                ],
                config('permission.models.permission')::all()->pluck('name', 'id')->toArray(),
                function ($value) { // if the filter is active
                    $this->crud->addClause('whereHas', 'permissions', function ($query) use ($value) {
                        $query->where('permission_id', '=', $value);
                    });
                }
            );
        }
        else if (backpack_pro() && canManageCompany()) {

            $this->crud->addFilter(
                [
                    'name'  => 'role',
                    'type'  => 'dropdown',
                    'label' => trans('backpack::permissionmanager.role'),
                ],
                config('permission.models.role')::whereIn('name', ['Entity Manager', 'Reporter'])->pluck('name', 'id')->toArray(),
                function ($value) { // if the filter is active
                    $this->crud->addClause('whereHas', 'roles', function ($query) use ($value) {
                        $query->where('role_id', '=', $value);
                    });
                }
            );
        }
    }

    public function setupCreateOperation()
    {
        $this->addUserFields();
        Widget::add()->type('script')->content('/js/role-switcher.js');
        $this->crud->setValidation(StoreRequest::class);
        \App\Models\User::creating(function ($entry) {
            // Mail::to($entry)->send(new UserCreated($entry));
            if (!isAdmin()) {
                $entry->company_id = backpack_user()->company->id;
            }
        });
        \App\Models\User::created(function ($entry) {
            if(!isAdmin() && canManageCompany() ) {
                $entry->roles()->sync(Role::find($this->crud->getRequest()->get('reduced_roles')));
            } else if(!isAdmin() && canManageEntity()) {
                $entry->roles()->sync(Role::Where('name', 'like', '%Reporter%')->first());
            }
        });
    }

    public function setupUpdateOperation()
    {
        $this->addUserFields();
        Widget::add()->type('script')->content('/js/role-switcher.js');
        $this->crud->setValidation(UpdateRequest::class);
        \App\Models\User::saving(function ($entry) {
            if(!isAdmin() && canManageCompany() ) {
                $entry->roles()->sync(Role::find($this->crud->getRequest()->get('reduced_roles')));
            } else if(!isAdmin() && canManageEntity()) {
                $entry->roles()->sync(Role::Where('name', 'like', '%Reporter%')->first());
            }
        });
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitStore();
    }

    /**
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        $this->crud->setRequest($this->crud->validateRequest());
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return $this->traitUpdate();
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

    protected function addUserFields()
    {
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => trans('backpack::permissionmanager.name'),
                'type'  => 'text',
                'validationRules' => 'required',
                'validationMessages' => [
                    'required' => 'The "Name" field is required',
                ],
            ],
            [
                'name'  => 'email',
                'label' => trans('backpack::permissionmanager.email'),
                'type'  => 'email',
                'validationRules' => 'sometimes|required|email',
                'validationMessages' => [
                    'required' => 'The "Email" field is required',
                    'email' => 'The format of the "Email" field is incorrect.',
                    'unique' => 'The "Email" field must be unique.'
                ],
            ],
            [
                'name'  => 'password',
                'label' => trans('backpack::permissionmanager.password'),
                'type'  => 'password',
                // 'validationRules' => 'required|confirmed|size:8',
                // 'validationMessages' => [
                //     'required' => 'The "Password" field is required',
                //     'confirmed' => 'The "Password" confirmation does not match.',
                //     'size' => 'The "Password" must be at least 8 characters long.',
                // ],

            ],
            [
                'name'  => 'password_confirmation',
                'label' => trans('backpack::permissionmanager.password_confirmation'),
                'type'  => 'password',
                // 'validationRules' => 'required|size:8',
                // 'validationMessages' => [
                //     'required' => 'The "Password" field is required',
                //     'size' => 'The "Password" must be at least 8 characters long.',
                // ],
            ],
            [   // Switch
                'name'  => 'is_medialab',
                'type'  => 'switch',
                'label'    => 'Medialab user ?',

                // optional
                'color'    => 'primary', // May be any bootstrap color class or an hex color
                'onLabel' => '✓',
                'offLabel' => '✕',
            ],
        ]);
        if (isAdmin()) {
            $this->crud->addFields([
            [
                'name'  => 'company',
                'label' => 'Company',
                'type'  => 'relationship',
                'entity' => 'company',
                'attribute' => 'name',
                'wrapper' => [
                    'class' => 'form-group col-md-12 company-select',
                ],
            ],
                [
                    'label'     => "Companies",
                    'type'      => 'select2_multiple',
                    'name'      => 'companies',
                    'entity'    => 'companies',
                    'attribute' => 'name',
                    'wrapper' => [
                        'class' => 'form-group col-md-12 company-relationship',
                    ],
                ]
        ]);
        $this->crud->setValidation();
    }
        if(isAdmin()) {
            $this->crud->addFields([
            [
                // two interconnected entities
                'label'             => trans('backpack::permissionmanager.user_role_permission'),
                'field_unique_name' => 'user_role_permission',
                'type'              => 'checklist_dependency',
                'name'              => ['roles', 'permissions'],
                'subfields'         => [
                    'primary' => [
                        'label'            => trans('backpack::permissionmanager.roles'),
                        'name'             => 'roles',
                        'entity'           => 'roles',
                        'entity_secondary' => 'permissions',
                        'attribute'        => 'name',
                        'model'            => config('permission.models.role'),
                        'pivot'            => true,
                        'number_columns'   => 3, //can be 1,2,3,4,6
                    ],
                    'secondary' => [
                        'label'          => mb_ucfirst(trans('backpack::permissionmanager.permission_plural')),
                        'name'           => 'permissions',
                        'entity'         => 'permissions',
                        'entity_primary' => 'roles',
                        'attribute'      => 'name',
                        'model'          => config('permission.models.permission'),
                        'pivot'          => true,
                        'number_columns' => 3,
                    ],
                ],
            ]
            ]);
        }

        if(!isAdmin() && canManageCompany()) {
            $this->crud->addFields([
                [  // Select
                    'label'     => "Role",
                    'type'      => 'reduced_roles',
                    'name'             => 'role',
                ]
            ]);
        }
    }


}
