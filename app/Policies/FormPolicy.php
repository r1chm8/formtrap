<?php

namespace App\Policies;

use App\Form;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Form $form)
    {
        return $form->isOwnedBy($user);
    }
}
