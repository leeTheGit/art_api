<?php
namespace mvcr\model;

use mvcr\router\Request;

class Search
{
	protected $db;

	public function __construct(Request $request)
	{
		$this->db = $request->db;
	}

	public function getSearch($search)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		$date = date('Y-m-d');

		$sql = "WITH
				horses as (
					SELECT 'Horse'::text as type,
							runners.name as result,
							races.name as racename,
							runners.number as runnernumber,
							races.id as raceid,
							races.number as racenumber,
							runners.id,
							to_char(races.racetime, 'YYYY-MM-DD HH:MI') as racetime,
							races.meetid,
							meetings.venue as meeting
					FROM runners
					LEFT JOIN races on runners.raceid = races.id
					LEFT JOIN meetings on races.meetid = meetings.id
					WHERE lower(runners.name) ~:search
				),
				jockeys as (
					SELECT 'Jockey'::text as type,
							jockey as result,
							races.name as racename,
							runners.number as runnernumber,
							races.id as raceid,
							races.number as racenumber,
							runners.id,
							to_char(races.racetime, 'YYYY-MM-DD HH:MI') as racetime,
							races.meetid,
							meetings.venue as meeting
					FROM runners
					LEFT JOIN races on runners.raceid = races.id
				LEFT JOIN meetings on races.meetid = meetings.id
					WHERE lower(runners.jockey) ~:search
				),
				races as (
					SELECT 'Race'::text as type,
							races.name as result,
							races.name as racename,
							null::smallint as runnernumber,
							races.id as raceid,
							races.number as racenumber,
							races.id, to_char(races.racetime, 'YYYY-MM-DD HH:MI') as racetime,
							races.meetid,
							meetings.venue as meeting
					FROM races
					LEFT JOIN meetings on races.meetid = meetings.id
					WHERE lower(name) ~:search
				),

					combined as (
						SELECT * from races
						UNION
						SELECT * from horses
						UNION
						SELECT * from jockeys
					)
				SELECT * FROM combined WHERE racetime::Date >= :date
				ORDER BY type, result, meeting, racetime";


		$param = array(":search" => strtolower($search), "date"=>$date);
		$result = $this->db->query($sql, $param);
		return $result;
	}

}
