<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\MediaType\MediaFormData;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class NewsRequestBody extends RequestBodyFactory
{
    public function build(): RequestBody
    {
        return RequestBody::create()->content(
            MediaFormData::formData()->schema(
                Schema::object()->properties(
                    Schema::string('title')->default('title'),
                    Schema::string('cover')->type('file'),
                    Schema::string('description')->default('description'),
                    Schema::string('text')->default('text'),
                )
            )
        );
    }
}
