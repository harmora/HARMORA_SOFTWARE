<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use PDF; // If using the dompdf library for PDF generation

class FactureController extends Controller
{
    /**
     * Show the invoice details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Retrieve the facture by ID
        $facture = Facture::with(['client', 'items'])->findOrFail($id);

        // Load the view to display the facture
        return view('factures.show', compact('facture'));
    }

    /**
     * Generate and download the invoice PDF.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        // Retrieve the facture by ID
        $facture = Facture::with(['client', 'items'])->findOrFail($id);

        // Load the view to generate the PDF
        $pdf = PDF::loadView('factures.pdf', compact('facture'));

        // Download the PDF file
        return $pdf->download('facture_' . $facture->id . '.pdf');
    }
}
