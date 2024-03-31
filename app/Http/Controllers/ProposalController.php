<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalCreateRequest;
use App\Http\Resources\ProposalResponse;
use App\Models\Proposal;
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
}