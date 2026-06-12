<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'alias',
        'tagline',
        'description',
        'vision',
        'mission',
        'address',
        'email',
        'phone',
        'instagram',
        'youtube',
        'tiktok',
        'website',
        'logo',
        'banner',
        'video_url',
        'structure_image',
        'recruitment_active',
        'recruitment_start_date',
        'recruitment_end_date',
        'recruitment_requirements',
        'recruitment_stages',
        'company_profile_pdf',
        'sponsorship_proposal_pdf',
        'media_kit_pdf',
    ];

    protected $casts = [
        'recruitment_active' => 'boolean',
        'recruitment_start_date' => 'date',
        'recruitment_end_date' => 'date',
    ];
}
