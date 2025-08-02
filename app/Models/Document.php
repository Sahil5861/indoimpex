<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['document_type_id', 'document_name', 'document_content'];
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
}
