<?php

namespace eru\nczone\zone;

use eru\nczone\config\config;
use eru\nczone\utility\db;
use eru\nczone\utility\zone_util;

class bets
{
    /** @var db */
    private $db;

    /**
     * Constructor
     *
     * @param db $db Database object
     */
    public function __construct(db $db)
    {
        $this->db = $db;
    }

    public function has_bet(int $user_id, int $match_id): bool
    {
        $sql = '
            SELECT
                COUNT(*)
            FROM
                ' . $this->db->bets_table . ' b
                INNER JOIN ' . $this->db->match_teams_table . ' t 
                ON b.team_id = t.team_id
                INNER JOIN ' . $this->db->matches_table . ' m 
                ON m.match_id = t.match_id AND m.match_id = ' . $match_id . ' 
            WHERE
                b.user_id = ' . $user_id . '
            ;
        ';
        return $this->db->get_int_var($sql) > 0;
    }

    public function place_bet(int $user_id, int $match_id, int $team): void
    {
        $team_ids = zone_util::matches()->get_match_team_ids($match_id);
        $this->db->insert($this->db->bets_table, [
            'user_id' => $user_id,
            'time' => time(),
            'team_id' => $team_ids[$team - 1],
            'counted' => 0,
        ]);
    }

    public function get_bets(
        int $team1_id,
        int $team2_id,
        bool $counted_only = false
    ): array {
        $rows = $this->db->get_rows([
            'SELECT' => 'b.user_id, b.team_id, b.time, u.username',
            'FROM' => [$this->db->bets_table => 'b', $this->db->users_table => 'u'],
            'WHERE' => 'b.user_id = u.user_id AND ' . $this->db->sql_in_set('b.team_id', [$team1_id, $team2_id]). ($counted_only ? ' AND b.counted' : '')
        ]);

        $bets = [
            'team1' => [],
            'team2' => [],
        ];
        foreach($rows as $r)
        {
            $teamIndex = (int)$r['team_id'] === $team1_id ? 'team1' : 'team2';
            $bets[$teamIndex][] = [
                'timestamp' => (int)$r['time'],
                'user' => [
                    'id' => (int)$r['user_id'],
                    'username' => $r['username'],
                ],
            ];
        }

        return $bets;
    }

    public function evaluate_bets(int $winner_team, int $loser_team, int $end_time): void
    {
        $until = $end_time - ((int)config::get(config::bet_time));

        $correct_user_ids = $this->get_open_bet_user_ids_until($winner_team, $until);
        $wrong_user_ids = $this->get_open_bet_user_ids_until($loser_team, $until);

        $this->set_bets_counted_until([$winner_team, $loser_team], $until);

        if($correct_user_ids)
        {
            $this->change_won_bets($correct_user_ids, 1);
        }
        if($wrong_user_ids)
        {
            $this->change_lost_bets($wrong_user_ids, 1);
        }
    }

    private function get_open_bet_user_ids_until(
        int $team_id,
        int $until
    ): array {
        return $this->db->get_col([
            'SELECT' => 't.user_id',
            'FROM' => [$this->db->bets_table => 't'],
            'WHERE' => 't.team_id = ' . $team_id . ' AND t.time <= ' . $until,
        ]);
    }

    public function evaluate_bets_undo(int $winner_team, int $loser_team, int $end_time): void
    {
        $correct_user_ids = $this->get_counted_bet_user_ids($winner_team);
        $wrong_user_ids = $this->get_counted_bet_user_ids($loser_team);

        $this->set_bets_uncounted([$winner_team, $loser_team]);

        if($correct_user_ids)
        {
            $this->change_won_bets($correct_user_ids, -1);
        }
        if($wrong_user_ids)
        {
            $this->change_lost_bets($wrong_user_ids, -1);
        }
    }

    /**
     * @param int $team_id
     * @return array
     */
    private function get_counted_bet_user_ids(int $team_id): array
    {
        return $this->db->get_col([
            'SELECT' => 't.user_id',
            'FROM' => [$this->db->bets_table => 't'],
            'WHERE' => 't.team_id = ' . $team_id . ' AND t.counted',
        ]);
    }

    private function change_won_bets(array $user_ids, int $step): void
    {
        $this->db->sql_query('
            UPDATE ' . $this->db->players_table . '
            SET `bets_won` = `bets_won` + ' . $step .' 
            WHERE ' . $this->db->sql_in_set('user_id', $user_ids)
        );
    }

    private function change_lost_bets(array $user_ids, int $step): void
    {
        $this->db->sql_query('
            UPDATE ' . $this->db->players_table . ' 
            SET `bets_loss` = `bets_loss` + ' . $step . ' 
            WHERE ' . $this->db->sql_in_set('user_id', $user_ids));
    }

    private function set_bets_counted_until(array $team_ids, int $until): void
    {
        $this->db->sql_query('
            UPDATE ' . $this->db->bets_table . ' 
            SET `counted` = 1 
            WHERE 
                '. $this->db->sql_in_set('team_id', $team_ids).'
                AND `time` <= ' . $until
        );
    }

    private function set_bets_uncounted(array $team_ids): void
    {
        $this->db->sql_query('
            UPDATE ' . $this->db->bets_table . ' 
            SET `counted` = 0 
            WHERE 
                '. $this->db->sql_in_set('team_id', $team_ids)
        );
    }
}
