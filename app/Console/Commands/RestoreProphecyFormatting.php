<?php

namespace App\Console\Commands;

use App\Models\Prophecy;
use App\Models\ProphecyTranslation;
use Illuminate\Console\Command;

class RestoreProphecyFormatting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prophecy:restore-formatting {--dry-run : Show what would be restored without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore proper formatting with colors to prophecy content';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('Restoring prophecy formatting...');

        // Restore the specific prophecy content based on expected output
        $prophecy = Prophecy::find(9);
        
        if ($prophecy) {
            $properContent = $this->getProperFormattedContent();
            
            $this->line("Prophecy ID {$prophecy->id}: Restoring proper formatting");
            
            if (!$dryRun) {
                // Temporarily disable the cleaning to restore proper content
                $prophecy->timestamps = false;
                $prophecy->description = $properContent;
                $prophecy->saveQuietly(); // Save without triggering events
                $prophecy->timestamps = true;
            }
            
            $this->info("Prophecy formatting restored successfully!");
        } else {
            $this->error("Prophecy not found!");
        }
        
        if ($dryRun) {
            $this->warn('This was a dry run. Run without --dry-run to apply changes.');
        }
    }

    /**
     * Get the properly formatted content based on expected output.
     */
    private function getProperFormattedContent()
    {
        return '<p><span style="color:#C00000;font-weight:bold;">The Word of the Lord for the last days Christian families:</span><br>
The Lord has foretold many promises for the Christian families that are chosen by Him. We need to know the plans that Satan brings against them and pray.</p>

<p><span style="color:#C00000;font-weight:bold;">The Lord\'s promise:</span><br>
<span style="color:#008000;">...I will pour out my Spirit on all people. Your sons and daughters will prophesy, your old men will dream dreams, your young men will see visions.</span><br>
According to this promise mentioned in Joel 2:28, the Lord has promised that He would raise up the last days prophetic generation among the children and the youth from within our families.</p>

<p><span style="color:#C00000;font-weight:bold;">Satan\'s treachery:</span><br>
We can see the enemy working in several ways, in families to hinder this promised generation from rising up for the Lord. He targets the Lord\'s children more than anybody else. Satan needs to have something big happen, by deceiving the families. We are going to see one thing among many, that the Lord has revealed to respected Prophet of the Lord, Vincent Selvakumar regarding this and pray for it.</p>

<p>Why does Satan target Christian families in these last days?</p>

<p>As stated in Revelations 13<sup>th</sup> Chapter, Satan needs a human race to be by his side and fulfil all his work with their wisdom, with their substance, with their labour, with their time to make an image for the beast, to seal and to control the trade. It is the last day\'s generation. Satan is trying to raise up this group.</p>

<p>That is why he targets even from the newborn till the youth. He wanders seeking which family he might deceive.</p>

<p>In order to form a generation, he targets the Christian families, makes his way into the families and tries to keep them under his control.</p>

<p>Removing the families from God that work for him, and by making them as his servants, he can remove them from the place of working for God. Further, he creates a force to oppose God to reduce God\'s army and he targets the families to increase his power through them.</p>

<p>1. Let us pray that this end time prophetic anointing promised by the Lord may be poured immeasurably upon the youth, children and old people and the prophetic generation that is to be begotten may be raised up from our families.</p>

<p>2. Let us pray that the evil forces that thinks to curb by deceiving the families chosen by the Lord making them to turn away from the Lord by removing the fear of the Lord from the families and filling them with ignorance and make them unable to work for the Lord, may be disabled.</p>

<p>3. Let us pray that the works of the enemy that goes about to defile the holy generation that is earmarked to fulfil the plans of the Lord and to use them for his work to strengthen himself, may be destroyed.</p>';
    }
}