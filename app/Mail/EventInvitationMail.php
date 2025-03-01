<?php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon; 

class EventInvitationMail extends Mailable
{
    public $eventDetails;

    public function __construct($eventDetails)
    {
        $this->eventDetails = $eventDetails;
    }

    public function build()
    {
        // Generate the ICS file content
        $icsContent = $this->generateIcs($this->eventDetails);

        // Save it to a temporary location
        $icsPath = storage_path('app/event.ics');
        file_put_contents($icsPath, $icsContent);

        // Build the email with the ICS file attached
        return $this->subject('Event Invitation')
                    ->view('emails.event_invitation') // The view for the email body
                    ->attach($icsPath, [
                        'as' => 'event.ics', 
                        'mime' => 'text/calendar'
                    ]);
    }

    private function generateIcs($eventDetails)
    {
        $startDate = Carbon::parse($eventDetails['startDate'])->format('Ymd\THis\Z');
        $endDate = Carbon::parse($eventDetails['endDate'])->format('Ymd\THis\Z');
        $dtStamp = Carbon::now()->format('Ymd\THis\Z');
        $createdDate = Carbon::now()->format('Ymd\THis\Z');

        $attendees = '';
        foreach ($eventDetails['attendees'] as $attendee) {
            $attendees .= "ATTENDEE;CUTYPE=INDIVIDUAL;ROLE=REQ-PARTICIPANT;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN={$attendee}:mailto:{$attendee}\n";
        }

        $icsContent = "BEGIN:VCALENDAR\n";
        $icsContent .= "PRODID:-//Google Inc//Google Calendar 70.9054//EN\n";
        $icsContent .= "VERSION:2.0\n";
        $icsContent .= "CALSCALE:GREGORIAN\n";
        $icsContent .= "METHOD:REQUEST\n";
        $icsContent .= "BEGIN:VTIMEZONE\n";
        $icsContent .= "TZID:{$eventDetails['timezone']}\n";
        $icsContent .= "X-LIC-LOCATION:{$eventDetails['timezone']}\n";
        $icsContent .= "BEGIN:STANDARD\n";
        $icsContent .= "TZOFFSETFROM:+0530\n";
        $icsContent .= "TZOFFSETTO:+0530\n";
        $icsContent .= "TZNAME:GMT+5:30\n";
        $icsContent .= "DTSTART:19700101T000000\n";
        $icsContent .= "END:STANDARD\n";
        $icsContent .= "END:VTIMEZONE\n";
        $icsContent .= "BEGIN:VEVENT\n";
        $icsContent .= "DTSTART;TZID={$eventDetails['timezone']}:{$startDate}\n";
        $icsContent .= "DTEND;TZID={$eventDetails['timezone']}:{$endDate}\n";
        $icsContent .= "DTSTAMP:{$dtStamp}\n";
        $icsContent .= "ORGANIZER;CN={$eventDetails['organizer']}:mailto:{$eventDetails['organizer']}\n";
        $icsContent .= "UID:" . uniqid('event-', true) . "@example.com\n"; 
        $icsContent .= $attendees;
        $icsContent .= "X-GOOGLE-CONFERENCE:{$eventDetails['googleMeetLink']}\n";
        $icsContent .= "X-MICROSOFT-CDO-OWNERAPPTID:" . rand(1000000000, 9999999999) . "\n";
        $icsContent .= "CLASS:PUBLIC\n";
        $icsContent .= "CREATED:{$createdDate}\n";
        $icsContent .= "DESCRIPTION:{$eventDetails['description']}\n";
        $icsContent .= "LAST-MODIFIED:{$createdDate}\n";
        $icsContent .= "LOCATION:{$eventDetails['location']}\n";
        $icsContent .= "SEQUENCE:0\n";
        $icsContent .= "STATUS:CONFIRMED\n";
        $icsContent .= "SUMMARY:{$eventDetails['summary']}\n";
        $icsContent .= "TRANSP:TRANSPARENT\n";
        $icsContent .= "END:VEVENT\n";
        $icsContent .= "END:VCALENDAR";

        return $icsContent;
    }
}
