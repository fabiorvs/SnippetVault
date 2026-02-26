<?php
namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\SnippetModel;
use Ramsey\Uuid\Uuid;

class SnippetsController extends BaseController
{
    private function userId(): int
    {
        return (int) session('user_id');
    }

    public function index()
    {
        $m    = new SnippetModel();
        $rows = $m->listForUser($this->userId(), [
            'q'      => $this->request->getGet('q'),
            'filter' => $this->request->getGet('filter'),
            'sort'   => $this->request->getGet('sort'),
            'lang'   => $this->request->getGet('lang'),
        ]);

        return $this->response->setJSON(['ok' => true, 'data' => $rows]);
    }

    public function show($id = null)
    {
        if (! $id) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false]);
        }

        $m  = new SnippetModel();
        $sn = $m->getForUser($this->userId(), $id);

        if (! $sn) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'message' => 'Não encontrado']);
        }

        return $this->response->setJSON(['ok' => true, 'data' => $sn]);
    }

    public function create()
    {
        $m = new SnippetModel();

        $id = Uuid::uuid4()->toString();

        $m->insert([
            'id'       => $id,
            'user_id'  => $this->userId(),
            'title'    => 'Novo snippet',
            'language' => 'plaintext',
            'tags'     => '',
            'pinned'   => 0,
            'deleted'  => 0,
            'content'  => '',
        ]);

        return $this->response->setJSON(['ok' => true, 'id' => $id]);
    }

    public function update($id = null)
    {
        if (! $id) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false]);
        }

        $m  = new SnippetModel();
        $sn = $m->getForUser($this->userId(), $id);
        if (! $sn) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false]);
        }

        $payload = $this->request->getJSON(true) ?? [];

        $data = [
            'title'    => (string) ($payload['title'] ?? $sn['title']),
            'language' => (string) ($payload['language'] ?? $sn['language']),
            'tags'     => (string) ($payload['tags'] ?? $sn['tags']),
            'pinned'   => ! empty($payload['pinned']) ? 1 : 0,
            'deleted'  => ! empty($payload['deleted']) ? 1 : 0,
            'content'  => (string) ($payload['content'] ?? $sn['content']),
        ];

        $m->update($id, $data);
        return $this->response->setJSON(['ok' => true]);
    }

    public function destroy($id = null)
    {
        if (! $id) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false]);
        }

        $m  = new SnippetModel();
        $sn = $m->getForUser($this->userId(), $id);
        if (! $sn) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false]);
        }

        // hard delete
        $m->delete($id);
        return $this->response->setJSON(['ok' => true]);
    }
}
