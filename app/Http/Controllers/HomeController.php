<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;


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

    public function progressPayment()
    {
        $result = $this->userService->progressPayment(Auth::user()->id);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
}
