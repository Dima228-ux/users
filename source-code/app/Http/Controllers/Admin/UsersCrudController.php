<?php


namespace App\Http\Controllers\Admin;


use App\Http\Requests\UsersRequest;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class CategoryCrudController
 *
 * @property-read CrudPanel $crud
 */
class UsersCrudController extends CrudController
{
    use ListOperation;
    use UpdateOperation {
        update as traitUpdate;
    }
    use DeleteOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '');
        CRUD::setEntityNameStrings('new', 'Users');
    }

    /**\
     * @return JsonResponse|RedirectResponse
     */
    public function update()
    {
        CRUD::setRequest(CRUD::validateRequest());

        /** @var Request $request */
        $request = CRUD::getRequest();

        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        CRUD::setRequest($request);
        CRUD::unsetValidation();
        $response = $this->traitUpdate();

        return $response;
    }


    protected function setupShowOperation()
    {
        $this->setupListOperation();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     *
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id')->label('ID');
        CRUD::column('name')->label('Name')->searchLogic(
            function ($query, $column, $searchTerm) {
                $query->orWhere('name', 'ilike', '%' . $searchTerm . '%');
            }
        );
        CRUD::column('email')->label('Email');
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     *
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(UsersRequest::class);

        CRUD::field('name')->label('Name')->type('text');
        CRUD::field('email')->label('Email')->type('email');
        CRUD::field('password')->label('Password')->type('password');
        CRUD::field('phone')->label('Phone')->type('text');
        CRUD::field('telegram')->label('Telegram')->type('text');
    }
}
