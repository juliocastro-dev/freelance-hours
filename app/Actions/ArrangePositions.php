<?php
namespace App\Actions;
use Illuminate\Support\Facades\DB;

class ArrangePositions
{
    public static function run(int $id) 
    {
        $rankedProposals = DB::select("
        SELECT id, row_number() OVER (ORDER BY hours ASC) as p
        FROM proposals
        WHERE project_id = :project
        ", ['project' => $id]);

        // Agora atualize as propostas com base nas posições classificadas
        foreach ($rankedProposals as $proposal) {
            DB::update("
                UPDATE proposals
                SET position = :position
                WHERE id = :id
            ", ['position' => $proposal->p, 'id' => $proposal->id]);
        }
    }
}