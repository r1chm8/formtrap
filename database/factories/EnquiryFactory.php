<?php

$factory->define(App\Enquiry::class, function () {
    return [
        'form_id' => function () {
            return factory(App\Form::class)->create()->id;
        }
    ];
});
