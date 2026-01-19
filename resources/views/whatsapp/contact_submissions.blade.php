<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        h1 { color: #333; }
    </style>
</head>
<body>
<h1>📋 Contact Form Submissions</h1>
<p>Generated on: {{ date('Y-m-d H:i:s') }}</p>

@if($submissions->isEmpty())
    <p>No submissions found.</p>
@else
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Subject</th>
            <th>Message</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($submissions as $sub)
            <tr>
                <td>{{ $sub->id }}</td>
                <td>{{ $sub->name }}</td>
                <td>{{ $sub->email }}</td>
                <td>{{ $sub->subject }}</td>
                <td>{{ substr($sub->message, 0, 50) }}...</td>
                <td>{{ $sub->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif
</body>
</html>
