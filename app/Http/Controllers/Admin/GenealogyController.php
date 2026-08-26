<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GenealogyController extends Controller
{
    /**
     * Display the Team Saya (Generasi Multi-Tier Level 1-12) page.
     * Replaces binary tree with Unilevel Team Structure.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user() ?: User::first();
        
        // Focus user selection
        $focusId = $request->query('focus_id');
        $username = $request->query('username');

        $focusedUser = null;
        if ($focusId) {
            $focusedUser = User::find($focusId);
        } elseif ($username) {
            $focusedUser = User::where('username', $username)->first();
        }

        if (!$focusedUser) {
            $focusedUser = $currentUser;
        }

        $selectedLevel = $request->input('level', 'all'); // 'all' or 1..12
        $search = $request->input('search', '');
        $multiplier = (int) $request->input('multiplier', 5); // 5, 10, 15, etc.
        if ($multiplier < 1) {
            $multiplier = 5;
        }

        // Generate 12 Team Levels (Generasi 1 - 12)
        $teamLevels = [];
        $currentLevelUserIds = [$focusedUser->id];

        $totalMembersAllLevels = 0;
        $totalCommissionAllLevels = 0;
        $activeLevelsCount = 0;
        $allMembersList = [];

        for ($level = 1; $level <= 12; $level++) {
            $commPerMember = $this->getCommissionForLevel($level);
            $projectionCount = pow($multiplier, $level);
            $projectionTotalComm = $projectionCount * $commPerMember;

            if (empty($currentLevelUserIds)) {
                $teamLevels[$level] = [
                    'level'                 => $level,
                    'name'                  => "Team {$level}",
                    'count'                 => 0,
                    'commission_per_member' => $commPerMember,
                    'total_commission'      => 0,
                    'projection_count'      => $projectionCount,
                    'projection_commission' => $projectionTotalComm,
                    'members'               => [],
                ];
                continue;
            }

            // Fetch members at this level (parent_id in current level user IDs)
            $levelMembers = User::whereIn('parent_id', $currentLevelUserIds)
                ->select('id', 'name', 'username', 'email', 'parent_id', 'created_at', 'package_name', 'phone')
                ->with('parent:id,name,username')
                ->orderBy('id', 'desc')
                ->get();

            $nextUserIds = $levelMembers->pluck('id')->toArray();
            $count = $levelMembers->count();

            if ($count > 0) {
                $activeLevelsCount++;
            }

            $totalMembersAllLevels += $count;
            $levelTotalComm = $count * $commPerMember;
            $totalCommissionAllLevels += $levelTotalComm;

            $formattedMembers = $levelMembers->map(function ($m) use ($level, $commPerMember) {
                return [
                    'id'           => $m->id,
                    'name'         => $m->name,
                    'username'     => $m->username ?: 'user' . $m->id,
                    'email'        => $m->email,
                    'phone'        => $m->phone ?? '-',
                    'sponsor'      => $m->parent ? '@' . ($m->parent->username ?: 'user' . $m->parent->id) . ' (' . $m->parent->name . ')' : 'FOUNDER',
                    'package'      => $m->package_name ?: 'Basic DP Rp 500rb',
                    'joined_at'    => $m->created_at ? $m->created_at->format('d/m/Y') : '-',
                    'level'        => "Team {$level}",
                    'level_num'    => $level,
                    'commission'   => $commPerMember,
                ];
            })->toArray();

            $teamLevels[$level] = [
                'level'                 => $level,
                'name'                  => "Team {$level}",
                'count'                 => $count,
                'commission_per_member' => $commPerMember,
                'total_commission'      => $levelTotalComm,
                'projection_count'      => $projectionCount,
                'projection_commission' => $projectionTotalComm,
                'members'               => $formattedMembers,
            ];

            foreach ($formattedMembers as $fm) {
                $allMembersList[] = $fm;
            }

            $currentLevelUserIds = $nextUserIds;
        }

        // Filter members list based on selected level & search keyword
        $filteredMembers = collect($allMembersList);

        if ($selectedLevel !== 'all') {
            $lvlNum = (int) $selectedLevel;
            $filteredMembers = $filteredMembers->filter(fn($m) => $m['level_num'] === $lvlNum);
        }

        if (!empty($search)) {
            $searchLower = strtolower($search);
            $filteredMembers = $filteredMembers->filter(function ($m) use ($searchLower) {
                return str_contains(strtolower($m['name']), $searchLower)
                    || str_contains(strtolower($m['username']), $searchLower)
                    || str_contains(strtolower($m['email']), $searchLower)
                    || str_contains(strtolower($m['sponsor']), $searchLower);
            });
        }

        $allUsers = User::select('id', 'name', 'username', 'email')->get()->map(function ($u) {
            return [
                'id'       => $u->id,
                'name'     => $u->name,
                'username' => $u->username ?: ('@' . strtolower(explode(' ', $u->name)[0])),
                'label'    => $u->name . ' (' . ($u->username ? '@' . $u->username : $u->email) . ')',
            ];
        });

        $directSponsorsCount = $teamLevels[1]['count'] ?? 0;

        return Inertia::render('Admin/Genealogy/Index', [
            'focus_user' => [
                'id'       => $focusedUser->id,
                'name'     => $focusedUser->name,
                'username' => $focusedUser->username ? '@' . $focusedUser->username : '@admin',
            ],
            'summary' => [
                'total_members'    => $totalMembersAllLevels,
                'direct_sponsors'  => $directSponsorsCount,
                'total_commission' => $totalCommissionAllLevels,
                'active_levels'    => $activeLevelsCount,
            ],
            'team_levels'      => array_values($teamLevels),
            'filtered_members' => array_values($filteredMembers->toArray()),
            'all_users'        => $allUsers,
            'filters' => [
                'level'      => (string) $selectedLevel,
                'search'     => $search,
                'multiplier' => $multiplier,
                'focus_id'   => $focusedUser->id,
            ],
        ]);
    }

    /**
     * Get commission per member by team level according to marketing plan.
     */
    private function getCommissionForLevel(int $level): float
    {
        return match (true) {
            $level >= 1 && $level <= 7  => 5000,
            $level == 8                 => 3000,
            $level >= 9 && $level <= 10 => 2000,
            $level >= 11 && $level <= 12=> 1000,
            default                     => 0,
        };
    }
}

