<?php

use Faker\Generator as Faker;

$factory->define(App\Model\Product::class, function (Faker $faker) {
    return [
        "name"=>$faker->word,
        "details"=>$faker->realText(),
        "price"=>$faker->numberBetween(5,1000),
        "stock"=>$faker->randomDigit,
        "discount"=>$faker->numberBetween(0,50),
        "user_id"=>$faker->numberBetween(1,11)
    ];
});
