<?php

namespace App\Models;

use CodeIgniter\Model;

class TbProduto extends Model
{
    protected $table            = 'tb_produto';
    protected $primaryKey       = 'id_produto';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['userCreated'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['userUpdated'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['userDeleted'];
    protected $afterDelete    = [];
    
    protected function userCreated($data) {
        $data['data']['user_created'] = session('usuario_logado')['id_usuario'] ?? 1; 
        return $data;
    }

    protected function userUpdated($data) {
        $data['data']['user_updated'] = session('usuario_logado')['id_usuario'] ?? 1; 
        return $data;
    }

    protected function userDeleted($data) {
        $data['data']['user_deleted'] = session('usuario_logado')['id_usuario'] ?? 1; 
        return $data;
    }
      
}
