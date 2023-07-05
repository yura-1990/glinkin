<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleTypeEnum;
use App\Models\User;
use App\OpenApi\Parameters\UserParameters;
use App\OpenApi\Parameters\VerifySMSCodeParameters;
use App\OpenApi\RequestBodies\SMSSenderRequestBody;
use App\OpenApi\RequestBodies\UserRequestBody;
use App\OpenApi\Responses\SMSSenderResponse;
use App\OpenApi\Responses\UserResponse;
use App\OpenApi\Responses\VerifySMSCodeResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Psr\Http\Client\ClientExceptionInterface;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use Vyuldashev\LaravelOpenApi\Attributes\Operation;
use Vyuldashev\LaravelOpenApi\Attributes\Parameters;
use Vyuldashev\LaravelOpenApi\Attributes\PathItem;
use Vyuldashev\LaravelOpenApi\Attributes\RequestBody;
use Vyuldashev\LaravelOpenApi\Attributes\Response;

#[Prefix('oauth')]
#[PathItem]
class OAuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Client\Exception\Exception
     * @throws ClientExceptionInterface
     */
    #[Post('/send-sms-code-to-phone')]
    #[Operation(tags: ['OAuth'], method: 'Post')]
    #[RequestBody(factory: SMSSenderRequestBody::class)]
    #[Response(factory: SMSSenderResponse::class)]
    public function sendSMS(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone' => 'required|string'
        ]);

        $basic  = new Basic(env('VONAGE_USER_ID'), env('VONAGE_USER_SECRET'));
        $client = new Client($basic);

        $code = Str::random(6);

        $response = $client->sms()->send(
            new SMS($data['phone'], env('APP_NAME'), "$code")
        );

        $message = $response->current();

        if ($message->getStatus() == 0) {
            $user = User::query()->updateOrCreate(
                ['phone' => $data['phone']],
                [
                    'phone_verification_code' => $code,
                    'role_id' => UserRoleTypeEnum::USER->value,
                ]
            );

            Cache::put($code, $user->id, now()->addMinutes(3600));

            return $this->success('Send SMS Code to Phone Successfully');
        } else {

            return $this->error([], "The message failed with status: " . $message->getStatus());
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Post('/verify-phone-SMS-code')]
    #[Operation(tags: ['OAuth'], method: 'POST')]
    #[Parameters(factory: VerifySMSCodeParameters::class)]
    #[Response(factory: VerifySMSCodeResponse::class)]
    public function verifyPhoneSMSCode(Request $request): JsonResponse
    {
        $code = $request->query('code');

        if (Cache::has($code)){
            $code = Cache::get($code);

            if ($code){
                return $this->success(['status' => true, 'userId' => $code]);
            }
        }


        return $this->error([], 'code incorrect or expired');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return JsonResponse
     */
    #[Post('/authenticate/{user}')]
    #[Operation(tags: ['OAuth'], method: 'POST')]
    #[Parameters(factory: UserParameters::class)]
    #[RequestBody(factory: UserRequestBody::class)]
    #[Response(factory: UserResponse::class)]
    public function authenticate(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $user->update([
            'name' => $data['name'],
            'password' => Hash::make($data['password'])
        ]);

        $token = Auth::login($user);

        return $this->success([
            'user' => $user,
            'token' => $token
        ]);
    }


}
