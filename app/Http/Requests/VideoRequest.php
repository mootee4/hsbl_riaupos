<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VideoRequest extends FormRequest
{
    /**
     * Tentukan apakah user berwenang melakukan request ini.
     */
    public function authorize(): bool
    {
        // Izinkan hanya jika user login dan memiliki role 'admin'
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Aturan validasi untuk input video.
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',

            'youtube_link' => [
                'required',
                'url',
                // Diterima: youtube.com/watch?v=xxx, youtu.be/xxx, dst
                'regex:/^(https?\:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/i',
            ],

            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // Naik ke 5MB, dukung webp

            'description' => 'nullable|string',

            'status' => 'required|in:view,draft',

            'type' => 'required|in:video,live',
        ];
    }

    /**
     * Pesan error validasi kustom.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul video wajib diisi.',

            'youtube_link.required' => 'Link YouTube wajib diisi.',
            'youtube_link.url' => 'Link YouTube harus berupa URL yang valid.',
            'youtube_link.regex' => 'Link YouTube harus berasal dari youtube.com atau youtu.be.',

            'thumbnail.image' => 'Thumbnail harus berupa gambar.',
            'thumbnail.mimes' => 'Thumbnail harus berekstensi jpeg, png, jpg, gif, atau webp.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 5MB.',

            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status hanya boleh bernilai view atau draft.',

            'type.required' => 'Jenis video wajib dipilih.',
            'type.in' => 'Jenis hanya boleh bernilai video atau live.',
        ];
    }
}
