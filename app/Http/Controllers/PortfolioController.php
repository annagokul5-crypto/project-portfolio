<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Hero;
use App\Models\Setting;
class PortfolioController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', 'published')
            ->orderBy('order_index')
            ->orderByDesc('created_at')
            ->get();

        $skills = Skill::orderBy('category_id')
            ->orderByDesc('percentage')
            ->get();

        $hero = Hero::first();

        return response()->json([
            'hero' => $hero,
            'skills' => $skills,
            'projects' => $projects,
        ]);
    }

    public function page()
    {

        $projects = Project::where('status', 'published')
            ->orderBy('order_index')
            ->orderByDesc('created_at')
            ->get();

        $skills = Skill::orderBy('category_id')
            ->orderByDesc('percentage')
            ->get();

        $hero = Hero::first();

        $footerYear = Setting::where('key', 'footer_year')->value('value') ?? date('Y');

        // If your index.blade.php is the main page:
        return view('index', compact('footerYear','projects','skills','hero'));
    }

}
