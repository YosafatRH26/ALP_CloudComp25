<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | 1. MARKETING
        |--------------------------------------------------------------------------
        */
        $marketing = Division::create([
            'name' => 'Marketing',
            'description' => 'Responsible for branding, promotion, and audience engagement strategies.'
        ]);

        $marketing->skills()->createMany([
            ['skill_name' => 'Copywriting', 'importance_level' => 5],
            ['skill_name' => 'Public Speaking', 'importance_level' => 4],
            ['skill_name' => 'Social Media Strategy', 'importance_level' => 4],
            ['skill_name' => 'Branding', 'importance_level' => 4],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2. SPONSORSHIP
        |--------------------------------------------------------------------------
        */
        $sponsorship = Division::create([
            'name' => 'Sponsorship',
            'description' => 'Handles partnership acquisition and external funding relationships.'
        ]);

        $sponsorship->skills()->createMany([
            ['skill_name' => 'Negotiation', 'importance_level' => 5],
            ['skill_name' => 'Business Communication', 'importance_level' => 5],
            ['skill_name' => 'Proposal Writing', 'importance_level' => 4],
            ['skill_name' => 'Relationship Management', 'importance_level' => 4],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. HUMAN RESOURCES
        |--------------------------------------------------------------------------
        */
        $hr = Division::create([
            'name' => 'Human Resources',
            'description' => 'Manages recruitment, people development, and organizational wellbeing.'
        ]);

        $hr->skills()->createMany([
            ['skill_name' => 'Interviewing', 'importance_level' => 5],
            ['skill_name' => 'Talent Assessment', 'importance_level' => 4],
            ['skill_name' => 'Conflict Management', 'importance_level' => 4],
            ['skill_name' => 'Organizational Psychology', 'importance_level' => 3],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 4. FINANCE
        |--------------------------------------------------------------------------
        */
        $finance = Division::create([
            'name' => 'Finance',
            'description' => 'Oversees budgeting, financial planning, and expense tracking.'
        ]);

        $finance->skills()->createMany([
            ['skill_name' => 'Budget Planning', 'importance_level' => 5],
            ['skill_name' => 'Financial Reporting', 'importance_level' => 5],
            ['skill_name' => 'Spreadsheet Analysis', 'importance_level' => 4],
            ['skill_name' => 'Risk Analysis', 'importance_level' => 3],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 5. OPERATIONS
        |--------------------------------------------------------------------------
        */
        $operations = Division::create([
            'name' => 'Operations',
            'description' => 'Ensures smooth execution of processes and logistics.'
        ]);

        $operations->skills()->createMany([
            ['skill_name' => 'Process Management', 'importance_level' => 5],
            ['skill_name' => 'Logistics Planning', 'importance_level' => 4],
            ['skill_name' => 'Time Management', 'importance_level' => 4],
            ['skill_name' => 'Problem Solving', 'importance_level' => 4],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 6. INFORMATION TECHNOLOGY
        |--------------------------------------------------------------------------
        */
        $it = Division::create([
            'name' => 'Information Technology',
            'description' => 'Handles technical systems, software, and infrastructure.'
        ]);

        $it->skills()->createMany([
            ['skill_name' => 'Programming', 'importance_level' => 5],
            ['skill_name' => 'System Analysis', 'importance_level' => 4],
            ['skill_name' => 'Database Management', 'importance_level' => 4],
            ['skill_name' => 'Cybersecurity Basics', 'importance_level' => 3],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 7. DATA & RESEARCH
        |--------------------------------------------------------------------------
        */
        $data = Division::create([
            'name' => 'Data & Research',
            'description' => 'Focuses on data-driven insights and analytical decision making.'
        ]);

        $data->skills()->createMany([
            ['skill_name' => 'Data Analysis', 'importance_level' => 5],
            ['skill_name' => 'Research Methodology', 'importance_level' => 4],
            ['skill_name' => 'Critical Thinking', 'importance_level' => 4],
            ['skill_name' => 'Statistics', 'importance_level' => 3],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 8. CREATIVE
        |--------------------------------------------------------------------------
        */
        $creative = Division::create([
            'name' => 'Creative',
            'description' => 'Produces visual, multimedia, and creative assets.'
        ]);

        $creative->skills()->createMany([
            ['skill_name' => 'Graphic Design', 'importance_level' => 5],
            ['skill_name' => 'Video Editing', 'importance_level' => 4],
            ['skill_name' => 'UI/UX Basics', 'importance_level' => 4],
            ['skill_name' => 'Visual Storytelling', 'importance_level' => 4],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 9. PUBLIC RELATIONS
        |--------------------------------------------------------------------------
        */
        $pr = Division::create([
            'name' => 'Public Relations',
            'description' => 'Manages public image and media communication.'
        ]);

        $pr->skills()->createMany([
            ['skill_name' => 'Media Relations', 'importance_level' => 5],
            ['skill_name' => 'Press Release Writing', 'importance_level' => 4],
            ['skill_name' => 'Crisis Communication', 'importance_level' => 4],
            ['skill_name' => 'Public Communication', 'importance_level' => 4],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 10. EVENT MANAGEMENT
        |--------------------------------------------------------------------------
        */
        $event = Division::create([
            'name' => 'Event Management',
            'description' => 'Plans and executes events from preparation to completion.'
        ]);

        $event->skills()->createMany([
            ['skill_name' => 'Event Planning', 'importance_level' => 5],
            ['skill_name' => 'Vendor Coordination', 'importance_level' => 4],
            ['skill_name' => 'Risk Management', 'importance_level' => 4],
            ['skill_name' => 'On-site Execution', 'importance_level' => 4],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 11. PROJECT MANAGEMENT
        |--------------------------------------------------------------------------
        */
        $pm = Division::create([
            'name' => 'Project Management',
            'description' => 'Oversees timelines, deliverables, and stakeholder coordination.'
        ]);

        $pm->skills()->createMany([
            ['skill_name' => 'Agile / Scrum', 'importance_level' => 4],
            ['skill_name' => 'Roadmapping', 'importance_level' => 4],
            ['skill_name' => 'Stakeholder Management', 'importance_level' => 5],
            ['skill_name' => 'Documentation', 'importance_level' => 4],
        ]);

        /*
        |--------------------------------------------------------------------------
        | 12. LEGAL & COMPLIANCE
        |--------------------------------------------------------------------------
        */
        $legal = Division::create([
            'name' => 'Legal & Compliance',
            'description' => 'Ensures compliance with regulations, policies, and contracts.'
        ]);

        $legal->skills()->createMany([
            ['skill_name' => 'Contract Reading', 'importance_level' => 5],
            ['skill_name' => 'Policy Understanding', 'importance_level' => 4],
            ['skill_name' => 'Attention to Detail', 'importance_level' => 5],
            ['skill_name' => 'Ethics & Compliance', 'importance_level' => 4],
        ]);
    }
}
