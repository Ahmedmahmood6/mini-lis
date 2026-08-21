<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'patient_id',
        'appointment_id',
        'status',
        'notes',
        'created_by',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function report(): HasOne
    {
        return $this->hasOne(Report::class);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get formatted WhatsApp click-to-chat URL for patient.
     */
    public function getWhatsappUrlAttribute(): string
    {
        $phone = $this->patient?->phone ?? '';
        $digits = preg_replace('/\D/', '', $phone);

        if (str_starts_with($digits, '0020')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '0')) {
            $digits = '2'.$digits;
        }

        $reportUrl = route('reports.download', $this);
        $patientName = $this->patient?->name ?? 'Patient';

        $message = "مرحباً {$patientName}،\n"
            ."تقرير التحاليل الطبية الخاص بك للطلب رقم {$this->order_number} جاهز الآن.\n"
            ."يمكنك تحميل التقرير عبر الرابط التالي:\n{$reportUrl}\n\n"
            ."شكراً لاختياركم معملنا!\n\n"
            ."Hello {$patientName},\n"
            ."Your laboratory test report for Order {$this->order_number} is ready.\n"
            ."Download report: {$reportUrl}\n\n"
            .'Thank you for choosing Mini LIS Laboratory!';

        return 'https://wa.me/'.$digits.'?text='.urlencode($message);
    }
}
