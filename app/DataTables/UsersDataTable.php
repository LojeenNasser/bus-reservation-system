<?php

namespace App\DataTables;

use App\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created_at', fn(User $user) => $user->created_at)
            ->editColumn('email_verified_at', fn(User $user) => $user->hasVerifiedEmail() ? 'Yes' : 'No')
            ->editColumn('roles', fn(User $user) => $user->roles->pluck('name')->implode(', '))
            ->addColumn('actions', fn(User $user) => $this->renderActionButtons($user));
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->newQuery()->with('roles');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('users-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(0)
            ->addTableClass('table-hover')
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $columns = [
            Column::make('id')->width('5%'),
            Column::make('first_name')->width('12%'),
            Column::make('last_name')->width('12%'),
            Column::make('email')->width('20%'),
            Column::make('created_at')->width('16%')->title('Joined At'),
            Column::make('email_verified_at')->width('5%')->title('Verified')->searchable(false),
            Column::make('roles')->width('10%')->name('roles.name')->orderable(false),
        ];

        if ($this->userCanUpdateOrDelete()) {
            $columns[] = Column::computed('actions')->width('20%')->addClass('text-center');
        }

        return $columns;
    }

    /**
     * Render action buttons.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    protected function renderActionButtons(User $user)
    {
        return view('admin.partials.datatable-action-buttons', [
            'id' => $user->id,
            'itemName' => 'user',
            'editRoute' => route('admin.users.edit', $user),
            'destroyRoute' => route('admin.users.destroy', $user),
        ]);
    }

    /**
     * Check if the authenticated user can update or delete.
     *
     * @return bool
     */
    protected function userCanUpdateOrDelete()
    {
        $authenticatedUser = auth()->user();

        return $authenticatedUser && $authenticatedUser->can('updateOrDeleteAny', User::class);
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'Users_' . date('YmdHis');
    }
}
