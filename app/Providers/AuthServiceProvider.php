<?php

namespace App\Providers;

use App\Form;
use App\Enquiry;
use App\Policies\FormPolicy;
use App\Policies\EnquiryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Enquiry::class => EnquiryPolicy::class,
        Form::class => FormPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
