<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvitationPdfUploadRequest;
use App\Http\Resources\InvitationPdfResponse;
use App\Models\Invitation;
use App\Models\InvitationPdf;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvitationPdfController extends Controller
{
    private function getInvitationToFK(int $invitation_id): Invitation
    {
        $invitation = Invitation::where('id', $invitation_id)->first();
        if (!$invitation) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "invitation data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $invitation;
    }
    
    public function upload(?int $invitation_id, InvitationPdfUploadRequest $request): JsonResponse
    {
        $file = $request->file('invitation_doc');
        $name = time() . $file->getClientOriginalName();
        
        $proposal = $this->getInvitationToFK($invitation_id);

        $file->storeAs('public/PDF/Invitations', $name);

        $invitationPdf = new InvitationPdf();
        $invitationPdf->name = $name;
        $invitationPdf->original_name = $file->getClientOriginalName();
        $invitationPdf->path = 'public/PDF/Invitations';
        $invitationPdf->invitation_id = $proposal->id;
        $invitationPdf->save();

        return (new InvitationPdfResponse($invitationPdf))->response()->setStatusCode(201);
  
    }

    public function download($invitation_id): StreamedResponse
    {

        $invitation = Invitation::where("id", $invitation_id)->first();

        if (!$invitation) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "invitation data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        
        
        $invitationPdf = $invitation->invitationPdf;
  
        
        return Storage::download($invitationPdf->path . "/{$invitationPdf->name}", "this is default name parameters", ['Content-Type: application/pdf']);
    }
    
    public function delete(?int $invitation_pdf_id): JsonResponse
    {
        
        $invitationPdf = InvitationPdf::where('id', $invitation_pdf_id)->first();

        if (!$invitationPdf) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "invitation pdf file data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        Storage::delete($invitationPdf->path."/".$invitationPdf->name);

        $invitationPdf->delete();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}