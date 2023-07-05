<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\UserSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class UserRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()->content(
            MediaType::json()->schema(
                Schema::object()->properties(
                    Schema::string('name')->default('Murod'),
                    Schema::string('password')->default('123456789'),
                    Schema::string('password_confirmation')->default('123456789'),
                )
            )
        );
    }
}
