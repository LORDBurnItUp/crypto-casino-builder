<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CharitableCause;

class CharitableCausesSeeder extends Seeder
{
    public function run()
    {
        $causes = [
            [
                'name' => 'Plant Trees Worldwide',
                'description' => 'Help combat climate change by funding tree planting initiatives across the globe. Every donation helps restore forests and create habitats for wildlife.',
                'category' => 'environment',
                'icon' => '🌳',
                'organization' => 'One Tree Planted',
                'website' => 'https://onetreeplanted.org',
                'is_active' => true,
                'impact_metrics' => ['trees_planted' => 0],
            ],
            [
                'name' => 'Clean Water Projects',
                'description' => 'Provide access to clean, safe drinking water for communities in need. Support water filtration systems and well construction.',
                'category' => 'water',
                'icon' => '💧',
                'organization' => 'charity: water',
                'website' => 'https://www.charitywater.org',
                'is_active' => true,
                'impact_metrics' => ['people_served' => 0],
            ],
            [
                'name' => 'Feed the Hungry',
                'description' => 'Support food banks and meal programs that provide nutritious meals to families facing food insecurity.',
                'category' => 'food',
                'icon' => '🍞',
                'organization' => 'Feeding America',
                'website' => 'https://www.feedingamerica.org',
                'is_active' => true,
                'impact_metrics' => ['meals_provided' => 0],
            ],
            [
                'name' => 'Support Orphanages',
                'description' => 'Help provide shelter, education, and care for orphaned and vulnerable children around the world.',
                'category' => 'children',
                'icon' => '👶',
                'organization' => 'SOS Children\'s Villages',
                'website' => 'https://www.sos-childrensvillages.org',
                'is_active' => true,
                'impact_metrics' => ['children_helped' => 0],
            ],
            [
                'name' => 'Ocean Cleanup Initiative',
                'description' => 'Remove plastic waste from oceans and support marine conservation efforts to protect our seas.',
                'category' => 'environment',
                'icon' => '🌊',
                'organization' => 'The Ocean Cleanup',
                'website' => 'https://theoceancleanup.com',
                'is_active' => true,
                'impact_metrics' => ['plastic_removed_kg' => 0],
            ],
            [
                'name' => 'Medical Aid for Communities',
                'description' => 'Fund medical supplies, equipment, and healthcare services for underserved communities.',
                'category' => 'medical',
                'icon' => '🏥',
                'organization' => 'Doctors Without Borders',
                'website' => 'https://www.doctorswithoutborders.org',
                'is_active' => true,
                'impact_metrics' => ['patients_treated' => 0],
            ],
            [
                'name' => 'Education for All',
                'description' => 'Support educational programs, school supplies, and scholarships for children in developing countries.',
                'category' => 'education',
                'icon' => '📚',
                'organization' => 'Room to Read',
                'website' => 'https://www.roomtoread.org',
                'is_active' => true,
                'impact_metrics' => ['students_helped' => 0],
            ],
            [
                'name' => 'Homeless Shelter Support',
                'description' => 'Provide shelter, meals, and support services for people experiencing homelessness.',
                'category' => 'housing',
                'icon' => '🏠',
                'organization' => 'National Alliance to End Homelessness',
                'website' => 'https://endhomelessness.org',
                'is_active' => true,
                'impact_metrics' => ['nights_of_shelter' => 0],
            ],
        ];

        foreach ($causes as $cause) {
            CharitableCause::create($cause);
        }
    }
}
