@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2>Contact Submissions</h2>

        <!-- Date Filter Form -->
        <form method="GET" action="{{ route('submissions.view') }}" class="row mb-4">
            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3 pt-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('submissions.view') }}" class="btn btn-secondary">Clear</a>
            </div>
        </form>

        <!-- Download PDF Button -->
        <div class="mb-3">
            <form method="GET" action="{{ route('submissions.download-pdf') }}" class="d-inline">
                <input type="hidden" name="from_date" value="{{ request('from_date') }}">
                <input type="hidden" name="to_date" value="{{ request('to_date') }}">
                <button type="submit" class="btn btn-success">📥 Download as PDF</button>
            </form>
        </div>

        <!-- Submissions Table -->
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Subject</th>
                <th>Message</th>
                <th>Submitted At</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($submissions as $submission)
                <tr>
                    <td>{{ $submission->id }}</td>
                    <td>{{ $submission->name }}</td>
                    <td>{{ $submission->email }}</td>
                    <td>{{ $submission->contact_number }}</td>
                    <td>{{ $submission->subject }}</td>
                    <td>{{ Str::limit($submission->message, 50) }}</td>
                    <td>{{ $submission->submitted_at->format('Y-m-d H:i:s') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No submissions found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
