<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class WhatsappPdfController extends Controller
{
    // **THIS METHOD GENERATES & SAVES PDF TO STORAGE**
    public function generateAndStorePdf(): string
    {
        $submissions = Contactsubmission::latest()->get();

        $pdf = Pdf::loadView('whatsapp.submissions-pdf', compact('submissions'));


        $fileName = 'contact-submissions-'.time().'.pdf';
        Storage::disk('public')->put('whatsapp/'.$fileName, $pdf->output());

        return Storage::disk('public')->path('whatsapp/'.$fileName);
    }
}
