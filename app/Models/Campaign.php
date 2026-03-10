<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['subject', 'body', 'contact_list_id', 'status', 'scheduled_at'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * The ContactList that belong to the Campaign
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TRelatedModel, $this>
     */
    public function contactList(): BelongsTo
    {
        return $this->belongsTo(ContactList::class);
    }

    /**
     * The CampaignSend's for the Campaign
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<TRelatedModel, $this>
     */
    public function sends(): HasMany
    {
        return $this->hasMany(CampaignSend::class);
    }

    /**
     * The Stats Attribute
     * 
     * @return array
     */
    public function getStatsAttribute(): array
    {
        $sends = $this->sends;

        return [
            'pending' => $sends->where('status', 'pending')->count(),
            'sent'    => $sends->where('status', 'sent')->count(),
            'failed'  => $sends->where('status', 'failed')->count(),
            'total'   => $sends->count(),
        ];
    }
}
