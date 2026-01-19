<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Submissions Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #25D366;
            border-bottom: 2px solid #25D366;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th {
            background-color: #25D366;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 11px;
        }
    </style>
</head>
<body>
<h1>Contact Submissions Report</h1>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Contact #</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Submitted</th>
        <th>Read?</th>
    </tr>
    </thead>
    <tbody>
    @forelse($submissions as $submission)
        <tr>
            <td>{{ $submission->id }}</td>
            <td>{{ $submission->name ?? 'N/A' }}</td>
            <td>{{ $submission->email ?? 'N/A' }}</td>
            <td>{{ $submission->contact_number ?? 'N/A' }}</td>
            <td>{{ $submission->subject ?? 'N/A' }}</td>
            <td>{{ $submission->message ?? 'N/A' }}</td>
            <td>{{ $submission->submitted_at ?? $submission->created_at ?? 'N/A' }}</td>
            <td>{{ $submission->is_read ? 'Yes' : 'No' }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="8" style="text-align: center; color: #999;">No submissions yet</td>
        </tr>
    @endforelse
    </tbody>

</table>

<div class="footer">
    <p>Generated on {{ now()->format('Y-m-d H:i:s') }}</p>
</div>
</body>
</html>
