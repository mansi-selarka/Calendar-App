<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Mail\EventInvitationMail;
use Illuminate\Support\Facades\Mail;

class CalendarController extends Controller
{
    public function sendEventEmail()
    {
        // Event details
        $eventDetails = [
            'summary' => 'Online interview session for Laravel Backend Developer',
            'description' => 'Join with Google Meet: https://meet.google.com/yxr-wfuz-gvt\nLearn more about Meet at: https://support.google.com/a/users/answer/9282720',
            'location' => '',
            'startDate' => '2024-10-16 14:00:00',
            'endDate' => '2024-10-16 15:00:00',
            'timezone' => 'Asia/Kolkata',
            'attendees' => [
                'mansi.adeyetech@gmail.com',
            ],
            'organizer' => 'mansi.adeyetech@gmail.com',
            'googleMeetLink' => 'https://meet.google.com/yxr-wfuz-gvt',
        ];

        // Send the email with the event invitation
        Mail::to('mansi.adeyetech@gmail.com') // Add the recipient email address here
            ->send(new EventInvitationMail($eventDetails));

        return 'Event invitation sent!';
    }
}
