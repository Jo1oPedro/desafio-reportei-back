<?php

namespace App\Http\Controllers\Api;

use App\DTO\UserDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;

/**
 * @OA\Info(
 *   title="API GithubAnalyzer Swagger Documentation",
 *   version="1.0",
 *   contact={
 *     "email": "joao.pedreira@estudante.ufjf.br"
 *   }
 * )
 * @OA\SecurityScheme(
 *  type="http",
 *  description="Acess token obtido na autenticação",
 *  name="Authorization",
 *  in="header",
 *  scheme="bearer",
 *  bearerFormat="JWT",
 *  securityScheme="bearerAuth"
 * )
 */
class RegisterController extends Controller
{
    public function __construct(
        private UserService $user_service
    ) {}

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"User"},
     *     summary="Register a new user",
     *     description="This endpoint register a new user and returns his autentication code",
     *     operationId="register",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name","github_login","github_id","avatar_url","access_token"},
     *                 @OA\Property(property="name", type="string", example="gabriel"),
     *                 @OA\Property(property="email", type="string", example="gabriel_nunes@example.org"),
     *                 @OA\Property(property="github_login", type="string", example="#sdasd$ssdaAA@"),
     *                 @OA\Property(property="github_id", type="string", example="#sdasd$ssdaAA@"),
     *                 @OA\Property(property="avatar_url", type="string", example="#sdasd$ssdaAA@"),
     *                 @OA\Property(property="access_token", type="string", example="#sdasd$ssdaAA@", description="Token got from the github api"),
     *              )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token generated",
     *     @OA\JsonContent(
     *         @OA\Property(
     *           property="token",
     *           type="string",
     *           example="112124alsglasg",
     *           description="The type of token"
     *          ),
     *          @OA\Property(
     *                  property="user",
     *                  type="object",
     *                  @OA\Property(property="github_id", type="string", example="#sdasd$ssdaAA@"),
     *                  @OA\Property(property="name", type="string", example="gabriel"),
     *                  @OA\Property(property="email", type="string", example="gabriel_nunes@example.org"),
     *                  @OA\Property(property="avatar_url", type="string", example="#sdasd$ssdaAA@"),
     *                  @OA\Property(property="github_login", type="string", example="#sdasd$ssdaAA@"),
     *                  @OA\Property(property="access_token", type="string", example="#sdasd$ssdaAA@"),
     *                  @OA\Property(property="updated_at", type="string", example="2024-07-08T21:53:43.000000Z"),
     *                  @OA\Property(property="created_at", type="string", example="2024-07-08T21:53:43.000000Z"),
     *                  @OA\Property(property="id", type="integer", example=1)
     *              )
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
        $user = $this->user_service->create(
            new UserDTO(...$request->validated())
        );

        $token = $user->createToken(env('SECRET'))->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }
}
