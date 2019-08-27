<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class NotificationController extends Controller
{
  public function get()
  {
    $user = User::find(1);
    return response()->json($user->unreadNotifications);
  }
}
