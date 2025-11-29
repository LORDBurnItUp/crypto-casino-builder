<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CharitableCause;
use App\Services\Donation\DonationService;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    protected $donationService;

    public function __construct(DonationService $donationService)
    {
        $this->donationService = $donationService;
    }

    public function causes(Request $request)
    {
        $category = $request->query('category');
        $causes = $this->donationService->getCausesByCategory($category);

        return response()->json($causes);
    }

    public function donate(Request $request)
    {
        $request->validate([
            'cause_id' => 'required|exists:charitable_causes,id',
            'forum_gold_amount' => 'required|numeric|min:1000',
        ]);

        $user = $request->user();
        $cause = CharitableCause::findOrFail($request->cause_id);

        try {
            $donation = $this->donationService->createDonation(
                $user,
                $cause,
                $request->forum_gold_amount
            );

            return response()->json([
                'message' => 'Donation successful! Thank you for making a difference!',
                'donation' => $donation->load('cause'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function myDonations(Request $request)
    {
        $user = $request->user();
        $stats = $this->donationService->getUserDonationStats($user);

        return response()->json($stats);
    }

    public function globalImpact()
    {
        $impact = $this->donationService->getGlobalImpact();
        return response()->json($impact);
    }

    public function conversionRate()
    {
        return response()->json([
            'rate' => $this->donationService->getConversionRate(),
            'description' => 'Forum Gold per 1 USD',
        ]);
    }
}
