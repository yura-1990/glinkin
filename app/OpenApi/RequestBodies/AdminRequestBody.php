<?php

namespace App\OpenApi\RequestBodies;

use App\Enums\UserRoleTypeEnum;
use App\Enums\UserStatusTypeEnum;
use App\OpenApi\MediaType\MediaFormData;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class AdminRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()->content(
            MediaFormData::formData()->schema(
                Schema::object()->properties(
                    Schema::string('name')->default('Murod'),
                    Schema::string('phone')->default('+99897592365'),
                    Schema::integer('status')->enum(...UserStatusTypeEnum::cases()),
                    Schema::integer('role_id')->enum(...UserRoleTypeEnum::cases()),
                    Schema::string('password')->default('password'),
                )
            )
        );
    }
}
