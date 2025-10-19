<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Prophecy;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class CreateSampleProphecies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prophecies:create-samples';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create sample prophecies for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating sample prophecies...');

        // Get or create a user
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Super Administrator',
                'email' => 'admin@jvprophecy.com',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
        }

        // Get or create categories
        $categories = [];
        $categoryData = [
            ['name' => 'FAMILY', 'description' => 'Family-related prophecies'],
            ['name' => 'General Prophecies', 'description' => 'General Christian prophecies'],
            ['name' => 'End Times', 'description' => 'End times prophecies'],
            ['name' => 'Healing & Miracles', 'description' => 'Divine healing prophecies'],
            ['name' => 'Church & Ministry', 'description' => 'Church and ministry prophecies'],
        ];

        foreach ($categoryData as $catData) {
            $category = Category::firstOrCreate(
                ['name' => $catData['name']],
                [
                    'description' => $catData['description'],
                    'status' => 'active',
                    'created_by' => $user->id,
                ]
            );
            $categories[] = $category;
        }

        // Sample prophecy data
        $prophecies = [
            [
                'id' => 1,
                'title' => 'FAMILY / INDIVIDUAL – 1',
                'description' => '<p><strong>The Word of the Lord for the last days Christian families:</strong></p><p>The Lord says, "I am calling My people to return to the foundations of faith. In these last days, families must stand together in unity and prayer."</p><p style="color: #ee0000;"><strong>Prayer Points:</strong></p><ul><li>Pray for family unity and restoration</li><li>Seek God\'s protection over your household</li><li>Stand firm in faith during trials</li></ul>',
                'category_id' => $categories[0]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'The Word of the Lord for the last days Christian families and individuals.',
                'tags' => ['healing', 'miracles', 'divine', 'restoration'],
            ],
            [
                'id' => 2,
                'title' => 'Divine Revelation of Hope',
                'description' => '<p><strong>A message of hope and restoration for God\'s people:</strong></p><p>The Lord declares, "I am doing a new thing in the earth. Do not look to the former things, for behold, I make all things new."</p><p style="color: #0066cc;"><em>This is a season of breakthrough and divine intervention.</em></p>',
                'category_id' => $categories[1]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'A message of hope and restoration for God\'s people in challenging times.',
            ],
            [
                'id' => 3,
                'title' => 'The Coming Revival',
                'description' => '<p><strong>A great spiritual awakening is coming:</strong></p><p>The Spirit of the Lord says, "Prepare your hearts, for I am about to pour out My Spirit in unprecedented ways."</p><p style="color: #cc6600;"><strong>Signs of the Revival:</strong></p><ol><li>Hearts turning back to God</li><li>Supernatural healings and miracles</li><li>Unity among believers</li></ol>',
                'category_id' => $categories[2]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'A great spiritual awakening is coming that will transform nations.',
            ],
            [
                'id' => 4,
                'title' => 'Healing Waters Flow',
                'description' => '<p><strong>Divine healing miracles will flow like rivers:</strong></p><p>The Lord says, "I am releasing healing waters that will flow through My people. Every sickness and disease must bow to My name."</p><p style="color: #009900;"><em>Expect miraculous healings and divine interventions.</em></p>',
                'category_id' => $categories[3]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'Divine healing miracles will flow like rivers in this season.',
            ],
            [
                'id' => 5,
                'title' => 'Season of Breakthrough',
                'description' => '<p><strong>Breakthrough and victory are coming:</strong></p><p>The Lord declares, "This is your season of breakthrough! Every barrier that has stood against you will be removed by My mighty hand."</p><p style="color: #ff6600;"><strong>Areas of Breakthrough:</strong></p><ul><li>Financial breakthrough and provision</li><li>Healing and restoration</li><li>Relationship reconciliation</li><li>Ministry expansion</li></ul><p><em>Stand in faith and watch Me work!</em></p>',
                'category_id' => $categories[1]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'Breakthrough and victory are coming in areas of struggle and challenge.',
            ],
            [
                'id' => 6,
                'title' => 'Divine Revelation of Hope - Extended',
                'description' => '<p><strong>Extended message of hope and restoration:</strong></p><p>The Lord continues to speak, "My children, do not be discouraged by what you see in the natural. I am working behind the scenes to bring about My perfect will."</p><p style="color: #9900cc;"><strong>Promises for This Season:</strong></p><ol><li>Divine appointments and connections</li><li>Supernatural provision</li><li>Healing of broken relationships</li><li>Expansion of your territory</li></ol>',
                'category_id' => $categories[1]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'Extended message of hope with specific promises for this season.',
            ],
            [
                'id' => 7,
                'title' => 'The Coming Revival - Part 2',
                'description' => '<p><strong>The second wave of revival is approaching:</strong></p><p>The Spirit of the Lord says, "The first wave was preparation, but the second wave will be transformation. Nations will be shaken and hearts will be changed."</p><p style="color: #cc0000;"><strong>Characteristics of the Second Wave:</strong></p><ul><li>Greater manifestation of God\'s power</li><li>Mass conversions and salvations</li><li>Healing of the nations</li><li>Unity among all believers</li></ul><p><em>Prepare your hearts for what is coming!</em></p>',
                'category_id' => $categories[2]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'The second wave of revival will bring transformation to nations.',
                'tags' => ['revival', 'transformation', 'nations', 'power', 'unity'],
            ],
            [
                'id' => 8,
                'title' => 'Healing Waters Flow - Continued',
                'description' => '<p><strong>The healing waters continue to flow:</strong></p><p>The Lord says, "As the healing waters flow, they will reach every corner of the earth. No sickness can stand before the power of My healing touch."</p><p style="color: #006633;"><strong>Testimonies to Expect:</strong></p><ol><li>Miraculous physical healings</li><li>Emotional and mental restoration</li><li>Spiritual revival and renewal</li><li>Family reconciliation</li></ol>',
                'category_id' => $categories[3]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'The healing waters continue to flow with miraculous testimonies.',
            ],
            [
                'id' => 9,
                'title' => 'Church & Ministry Expansion',
                'description' => '<p><strong>A season of church and ministry expansion:</strong></p><p>The Lord declares, "I am expanding My kingdom through My faithful servants. Churches will grow, ministries will flourish, and My glory will be seen."</p><p style="color: #ff9900;"><strong>Areas of Expansion:</strong></p><ul><li>Church planting and growth</li><li>Ministry outreach programs</li><li>International missions</li><li>Youth and children\'s ministries</li></ul><p><em>Be ready to step into your calling!</em></p>',
                'category_id' => $categories[4]->id,
                'status' => 'published',
                'visibility' => 'public',
                'excerpt' => 'A season of church and ministry expansion is upon us.',
            ],
        ];

        // Create or update prophecies
        foreach ($prophecies as $prophecyData) {
            $prophecy = Prophecy::updateOrCreate(
                ['id' => $prophecyData['id']],
                [
                    'title' => $prophecyData['title'],
                    'description' => $prophecyData['description'],
                    'jebikalam_vanga_date' => Carbon::now()->subDays(rand(1, 30)),
                    'category_id' => $prophecyData['category_id'],
                    'created_by' => $user->id,
                    'status' => $prophecyData['status'],
                    'visibility' => $prophecyData['visibility'],
                'excerpt' => $prophecyData['excerpt'],
                'tags' => $prophecyData['tags'] ?? [],
                'view_count' => rand(10, 100),
                'download_count' => rand(5, 50),
                'print_count' => rand(1, 20),
                'published_at' => $prophecyData['status'] === 'published' ? now() : null,
                ]
            );

            $this->info("Created/Updated prophecy: {$prophecy->title} (ID: {$prophecy->id})");
        }

        $this->info('Sample prophecies created successfully!');
        $this->info('Total prophecies in database: ' . Prophecy::count());
    }
}