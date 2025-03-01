<!DOCTYPE html>
<html>
<head>
    <title>Event Invitation</title>
</head>
<body>
    <h2>You're Invited to an Event</h2>
    <p>You have been invited to the following event:</p>
    <ul>
        <li><strong>Summary:</strong> {{ $eventDetails['summary'] }}</li>
        <li><strong>Description:</strong> {{ $eventDetails['description'] }}</li>
        <li><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($eventDetails['startDate'])->toFormattedDateString() }} at {{ \Carbon\Carbon::parse($eventDetails['startDate'])->toTimeString() }}</li>
        <li><strong>End Time:</strong> {{ \Carbon\Carbon::parse($eventDetails['endDate'])->toFormattedDateString() }} at {{ \Carbon\Carbon::parse($eventDetails['endDate'])->toTimeString() }}</li>
        <li><strong>Location:</strong> {{ $eventDetails['location'] ?: 'Online' }}</li>
    </ul>
    <p>Please find the attached calendar invitation for the event.</p>
</body>
</html>
