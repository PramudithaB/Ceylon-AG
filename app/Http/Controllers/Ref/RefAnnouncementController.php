<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RefAnnouncementController extends Controller
{
    /**
     * Display list of company announcements for Sales Representatives.
     */
    public function index()
    {
        $announcements = [
            [
                'id' => 1,
                'title' => 'Q3 Sales Targets & Commission Bonus Structure',
                'category' => 'Incentives',
                'date' => '2026-08-01',
                'author' => 'Executive Management',
                'priority' => 'High',
                'content' => 'We are pleased to announce our updated Q3 commission structure! Sales Representatives achieving 110% of their quarterly assigned target will receive an extra 2.5% performance bonus on total volume sold. Please coordinate with your assigned clients regarding top-performing product lines.',
            ],
            [
                'id' => 2,
                'title' => 'New Fertilizer & Agrochemical Product Line Release',
                'category' => 'Product Catalog',
                'date' => '2026-07-28',
                'author' => 'Product Team',
                'priority' => 'Medium',
                'content' => 'New high-yield bio-fertilizers and organic soil conditioners have been added to the product catalog. Ensure your client profiles are updated with these stock items for upcoming season requests.',
            ],
            [
                'id' => 3,
                'title' => 'Stock Request Submission Guidelines Update',
                'category' => 'Operations',
                'date' => '2026-07-20',
                'author' => 'Operations Head',
                'priority' => 'Normal',
                'content' => 'All product stock requests submitted via the Ref dashboard will be processed within 24 hours by Admin. Please include clear notes regarding delivery preferences and client payment confirmations.',
            ],
        ];

        return view('ref.announcements.index', compact('announcements'));
    }
}
