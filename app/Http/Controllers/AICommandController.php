<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\Skill;
use App\Models\Project;

class AICommandController extends Controller
{
    public function processCommand($message)
    {
        $message = strtolower(trim($message));

        // Add Skill
        if (preg_match('/add.*skill[:\s]+(.+)/i', $message, $matches)) {
            return $this->addSkill($matches[1]);
        }

        // Add Project
        if (preg_match('/add.*project[:\s]+(.+)/i', $message, $matches)) {
            return $this->addProject($matches[1]);
        }

        // Update Hero
        if (preg_match('/update.*hero.*title[:\s]+(.+)/i', $message, $matches)) {
            return $this->updateHeroTitle($matches[1]);
        }

        // List Skills
        if (preg_match('/list skills|show skills|get skills/i', $message)) {
            return $this->listSkills();
        }

        // List Projects
        if (preg_match('/list projects|show projects|get projects/i', $message)) {
            return $this->listProjects();
        }

        // Delete Skill
        if (preg_match('/delete.*skill[:\s]+(.+)/i', $message, $matches)) {
            return $this->deleteSkill($matches[1]);
        }

        // Help
        if (preg_match('/help|commands/i', $message)) {
            return $this->getHelp();
        }

        return "❓ I didn't understand that. Type 'help' to see available commands!";
    }

    private function addSkill($skillName)
    {
        $skillName = trim($skillName);

        $skill = Skill::create([
            'name' => $skillName,
            'proficiency' => 50
        ]);

        return "✅ Added skill: {$skillName} (ID: {$skill->id})";
    }

    private function addProject($projectName)
    {
        $projectName = trim($projectName);

        $project = Project::create([
            'title' => $projectName,
            'description' => 'Description pending'
        ]);

        return "✅ Added project: {$projectName} (ID: {$project->id})";
    }

    private function updateHeroTitle($title)
    {
        $title = trim($title);

        $hero = Hero::first();

        if (!$hero) {
            $hero = Hero::create([
                'title' => $title,
                'description' => 'Your portfolio hero section'
            ]);
            return "✅ Created hero with title: {$title}";
        }

        $hero->update(['title' => $title]);
        return "✅ Updated hero title to: {$title}";
    }

    private function listSkills()
    {
        $skills = Skill::all();

        if ($skills->isEmpty()) {
            return "📋 No skills found. Add one with: 'Add skill: Laravel'";
        }

        $list = "📋 Your Skills:\n\n";
        foreach ($skills as $skill) {
            $list .= "• {$skill->name} (ID: {$skill->id})\n";
        }

        return $list;
    }

    private function listProjects()
    {
        $projects = Project::all();

        if ($projects->isEmpty()) {
            return "📋 No projects found. Add one with: 'Add project: My App'";
        }

        $list = "📋 Your Projects:\n\n";
        foreach ($projects as $project) {
            $list .= "• {$project->title} (ID: {$project->id})\n";
        }

        return $list;
    }

    private function deleteSkill($skillName)
    {
        $skillName = trim($skillName);

        $skill = Skill::where('name', 'like', "%{$skillName}%")->first();

        if (!$skill) {
            return "❌ Skill not found: {$skillName}";
        }

        $name = $skill->name;
        $skill->delete();

        return "✅ Deleted skill: {$name}";
    }

    private function getHelp()
    {
        return "🤖 Portfolio Admin AI Commands:\n\n" .
            "📝 Add Commands:\n" .
            "• Add skill: Laravel\n" .
            "• Add project: My Portfolio\n" .
            "• Update hero title: Full Stack Developer\n\n" .
            "📋 List Commands:\n" .
            "• List skills\n" .
            "• List projects\n\n" .
            "🗑️ Delete Commands:\n" .
            "• Delete skill: Laravel\n\n" .
            "Type any command to get started!";
    }
}
