<?php

namespace App\Http\Controllers;

use App\Models\Masyarakat;
use App\Models\Petugas;
use App\Mail\OtpMail;
use App\Mail\VerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    // Send verification email
    public function sendVerificationEmail(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'User tidak ditemukan'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah terverifikasi'], 200);
        }

        // Generate verification token
        $token = Str::random(64);
        $user->verification_token = $token;
        $user->save();

        // Create verification URL
        $verificationUrl = url('/verify-email/' . $token);

        // Send email
        Mail::to($user->email)->send(new VerificationMail($verificationUrl, $user->nama));

        return response()->json(['message' => 'Email verifikasi telah dikirim'], 200);
    }

    // Verify email with token
    public function verifyEmail($token)
    {
        // Check in Masyarakat
        $user = Masyarakat::where('verification_token', $token)->first();
        
        if (!$user) {
            // Check in Petugas
            $user = Petugas::where('verification_token', $token)->first();
        }

        if (!$user) {
            return redirect('/')->with('error', 'Token verifikasi tidak valid');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/')->with('info', 'Email sudah terverifikasi sebelumnya');
        }

        $user->email_verified_at = now();
        $user->verification_token = null;
        $user->save();

        return redirect('/')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    // Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'required|in:masyarakat,petugas'
        ]);

        $model = $request->type === 'masyarakat' ? Masyarakat::class : Petugas::class;
        $user = $model::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email tidak ditemukan'], 404);
        }

        // Generate OTP
        $otp = $user->generateOtp();

        // Send OTP via email
        try {
            Mail::to($user->email)->send(new OtpMail($user, $otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengirim OTP'], 500);
        }

        return response()->json([
            'message' => 'Kode OTP telah dikirim ke email Anda',
            'expires_at' => $user->otp_expires_at
        ], 200);
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
            'type' => 'required|in:masyarakat,petugas'
        ]);

        $model = $request->type === 'masyarakat' ? Masyarakat::class : Petugas::class;
        $user = $model::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['error' => 'Email tidak ditemukan'], 404);
        }

        if (!$user->isOtpValid($request->otp)) {
            return response()->json(['error' => 'Kode OTP tidak valid atau sudah kadaluarsa'], 400);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json(['message' => 'OTP berhasil diverifikasi'], 200);
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        return $this->sendOtp($request);
    }
}
