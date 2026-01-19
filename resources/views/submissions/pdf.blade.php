<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1 { color: #333; border-bottom: 2px solid #007bff; }
        .submission {
            page-break-inside: avoid;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .submission h3 { margin-top: 0; color: #007bff; }
        .field { margin: 8px 0; }
        .label { font-weight: bold; color: #555; }
        .timestamp { text-align: right; color: #999; font-size: 12px; }
    </style>
</head>
<body>
<h1>📧 Contact Form Submissions Report</h1>
<p><strong>Generated at:</strong> {{ $generatedAt }}</p>
<p><strong>Total Submissions:</strong> {{ count($submissions) }}</p>

<hr>

@forelse ($submissions as $key => $submission)
    <div class="submission">
        <h3>Submission #{{ $key + 1 }}</h3>

        <div class="field">
            <span class="label">Name:</span> {{ $submission->name }}
        </div>

        <div class="field">
            <span class="label">Email:</span> {{ $submission->email }}
        </div>

        @if ($submission->contact_number)
            <div class="field">
                <span class="label">Contact:</span> {{ $submission->contact_number }}
            </div>
        @endif

        <div class="field">
            <span class="label">Subject:</span> {{ $submission->subject }}
        </div>

        <div class="field">
            <span class="label">Message:</span><br>
            {!! nl2br(e($submission->message)) !!}
        </div>

        <div class="timestamp">
            📅 Submitted: {{ $submission->submitted_at->format('Y-m-d H:i:s') }}
        </div>
    </div>
@empty
    <p style="text-align: center; color: #999;">No submissions found.</p>
@endforelse

</body>
</html>
