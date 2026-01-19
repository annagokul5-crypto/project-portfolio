<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Twilio\Rest\Client;
use Barryvdh\DomPDF\Facade\Pdf;

class ContactController extends Controller
{
    // Save form submission
    public function submit(Request $request)
    {
        \Log::info('CONTACT FORM', $request->all());
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'contact_number' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $submission = ContactSubmission::create([
            'name'    => $data['name'],
            'email'   => $data['email'],
            'contact_number' => $data['contact_number'] ?? null,
            'subject' => $data['subject'],
            'message' => $data['message'],
            'submitted_at' => now(),
        ]);

        $timestamp = $submission->submitted_at->format('Y-m-d H:i:s');
        $contact   = $submission->contact_number ?? 'Not provided';


        // ===== SEND EMAIL =====
        $emailBody = "New contact submission at {$timestamp}\n\n"
            . "Name: {$submission->name}\n"
            . "Email: {$submission->email}\n"
            . "Contact: {$contact}\n"
            . "Subject: {$submission->subject}\n"
            . "Message:\n{$submission->message}";

        Mail::raw($emailBody, function ($m) {
            $m->to('annagokul5@gmail.com')
                ->subject('New contact form submission');
        });

        // ===== SEND WHATSAPP =====
        $this->sendWhatsAppNotification($submission, $timestamp);

        return back()->with('status', 'Message sent successfully! We\'ll contact you soon.');
    }

    // Send WhatsApp notification
    private function sendWhatsAppNotification($submission, $timestamp)
    {
        try {
            $sid   = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $from  = env('TWILIO_WHATSAPP_NUMBER');
            $to    = env('TWILIO_WHATSAPP_TO'); // use your .env key

            $contact = $submission->contact_number ?? 'Not provided';

            $twilio = new Client($sid, $token);
            $twilio->messages->create($to, [
                'from' => $from,
                'body' => "📧 New Contact Submission\n"
                    . "Time: {$timestamp}\n\n"
                    . "Name: {$submission->name}\n"
                    . "Email: {$submission->email}\n"
                    . "Phone: {$contact}\n"
                    . "Subject: {$submission->subject}\n\n"
                    . "Message:\n{$submission->message}",
            ]);
        } catch (\Exception $e) {
            \Log::error('WhatsApp send failed: ' . $e->getMessage());
        }
    }

    // View all submissions with optional date filter
    public function viewSubmissions(Request $request)
    {
        $query = ContactSubmission::orderBy('submitted_at', 'desc');

        if ($request->filled('from_date')) {
            $query->where('submitted_at', '>=', $request->from_date . ' 00:00:00');
        }

        if ($request->filled('to_date')) {
            $query->where('submitted_at', '<=', $request->to_date . ' 23:59:59');
        }

        $submissions = $query->get();

        return view('submissions.index', compact('submissions'));
    }

    // Download as PDF
    public function downloadPDF(Request $request)
    {
        $query = ContactSubmission::orderBy('submitted_at', 'asc');

        // Filter by date if provided
        if ($request->filled('from_date')) {
            $query->where('submitted_at', '>=', $request->from_date . ' 00:00:00');
        }

        if ($request->filled('to_date')) {
            $query->where('submitted_at', '<=', $request->to_date . ' 23:59:59');
        }

        $submissions = $query->get();
        $generatedAt = now()->format('Y-m-d H:i:s');

        $pdf = Pdf::loadView('submissions.pdf', [
            'submissions' => $submissions,
            'generatedAt' => $generatedAt,
        ]);


        $filename = 'contact-submissions-' . now()->format('Y-m-d-H-i-s') . '.pdf';
        return $pdf->download($filename);
    }
}
