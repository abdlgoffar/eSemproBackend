<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalPdfUploadRequest;
use App\Http\Resources\ProposalPdfResponse;
use App\Models\Proposal;
use App\Models\ProposalPdf;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $proposalPdf->path = 'public/PDF/Proposals';
        $proposalPdf->proposal_id = $proposal->id;
        $proposalPdf->save();

        return (new ProposalPdfResponse($proposalPdf))->response()->setStatusCode(201);
  
    }
}