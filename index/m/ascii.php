<?php

class ArrayToAsciiTable {

	const SPACING_X = 1;
	const SPACING_Y = 0;
	const JOINT_CHAR = '+';
	const LINE_X_CHAR = '-';
	const LINE_Y_CHAR = '|';
	const NL = "\n";

	function draw_table($table,$per_row = false){
		if (!$table) {
			$table = array(array('' => 'No data found'));
		}

		$nl = self::NL;
		$columns_headers = $this->columns_headers($table);
		$columns_lengths = $this->columns_lengths($table, $columns_headers);
		$row_separator = $this->row_seperator($columns_lengths);
		$row_spacer = $this->row_spacer($columns_lengths);
		$row_headers = $this->row_headers($columns_headers, $columns_lengths);

		$ascii  = '<pre style="float:left;">';
		$ascii .= $row_separator.$nl;
		$ascii .= str_repeat($row_spacer.$nl, self::SPACING_Y);
		$ascii .= $row_headers.$nl;
		$ascii .= str_repeat($row_spacer.$nl, self::SPACING_Y);
		$ascii .= $row_separator.$nl;
		$ascii .= str_repeat($row_spacer.$nl, self::SPACING_Y);
		foreach($table as $row_cells) {
			$row_cells = $this->row_cells($row_cells, $columns_headers, $columns_lengths);
			$ascii .= $row_cells.$nl;
			$ascii .= str_repeat($row_spacer.$nl, self::SPACING_Y);
			if($per_row) {
				$ascii .= $row_separator.$nl;
				$ascii .= str_repeat($row_spacer.$nl, self::SPACING_Y);
			}
		}
		$ascii .= $row_separator;
		$ascii .= '</pre>';
		return $ascii;
	}

	private function columns_headers($table){
		return array_keys(reset($table));
	}

	private function columns_lengths($table, $columns_headers){
		$lengths = array();
		foreach($columns_headers as $header) {
			// $header_length = strlen($header);
			$header_length = 0;
			foreach(explode("\n",$header) as $r) {
				$header_length = max(mb_strlen($r,mb_detect_encoding($r)),$header_length);
			}
			$max = $header_length;
			foreach($table as $row) {
				// $length = strlen($row[$header]);
				$length = 0;
				foreach(explode("\n",$row[$header]) as $r) {
					$length = max(mb_strlen($r,mb_detect_encoding($r)),$length);
				}
				if($length > $max)
					$max = $length;
			}

			if(($max % 2) != ($header_length % 2))
				$max += 1;

			$lengths[$header] = $max;
		}

		return $lengths;
	}

	private function row_seperator($columns_lengths){
		$row = '';
		foreach($columns_lengths as $column_length)
		{
			$row .= self::JOINT_CHAR.str_repeat(self::LINE_X_CHAR, (self::SPACING_X * 2) + $column_length);
		}
		$row .= self::JOINT_CHAR;

		return $row;
	}

	private function row_spacer($columns_lengths){
		$row = '';
		foreach($columns_lengths as $column_length)
		{
			$row .= self::LINE_Y_CHAR.str_repeat(' ', (self::SPACING_X * 2) + $column_length);
		}
		$row .= self::LINE_Y_CHAR;

		return $row;
	}

	private function row_headers($columns_headers, $columns_lengths){
		$row = '';
		foreach($columns_headers as $header)
		{
			$row .= self::LINE_Y_CHAR.str_pad($header, (self::SPACING_X * 2) + $columns_lengths[$header], ' ', STR_PAD_BOTH);
		}
		$row .= self::LINE_Y_CHAR;

		return $row;
	}

	private function row_cells(
		$row_cells,
		$columns_headers,
		$columns_lengths
	){
		$row = '';
		$filas = 1;
		foreach($row_cells as $k => $v) {
			$filas = max(count(explode("\n",$v)),$filas);
		}

		// p($filas);
		// p($row_cells);
		// p($columns_headers);
		// p($columns_lengths);
		//
		// for($i = 0;$i<$filas;$i++) {
		// 	foreach($columns_headers as $header) {
		// 		$texto = explode("\n",$row_cells[$header]);
		// 		$texto = trim($texto[$i]);
		// 		p(array($texto,mb_strlen($texto)));
		// 		$row =
		// 	}
		// }
		
		for($i = 0;$i<$filas;$i++) {
			foreach($columns_headers as $header) {
				$t = $row_cells[$header];
				$t = explode("\n",$t);
				$t = trim($t[$i]);
				// $row .= self::LINE_Y_CHAR.
				// 	str_repeat(' ',self::SPACING_X).
				// 	str_pad($t,self::SPACING_X+$columns_lengths[$header],' ',STR_PAD_RIGHT);

				$row .= self::LINE_Y_CHAR.
					str_repeat(' ',self::SPACING_X).
					$t.
					str_repeat(' ',self::SPACING_X+$columns_lengths[$header]-mb_strlen($t));

			}
			if($i < $filas-1) {
				$row .= self::LINE_Y_CHAR;
				$row .= self::NL;
			}
			// $row .= self::LINE_Y_CHAR.str_repeat(' ', self::SPACING_X).str_pad($row_cells[$header],self::SPACING_X+$columns_lengths[$header],' ',STR_PAD_RIGHT);
		}
		$row .= self::LINE_Y_CHAR;

		return $row;
	}

}
?>