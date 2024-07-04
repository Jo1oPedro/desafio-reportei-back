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
     *                 @OA\Property(property="avatar_url", type="string", example="#sdasd$ssdaAA@"),
     *                 @OA\Property(property="html_url", type="string", example="#sdasd$ssdaAA@"),
     *                 @OA\Property(property="access_token", type="string", example="#sdasd$ssdaAA@"),
     *              )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token generated",
     *     @OA\JsonContent(
     *         @OA\Property(
     *           property="token_type",
     *           type="string",
     *           enum={"bearer"},
     *           description="The type of token"
     *          ),
     *          @OA\Property(
     *            property="expires_in",
     *            type="integer",
     *            description="The expiration time of the token in minutes"
     *          ),
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
        return $this->user_service->create(
            new UserDTO(...$request->validated())
        );
    }
}
