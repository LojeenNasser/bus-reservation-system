<?php

namespace App\Policies;

use App\User; // تم تحديثها إلى App\User
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true; // تمكين الوصول لجميع المستخدمين
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isAdmin(); // يمكن للمسؤول (admin) إنشاء نماذج
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin(); // يمكن للمسؤول (admin) تحديث النماذج
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $user->isAdmin() && $user->id !== $model->id; // يمكن للمسؤول (admin) حذف النماذج ما لم يكونوا نفس المستخدم
    }

    /**
     * Determine whether the user can update or delete any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function updateOrDeleteAny(User $user)
    {
        return $user->isAdmin(); // يمكن للمسؤول (admin) تحديث أو حذف أي نموذج
    }
}
