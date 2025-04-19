<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('profile.index');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);
        
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }
    
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak valid.']);
        }
        
        $user->password = Hash::make($request->password);
        $user->save();
        
        return redirect()->route('profile.index')->with('success', 'Password berhasil diperbarui.');
    }
    
    public function updateStudent(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->student) {
            abort(403);
        }
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);
        
        $student = $user->student;
        $student->full_name = $request->full_name;
        $student->phone_number = $request->phone_number;
        $student->date_of_birth = $request->date_of_birth;
        $student->address = $request->address;
        
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($student->profile_photo) {
                Storage::delete('public/' . $student->profile_photo);
            }
            
            $path = $request->file('profile_photo')->store('student-photos', 'public');
            $student->profile_photo = $path;
        }
        
        $student->save();
        
        return redirect()->route('profile.index')->with('success', 'Profil siswa berhasil diperbarui.');
    }
    
    public function updateTeacher(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->teacher) {
            abort(403);
        }
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'profile_photo' => 'nullable|image|max:2048',
        ]);
        
        $teacher = $user->teacher;
        $teacher->full_name = $request->full_name;
        $teacher->phone_number = $request->phone_number;
        $teacher->address = $request->address;
        
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($teacher->profile_photo) {
                Storage::delete('public/' . $teacher->profile_photo);
            }
            
            $path = $request->file('profile_photo')->store('teacher-photos', 'public');
            $teacher->profile_photo = $path;
        }
        
        $teacher->save();
        
        return redirect()->route('profile.index')->with('success', 'Profil guru berhasil diperbarui.');
    }
}