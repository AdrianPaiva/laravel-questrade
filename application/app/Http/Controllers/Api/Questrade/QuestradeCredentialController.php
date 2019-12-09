<?php

namespace App\Http\Controllers\Api\Questrade;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Questrade\CreateQuestradeCredentialRequest;
use App\Http\Requests\Questrade\UpdateQuestradeCredentialRequest;
use App\Http\Resources\Questrade\QuestradeCredentialResource;
use App\Http\Resources\Questrade\QuestradeCredentialCollection;
use App\Models\Questrade\QuestradeCredential;
use App\Services\Questrade\QuestradeCredentialService;
use Illuminate\Http\Request;

/**
 * @resource QuestradeCredential
 */
class QuestradeCredentialController extends BaseApiController
{
    private $questrade_credential_service;

    public function __construct(QuestradeCredentialService $questrade_credential_service)
    {
        $this->questrade_credential_service = $questrade_credential_service;
    }

    /**
     * Get all QuestradeCredentials for the current Domain
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questrade_credentials = $this->questrade_credential_service->all($this->getEagerLoads());

        return $this->sendResponse(new QuestradeCredentialCollection($questrade_credentials));
    }

    /**
     * Create a new QuestradeCredential
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateQuestradeCredentialRequest $request)
    {
        $data = $request->all();

        $questrade_credential = $this->questrade_credential_service->create($data);

        return $this->sendResponse(new QuestradeCredentialResource($questrade_credential), 'QuestradeCredential created!', 201);
    }

    /**
     * Display the specified QuestradeCredential
     *
     * @param  QuestradeCredential
     * @return \Illuminate\Http\Response
     */
    public function show(QuestradeCredential $questrade_credential)
    {
        $this->authorize('view', $questrade_credential);

        $questrade_credential->load($this->getEagerLoads());

        return $this->sendResponse(new QuestradeCredentialResource($questrade_credential));
    }

    /**
     * Update the specified QuestradeCredential
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  QuestradeCredential $questrade_credential
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuestradeCredentialRequest $request, QuestradeCredential $questrade_credential)
    {
        $data = $request->all();
        $questrade_credential = $this->questrade_credential_service->update($questrade_credential, $data);

        return $this->sendResponse(new QuestradeCredentialResource($questrade_credential), 'QuestradeCredential updated!');
    }

    /**
     * Remove the specified QuestradeCredential from storage.
     *
     * @param  QuestradeCredential $questrade_credential
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestradeCredential $questrade_credential)
    {
        $this->authorize('delete', $questrade_credential);

        if ($this->questrade_credential_service->delete($questrade_credential)) {
            return $this->sendResponse(null, "QuestradeCredential deleted successfully", 204);
        }
        
        return $this->sendError('Unable to delete this QuestradeCredential', [], 500);
    }

    /**
     * Get QuestradeCredential for the current user
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        $questrade_credential = $this->questrade_credential_service->getCurrent();
        $questrade_credential->load($this->getEagerLoads());

        return $this->sendResponse(new QuestradeCredentialResource($questrade_credential));
    }
}
