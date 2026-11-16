<h2>Hello {{ $incident->creator->name }},</h2>

<p>The status of your incident <strong>"{{ $incident->title }}"</strong> has changed.</p>

<p>
    <strong>Previous status:</strong> {{ ucfirst($oldStatus) }} <br>
    <strong>New status:</strong> {{ ucfirst($newStatus) }}
</p>

<p>{{ $incident->description }}</p>

<p>
    <a href="{{ url('/incidents/' . $incident->id) }}" style="
        display: inline-block;
        padding: 10px 15px;
        background-color: #0d6efd;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 10px;
    ">View Incident</a>
</p>

<p>Thanks,<br>Your Support Team</p>
