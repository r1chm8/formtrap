<?php

use Faker\Generator as Faker;

$factory->define(App\Field::class, function (Faker $faker) {
    return [
        'form_version_id' => function () {
            return factory(App\Form::class)->create()->id;
        },
        'type_id' => function () {
            return factory(App\FieldType::class)->create()->id;
        },
        'name' => $faker->slug,
        'label' => $faker->sentence,
    ];
});
