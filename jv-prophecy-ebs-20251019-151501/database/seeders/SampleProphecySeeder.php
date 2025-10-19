<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SampleProphecySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = \App\Models\User::where('email', 'superadmin@jvprophecy.com')->first();
        $generalCategory = \App\Models\Category::where('slug', 'general-prophecies')->first();
        $endTimesCategory = \App\Models\Category::where('slug', 'end-times')->first();
        $healingCategory = \App\Models\Category::where('slug', 'healing-miracles')->first();
        
        if (!$superAdmin) return;
        
        $prophecies = [
            [
                'title' => 'Divine Revelation of Hope',
                'description' => 'A powerful prophecy about hope and restoration in difficult times. The Lord speaks of His faithfulness and promises of renewal for His people.',
                'jebikalam_vanga_date' => '2024-01-15',
                'category_id' => $generalCategory?->id,
                'created_by' => $superAdmin->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'A message of hope and restoration for God\'s people in challenging times.',
                'tags' => ['hope', 'restoration', 'faith', 'divine'],
                'published_at' => now(),
                'translations' => [
                    'en' => [
                        'title' => 'Divine Revelation of Hope',
                        'description' => 'A powerful prophecy about hope and restoration in difficult times.',
                        'content' => 'Thus says the Lord: "In the midst of darkness, I am your light. In the valley of despair, I am your hope. My children, do not fear the storms that rage around you, for I am your shelter and your strength."',
                        'excerpt' => 'A message of hope and restoration for God\'s people in challenging times.'
                    ],
                    'ta' => [
                        'title' => 'நம்பிக்கையின் தெய்வீக வெளிப்பாடு',
                        'description' => 'கடினமான காலங்களில் நம்பிக்கை மற்றும் மறுசீரமைப்பு பற்றிய சக்திவாய்ந்த தீர்க்கதரிசனம்.',
                        'content' => 'கர்த்தர் சொல்கிறார்: "இருளின் மத்தியில், நான் உங்கள் ஒளி. நம்பிக்கையின்மையின் பள்ளத்தாக்கில், நான் உங்கள் நம்பிக்கை."',
                        'excerpt' => 'சவாலான காலங்களில் கடவுளின் மக்களுக்கான நம்பிக்கை மற்றும் மறுசீரமைப்பின் செய்தி.'
                    ]
                ]
            ],
            [
                'title' => 'The Coming Revival',
                'description' => 'A prophecy about a great spiritual awakening that will sweep across the nations, bringing many souls to Christ.',
                'jebikalam_vanga_date' => '2024-02-20',
                'category_id' => $endTimesCategory?->id,
                'created_by' => $superAdmin->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'A great spiritual awakening is coming that will transform nations.',
                'tags' => ['revival', 'awakening', 'souls', 'nations'],
                'published_at' => now(),
                'translations' => [
                    'en' => [
                        'title' => 'The Coming Revival',
                        'description' => 'A prophecy about a great spiritual awakening that will sweep across the nations.',
                        'content' => 'Behold, I will pour out My Spirit upon all flesh. The young shall prophesy, the old shall dream dreams. A great harvest of souls is coming, and My church shall be prepared.',
                        'excerpt' => 'A great spiritual awakening is coming that will transform nations.'
                    ],
                    'hi' => [
                        'title' => 'आने वाला पुनरुत्थान',
                        'description' => 'एक महान आध्यात्मिक जागृति के बारे में भविष्यवाणी जो राष्ट्रों में फैलेगी।',
                        'content' => 'देखो, मैं अपनी आत्मा सभी मांस पर उंडेलूंगा। युवा भविष्यवाणी करेंगे, बुजुर्ग सपने देखेंगे।',
                        'excerpt' => 'एक महान आध्यात्मिक जागृति आ रही है जो राष्ट्रों को बदल देगी।'
                    ]
                ]
            ],
            [
                'title' => 'Healing Waters Flow',
                'description' => 'A prophetic word about divine healing and miracles that will manifest in this season.',
                'jebikalam_vanga_date' => '2024-03-10',
                'category_id' => $healingCategory?->id,
                'created_by' => $superAdmin->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'Divine healing and miracles will flow like rivers in this season.',
                'tags' => ['healing', 'miracles', 'divine', 'restoration'],
                'published_at' => now(),
                'translations' => [
                    'en' => [
                        'title' => 'Healing Waters Flow',
                        'description' => 'A prophetic word about divine healing and miracles that will manifest in this season.',
                        'content' => 'I am releasing healing waters that will flow like rivers. The sick shall be made whole, the broken shall be restored, and My glory shall be revealed through signs and wonders.',
                        'excerpt' => 'Divine healing and miracles will flow like rivers in this season.'
                    ],
                    'kn' => [
                        'title' => 'ಗುಣಪಡಿಸುವ ನೀರು ಹರಿಯುತ್ತದೆ',
                        'description' => 'ಈ ಋತುವಿನಲ್ಲಿ ಪ್ರಕಟವಾಗುವ ದೈವಿಕ ಗುಣಪಡಿಸುವಿಕೆ ಮತ್ತು ಪವಾಡಗಳ ಬಗ್ಗೆ ಪ್ರವಾದಿಯ ಮಾತು.',
                        'content' => 'ನಾನು ನದಿಗಳಂತೆ ಹರಿಯುವ ಗುಣಪಡಿಸುವ ನೀರನ್ನು ಬಿಡುಗಡೆ ಮಾಡುತ್ತಿದ್ದೇನೆ. ರೋಗಿಗಳು ಸ್ವಸ್ಥರಾಗುವರು.',
                        'excerpt' => 'ದೈವಿಕ ಗುಣಪಡಿಸುವಿಕೆ ಮತ್ತು ಪವಾಡಗಳು ಈ ಋತುವಿನಲ್ಲಿ ನದಿಗಳಂತೆ ಹರಿಯುತ್ತವೆ.'
                    ]
                ]
            ],
            [
                'title' => 'Season of Breakthrough',
                'description' => 'A word about breakthrough and victory in areas where there has been struggle and delay.',
                'jebikalam_vanga_date' => '2024-04-05',
                'category_id' => $generalCategory?->id,
                'created_by' => $superAdmin->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'Breakthrough and victory are coming in areas of struggle and delay.',
                'tags' => ['breakthrough', 'victory', 'struggle', 'delay'],
                'published_at' => now(),
                'translations' => [
                    'en' => [
                        'title' => 'Season of Breakthrough',
                        'description' => 'A word about breakthrough and victory in areas where there has been struggle and delay.',
                        'content' => 'This is your season of breakthrough! What has been delayed shall now come to pass. What has been hindered shall now flow freely. I am breaking every chain and removing every obstacle.',
                        'excerpt' => 'Breakthrough and victory are coming in areas of struggle and delay.'
                    ],
                    'te' => [
                        'title' => 'పురోగతి కాలం',
                        'description' => 'పోరాటం మరియు ఆలస్యం ఉన్న ప్రాంతాలలో పురోగతి మరియు విజయం గురించి మాట.',
                        'content' => 'ఇది మీ పురోగతి కాలం! ఆలస్యమైనది ఇప్పుడు జరుగుతుంది. అడ్డుకున్నది ఇప్పుడు స్వేచ్ఛగా ప్రవహిస్తుంది.',
                        'excerpt' => 'పోరాటం మరియు ఆలస్యం ఉన్న ప్రాంతాలలో పురోగతి మరియు విజయం వస్తున్నాయి.'
                    ]
                ]
            ]
        ];
        
        foreach ($prophecies as $prophecyData) {
            $translations = $prophecyData['translations'];
            unset($prophecyData['translations']);
            
            $prophecy = \App\Models\Prophecy::create($prophecyData);
            
            // Create translations
            foreach ($translations as $language => $translationData) {
                \App\Models\ProphecyTranslation::create([
                    'prophecy_id' => $prophecy->id,
                    'language' => $language,
                    'title' => $translationData['title'],
                    'description' => $translationData['description'],
                    'content' => $translationData['content'],
                    'excerpt' => $translationData['excerpt'],
                ]);
            }
        }
    }
}
