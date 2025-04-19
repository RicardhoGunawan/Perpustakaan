<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberCardController extends Controller
{
    /**
     * Display the member card for public access via QR code scan.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Find the member by ID
        $member = Member::with(['user.student'])->findOrFail($id);
        
        // Get the associated student data
        $student = $member->user->student;
        
        // Check if the member is active
        if (!$member->is_active) {
            return view('member-cards.inactive', compact('member', 'student'));
        }
        
        // Pass the data to the view
        return view('member-cards.show', compact('member', 'student'));
    }
    
    /**
     * Display a digital version of the member card for authenticated users.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function myCard(Request $request)
    {
        // Get the authenticated user's member card
        $user = $request->user();
        $member = $user->member;
        
        if (!$member) {
            return view('member-cards.error', ['message' => 'Anda belum memiliki kartu anggota perpustakaan.']);
        }
        
        $student = $user->student;
        
        return view('member-cards.my-card', compact('member', 'student'));
    }
    
    /**
     * Download the member card as PDF for an authenticated user.
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadCard(Request $request)
    {
        $user = $request->user();
        $member = $user->member;
        
        if (!$member) {
            return redirect()->route('home')->with('error', 'Anda belum memiliki kartu anggota perpustakaan.');
        }
        
        $student = $user->student;
        
        $pdf = PDF::loadView('pdf.member-card', compact('member', 'student'));
        
        // Return the PDF as a download
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'kartu-anggota-' . $student->nis . '.pdf');
    }
}