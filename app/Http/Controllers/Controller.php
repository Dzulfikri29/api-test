<?php

namespace App\Http\Controllers;

use App\Notifications\NewNotification;
use App\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Notification;
use App\ActivityLog;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function notifToRole($branch_id, $role, $title, $body, $type, $content, $link)
    {
        $query = User::whereHas('role', function ($q) use ($role) {
            $q->where('name', $role);
        });
        if ($branch_id) {
            $query->where('branch_id', $branch_id);
        }

        $fcm_tokens = $query->pluck('fcm_token')->toArray();

        $tokens = [];
        foreach ($fcm_tokens as $fcm_token) {
            if ($fcm_token) {
                array_push($tokens, $fcm_token);
            }
        }

        $data = [
            "registration_ids" => $tokens,
            "notification" =>
            [
                "title" => $title,
                "body" => $body,
                "icon" => 'https://ska.intivestudio.com/app-assets/images/logo/logo.png',
            ],
            "data" => [
                "type" => $type,
                "content" => $content,
            ],
            "webpush" => [
                "fcm_options" => [
                    "link" => $link,
                ],
            ],
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . config('app.firebase_server_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $result = curl_exec($ch);
        curl_close($ch);

        $notification = [
            'icon' => '',
            'title' => $title,
            'text' => $body,
            'links' => $link,
            'menu' => $type,
        ];
        Notification::send($query->get(), new NewNotification($notification));
        return response()->json(json_encode($result));
    }

    public function notifToSingle($user_id, $title, $body, $type, $content, $link)
    {
        $user = User::find($user_id);

        $data = [
            "to" => $user->fcm_token,
            "notification" =>
            [
                "title" => $title,
                "body" => $body,
                "icon" => 'https://ska.intivestudio.com/app-assets/images/logo/logo.png',
            ],
            "data" => [
                "type" => $type,
                "content" => $content,
            ],
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . config('app.firebase_server_key'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $result = curl_exec($ch);
        curl_close($ch);

        $notification = [
            'icon' => '',
            'title' => $title,
            'text' => $body,
            'links' => $link,
            'menu' => $type,
        ];

        Notification::send($user, new NewNotification($notification));
        return response()->json(json_encode($result));
    }

    public function log($subject_id, $type, $subject_route, $log){
        $activity_log = new ActivityLog();
        $activity_log->user_id = Auth::user()->id;
        $activity_log->subject_id = $subject_id;
        $activity_log->type = $type;
        $activity_log->subject_route = $subject_route;
        $activity_log->log = $log;
        $activity_log->save();
    }
}
