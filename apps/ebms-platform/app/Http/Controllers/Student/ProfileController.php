<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        return view('student.profile', [
            'student' => Auth::guard('student')->user(),
        ]);
    }

    public function uploadPhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,jpg,png|max:200',
        ]);

        $student = Auth::guard('student')->user();
        $path    = 'students/photos/' . $student->hall_ticket . '.jpg';

        Storage::disk('public')->putFileAs(
            'students/photos',
            $request->file('photo'),
            $student->hall_ticket . '.jpg'
        );

        $student->update(['photo_path' => $path]);

        return redirect()->route('student.profile')->with('success', 'Photo uploaded successfully.');
    }

    public function uploadSignature(Request $request): RedirectResponse
    {
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,jpg,png|max:100',
        ]);

        $student = Auth::guard('student')->user();
        $path    = 'students/signatures/' . $student->hall_ticket . '.jpg';

        Storage::disk('public')->putFileAs(
            'students/signatures',
            $request->file('signature'),
            $student->hall_ticket . '.jpg'
        );

        $student->update(['signature_path' => $path]);

        return redirect()->route('student.profile')->with('success', 'Signature uploaded successfully.');
    }
}
