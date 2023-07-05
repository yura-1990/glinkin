<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsStoreRequest;
use App\Models\News\News;
use App\OpenApi\Parameters\NewsParameters;
use App\OpenApi\RequestBodies\NewsRequestBody;
use App\OpenApi\Responses\NewsResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\Parameters;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody;
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

    /**
     * @param News $news
     * @return JsonResponse
     */
    #[Get('show/{news}')]
    #[Operation(tags: ['News'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[Parameters(factory: NewsParameters::class)]
    #[Response(factory: NewsResponse::class)]
    public function show(News $news): JsonResponse
    {
        return $this->success($news);
    }

    /**
     * @param NewsStoreRequest $request
     * @return JsonResponse
     */
    #[Post('create')]
    #[Operation(tags: ['News'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[RequestBody(factory: NewsRequestBody::class)]
    #[Response(factory: NewsResponse::class)]
    public function store(NewsStoreRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            $data['cover'] = $this->uploadFile($request->file('cover'), 'cover');
        }

        $news = News::query()->create([
            'title' => $data['title'],
            'cover' => $data['cover'],
            'description' => $data['description'],
            'text' => $data['text']
        ]);

        return $this->success($news);
    }

    /**
     * @param NewsStoreRequest $request
     * @param News $news
     * @return JsonResponse
     */
    #[Post('update/{news}')]
    #[Operation(tags: ['News'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[Parameters(factory: NewsParameters::class)]
    #[RequestBody(factory: NewsRequestBody::class)]
    #[Response(factory: NewsResponse::class)]
    public function update(NewsStoreRequest $request, News $news): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover')) {
            if ($news->cover){
                $this->deleteFile($news->cover);
            }

            $data['cover'] = $this->uploadFile($request->file('cover'), 'cover');
        } else {
            $data['cover'] = $news->cover;
        }

        $news->update($data);

        return $this->success($news);
    }

    /**
     * @param News $news
     * @return JsonResponse
     */
    #[Delete('delete/{news}')]
    #[Operation(tags: ['News'], security: BearerTokenSecurityScheme::class, method: 'DELETE')]
    #[Parameters(factory: NewsParameters::class)]
    #[Response(factory: NewsResponse::class)]
    public function delete(News $news): JsonResponse
    {
        $news->delete();

        return $this->success(['message' => 'The data deleted successfully']);
    }
}
