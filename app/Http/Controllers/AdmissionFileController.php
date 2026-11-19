<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdmissionFileController extends Controller
{
    use AuthorizesRequests;
    /**
     * Force download or preview of an admission file stored on public disk.
     */
    public function download(Admission $admission, string $field)
    {
        // Authorize view via policy (owner or staff)
        $this->authorize('view', $admission);

        $allowed = [
            'report_card', 'form_137', 'birth_certificate', 'good_moral', 'id_picture', 'medical_certificate'
        ];
        if (!in_array($field, $allowed, true)) {
            abort(404);
        }

        $path = $admission->{$field};
        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        // Return a download response from public disk
        return Storage::disk('public')->download($path);
    }
}


