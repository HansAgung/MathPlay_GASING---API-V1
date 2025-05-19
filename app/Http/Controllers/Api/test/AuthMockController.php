<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthMockController extends Controller
{
    public function mockLogin()
    {
        return response()->json([
            "access_token" => "8|G5uSrnPoI6w3uvLjAYudfmWECs4YG2Ky5jHvhwzm27dec071",
            "token_type" => "Bearer",
            "users" => [
                [
                    "id_users" => 1,
                    "lives" => 5,
                    "email" => "atalya11@gmail.com",
                    "password" => 'Atalya12',
                    "profile" => [
                        "username" => "Atalyaaa",
                        "fullname" => "Atalya Saragih",
                        "birth" => "2003-11-08",
                        "gender" => "male",
                        "character_img" => "images/characters/jKxoSSIZV08tKV273cvm9ZxMUCeAfx8eBVH5OBHz.jpg",
                        "user_desc" => "Seorang yang suka mempelajari hal hal baru."
                    ],
                    "is_active" => 1,
                    "created_at" => "2025-04-14T09:22:53.000000Z",
                    "updated_at" => "2025-04-14T09:22:53.000000Z"
                ],
                [
                    "id_users" => 2,
                    "lives" => 3,
                    "email" => "budi23@gmail.com",
                    "password" => 'Budi123',
                    "profile" => [
                        "username" => "Budi23",
                        "fullname" => "Budi Santoso",
                        "birth" => "2004-07-15",
                        "gender" => "male",
                        "character_img" => "images/characters/budi_char.jpg",
                        "user_desc" => "Suka tantangan dan berhitung cepat."
                    ],
                    "is_active" => 1,
                    "created_at" => "2025-04-18T12:45:00.000000Z",
                    "updated_at" => "2025-04-18T12:45:00.000000Z"
                ]
            ]
        ]);
    }
}
