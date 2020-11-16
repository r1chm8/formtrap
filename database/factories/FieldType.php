<?php

use Faker\Generator as Faker;

$factory->define(App\FieldType::class, function (Faker $faker) {
    return [
        'name' => $faker->slug
    ];
});
