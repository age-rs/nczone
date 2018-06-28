<?php
/**
 *
 * nC Zone. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2018, Marian Cepok, https://new-chapter.eu
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'ACP_NCZONE_SAVED' => 'Saved!',

	'NCZONE_RULES_POST_ID' => 'Rules post ID',
	'NCZONE_RULES_POST_ID_DESCR' => 'The ID of the post which should be displayed on the rules page.',
	'NCZONE_MATCH_FORUM_ID' => 'Post forum ID',
	'NCZONE_MATCH_FORUM_ID_DESCR' => 'The ID of the forum in which match results will be posted.',
	'NCZONE_PMATCHES_PAGE_SIZE' => 'Past matches page size',
	'NCZONE_PMATCHES_PAGE_SIZE_DESCR' => 'Number of past matches showed on a single page in the zone.',
	'NCZONE_ACTIVITY_TIME' => 'Activity time',
	'NCZONE_ACTIVITY_TIME_DESCR' => 'The time intervall (in days) in which activity will be counted.',
	'NCZONE_ACTIVITY' => 'Activity',
	'NCZONE_ACTIVITY_DESCR' => 'Number of matches played in the time intervall above to be played to reach activity 1/2/3/4/5.',
	'NCZONE_DRAW_TIME' => 'Draw time',
	'NCZONE_DRAW_TIME_DESCR' => 'The amount of time a player can confirm a drawing.',
	'NCZONE_BET_TIME' => 'Bet time',
	'NCZONE_BET_TIME_DESCR' => 'Time (in seconds) a bet has to be placed before the ending of a match to be counted.',
	'NCZONE_INFO_POSTS' => 'Information post IDs',
	'NCZONE_INFO_POSTS_DESCR' => 'IDs of the posts which will be displayed as \'Max important information\'.',

	'NCZONE_DRAW_SETTINGS' => 'Draw settings',
	'NCZONE_DRAW_PLAYER_CIVS' => 'Difference for player civs',
	'NCZONE_DRAW_PLAYER_CIVS_DESCR' => 'Minimum difference of ratings between the player with highest rating and the player with lowest rating in order to draw player civs.',
	'NCZONE_DRAW_TEAM_CIVS' => 'Difference for team civs',
	'NCZONE_DRAW_TEAM_CIVS_DESCR' => 'Minimum difference of ratings of the teams in order to draw team civs.',
	'NCZONE_DRAW_MATCH_EXTRA_CIVS' => 'Extra civs for match civs',
	'NCZONE_DRAW_MATCH_EXTRA_CIVS_DESCR' => 'Number of extra civs to draw for match civs. A higher value results in civs with a more similiar multiplier, but also increases calculation time. 1vs1 / 2vs2 / 3vs3 / 4vs4',
	'NCZONE_DRAW_TEAM_EXTRA_CIVS' => 'Extra civs for team civs',
	'NCZONE_DRAW_TEAM_EXTRA_CIVS_DESCR' => 'Number of extra civs to draw for team civs. A higher value results in fairer civs in relation to the rating difference of the teams, but also increases calculation time. 1vs1 / 2vs2 / 3vs3 / 4vs4',
	'NCZONE_DRAW_PLAYER_NUM_CIVS' => 'Number civs for player civs',
	'NCZONE_DRAW_PLAYER_NUM_CIVS_DESCR' => 'Number of civs to draw for player civs. A higher value results in fairer civs in relation to the rating difference of the teams, but also increases calculation time <i>a lot</i>. The value should be at least 1 and at least 2 to enable the mechanism to draw fair(er) civs. 1vs1 / 2vs2 / 3vs3 / 4vs4',
));
