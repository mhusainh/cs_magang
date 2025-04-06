<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $userService) {}

    public function index()
    {
        $card = $this->userService->cardUser(Auth::user()->id);

        if (!$card['success']) {
            return $this->error($card['message'], 400, null);
        }
        return $this->success($card['data'], 'Success' , 200);
    }
}
