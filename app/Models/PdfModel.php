<?php

namespace App\Models;

use App\Entities\Pdf;
use CodeIgniter\Model;

class PdfModel extends Model
{
    protected $table = 'pdfs';
    protected $primaryKey = 'id';
    protected $returnType = Pdf::class;
    protected $allowedFields = ['user_id', 'pdf_path', 'data', 'created_at'];

    public function getPdfsByUser(int $userId)
    {
        return $this->where('user_id', $userId)->findAll();
    }
}
