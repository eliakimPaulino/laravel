<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validateUser = Validator::make(
                $request->all(),
                [
                    'avatar' => 'required',
                    'type' => 'required',
                    'open_id' => 'required',
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'required|min:6'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $validated = $validateUser->validated();

            $map = [];
            $map['type'] = $validated['type'];
            $map['open_id'] = $validated['open_id'];

            $user = User::where($map)->first();

            if (empty($user->id)) {

                $validated['token']  = md5(uniqid(mt_rand(10000, 99999)));
                $validated['created_at'] = Carbon::now();

                // Insira os dados fornecidos no array $validated na tabela users e retorne o ID do registro recém-criado.
                $validated['password'] = Hash::make($validated['password']);
                $userID = User::insertGetId($validated);

                // Selecione todos os registros na tabela users onde a coluna id seja igual a $userID.
                $userInfo = User::where('id', '=', $userID)->first();
                // Cria um token único de acesso associado ao usuário em $userInfo e armazena seu valor em texto simples na variável $accessToken.

                $accessToken = $userInfo->createToken(uniqid())->plainTextToken;

                $userInfo->access_token = $accessToken;

                User::where('id', '=', $userID)->update(['access_token' => $accessToken]);

                return response()->json([
                    'code' => 200,
                    'msg' => 'User Created Successfully',
                    'data' => $userInfo
                ], 200);
            }

            // 144. Backend and modify users table part 9 correction:
            $accessToken = $user->createToken(uniqid())->plainTextToken;
            $user->access_token = $accessToken;
            User::where('open_id', '=', $validated['open_id'])->update(['access_token' => $accessToken]);

            return response()->json([
                'code' => 200,
                'msg' => 'User Created In Successfully',
                'data' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */

    public function loginUser(Request $request)
    {
        try {

            // Se o usuário estiver tentando logar com open_id
            if ($request->has('open_id')) {
                $user = User::where('open_id', $request->open_id)->first();

                if (!$user) {
                    return response()->json([
                        'status' => 404,
                        'message' => 'User not found with this Open ID.',
                    ], 404);
                }

                // Gerar um novo token para o usuário
                $accessToken = $user->createToken("API TOKEN")->plainTextToken;
                $user->access_token = $accessToken;
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'User Logged In Successfully',
                    'access_token' => $accessToken,
                    'data' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'avatar' => $user->avatar ?? 'uploads/images/default.png',
                        'access_token' => $accessToken, // Incluindo token nos dados do usuário
                        'type' => $user->type,
                        'online' => $user->online,
                    ]
                ], 200);
            }

            // Se o usuário estiver tentando logar com email e senha

            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => 401,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            $accessToken = $user->createToken("API TOKEN")->plainTextToken;

            return response()->json([
                'status' => 200,
                'message' => 'User Logged In Successfully',
                'access_token' => $accessToken,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'avatar' => $user->avatar ?? 'uploads/images/default.png',
                    'access_token' => $accessToken, // Incluindo token nos dados do usuário
                    'type' => $user->type,
                    'online' => $user->online,
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
