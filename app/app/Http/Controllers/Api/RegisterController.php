<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\UserRegistered;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"User Register"},
     *     summary="Register a new user",
     *     description="This endpoint register a new user and returns his autentication code",
     *     operationId="register",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","email","password","password_confirmation"},
     *                 @OA\Property(property="name", type="string", example="gabriel", description="User's email address. Must be unique."),
     *                 @OA\Property(property="email", type="string", example="gabriel_nunes@example.org"),
     *                 @OA\Property(property="password", type="string", example="#sdasd$ssdaAA@"),
     *                 @OA\Property(property="password_confirmation", type="string", example="#sdasd$ssdaAA@", description="Confirmation of the password. Must match the password field.")
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token generated",
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="data",
     *             type="object",
     *             required={"token", "token_type", "expires_in"},
     *             @OA\Property(
     *                 property="token",
     *                 type="string",
     *                 description="The access token"
     *             ),
     *             @OA\Property(
     *                 property="token_type",
     *                 type="string",
     *                 enum={"bearer"},
     *                 description="The type of token"
     *             ),
     *             @OA\Property(
     *                 property="expires_in",
     *                 type="integer",
     *                 description="The expiration time of the token in minutes"
     *              ),
     *          )
     *      )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Incorrect credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The provided credentials are incorrect.")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        /** @var User $user */
        $user = User::updateOrCreate(
            ['email' => $data['email']], // Critério de busca, e-mail deve estar aqui
            [
                'name' => $data['name'],
                'user_id' => $data['user_id'],
                'avatar_url' => $data['avatar_url'],
                'html_url' => $data['html_url'],
                "access_token" => $data["access_token"],
            ]
        );
        $token = $user->createToken(env('SECRET'))->plainTextToken;

        Mail::to($user)->queue(new UserRegistered($user->name));

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
