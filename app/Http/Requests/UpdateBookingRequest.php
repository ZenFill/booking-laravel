<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $booking = $this->route('booking'); // Ambil booking dari route binding

        // Izinkan jika user adalah Admin ATAU pemilik booking
        return Auth::user()->isAdmin() || $booking->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_type' => 'required|string',
            'booking_date' => 'required|date',
            'notes' => 'nullable|string',
        ];
    }
}
