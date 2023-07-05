<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRoleTypeEnum;
use App\Enums\UserStatusTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\AdminUpdateRequest;
use App\Models\User;
use App\OpenApi\Parameters\AdminParameters;
use App\OpenApi\RequestBodies\AdminRequestBody;
use App\OpenApi\Responses\AdminResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\RouteAttributes\Attributes\Delete;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\Parameters;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[Prefix('admin')]
#[PathItem]
class AdminController extends Controller
{
    /**
     * @return JsonResponse
     */
    #[Get('/get-all-users')]
    #[Operation(tags: ['Admin'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[Response(factory: AdminResponse::class)]
    public function getAllUsers(): JsonResponse
    {
        if (auth()->user()->role_id === UserRoleTypeEnum::ADMIN->name){
            $users = User::query()->get();
        } else {
            $users = ['message' => 'This action is unauthorized'];
        }

        return $this->success($users);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Get('show/{user}')]
    #[Operation(tags: ['Admin'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[Parameters(factory: AdminParameters::class)]
    #[Response(factory: AdminResponse::class)]
    public function show(User $user): JsonResponse
    {
        if (auth()->user()->role_id === UserRoleTypeEnum::ADMIN->name){
            return $this->success($user);
        } else {
            return $this->success(['message' => 'This action is unauthorized']);
        }
    }

    /**
     * @param AdminRequest $request
     * @return JsonResponse
     */
    #[Post('create')]
    #[Operation(tags: ['Admin'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[RequestBody(factory: AdminRequestBody::class)]
    #[Response(factory: AdminResponse::class)]
    public function create(AdminRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::query()->create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'status' => $data['status'] ?? UserStatusTypeEnum::ACTIVE->value,
            'role_id' => $data['role_id'] ?? UserRoleTypeEnum::USER->value,
            'password' => Hash::make($data['password'])
        ]);

        return $this->success($user);
    }

    /**
     * @param AdminUpdateRequest $request
     * @param User $user
     * @return JsonResponse
     */
    #[Post('update/{user}')]
    #[Operation(tags: ['Admin'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[Parameters(factory: AdminParameters::class)]
    #[RequestBody(factory: AdminRequestBody::class)]
    #[Response(factory: AdminResponse::class)]
    public function update(AdminUpdateRequest $request, User $user): JsonResponse
    {
        $data = $request->validated();

        $filteredArray = array_filter($data, function ($value) {
            return $value !== null;
        });

        if(isset($filteredArray['password'])){
            $filteredArray['password'] = Hash::make($filteredArray['password']);
        }

        $user->update($filteredArray);

        return $this->success($user);
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    #[Delete('delete/{user}')]
    #[Operation(tags: ['Admin'], security: BearerTokenSecurityScheme::class, method: 'DELETE')]
    #[Parameters(factory: AdminParameters::class)]
    #[Response(factory: AdminResponse::class)]
    public function delete(User $user): JsonResponse
    {
        if (auth()->user()->role_id === UserRoleTypeEnum::ADMIN->name){
            $user->delete();
            return $this->success(['message' => 'The data deleted successfully']);
        } else {
            return $this->success(['message' => 'This action is unauthorized']);
        }


    }


}
