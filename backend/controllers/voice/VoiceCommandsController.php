<?php

namespace suplascripts\controllers\voice;

use suplascripts\controllers\BaseController;
use suplascripts\controllers\exceptions\Http403Exception;
use suplascripts\models\voice\VoiceCommand;

class VoiceCommandsController extends BaseController
{
    public function postAction()
    {
        $this->ensureAuthenticated();
        $parsedBody = $this->request()->getParsedBody();
        $voiceCommand = $this->getCurrentUser()->voiceCommands()->create($parsedBody);
        $voiceCommand->save();
        $voiceCommand->log('Utworzono komendę głosową');
        return $this->response($voiceCommand)->withStatus(201);
    }

    public function getListAction()
    {
        $this->ensureAuthenticated();
        $voiceCommands = $this->getCurrentUser()->voiceCommands()->getResults();
        return $this->response($voiceCommands);
    }

    public function getLastVoiceCommandAction()
    {
        $this->ensureAuthenticated();
        $user = $this->getCurrentUser();
        return $this->response(['command' => $user->lastVoiceCommand]);
    }

    public function putAction($id)
    {
        $this->ensureAuthenticated();
        /** @var VoiceCommand $voiceCommand */
        $voiceCommand = $this->ensureExists(VoiceCommand::find($id)->first());
        if ($voiceCommand->userId != $this->getCurrentUser()->id) {
            throw new Http403Exception();
        }
        $parsedBody = $this->request()->getParsedBody();
        $voiceCommand->update($parsedBody);
        $voiceCommand->save();
        $voiceCommand->log('Wprowadzono zmiany w komendzie głosowej.');
        return $this->response($voiceCommand);
    }

    public function deleteAction($id)
    {
        $this->ensureAuthenticated();
        $voiceCommand = $this->ensureExists(VoiceCommand::find($id)->first());
        if ($voiceCommand->userId != $this->getCurrentUser()->id) {
            throw new Http403Exception();
        }
        $voiceCommand->log('Usunięto komendę głosową.');
        $voiceCommand->delete();
        return $this->response()->withStatus(204);
    }
}
