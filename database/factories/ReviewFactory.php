<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Review::class, function (Faker $faker) {
    return [
        "product_id"=>$faker->numberBetween(1,50),
        "review"=>$faker->paragraph,
        "star"=>$faker->numberBetween(1,5),
        "user_id"=>$faker->numberBetween(1,30)
    ];
});
