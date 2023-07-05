<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsStoreRequest;
use App\Models\News\News;
use App\OpenApi\Responses\NewsResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[Prefix('news')]
#[PathItem]
class NewsController extends Controller
{
    /**
     * @return JsonResponse
     */
    #[Get('get-all')]
    #[Operation(tags: ['News'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[Response(factory: NewsResponse::class)]
    public function index(): JsonResponse
    {
        $news = News::query()->get();

        return $this->success($news);
    }

    public function store(NewsStoreRequest $request)
    {

    }
}
