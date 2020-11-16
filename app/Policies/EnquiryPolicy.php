<?php

namespace App\Policies;

use App\User;
use App\Enquiry;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnquiryPolicy
{
    use HandlesAuthorization;

    public function manage(User $user, Enquiry $enquiry)
    {
        $form = $enquiry->form;

        return $form && $form->isOwnedBy($user);
    }
}
