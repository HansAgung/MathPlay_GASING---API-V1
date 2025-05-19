<?php

namespace App\Http\Controllers\Api\test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LeaderboardMockController extends Controller
{
    public function leaderboard()
    {
        return response()->json([
            "leaderboard" => [
                ["username" => "aisyah_maulana", "points" => rand(60, 280)],
                ["username" => "bima_rahman", "points" => rand(60, 280)],
                ["username" => "cindy.putri", "points" => rand(60, 280)],
                ["username" => "daniel_aditya", "points" => rand(60, 280)],
                ["username" => "elisa.nur", "points" => rand(60, 280)],
                ["username" => "farhan_syah", "points" => rand(60, 280)],
                ["username" => "gita_amel", "points" => rand(60, 280)],
                ["username" => "hendra.k", "points" => rand(60, 280)],
                ["username" => "intan_maya", "points" => rand(60, 280)],
                ["username" => "joko_susanto", "points" => rand(60, 280)]
            ]
        ]);
    }
}
