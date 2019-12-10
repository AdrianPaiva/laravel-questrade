<?php

namespace App\Http\Controllers\Api;


use App\Http\Resources\QuestradeCredentialCollection;
use App\Services\QuestradeCredentialService;

/**
 * @resource QuestradeSync
 */
class QuestradeSyncController extends BaseApiController
{
    /**
     * 
     * @return \Illuminate\Http\Response
     */
    public function __invoke(QuestradeCredentialService $questrade_credential_service)
    {
        $questrade_credentials = $questrade_credential_service->getCurrent();

        return $this->sendResponse(new QuestradeCredentialCollection($questrade_credentials));
    }
}
