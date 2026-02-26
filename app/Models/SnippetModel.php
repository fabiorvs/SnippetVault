<?php
namespace App\Models;

use CodeIgniter\Model;

class SnippetModel extends Model
{
    protected $table         = 'snippets';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'id', 'user_id', 'title', 'language', 'tags', 'pinned', 'deleted', 'content',
    ];
    protected $useTimestamps = true;
    protected $returnType    = 'array';

    public function listForUser(int $userId, array $opts = [])
    {
        $q      = trim((string) ($opts['q'] ?? ''));
        $filter = (string) ($opts['filter'] ?? 'all');
        $sort   = (string) ($opts['sort'] ?? 'updated');
        $lang   = (string) ($opts['lang'] ?? '');

        $b = $this->builder();
        $b->where('user_id', $userId);

        if ($filter === 'pinned') {
            $b->where('pinned', 1)->where('deleted', 0);
        } elseif ($filter === 'trash') {
            $b->where('deleted', 1);
        } elseif ($filter === 'lang') {
            $b->where('deleted', 0);
            if ($lang !== '') {
                $b->where('language', $lang);
            }

        } else {
            $b->where('deleted', 0);
        }

        if ($q !== '') {
            // busca simples (pode evoluir p/ FULLTEXT depois)
            $b->groupStart()
                ->like('LOWER(title)', mb_strtolower($q))
                ->orLike('LOWER(tags)', mb_strtolower($q))
                ->orLike('LOWER(content)', mb_strtolower($q))
                ->groupEnd();
        }

        if ($sort === 'created') {
            $b->orderBy('created_at', 'DESC');
        } elseif ($sort === 'alpha') {
            $b->orderBy('title', 'ASC');
        } else {
            $b->orderBy('updated_at', 'DESC');
        }

        // pinned sempre por cima
        $b->orderBy('pinned', 'DESC');

        // preview
        $b->select("id,title,language,tags,pinned,deleted,created_at,updated_at,LEFT(content, 240) AS preview", false);

        return $b->get()->getResultArray();
    }

    public function getForUser(int $userId, string $id): ?array
    {
        return $this->where('user_id', $userId)->where('id', $id)->first();
    }
}
