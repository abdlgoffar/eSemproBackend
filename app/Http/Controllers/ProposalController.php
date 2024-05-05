<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalCreateRequest;
use App\Http\Resources\ProposalResponse;
use App\Models\Proposal;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function create(ProposalCreateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $proposal = new Proposal($data);
        $proposal->save();

        return (new ProposalResponse($proposal))->response()->setStatusCode(201);
    }

    
    public function getProposalPdfFile(int $proposal_id) 
    {
        $proposal = Proposal::find($proposal_id);
        $proposalPdf = $proposal->proposalPdf;

        return $proposalPdf;
    }
    
    
    public function delete(int $proposal_id): JsonResponse {

        if (!Proposal::where('id', $proposal_id)->first()) {
        
            throw new HttpResponseException(response([
              
                'errors' => [
                    "messages" => [
                        "proposal data not found"
                    ]
                ]
            ], 404));
        }

        Proposal::find($proposal_id)->delete();
        
        return response()->json([
            "data" => true
        ])->setStatusCode(200);
    }
}