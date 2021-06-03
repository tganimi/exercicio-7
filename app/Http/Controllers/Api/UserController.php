<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *      path="/users",
     *      tags={"/users"},
     *      summary="Get list of users",
     *      description="get all users from users.txt file",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=400, description="Bad request"),
     *       security={
     *           {"api_key_security_example": {}}
     *       }
     * )
     *
     * Returns list of users
     */
    public function index()
    {
        $users = $this->userService->getUsers();
        return $users;
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     tags={"/users"},
     *     summary="Store a new user",
     *     operationId="storeUser",
     *     description="store a new user on users.txt file",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="last_name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="phone_number", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="New user created"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $user = $this->userService->insertUser($input);
        return Response::json(['msg' => $user], 201);
    }

    /**
     * @OA\Put(
     *     path="/users/{email}",
     *     tags={"/users"},
     *     summary="Update the specified user",
     *     operationId="updateUser",
     *     description="update the specified user on users.txt file",
     *     @OA\Parameter(
     *          description="User email",
     *          in="path",
     *          name="email",
     *          required=true,
     *          @OA\Schema(
     *              type="string"
     *          )
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="last_name", type="string"),
     *              @OA\Property(property="email", type="string"),
     *              @OA\Property(property="phone_number", type="string")
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     * @param Request $request
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $email)
    {
        $input = $request->all();
        $user = $this->userService->updateUser($input, $email);

        if (!empty($user)) {
            return Response::json($user, 200);
        } else {
            return Response::json(['msg' => 'User not found'], 404);
        }

    }

    /**
     * @OA\Delete(
     *     path="/users/{email}",
     *     tags={"/users"},
     *     summary="Remove the specified user",
     *     operationId="deleteUser",
     *     description="remove the specified user on users.txt file",
     *     @OA\Parameter(
     *          description="User email",
     *          in="path",
     *          name="email",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
     *          )
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="User deleted"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($email)
    {
        $this->userService->destroyUser($email);
        return Response::json(null,204);
    }
}
