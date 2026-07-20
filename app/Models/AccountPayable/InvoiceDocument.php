<?php

namespace App\Models\AccountPayable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDocument extends Model
{
    use HasFactory;

    protected $table = 'ap_invoice_documents';

    protected $fillable = [
        'invoice_id', 'document_type', 'file_name', 'file_path', 'file_size_bytes', 'status',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size_bytes ?? 0;

        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }

        if ($bytes >= 1024) {
            return round($bytes / 1024) . ' KB';
        }

        return $bytes . ' B';
    }
}