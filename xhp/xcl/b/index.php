<?php
	require('../xcc_common.php');

	header('refresh: 300');
	db_connect();

	function a2lid($v)
	{
		switch ($v)
		{
		case '1':
		case 'ra2':
			return 1;
		case '2':
		case 'ra2_clan':
			return 2;
		case '3':
		case 'ra2_yr':
			return 3;
		case '4':
		case 'ra2_yr_clan':
			return 4;
		case '5':
		case 'ebfd':
			return 5;
		case '6':
		case 'ebfd_clan':
			return 6;
		case '7':
		case 'ts':
			return 7;
		case '8':
		case 'ts_clan':
			return 8;
		}
		return 0;
	}

	function js_encode($v)
	{
		return addcslashes($v, '\'');
	}

	$cid = isset($_REQUEST['cid']) ? 0 + $_REQUEST['cid'] : 0;
	$gid = isset($_REQUEST['gid']) ? 0 + $_REQUEST['gid'] : 0;
	$lid = isset($_REQUEST['lid']) ? 0 + a2lid($_REQUEST['lid']) : 0;
	$pid = isset($_REQUEST['pid']) ? 0 + $_REQUEST['pid'] : 0;
	$pname = isset($_REQUEST['pname']) ? trim($_REQUEST['pname']) : '';
	$s = isset($_REQUEST['s']) ? trim($_REQUEST['s']) : '';
	if (0 + $s)
		$gid = $s;
	else if (empty($pname))
		$pname = $s;
	if (isset($_REQUEST['js']))
	{
		$pnames = explode(',', $pname);
		foreach ($pnames as $key => $pname)
			$pnames[$key] = sprintf("'%s'", AddSlashes(trim($pname)));
		$results = db_query(sprintf("select * from %s left join %s using (pid) where name in (%s)", $tables['players'], $tables['players_rank'], implode(",", $pnames)));
		while ($result = mysql_fetch_assoc($results))
			printf("document.write('<a href=\"http://xwis.net/xcl/?%s=%d\">%s</a>: #%d %d / %d %dp<br>');", $result['lid'] & 1 ? "pid" : "cid", $result['pid'], $result['name'], $result['rank'], $result['win_count'], $result['loss_count'], $result['points']);
		return;
	}
	else if (isset($_REQUEST['pure']))
	{
		if ($cid)
		{
			$results = db_query(sprintf("select p.name, sum(pc > 0) w, sum(pc < 0) l, sum(greatest(pc, 0)) pw, sum(least(pc, 0)) pl, sum(pc) pc from %s gp inner join %s p using (pid) where cid = %d group by p.pid order by name", $tables['games_players'], $tables['players'], $cid));
			while ($result = mysql_fetch_assoc($results))
				printf("%s %d %d %d %d %d\n", $result['name'], $result[w], $result[l], $result[pw], $result[pl], $result[pc]);
		}
		else if ($lid || $pid)
		{
			if ($pid)
				$results = db_query(sprintf("select * from %s left join %s using (pid) where pid = %d", $tables['players'], $tables['players_rank'], $pid));
			else if ($pname)
				$results = db_query(sprintf("select * from %s left join %s using (pid) where lid = %d and name = '%s'", $tables['players'], $tables['players_rank'], $lid, AddSlashes($pname)));
			else
				$results = db_query(sprintf("select * from %s left join %s using (pid) where lid = %d and points", $tables['players'], $tables['players_rank'], $lid));
			while ($result = mysql_fetch_assoc($results))
				printf("%d %d %d %d %s\n", $result['rank'], $result['win_count'], $result['loss_count'], $result['points'], $result['name']);
		}
		return;
	}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<script type="text/javascript" src="xcl.js"></script>
<script type="text/javascript">page_top(<?php echo $frozen ? 1 : 0; ?>);
<?php
	function echo_hof($lid)
	{
		global $tables;
		printf("t13(%d,new Array(", $lid);
		$results = db_query(sprintf("select * from %s where lid = %d order by points desc limit 10", $tables['players'], $lid));
		while ($result = mysql_fetch_assoc($results))
			printf("'%s',", $result['name']);
		printf("0));");
	}

	function echo_player($v)
	{
		return sprintf("%d,%d,'%s',%d,%d,%d,%d,%d,'%s',%d,%d,%d,%d,%d,%d",
			$v['rank'], $v['pid'], $v['name'], $v['win_count'], $v['loss_count'], $v['points'],
			$v['crank'], $v['cid'], $v['cname'], $v['cwin_count'], $v['closs_count'], $v['cpoints'], $v['cty'], $v['cmp'], $v['pc']);
	}

	function echo_games($results, $pid, $cid, $unfair_games)
	{
		global $tables;
		printf("tr1f(%d);", $cid);
		if ($result = mysql_fetch_assoc($results))
		{
			do
			{
				$players_result = db_query(sprintf("
					select t1.*, t2.*, t4.rank, t5.rank crank, t3.name cname, t3.win_count cwin_count, t3.loss_count closs_count, t3.points cpoints
					from %s t1 inner join %s t2 using (pid) left join %s t4 using (pid) left join %s t3 on (t1.cid = t3.pid) left join %s t5 on (t3.pid = t5.pid)
					where gid = %d
					order by %s
					",
					$tables['games_players'],
					$tables['players'],
					$tables['players_rank'],
					$tables['players'],
					$tables['players_rank'],
					$result[gid],
					$cid ? sprintf("cid != %d, t2.pid", $cid) : ($pid ? sprintf("t2.pid != %d", $pid) : "cid, t2.pid")));
				$plrs = mysql_num_rows($players_result) / 2;
				for ($player_i = 0; $players[$player_i] = mysql_fetch_assoc($players_result); $player_i++)
					;
				$player_a = 0;
				$player_b = $plrs;
				printf("tr1a(%d,%d,new Array(%s,%s),%d,'%s',%d,%d,%d,%d,%d,%d);", $result[gid], $result[ws_gid], echo_player($players[$player_a++]), echo_player($players[$player_b++]),
					$result['dura'], js_encode($result['scen']), $result['mtime'], $result['afps'], $result['crat'], $result['oosy'], $result['trny'], $unfair_games);
				while ($player_a < $plrs)
					printf("tr1d(new Array(%s,%s));", echo_player($players[$player_a++]), echo_player($players[$player_b++]));
			}
			while ($result = mysql_fetch_assoc($results));
		}
		else
			echo("tr1g();");
		echo("tr1h();");
	}

	printf("page_search(%d);", $lid);
	if (isset($_REQUEST['hof']))
	{
		echo("t13a();");
		echo_hof(1);
		echo_hof(2);
		echo_hof(3);
		echo_hof(4);
		echo_hof(7);
		echo_hof(8);
		echo("t13c();");
		$results = db_query(sprintf("select * from %s order by date desc, lid, rank", $tables['hof']));
		while ($result = mysql_fetch_assoc($results))
			$v[$result['date']][$result['lid']][] = $result['name'];
		foreach ($v as $date => $w)
		{
			printf("t13d('%s',new Array(", gmdate("F Y", gmmktime(0, 0, 0, substr($date, 5, 2), 1, substr($date, 0, 4))));
			foreach ($w as $lid => $x)
			{
				printf("%d,new Array(", $lid);
				foreach ($x as $name)
					printf("'%s',", $name);
				printf("0),");
			}
			printf("0));");
		}
	}
	else if (isset($_REQUEST['hos']))
	{
		echo("t14(new Array(");
		$results = db_query(sprintf("select distinct p.name from %s p inner join bl using (name) order by p.name", $tables['players']));
		while ($result = mysql_fetch_assoc($results))
			printf("'%s',", $result['name']);
		echo("0));");
	}
	else if (isset($_REQUEST['stats']))
	{
		$games = array();
		$results = db_query(sprintf("select * from %s", $tables['stats_gsku']));
		while ($result = mysql_fetch_assoc($results))
		{
			$games[$result['gsku']][$result['trny']]['games'] = $result['games'];
			$games[-1][$result['trny']]['games'] += $result['games'];
			$games[$result['gsku']][$result['trny']]['players'] = $result['players'];
			$games[-1][$result['trny']]['players'] += $result['players'];
			$games[$result['gsku']][$result['trny']]['clans'] = $result['clans'];
			$games[-1][$result['trny']]['clans'] += $result['clans'];
		}
		echo("p6(new Array(");
		foreach ($games as $gsku => $game)
		{
			if ($gsku != -1)
				printf("%d,%d,%d,%d,%d,%d,%d,%d,", $gsku, $game[0]['games'], $game[1]['games'], $game[2]['games'], $game[0]['players'], $game[1]['players'], $game[2]['players'], $game[2]['clans']);
		}
		$game = $games[-1];
		printf("0,%d,%d,%d,%d,%d,%d,%d),new Array(", $game[0]['games'], $game[1]['games'], $game[2]['games'], $game[0]['players'], $game[1]['players'], $game[2]['players'], $game[2]['clans']);
		$d = array();
		$results = db_query(sprintf("select * from %s order by count desc", $tables['stats_countries']));
		while ($result = mysql_fetch_assoc($results))
			$d[$result['cty']][$result['gsku']][$result['trny']] = $result['count'];
		foreach($d as $cty => $d0)
			printf("%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,", $cty, $d0[18][0], $d0[18][1], $d0[18][2], $d0[33][0], $d0[33][1], $d0[33][2], $d0[41][0], $d0[41][1], $d0[41][2]);
		echo("0),new Array(");
		$results = db_query(sprintf("select lid, name, count from %s order by count desc", $tables['stats_games']));
		while ($result = mysql_fetch_assoc($results))
			printf("%d,%d,'%s',", $result['count'], $result['lid'], $result['name']);
		echo("0),new Array(");
		$d = array();
		$results = db_query(sprintf("select * from %s order by count desc", $tables['stats_maps']));
		while ($result = mysql_fetch_assoc($results))
			$d[$result['scen']][$result['gsku']][$result['trny']] = $result['count'];
		foreach($d as $scen => $d0)
		{
			if ($d0[18][0] + $d0[18][1] + $d0[18][2] + $d0[33][0] + $d0[33][1] + $d0[33][2] + $d0[41][0] + $d0[41][1] + $d0[41][2] >= 100)
				printf("'%s',%d,%d,%d,%d,%d,%d,%d,%d,%d,", js_encode($scen), $d0[18][0], $d0[18][1], $d0[18][2], $d0[33][0], $d0[33][1], $d0[33][2], $d0[41][0], $d0[41][1], $d0[41][2]);
		}
		echo("0),new Array(");
		$d = array();
		$results = db_query(sprintf("select * from %s order by dura", $tables['stats_dura']));
		while ($result = mysql_fetch_assoc($results))
			$d[$result['dura']][$result['gsku']][$result['trny']] = $result['count'];
		foreach($d as $dura => $d0)
			printf("%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,", $dura, $d0[18][0], $d0[18][1], $d0[18][2], $d0[33][0], $d0[33][1], $d0[33][2], $d0[41][0], $d0[41][1], $d0[41][2]);
		echo("0),new Array(");
		$d = array();
		$results = db_query(sprintf("select * from %s where afps < 60 order by afps", $tables['stats_afps']));
		while ($result = mysql_fetch_assoc($results))
			$d[$result['afps']][$result['gsku']][$result['trny']] = $result['count'];
		foreach($d as $afps => $d0)
			printf("%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,", $afps, $d0[18][0], $d0[18][1], $d0[18][2], $d0[33][0], $d0[33][1], $d0[33][2], $d0[41][0], $d0[41][1], $d0[41][2]);
		echo("0),new Array(");
		$games = array();
		$results = db_query(sprintf("select d, h, sum(c) c from %s group by d, h", $tables['stats_time']));
		while ($result = mysql_fetch_assoc($results))
			$games[$result['d']][$result['h']] = $result['c'];
		foreach ($games as $d => $hours)
		{
			printf("%d,", $d);
			for ($h = 0; $h < 24; $h++)
				printf("%d,", $hours[$h]);
		}
		echo("0));");
	}
	else
	{
		$recent_games = isset($_REQUEST['recent_games']);
		$unfair_games = isset($_REQUEST['unfair_games']);
		$wash_games = isset($_REQUEST['wash_games']) ? $_REQUEST['wash_games'] : 0;
		if ($cid || $gid || $pid || $recent_games || $unfair_games || $wash_games)
		{
			if ($gid)
				$results = db_query(sprintf("
					select t1.*, t3.name scen
					from %s t1 left join %s t3 using (mid)
					where t1.gid = %d
					order by gid desc
					", $tables['games'], $tables['maps'], $gid));
			else if ($recent_games)
				$results = db_query(sprintf("
					select t1.*, t3.name scen
					from %s t1 left join %s t3 using (mid)
					order by gid desc
					limit 100
					", $tables['games'], $tables['maps']));
			else if ($unfair_games)
			{
				$results = db_query(sprintf("
					select distinct t1.*, t4.name scen
					from bl inner join %s using (name) inner join %s t2 using (pid) inner join %s t1 using (gid) inner join %s t3 using (gid) left join %s t4 using (mid)
					where t2.pid != t3.pid and not t3.cid and t3.pc < 0
					order by gid desc
					limit 250
					", $tables['players'], $tables['games_players'], $tables['games'], $tables['games_players'], $tables['maps']));
				echo_games($results, 0, 0, true);
				echo("document.write('<hr>');");
				$results = db_query(sprintf("
					select distinct t1.*, t4.name scen
					from bl inner join %s p using (name) inner join %s t2 on p.pid = t2.pid inner join %s t1 using (gid) inner join %s t3 using (gid) left join %s t4 using (mid)
					where t2.cid != t3.cid and t3.pc < 0
					order by gid desc
					limit 100
					", $tables['players'], $tables['games_players'], $tables['games'], $tables['games_players'], $tables['maps']));
			}
			else if ($wash_games)
				$results = db_query(sprintf("
					select t1.*, t3.name scen
					from %s t1 left join %s t3 using (mid)
					where oosy
					order by gid desc
					", $tables['games'], $tables['maps']));
			else
			{
				$results = db_query(sprintf("select * from %s left join %s using (pid) where pid = %d", $tables['players'], $tables['players_rank'], $cid ? $cid : $pid));
				if ($result = mysql_fetch_assoc($results))
				{
					echo("t15(new Array(");
					do
					{
						printf("%d,%d,%d,'%s',%d,%d,%d,%d,%d,%d,", $result['rank'], $result['lid'], $result['pid'], $result['name'], $result['win_count'], $result['loss_count'], $result['points'], $result['points_max'], $result['mtime'], $result['countries']);
					}
					while ($result = mysql_fetch_assoc($results));
					echo("0));");
				}
				$results = db_query($cid
					? sprintf("select distinct t1.*, t3.name scen from %s t1 inner join %s t2 using (gid) left join %s t3 using (mid) where t2.cid = %d order by gid desc", $tables['games'], $tables['games_players'], $tables['maps'], $cid)
					: sprintf("select distinct t1.*, t3.name scen from %s t1 inner join %s t2 using (gid) left join %s t3 using (mid) where not t2.cid and t2.pid = %d order by gid desc", $tables['games'], $tables['games_players'], $tables['maps'], $pid));
			}
			echo_games($results, $pid, $cid, $unfair_games);
			if ($cid || $pid)
			{
				if ($cid)
				{
					$results = db_query(sprintf("select p.name, sum(pc > 0) w, sum(pc < 0) l, sum(greatest(pc, 0)) pw, sum(least(pc, 0)) pl, sum(pc) pc from %s gp inner join %s p using (pid) where cid = %d group by p.pid order by name", $tables['games_players'], $tables['players'], $cid));
					echo('t2(new Array(');
					while ($result = mysql_fetch_assoc($results))
					{
						printf("'%s',%d,%d,%d,%d,%d,", $result['name'], $result[w], $result[l], $result[pw], $result[pl], $result[pc]);
					}
					echo("0));");
				}
				$results = db_query($cid
					? sprintf("select cty, count(*) count from %s where cid = %d group by cty order by count desc", $tables['games_players'], $cid)
					: sprintf("select cty, count(*) count from %s where not cid and pid = %d group by cty order by count desc", $tables['games_players'], $pid));
				if ($result = mysql_fetch_assoc($results))
				{
					echo('t3(new Array(');
					do
					{
						printf('%d,%d,', $result['count'], $result['cty']);
					}
					while ($result = mysql_fetch_assoc($results));
					echo('0));');
				}
				$results = db_query($cid
					? sprintf("select m.name scen, count(*) count from %s g inner join %s using (gid) left join %s m using (mid) where cid = %d group by scen order by count desc", $tables['games'], $tables['games_players'], $tables['maps'], $cid)
					: sprintf("select m.name scen, count(*) count from %s g inner join %s using (gid) left join %s m using (mid) where not cid and pid = %d group by scen order by count desc", $tables['games'], $tables['games_players'], $tables['maps'], $pid));
				if ($result = mysql_fetch_assoc($results))
				{
					echo('t4(new Array(');
					do
					{
						printf("%d,'%s',", $result['count'], js_encode($result['scen']));
					}
					while ($result = mysql_fetch_assoc($results));
					echo('0));');
				}
			}
			else if ($gid)
			{
				$results = db_query(sprintf("select * from %s inner join %s using (pid) where gid = %d", $tables['games_players'], $tables['players'], $gid));
				echo("t5(new Array(");
				while ($result = mysql_fetch_assoc($results))
				{
					printf("'%s',%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,%d,", $result['name'],
						$result['unk'], $result['unb'], $result['unl'],
						$result['blk'], $result['blb'], $result['bll'], $result['blc'],
						$result['ink'], $result['inb'], $result['inl'],
						$result['plk'], $result['plb'], $result['pll']);
				}
				echo("0));");
			}
		}
		else
		{
			if ($lid || $pname)
			{
				$cty = $_REQUEST['cty'] ? sprintf("and !(countries & %d)", $_REQUEST['cty']) : '';
				$results = db_query($pname
					? $lid
					? sprintf("select * from %s left join %s using (pid) where lid = %d and name like '%s' order by points desc, rank limit 250", $tables['players'], $tables['players_rank'], $lid, AddSlashes($pname))
					: sprintf("select * from %s left join %s using (pid) where lid < 17 and name like '%s' order by points desc, rank limit 250", $tables['players'], $tables['players_rank'], AddSlashes($pname))
					: sprintf("select * from %s left join %s using (pid) where lid = %d and points %s order by points desc, rank limit 250", $tables['players'], $tables['players_rank'], $lid, $cty));
				echo('t0(new Array(');
				while ($result = mysql_fetch_assoc($results))
					printf("%d,%d,%d,'%s',%d,%d,%d,%d,%d,%d,", $result['rank'], $result['lid'], $result['pid'], $result['name'], $result['win_count'], $result['loss_count'], $result['points'], $result['points_max'], $result['mtime'], $result['countries']);
				printf('0), %d);', !$lid);
			}
			else
				printf('page_ladders(%d);', $frozen ? 1 : 0);
		}
	}
	printf("page_bottom(%d);", time());
	echo('</script>');
?>