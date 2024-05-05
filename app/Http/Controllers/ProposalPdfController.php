<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalPdfUploadRequest;
use App\Http\Resources\ProposalPdfResponse;
use App\Models\Proposal;
use App\Models\ProposalPdf;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProposalPdfController extends Controller
{
    private function getProposalToFK(int $proposal_id): Proposal
    {
        $proposal = Proposal::where('id', $proposal_id)->first();
        if (!$proposal) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "proposal data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        return $proposal;
    }
    
    public function upload(?int $proposal_id, ProposalPdfUploadRequest $request): JsonResponse
    {
        $file = $request->file('proposal_doc');
        $name = time() . $file->getClientOriginalName();
        
        $proposal = $this->getProposalToFK($proposal_id);

        $file->storeAs('public/PDF/Proposals', $name);

        $proposalPdf = new ProposalPdf();
        $proposalPdf->name = $name;
        $proposalPdf->original_name = $file->getClientOriginalName();
        $proposalPdf->path = 'public/PDF/Proposals';
        $proposalPdf->proposal_id = $proposal->id;
        $proposalPdf->save();

        return (new ProposalPdfResponse($proposalPdf))->response()->setStatusCode(201);
  
    }

    public function download($proposal_id): StreamedResponse
    {

        $proposal = Proposal::where("id", $proposal_id)->first();

        if (!$proposal) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "proposal data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }
        
        
        $proposalPdf = $proposal->proposalPdf;
  
        
        return Storage::download($proposalPdf->path . "/{$proposalPdf->name}", "this is default name parameters", ['Content-Type: application/pdf']);
    }
    
    public function delete(?int $proposal_pdf_id): JsonResponse
    {
        
        $proposalPdf = ProposalPdf::where('id', $proposal_pdf_id)->first();

        if (!$proposalPdf) {
            throw new HttpResponseException(response()->json([
                'errors' => [
                    "messages" => [
                        "proposal pdf file data not found"
                    ]
                ]
            ])->setStatusCode(404));
        }

        Storage::delete($proposalPdf->path."/".$proposalPdf->name);

        $proposalPdf->delete();

        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}