<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignSend extends Model
{
    /**
     * The list of fillable attributes
     *
     * @var array
     */
    protected $fillable = ['campaign_id', 'contact_id'];

    /**
     * The Contact that belong to the CampaignSend
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TRelatedModel, $this>
     */
    public function contact()
    {
        return parent::belongsTo(Contact::class);
    }

    /**
     * The Campaign that belong to the CampaignSend
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<TRelatedModel, $this>
     */
    public function campaign()
    {
        return parent::belongsTo(Campaign::class);
    }
}
