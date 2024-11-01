<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SystemPreferencesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'auto_save'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );

        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'dark_mode'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("false")
            ]
        );

        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'two_factor_auth'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("false")
            ]
        );

        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'auth_method'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("security_question")
            ]
        );

        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'toc_compact_mode'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );

        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_toc_create_date'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_toc_organisation_logo'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_toc_indicators_count'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_indicator_create_date'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_indicator_organisation_logo'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_indicator_response_count'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_indicator_category'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_indicator_qualitative_status'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_indicator_ruller'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'archive_compact_mode'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_archive_create_date'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_archive_organisation_logo'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_archive_indicators_count'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'show_archive_status'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'email_notifications'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("true")
            ]
        );
        
        DB::table('system_preferences')->updateOrInsert(
            ['key' => 'sms_notifications'], // Search condition
            [
                'id' => Uuid::uuid4(),    // New UUID if inserted
                'value' => json_encode("false")
            ]
        );

        // Add other default preferences here with similar structure
    }
}
