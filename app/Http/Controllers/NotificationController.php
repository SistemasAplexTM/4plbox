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

  public function viewedAll()
  {
    $user = User::find(1);
    foreach ($user->unreadNotifications as $notification) {
      $notification->markAsRead();
    }
    return response()->json($user->unreadNotifications);
  }

  public function cant()
  {
    $user = User::find(1);
    return response()->json(count($user->unreadNotifications));
  }

}
