<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'General Prophecies',
                'slug' => 'general-prophecies',
                'description' => 'General Christian prophecies and revelations',
                'parent_id' => null,
                'sort_order' => 1,
                'status' => 'active',
                'icon' => 'fas fa-cross',
                'color' => '#0284c7',
                'translations' => [
                    'en' => 'General Prophecies',
                    'ta' => 'பொது தீர்க்கதரிசனங்கள்',
                    'kn' => 'ಸಾಮಾನ್ಯ ಭವಿಷ್ಯವಾಣಿಗಳು',
                    'te' => 'సాధారణ ప్రవచనాలు',
                    'ml' => 'പൊതു പ്രവചനങ്ങൾ',
                    'hi' => 'सामान्य भविष्यवाणियां'
                ]
            ],
            [
                'name' => 'End Times',
                'slug' => 'end-times',
                'description' => 'Prophecies about the end times and second coming',
                'parent_id' => null,
                'sort_order' => 2,
                'status' => 'active',
                'icon' => 'fas fa-hourglass-end',
                'color' => '#dc2626',
                'translations' => [
                    'en' => 'End Times',
                    'ta' => 'இறுதிக் காலம்',
                    'kn' => 'ಅಂತ್ಯ ಕಾಲ',
                    'te' => 'అంత్య కాలం',
                    'ml' => 'അവസാന കാലം',
                    'hi' => 'अंत समय'
                ]
            ],
            [
                'name' => 'Healing & Miracles',
                'slug' => 'healing-miracles',
                'description' => 'Prophecies about divine healing and miracles',
                'parent_id' => null,
                'sort_order' => 3,
                'status' => 'active',
                'icon' => 'fas fa-hands-helping',
                'color' => '#059669',
                'translations' => [
                    'en' => 'Healing & Miracles',
                    'ta' => 'குணப்படுத்துதல் மற்றும் அற்புதங்கள்',
                    'kn' => 'ಗುಣಪಡಿಸುವಿಕೆ ಮತ್ತು ಪವಾಡಗಳು',
                    'te' => 'వైద్యం మరియు అద్భుతాలు',
                    'ml' => 'സുഖപ്പെടുത്തലും അത്ഭുതങ്ങളും',
                    'hi' => 'चंगाई और चमत्कार'
                ]
            ],
            [
                'name' => 'Personal Prophecies',
                'slug' => 'personal-prophecies',
                'description' => 'Individual and personal prophetic words',
                'parent_id' => null,
                'sort_order' => 4,
                'status' => 'active',
                'icon' => 'fas fa-user',
                'color' => '#7c3aed',
                'translations' => [
                    'en' => 'Personal Prophecies',
                    'ta' => 'தனிப்பட்ட தீர்க்கதரிசனங்கள்',
                    'kn' => 'ವೈಯಕ್ತಿಕ ಭವಿಷ್ಯವಾಣಿಗಳು',
                    'te' => 'వ్యక్తిగత ప్రవచనాలు',
                    'ml' => 'വ്യക്തിഗത പ്രവചനങ്ങൾ',
                    'hi' => 'व्यक्तिगत भविष्यवाणियां'
                ]
            ],
            [
                'name' => 'Church & Ministry',
                'slug' => 'church-ministry',
                'description' => 'Prophecies for churches and ministries',
                'parent_id' => null,
                'sort_order' => 5,
                'status' => 'active',
                'icon' => 'fas fa-church',
                'color' => '#ea580c',
                'translations' => [
                    'en' => 'Church & Ministry',
                    'ta' => 'தேவாலயம் மற்றும் ஊழியம்',
                    'kn' => 'ಚರ್ಚ್ ಮತ್ತು ಸೇವೆ',
                    'te' => 'చర్చి మరియు సేవ',
                    'ml' => 'സഭയും ശുശ്രൂഷയും',
                    'hi' => 'चर्च और सेवकाई'
                ]
            ]
        ];

        foreach ($categories as $category) {
            \App\Models\Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
