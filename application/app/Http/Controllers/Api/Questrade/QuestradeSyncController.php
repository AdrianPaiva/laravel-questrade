<?php

namespace App\Http\Controllers\Api\Questrade;

use App\Models\QuestradeCredential;
use App\Services\Questrade\QuestradeCredentialService;
use Illuminate\Http\Request;
use App\Services\External\QuestradeService;

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
