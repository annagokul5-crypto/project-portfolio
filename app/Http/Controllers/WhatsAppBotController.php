<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use App\Models\Skill;
use App\Models\Project;
use App\Models\Hero;
use App\Models\ProjectScreenshot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\AboutContent;
use App\Models\HeroContent;// make sure model exists, or AboutContent-like
use App\Models\ContactInfo;
use App\Models\Setting;
use App\Models\SkillCategory;
use Twilio\Security\RequestValidator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;









class WhatsAppBotController extends Controller
{
    protected $twilio;
    protected $from;
    protected $to;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $this->from = env('TWILIO_WHATSAPP_NUMBER');
        $this->to = env('TWILIO_WHATSAPP_TO');
        $this->twilio = new Client($sid, $token);
    }

    public function webhook(Request $request)
    {
//        if (! $this->validateTwilioRequest($request)) {
//            Log::warning('Invalid Twilio signature');
//            return response('Forbidden', 403);
//        }

        try {
            $from = $request->input('From');
            $body = trim($request->input('Body', ''));
            $hasImage = $request->input('MediaUrl0') ? 1 : 0;

            Log::info('📱 WhatsApp IN', ['from' => $from, 'body' => $body, 'hasImage' => $hasImage]);
// ✅ If admin already typed a valid command, skip AI and run directly
            if ($this->isDirectCommand($body)) {
                $response = $this->handleCommand($body);
                $this->sendMessage($response, $from);
                return response()->json(['status' => 'ok']);
            }

            // 1) Ask HPT to translate natural language -> command
            $ai = Http::asForm()
                ->timeout(60)
                ->post('http://127.0.0.1:8000/v1/hpt/translate', [
                    'text' => $body,
                    'has_image' => $hasImage,
                ]);

            $cmd = trim($ai->json('command') ?? 'UNKNOWN');
            Log::info('🤖 HPT COMMAND', ['cmd' => $cmd]);

            // 2) If AI can't map it, tell user
            if ($cmd === '' || strtoupper($cmd) === 'UNKNOWN') {
                $this->sendMessage("⚠️ I couldn't understand. Please try different words.", $from);
                return response()->json(['status' => 'ok']);
            }

            // 3) Execute existing command system (your old code)
            $response = $this->handleCommand($cmd);

            // 4) Reply back to WhatsApp
            $this->sendMessage($response, $from);

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            Log::error('❌ Webhook Error', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
    private function isDirectCommand(string $text): bool
    {
        $t = trim($text);

        // Exact commands (no params)
        if (preg_match('/^(help|help skills|help projects|help details|help links|help screenshots|help hero|help about|list skills|list projects|show hero|show hero content|show about|show contact|send me form pdf|form pdf)$/i', $t)) {
            return true;
        }

        // Parameterized commands (match YOUR controller regex styles)
        $patterns = [
            '/^footer[:\s]+\d{4}$/i',

            '/^skill[:\s]+.+\|\s*.+\|\s*\d{1,3}%?$/i',
            '/^delete skill[:\s]+.+\|\s*.+$/i',
            '/^add skill[:\s]+.+$/i',

            '/^add project[:\s]+.+$/i',
            '/^delete project[:\s]+.+$/i',
            '/^project details[:\s]+.+$/i',
            '/^update project[:\s]+.+\|\s*.+$/i',
            '/^project status[:\s]+.+\|\s*(published|draft|ongoing)$/i',
            '/^project order[:\s]+.+\|\s*\d+$/i',
            '/^project short[:\s]+.+\|\s*.+$/i',
            '/^project desc[:\s]+.+\|\s*.+$/i',
            '/^project features[:\s]+.+\|\s*.+$/i',
            '/^project tools[:\s]+.+\|\s*.+$/i',
            '/^project live[:\s]+.+\|\s*https?:\/\/\S+$/i',
            '/^project github[:\s]+.+\|\s*https?:\/\/\S+$/i',

            '/^delete short[:\s]+.+$/i',
            '/^delete desc[:\s]+.+$/i',
            '/^delete features[:\s]+.+$/i',
            '/^delete tools[:\s]+.+$/i',
            '/^delete live[:\s]+.+$/i',
            '/^delete github[:\s]+.+$/i',

            '/^add screenshot[:\s]+.+\|\s*.+$/i',
            '/^update screenshot[:\s]+.+\|\s*.+$/i',
            '/^rename\s+.+?:\s*.+\s*(->|to)\s*.+$/i',
            '/^delete the\s+.+\s+image in\s+.+$/i',

            '/^hero title[:\s]+.+$/i',
            '/^hero description[:\s]+.+$/i',
            '/^hero name[:\s]+.+$/i',
            '/^hero role[:\s]+.+$/i',
            '/^hero objective[:\s]+.+$/i',
            '/^hero resume[:\s]+.+$/i',

            '/^about text[:\s]+.+$/i',
            '/^about photo$/i',
            '/^delete about text$/i',
            '/^delete about photo$/i',

            '/^contact\s+(phone|email|whatsapp|linkedin|github|location)[:\s]+.+$/i',
            '/^project video[:\s]+.+\|\s*https?:\/\/\S+$/i',
            '/^project video\s+remove[:\s]+.+$/i',
            '/^add screenshot[:\s]+.+\|\s*.+$/i',
            '/^update screenshot[:\s]+.+\|\s*.+$/i',
            '/^delete screenshot[:\s]+.+\|\s*.+$/i',
            '/^list screenshots[:\s]+.+$/i',
            '/^delete screenshot[:\s]+\d+$/i',


        ];

        foreach ($patterns as $p) {
            if (preg_match($p, $t)) return true;
        }

        return false;

    }

    private function validateTwilioRequest(Request $request): bool
    {
        $signature = $request->header('X-Twilio-Signature');
        if (!$signature) return false;

        $validator = new RequestValidator(env('TWILIO_AUTH_TOKEN'));

        // IMPORTANT: must be the exact URL Twilio called
        $url = $request->fullUrl();

        // Twilio sends application/x-www-form-urlencoded params
        $params = $request->request->all();

        return $validator->validate($signature, $url, $params);

    }

    private function handleCommand($command)
    {
        $cmd = strtolower(trim($command));

        // HELP
//        if ($cmd === 'help') {
//            return "🤖 Portfolio Admin Bot\n\n"
//                . "Commands:\n"
//                . "• help - Show this message\n"
//                . "• add skill: [name] - Add a skill\n"
//                . "• list skills - Show all skills\n"
//                . "• add project: [name] - Add a project\n"
//                . "• list projects - Show projects"
//                . "📌 *GENERAL*\n"
//                . "• footer: [year] - Set footer year\n"
//                . "• form pdf - Get contact form submissions PDF\n\n"
//                . "🛠 *SKILLS*\n"
//                . "• list skills - Show all skills\n"
//                . "• skill: [Name] | [Category] | [Percent]\n"
//                . "• delete skill: [Name] | [Category]\n\n"
//                . "📂 *PROJECTS (Basics)*\n"
//                . "• list projects - Show all projects\n"
//                . "• add project: [Name]\n"
//                . "• update project: [Old Name] | [New Name]\n"
//                . "• delete project: [Name]\n"
//                . "• project status: [Name] | [published/draft/ongoing]\n"
//                . "• project order: [Name] | [Number]\n\n"
//                . "📝 *PROJECT DETAILS*\n"
//                . "• project details: [Name] - Show info\n"
//                . "• project short: [Name] | [Text]\n"
//                . "• project desc: [Name] | [Text]\n"
//                . "• project features: [Name] | [Text]\n"
//                . "• project tools: [Name] | [Tool1, Tool2]\n"
//                . "• delete short: [Name] - Remove short desc\n"
//                . "• delete desc: [Name] - Remove full desc\n"
//                . "• delete features: [Name] - Remove features\n"
//                . "• delete tools: [Name] - Remove tools\n\n"
//                . "🔗 *PROJECT LINKS*\n"
//                . "• project live: [Name] | [URL]\n"
//                . "• project github: [Name] | [URL]\n"
//                . "• project video: [Name] | [URL]\n"
//                . "• delete live: [Name] - Remove live link\n"
//                . "• delete github: [Name] - Remove GitHub link\n"
//                . "• project video remove: [Name] - Remove video\n\n"
//                . "🖼 *PROJECT SCREENSHOTS*\n"
//                . "• list screenshots: [Project Name] - Get IDs\n"
//                . "• add screenshot: [Project] | [Title] - (Attach Image)\n"
//                . "• update screenshot: [Project] | [Title] - (Attach Image)\n"
//                . "• delete screenshot: [Project] | [Title]\n"
//                . "• delete screenshot: [ID] - (Delete by ID)\n\n"
//                . "🦸 *HERO SECTION*\n"
//                . "• show hero\n"
//                . "• hero name: [Name]\n"
//                . "• hero role: [Role]\n"
//                . "• hero title: [Text]\n"
//                . "• hero description: [Text]\n"
//                . "• hero objective: [Text]\n"
//                . "• hero resume: [URL]\n\n"
//                . "ℹ️ *ABOUT SECTION*\n"
//                . "• show about\n"
//                . "• about text: [Text]\n"
//                . "• about photo - (Attach Image)\n"
//                . "• delete about text\n"
//                . "• delete about photo\n\n"
//                . "📞 *CONTACT INFO*\n"
//                . "• show contact\n"
//                . "• contact [phone/email/whatsapp/linkedin/github/location]: [Value]";
//        }
//
//        // ====================================================
        // HELP SYSTEM (Split into Categories to fix 1600 limit)
        // ====================================================

        // 1. MAIN MENU
//        if ($cmd === 'help') {
//            return "🤖 *Portfolio Bot Help Menu*\n"
//                . "Choose a category to see commands:\n\n"
//                . "📌 Type *help general* (Footer, PDF, Contact)\n"
//                . "🛠 Type *help skills* (Add, List, Delete)\n"
//                . "📂 Type *help projects* (Add, List, Status, Order)\n"
//                . "📝 Type *help details* (Desc, Features, Tools)\n"
//                . "🔗 Type *help links* (Live, GitHub, Video)\n"
//                . "🖼 Type *help screenshots* (Add, Delete, List)\n"
//                . "🦸 Type *help hero* (Hero Section)\n"
//                . "ℹ️ Type *help about* (About Section)";
//        }

        // 2. SUB-MENUS
        if ($cmd === 'help') {
            return "📌 *GENERAL & CONTACT*\n\n"
                . "• footer: [year]\n"
                . "• form pdf\n"
                . "• show contact\n"
                . "• contact phone: [Value]\n"
                . "• contact email: [Value]\n"
                . "• contact whatsapp: [Value]\n"
                . "• contact linkedin: [Value]\n"
                . "• contact github: [Value]\n"
                . "• contact location: [Value]";
        }

        if ($cmd === 'help skills') {
            return "🛠 *SKILLS COMMANDS*\n\n"
                . "• list skills\n"
                . "• skill: [Name] | [Category] | [Percent]\n"
                . "• delete skill: [Name] | [Category]";
        }

        if ($cmd === 'help projects') {
            return "📂 *PROJECT BASICS*\n\n"
                . "• list projects\n"
                . "• add project: [Name]\n"
                . "• update project: [Old Name] | [New Name]\n"
                . "• delete project: [Name]\n"
                . "• project status: [Name] | [published/draft/ongoing]\n"
                . "• project order: [Name] | [Number]";
        }

        if ($cmd === 'help details') {
            return "📝 *PROJECT DETAILS*\n\n"
                . "• project details: [Name]\n"
                . "• project short: [Name] | [Text]\n"
                . "• project desc: [Name] | [Text]\n"
                . "• project features: [Name] | [Text]\n"
                . "• project tools: [Name] | [Tool1, Tool2]\n"
                . "• delete short: [Name]\n"
                . "• delete desc: [Name]\n"
                . "• delete features: [Name]\n"
                . "• delete tools: [Name]";
        }

        if ($cmd === 'help links') {
            return "🔗 *PROJECT LINKS*\n\n"
                . "• project live: [Name] | [URL]\n"
                . "• project github: [Name] | [URL]\n"
                . "• project video: [Name] | [URL]\n"
                . "• delete live: [Name]\n"
                . "• delete github: [Name]\n"
                . "• project video remove: [Name]";
        }

        if ($cmd === 'help screenshots') {
            return "🖼 *SCREENSHOTS*\n\n"
                . "• list screenshots: [Project Name]\n"
                . "• add screenshot: [Project] | [Title] (Attach Image)\n"
                . "• update screenshot: [Project] | [Title] (Attach Image)\n"
                . "• delete screenshot: [Project] | [Title]\n"
                . "• delete screenshot: [ID] (Delete by ID)";
        }

        if ($cmd === 'help hero') {
            return "🦸 *HERO SECTION*\n\n"
                . "• show hero\n"
                . "• hero name: [Name]\n"
                . "• hero role: [Role]\n"
                . "• hero title: [Text]\n"
                . "• hero description: [Text]\n"
                . "• hero objective: [Text]\n"
                . "• hero resume: [URL]";
        }

        if ($cmd === 'help about') {
            return "ℹ️ *ABOUT SECTION*\n\n"
                . "• show about\n"
                . "• about text: [Text]\n"
                . "• about photo (Attach Image)\n"
                . "• delete about text\n"
                . "• delete about photo";
        }

        if ($cmd === 'send me form pdf' || $cmd === 'form pdf') {
            return $this->sendPdfViaWhatsApp();
        }

        // project video : Project Name | https://...
        if (preg_match('/^project\s+video\s*:\s*(.+?)\s*\|\s*(https?:\/\/\S+)$/i', $command, $m)) {
            $projectTitle = trim($m[1]);
            $url = trim($m[2]);

            $project = \App\Models\Project::whereRaw('LOWER(title) = ?', [strtolower($projectTitle)])->first();
            if (!$project) return "Project not found: {$projectTitle}";

            $v = \Illuminate\Support\Facades\Validator::make(['video_url' => $url], [
                'video_url' => 'required|url',
            ]);
            if ($v->fails()) return "Invalid URL. Example:\nproject video : {$projectTitle} | https://...";

            $project->update(['video_url' => $url]);
            return "✅ Video link updated for: {$project->title}";
        }
// project video remove : Project Name
        if (preg_match('/^project\s+video\s+remove\s*:\s*(.+)$/i', $command, $m)) {
            $projectTitle = trim($m[1]);

            $project = \App\Models\Project::whereRaw('LOWER(title) = ?', [strtolower($projectTitle)])->first();
            if (!$project) return "Project not found: {$projectTitle}";

            $project->update(['video_url' => null]);
            return "✅ Video link removed for: {$project->title}";
        }

// FOOTER:2025 to 2026
   // top of file


        // FOOTER:2026  → sets footer_year = 2026
        if (preg_match('/^footer[:\s]+(\d{4})$/i', $command, $m)) {
            $year = $m[1];

            \App\Models\Setting::updateOrCreate(
                ['key' => 'footer_year'],
                ['value' => $year]
            );

            return "✅ Footer year set to {$year}.";
        }


// ADD / UPDATE SKILL WITH AUTO-CATEGORY
// skill: tonner refilling | hardware | 80
        if (preg_match('/^skill[:\s]+(.+?)\s*\|\s*(.+?)\s*\|\s*(\d{1,3})%?$/i', $command, $m)) {
            $skillName    = trim($m[1]);
            $categoryName = trim($m[2]);
            $percentage   = (int) $m[3];

            if ($percentage < 0 || $percentage > 100) {
                return "⚠️ Percentage must be between 0 and 100.";
            }

            // find existing or compute next index
            $category = SkillCategory::where('name', $categoryName)->first();

            if (! $category) {
                $max = SkillCategory::max('order_index');   // null if none
                $nextOrder = is_null($max) ? 1 : $max + 1;

                $category = SkillCategory::create([
                    'name'        => $categoryName,
                    'order_index' => $nextOrder,
                ]);
            }

            $skill = Skill::updateOrCreate(
                [
                    'category_id' => $category->id,
                    'skill_name'  => $skillName,
                ],
                [
                    'percentage'  => $percentage,
                ]
            );

            return "✅ Skill saved: {$category->name} → {$skill->skill_name} ({$skill->percentage}%).";
        }


        // DELETE SKILL FROM CATEGORY
// delete skill: Programming | Laravel
        // delete skill: tonner refilling | hardware
        if (preg_match('/^delete skill[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $skillName    = trim($m[1]);   // tonner refilling
            $categoryName = trim($m[2]);   // hardware

            $category = SkillCategory::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();
            if (! $category) {
                return "⚠️ Skill category not found: {$categoryName}";
            }

            $deleted = Skill::where('category_id', $category->id)
                ->whereRaw('LOWER(skill_name) = ?', [strtolower($skillName)])
                ->delete();

            return $deleted
                ? "✅ Deleted skill: {$category->name} → {$skillName}."
                : "⚠️ Skill not found: {$category->name} → {$skillName}.";
        }





        // existing commands...
//        if ($cmd === 'help') {
//            return "Portfolio Admin Bot...\nType 'send me form pdf' to get all submissions";
//        }

        // SHOW HERO
        if ($cmd === 'show hero') {
            $hero = Hero::first();
            return "HERO:\n".($hero->description ?? 'Not set.');
        }

        // SHOW ABOUT
        if ($cmd === 'show about') {
            $about = AboutContent::first();
            return "ABOUT:\n".($about->content ?? 'Not set.');
        }

        // SHOW SHORT DESCRIPTION
        if (str_starts_with($cmd, 'show short:')) {
            $title = trim(substr($command, strlen('show short:')));
            $project = Project::where('title', $title)->first();
            if (! $project) {
                return "Project not found: ".$title;
            }
            return "SHORT (".$project->title."):\n".$project->short_description;
        }

        // SHOW FULL DESCRIPTION
        if (str_starts_with($cmd, 'show desc:')) {
            $title = trim(substr($command, strlen('show desc:')));
            $project = Project::where('title', $title)->first();
            if (! $project) {
                return "Project not found: ".$title;
            }
            return "DESCRIPTION (".$project->title."):\n".$project->description;
        }

        // SHOW FEATURES
        if (str_starts_with($cmd, 'show features:')) {
            $title = trim(substr($command, strlen('show features:')));
            $project = Project::where('title', $title)->first();
            if (! $project) {
                return "Project not found: ".$title;
            }
            return "FEATURES (".$project->title."):\n".$project->features;
        }

        // ... keep the rest of your existing code (rename screenshot, add skill, etc.)


// RENAME SCREENSHOT: rename kit connect: old title -> new title
        if (preg_match('/^rename\s+(.+?):\s*(.+)\s*(?:->|to)\s*(.+)$/i', $command, $m)) {
            $projectName = strtolower(trim($m[1]));   // "kit connect"
            $oldTitle    = trim($m[2]);               // "old title"
            $newTitle    = trim($m[3]);               // "new title"

            $project = Project::whereRaw('LOWER(title) = ?', [$projectName])->first();
            if (!$project) {
                return "⚠️ Project not found: {$projectName}";
            }

            $shot = ProjectScreenshot::where('project_id', $project->id)
                ->whereRaw('LOWER(title) = ?', [strtolower($oldTitle)])
                ->first();

            if (!$shot) {
                return "⚠️ Screenshot '{$oldTitle}' not found in '{$projectName}'.";
            }

            $shot->title = $newTitle;
            $shot->save();

            return "✅ Renamed screenshot '{$oldTitle}' to '{$newTitle}' for '{$projectName}'.";
        }

        // Add Skill
        if (preg_match('/^add skill[:\s]+(.+)/i', $command, $matches)) {
            $text = trim($matches[1]);   // e.g. "React 90" or "React - 90"

            if (preg_match('/^(.*?)[\s\-]+(\d{1,3})%?$/', $text, $m2)) {
                $skillName = trim($m2[1]);
                $percentage = (int)$m2[2];
            } else {
                $skillName = $text;
                $percentage = 80;
            }

            try {
                Skill::create([
                    'category_id' => 1,          // General
                    'skill_name'  => $skillName,
                    'percentage'  => $percentage,
                ]);

                return "✅ Added skill: {$skillName} ({$percentage}%) under General";
            } catch (\Exception $e) {
                return "❌ Failed to add skill: {$e->getMessage()}";
            }
        }


        // Add Project
        if (preg_match('/^add project[:\s]+(.+)/i', $command, $matches)) {
            $projectName = trim($matches[1]);
            try {
                Project::create([
                    'title'        => $projectName,
                    'description'  => 'Added via WhatsApp Bot',
                    'features'     => null,
                    'tools'        => 'Laravel, PHP',
                    'status'       => 'ongoing',
                    'github_link'  => null,
                    'live_link'    => null,
                    'order_index'  => 0,
                ]);
                return "✅ Added project: $projectName";
            } catch (\Exception $e) {
                return "❌ Failed to add project: {$e->getMessage()}";
            }
        }

        // List Skills
        if ($cmd === 'list skills') {
            $skills = Skill::latest()->take(1000)->get();

            if ($skills->isEmpty()) {
                return "📋 No skills found! Add one with:\nadd skill: React 90";
            }

            $response = "📋 Your skills:\n";
            foreach ($skills as $skill) {
                $response .= "• {$skill->skill_name} ({$skill->percentage}%)\n";
            }
            return $response;
        }
        // Update Hero Title: "hero title: I am a Software Developer"
        if (preg_match('/^hero title[:\s]+(.+)/i', $command, $matches)) {
            $newTitle = trim($matches[1]);

            $hero = Hero::first();
            if (!$hero) {
                return "❌ No hero record found in database.";
            }

            $hero->update(['title' => $newTitle]);

            return "✅ Hero title updated to:\n{$hero->title}";
        }

// Update Hero Description: "hero description: <text>"
        if (preg_match('/^hero description[:\s]+(.+)/i', $command, $matches)) {
            $newDesc = trim($matches[1]);

            $hero = Hero::first();
            if (!$hero) {
                return "❌ No hero record found in database.";
            }

            $hero->update(['description' => $newDesc]);

            return "✅ Hero description updated.";
        }

// Project Details
        if (preg_match('/^project details[:\s]+(.+)/i', $command, $matches)) {
            $title = trim($matches[1]);
            $p = Project::where('title', $title)->first();
            if (!$p) return "❌ Project not found: $title";

            return "📌 {$p->title}\n"
                . "Desc: " . ($p->description ?? '-') . "\n"
                . "GitHub: " . ($p->github_url ?? '-') . "\n"
                . "Live: " . ($p->live_url ?? '-') . "\n";
        }

// Delete Project
        if (preg_match('/^delete project[:\s]+(.+)/i', $command, $matches)) {
            $title = trim($matches[1]);
            $p = Project::where('title', $title)->first();
            if (!$p) return "❌ Project not found: $title";

            $p->delete();
            return "✅ Deleted project: $title";
        }

// Update Project Title
        if (preg_match('/^update project[:\s]+(.+)\s*\|\s*(.+)$/i', $command, $matches)) {
            $oldTitle = trim($matches[1]);
            $newTitle = trim($matches[2]);

            $p = Project::where('title', $oldTitle)->first();
            if (!$p) return "❌ Project not found: $oldTitle";

            $p->update(['title' => $newTitle]);
            return "✅ Updated project:\n{$oldTitle} → {$newTitle}";
        }

        // Project Status
        if (preg_match('/^project status[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $title  = trim($m[1]);      // e.g. AI Ecommerce
            $status = trim($m[2]);      // published / draft / ongoing
            $p = Project::where('title', $title)->first();
            if (!$p) return "❌ Project not found: $title";
            $p->update(['status' => $status]);
            return "✅ Status updated: {$title} → {$status}";
        }

// Project Order
        if (preg_match('/^project order[:\s]+(.+?)\s*\|\s*(\d+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $order = (int) $m[2];
            Log::info('DEBUG PROJECT ORDER MATCH', ['command' => $command, 'title' => $title, 'order' => $order]);
            $p = Project::whereRaw('BINARY title = ?', [$title])->first();
            Log::info('DEBUG PROJECT FOUND', ['title_input' => $title, 'project' => $p]);
            if (!$p) return "❌ Project not found: $title";
            $p->update(['order_index' => $order]);
            return "✅ Order updated: {$title} → {$order}";
        }

        // List Projects
        if ($cmd == 'list projects') {
            $projects = Project::latest()->take(1000)->get();
            if ($projects->isEmpty()) {
                return "📂 No projects found! Add one with:\nadd project: [name]";
            } else {
                $response = "📂 Your projects:\n";
                foreach ($projects as $project) {
                    $response .= "• {$project->title}\n";
                }
                return $response;
            }
        }
        // ====================================================
        // 1. ADD SCREENSHOT
        // Format: add screenshot: AI Ecommerce | Dashboard
        // ====================================================
        if (preg_match('/^add screenshot[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $projectName = trim($m[1]);
            $shotTitle   = trim($m[2]);

            // Find Project
            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
            if (!$project) return "⚠️ Project not found: {$projectName}";

            // Check for Image
            $mediaUrl = request()->input('MediaUrl0');
            if (!$mediaUrl) return "⚠️ No image attached. Please attach a photo.";

            try {
                // Upload to Cloudinary
                $uploadedFile = Cloudinary::upload($mediaUrl);
                $secureUrl = $uploadedFile->getSecurePath();

                // Create Record
                ProjectScreenshot::create([
                    'project_id' => $project->id,
                    'title'      => $shotTitle,
                    'image_path' => $secureUrl,
                ]);

                return "✅ Added screenshot '{$shotTitle}' to '{$projectName}'!";
            } catch (\Exception $e) {
                return "❌ Upload Error: " . $e->getMessage();
            }
        }

        // ====================================================
        // 2. UPDATE SCREENSHOT
        // Format: update screenshot: AI Ecommerce | Dashboard
        // ====================================================
        if (preg_match('/^update screenshot[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $projectName = trim($m[1]);
            $shotTitle   = trim($m[2]);

            // Find Project
            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
            if (!$project) return "⚠️ Project not found: {$projectName}";

            // Find Existing Screenshot
            $shot = ProjectScreenshot::where('project_id', $project->id)
                ->whereRaw('LOWER(title) = ?', [strtolower($shotTitle)])
                ->first();

            if (!$shot) return "⚠️ Screenshot '{$shotTitle}' not found in '{$projectName}'.";

            // Check for Image
            $mediaUrl = request()->input('MediaUrl0');
            if (!$mediaUrl) return "⚠️ No image attached. Please attach the new photo.";

            try {
                // Upload to Cloudinary
                $uploadedFile = Cloudinary::upload($mediaUrl);
                $secureUrl = $uploadedFile->getSecurePath();

                // Update Record
                $shot->update(['image_path' => $secureUrl]);

                return "✅ Updated screenshot '{$shotTitle}' successfully!";
            } catch (\Exception $e) {
                return "❌ Upload Error: " . $e->getMessage();
            }
        }

        // ====================================================
        // 3. DELETE SCREENSHOT
        // Format: delete screenshot: AI Ecommerce | Dashboard
        // ====================================================
        if (preg_match('/^delete screenshot[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $projectName = trim($m[1]);
            $shotTitle   = trim($m[2]);

            // Find Project
            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
            if (!$project) return "⚠️ Project not found: {$projectName}";

            // Find Screenshot
            $shot = ProjectScreenshot::where('project_id', $project->id)
                ->whereRaw('LOWER(title) = ?', [strtolower($shotTitle)])
                ->first();

            if (!$shot) return "⚠️ Screenshot '{$shotTitle}' not found in '{$projectName}'.";

            // Delete Record
            $shot->delete();

            return "✅ Deleted screenshot '{$shotTitle}' from '{$projectName}'.";
        }

        // ====================================================
        // 4. LIST SCREENSHOTS (To find the ID)
        // Format: list screenshots: Ecommerce Website
        // ====================================================
        if (preg_match('/^list screenshots[:\s]+(.+)$/i', $command, $m)) {
            $projectName = trim($m[1]);

            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
            if (!$project) return "⚠️ Project not found: {$projectName}";

            $shots = ProjectScreenshot::where('project_id', $project->id)->get();

            if ($shots->isEmpty()) {
                return "📂 No screenshots found for '{$project->title}'.";
            }

            $msg = "🖼️ Screenshots for {$project->title}:\n";
            foreach ($shots as $shot) {
                // Shows ID so you can use it to delete
                $msg .= "🆔 *{$shot->id}* - {$shot->title}\n";
            }
            return $msg;
        }

        // ====================================================
        // 5. DELETE SCREENSHOT BY ID (The "Emergency" Fix)
        // Format: delete screenshot: 15
        // ====================================================
        if (preg_match('/^delete screenshot[:\s]+(\d+)$/i', $command, $m)) {
            $id = (int) $m[1];

            $shot = ProjectScreenshot::find($id);
            if (!$shot) return "❌ Screenshot ID {$id} not found.";

            $shot->delete();

            return "✅ Screenshot deleted (ID: {$id}).";
        }
        // ADD SCREENSHOT: Ecommerce website | Footer Page [ new after cloudinary ]
//        if (preg_match('/^add screenshot[:\s]+(.+)\s*\|\s*(.+)$/i', $command, $m)) {
//            $projectName = trim($m[1]);
//            $shotTitle   = trim($m[2]);
//
//            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
//            if (!$project) return "⚠️ Project not found: {$projectName}";
//
//            $mediaUrl = request()->input('MediaUrl0');
//            if (!$mediaUrl) return "⚠️ No image attached. Please send with an image.";
//
//            try {
//                // NEW: Upload to Cloudinary
//                $uploadedFile = Cloudinary::upload($mediaUrl);
//                $secureUrl = $uploadedFile->getSecurePath();
//
//                ProjectScreenshot::create([
//                    'project_id' => $project->id,
//                    'title'      => $shotTitle,
//                    'image_path' => $secureUrl, // Save the Cloudinary URL
//                ]);
//
//                return "✅ Added screenshot '{$shotTitle}' to Cloudinary!";
//            } catch (\Exception $e) {
//                return "❌ Cloudinary Error: " . $e->getMessage();
//            }
//        }

//        // UPDATE SCREENSHOT [ new after cloudinary]
//        if (preg_match('/^update screenshot[:\s]+(.+)\s*\|\s*(.+)$/i', $command, $m)) {
//            $projectName = trim($m[1]);
//            $shotTitle   = trim($m[2]);
//
//            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
//            if (!$project) return "⚠️ Project not found: {$projectName}";
//
//            $mediaUrl = request()->input('MediaUrl0');
//            if (!$mediaUrl) return "⚠️ No image attached. Please send with a new image.";
//
//            $shot = ProjectScreenshot::where('project_id', $project->id)
//                ->whereRaw('LOWER(title) = ?', [strtolower($shotTitle)])
//                ->first();
//            if (!$shot) return "⚠️ Screenshot not found: '{$shotTitle}'";
//
//            try {
//                // NEW: Upload to Cloudinary
//                $uploadedFile = Cloudinary::upload($mediaUrl);
//                $secureUrl = $uploadedFile->getSecurePath();
//
//                $shot->update([
//                    'image_path' => $secureUrl,
//                ]);
//
//                return "✅ Updated screenshot '{$shotTitle}' on Cloudinary!";
//            } catch (\Exception $e) {
//                return "❌ Cloudinary Error: " . $e->getMessage();
//            }
//        }
        // ABOUT PHOTO
        if (preg_match('/^about photo$/i', $cmd)) {
            $mediaUrl = request()->input('MediaUrl0');
            if (! $mediaUrl) return "⚠️ Send this command with an image attached.";

            try {
                // NEW: Upload to Cloudinary
                $uploadedFile = Cloudinary::upload($mediaUrl);
                $secureUrl = $uploadedFile->getSecurePath();

                $about = AboutContent::first() ?? new AboutContent();
                $about->image_path = $secureUrl;
                $about->save();

                return "✅ About image updated on Cloudinary.";
            } catch (\Exception $e) {
                return "❌ Cloudinary Error: " . $e->getMessage();
            }
        }

//        // ADD SCREENSHOT: Ecommerce website | Footer Page
//        if (preg_match('/^add screenshot[:\s]+(.+)\s*\|\s*(.+)$/i', $command, $m)) {
//            $projectName = trim($m[1]);
//            $shotTitle   = trim($m[2]);
//
//            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
//            if (!$project) return "⚠️ Project not found: {$projectName}";
//
//            $mediaUrl = request()->input('MediaUrl0');
//            if (!$mediaUrl) return "⚠️ No image attached. Please send with an image.";
//
//            // Download image from Twilio (private URL)
//            $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
//                ->get($mediaUrl);
//
//            if (!$response->ok()) {
//                Log::error('TWILIO DOWNLOAD ERROR', [
//                    'status' => $response->status(),
//                    'body'   => $response->body(),
//                ]);
//                return "⚠️ Failed to download image from Twilio.";
//            }
//
//            // Save into storage/app/public/screenshots
//            $ext = 'jpg';
//            $fileName = 'screenshots/'.uniqid('shot_').'.'.$ext;
//            Storage::disk('public')->put($fileName, $response->body());
//
//            // Store public path (served via /storage symlink)
//            ProjectScreenshot::create([
//                'project_id' => $project->id,
//                'title'      => $shotTitle,
//                'image_path' => 'storage/'.$fileName,
//            ]);
//
//            return "✅ Added screenshot '{$shotTitle}' for '{$projectName}'.";
//        }

// UPDATE SCREENSHOT: Ecommerce website | Footer Page
//        if (preg_match('/^update screenshot[:\s]+(.+)\s*\|\s*(.+)$/i', $command, $m)) {
//            $projectName = trim($m[1]);
//            $shotTitle   = trim($m[2]);
//
//            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
//            if (!$project) return "⚠️ Project not found: {$projectName}";
//
//            $mediaUrl = request()->input('MediaUrl0');
//            if (!$mediaUrl) return "⚠️ No image attached. Please send with a new image.";
//
//            $shot = ProjectScreenshot::where('project_id', $project->id)
//                ->whereRaw('LOWER(title) = ?', [strtolower($shotTitle)])
//                ->first();
//            if (!$shot) return "⚠️ Screenshot not found with title '{$shotTitle}' in '{$projectName}'.";
//
//            // Download new image
//            $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
//                ->get($mediaUrl);
//
//            if (!$response->ok()) {
//                Log::error('TWILIO DOWNLOAD ERROR', [
//                    'status' => $response->status(),
//                    'body'   => $response->body(),
//                ]);
//                return "⚠️ Failed to download image from Twilio.";
//            }
//
//            // Optionally overwrite or create new file
//            $ext = 'jpg';
//            $fileName = 'screenshots/'.uniqid('shot_').'.'.$ext;
//            Storage::disk('public')->put($fileName, $response->body());
//
//            $shot->update([
//                'image_path' => 'storage/'.$fileName,
//            ]);
//
//            return "✅ Updated screenshot '{$shotTitle}' for '{$projectName}'.";
//        }
//
//
//        // delete the footer page image in e-commerce web application
//        if (preg_match('/^delete the (.+) image in (.+)$/i', $command, $m)) {
//            $shotTitle   = trim($m[1]); // "footer page"
//            $projectName = trim($m[2]); // "e-commerce web application"
//
//            $project = Project::whereRaw('LOWER(title) = ?', [strtolower($projectName)])->first();
//            if (!$project) {
//                return "⚠️ Project not found: {$projectName}";
//            }
//
//            $deleted = ProjectScreenshot::where('project_id', $project->id)
//                ->whereRaw('LOWER(title) = ?', [strtolower($shotTitle)])
//                ->delete();
//
//            return $deleted
//                ? "✅ Deleted {$shotTitle} image in {$projectName}."
//                : "⚠️ No image titled {$shotTitle} in {$projectName}.";
//        }
// PROJECT SHORT DESCRIPTION
        if (preg_match('/^project short[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $text  = trim($m[2]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['short_description' => $text]);
            return "✅ Short description updated for {$title}.";
        }

// PROJECT DESCRIPTION
        if (preg_match('/^project desc[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $text  = trim($m[2]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['description' => $text]);
            return "✅ Description updated for {$title}.";
        }

// PROJECT FEATURES
        if (preg_match('/^project features[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $text  = trim($m[2]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['features' => $text]);
            return "✅ Features updated for {$title}.";
        }

// PROJECT TOOLS
        if (preg_match('/^project tools[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $text  = trim($m[2]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['tools' => $text]);
            return "✅ Tools updated for {$title}.";
        }

// PROJECT LIVE LINK
        if (preg_match('/^project live[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $url   = trim($m[2]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['live_link' => $url]);
            return "✅ Live link updated for {$title}.";
        }

// PROJECT GITHUB LINK
        if (preg_match('/^project github[:\s]+(.+?)\s*\|\s*(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $url   = trim($m[2]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['github_link' => $url]);
            return "✅ GitHub link updated for {$title}.";
        }
// ABOUT CONTENT
        if (preg_match('/^about text[:\s]+(.+)/i', $command, $m)) {
            $text = trim($m[1]);
            $about = AboutContent::first();
            if (! $about) $about = AboutContent::create(['content' => $text]);
            else $about->update(['content' => $text]);
            return "✅ About content updated.";
        }
        if (preg_match('/^about photo$/i', $cmd)) {
            $mediaUrl = request()->input('MediaUrl0');
            if (! $mediaUrl) return "⚠️ Send this command with an image attached.";

            $response = Http::withBasicAuth(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'))
                ->get($mediaUrl);
            if (! $response->ok()) return "⚠️ Failed to download image.";

            $fileName = 'about/'.uniqid('about_').'.jpg';
            Storage::disk('public')->put($fileName, $response->body());

            $about = AboutContent::first() ?? new AboutContent();
            $about->image_path = 'storage/'.$fileName;
            $about->save();

            return "✅ About image updated.";
        }

// CLEAR SHORT DESCRIPTION
        if (preg_match('/^delete short[:\s]+(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['short_description' => null]);
            return "✅ Short description cleared for {$title}.";
        }

// CLEAR DESCRIPTION
        if (preg_match('/^delete desc[:\s]+(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['description' => null]);
            return "✅ Description cleared for {$title}.";
        }

// CLEAR FEATURES
        if (preg_match('/^delete features[:\s]+(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['features' => null]);
            return "✅ Features cleared for {$title}.";
        }

// CLEAR TOOLS
        if (preg_match('/^delete tools[:\s]+(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['tools' => null]);
            return "✅ Tools cleared for {$title}.";
        }

// CLEAR LIVE LINK
        if (preg_match('/^delete live[:\s]+(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['live_link' => null]);
            return "✅ Live link cleared for {$title}.";
        }

// CLEAR GITHUB LINK
        if (preg_match('/^delete github[:\s]+(.+)$/i', $command, $m)) {
            $title = trim($m[1]);
            $p = Project::where('title', $title)->first();
            if (! $p) return "❌ Project not found: {$title}";
            $p->update(['github_link' => null]);
            return "✅ GitHub link cleared for {$title}.";
        }
// CLEAR ABOUT CONTENT
        if ($cmd === 'delete about text') {
            $about = AboutContent::first();
            if (! $about) return "❌ No about record found.";
            $about->update(['content' => null]);
            return "✅ About content cleared.";
        }
// CLEAR ABOUT IMAGE PATH (does not delete file)
        if ($cmd === 'delete about photo') {
            $about = AboutContent::first();
            if (! $about) return "❌ No about record found.";
            $about->update(['image_path' => null]);
            return "✅ About image cleared.";
        }
        // SHOW HERO CONTENT
        if ($cmd === 'show hero content') {
            $hc = HeroContent::first();
            if (! $hc) return "No hero_content row yet.";
            return "HERO CONTENT:\n"
                ."Name: ".($hc->name ?? '-')."\n"
                ."Title: ".($hc->title ?? '-')."\n"
                ."Objective: ".($hc->objective ?? '-')."\n"
                ."Resume: ".($hc->resume_path ?? '-');
        }

// SET HERO NAME
        if (preg_match('/^hero name[:\s]+(.+)/i', $command, $m)) {
            $name = trim($m[1]);
            $hc = HeroContent::first();

            if (! $hc) {
                $hc = new HeroContent();
                $hc->title     = 'ASPIRING WEB DEVELOPER';
                $hc->objective = 'Enthusiastic AI and Data Science student...';
            }

            $hc->name = $name;
            $hc->save();
            return "✅ Hero name updated to: {$name}";
        }

// SET HERO TITLE
        if (preg_match('/^hero role[:\s]+(.+)/i', $command, $m)) {
            $title = trim($m[1]);
            $hc = HeroContent::first();

            if (! $hc) {
                $hc = new HeroContent();
                $hc->name      = 'GOKULRAJU A';
                $hc->objective = 'Enthusiastic AI and Data Science student...';
            }

            $hc->title = $title;
            $hc->save();
            return "✅ Hero title updated to: {$title}";
        }



// SET HERO TITLE (headline under name)
//        if (preg_match('/^hero role[:\s]+(.+)/i', $command, $m)) {
//            $title = trim($m[1]);
//            $hc = HeroContent::first();
//
//            if (! $hc) {
//                // create with a fallback name so 'name' is not null
//                $hc = new HeroContent();
//                $hc->name = 'GOKULRAJU A';
//            }
//
//            $hc->title = $title;
//            $hc->save();
//
//            return "✅ Hero title updated to: {$title}";
//        }


// SET HERO OBJECTIVE (paragraph on right)
        if (preg_match('/^hero objective[:\s]+(.+)/i', $command, $m)) {
            $obj = trim($m[1]);
            $hc = HeroContent::first() ?? new HeroContent();
            $hc->objective = $obj;
            $hc->save();
            return "✅ Hero objective updated.";
        }

// SET RESUME LINK
        if (preg_match('/^hero resume[:\s]+(\S+)$/i', $command, $m)) {
            $url = trim($m[1]);
            $hc = HeroContent::first() ?? new HeroContent();
            $hc->resume_path = $url;
            $hc->save();
            return "✅ Resume link updated.";
        }
        // SHOW CONTACT INFO
        if ($cmd === 'show contact') {
            $c = ContactInfo::first();
            if (! $c) return "No contact info yet.";

            return "CONTACT INFO:\n"
                ."Phone: ".($c->phone ?? '-') ."\n"
                ."Email: ".($c->email ?? '-') ."\n"
                ."WhatsApp: ".($c->whatsapp ?? '-') ."\n"
                ."LinkedIn: ".($c->linkedin ?? '-') ."\n"
                ."GitHub: ".($c->github ?? '-') ."\n"
                ."Location: ".($c->location ?? '-');
        }

// UPDATE SINGLE CONTACT FIELD
        if (preg_match('/^contact\s+(phone|email|whatsapp|linkedin|github|location)[:\s]+(.+)/i', $command, $m)) {
            $field = strtolower($m[1]);
            $value = trim($m[2]);

            $c = ContactInfo::first();

            if (! $c) {
                $c = new ContactInfo();
                $c->phone    = '+91 9790168632';
                $c->email    = 'annagokul5@gmail.com';
                $c->whatsapp = 'https://wa.me/919790168632';
                $c->linkedin = '#';
                $c->github   = '#';
                $c->location = 'Erode, Tamil Nadu';
            }

            $c->$field = $value;
            $c->save();

            return "✅ Contact {$field} updated.";
        }



        return "❓ Unknown command. Type 'help' for commands.";
    }
    public function sendMessage($message, $to = null)
    {
        try {
            $recipient = $to ?? $this->to;
            $this->twilio->messages->create(
                $recipient,
                [
                    'from' => $this->from,
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            Log::error('❌ Twilio Error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    private function sendPdfViaWhatsApp()
    {
        try {
            $controller = app(\App\Http\Controllers\WhatsappPdfController::class);
            $path = $controller->generateAndStorePdf();  // full local path

            // convert storage path to public URL
            $relative = str_replace(public_path().DIRECTORY_SEPARATOR, '', $path);
            $mediaUrl = url($relative);   // e.g. https://yourdomain.com/storage/whatsapp/...

            $this->twilio->messages->create(
                request('From'),  // send back to the same WhatsApp user
                [
                    'from'     => $this->from,
                    'body'     => 'Here is your latest contact form PDF.',
                    'mediaUrl' => [$mediaUrl],
                ]
            );

            return "✅ PDF is being sent to you.";
        } catch (\Exception $e) {
            Log::error('PDF send failed', ['error' => $e->getMessage()]);
            return "❌ Failed to send PDF. Try again.";
        }
    }


    public function sendTest()
    {
        $message = "🎉 WhatsApp Bot is LIVE!\n\nType 'help' for commands!";
        $this->sendMessage($message);
        return response()->json(['status' => 'Test sent!']);
    }

}

