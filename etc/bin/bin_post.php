<?php
/*
* bin_main.php - model post
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Post extends Bin
{

		public $_dba;

		function __construct() {
				$this->_dba = $this->db_access();
		}

		function get_data($_idpt = null, $_listing = null) {
				return $this->get_post($_idpt);
		}

		private function get_post($_idpt) {

				$stmt = $this->_dba->prepare('SELECT datepost, postname, filepath FROM postcase WHERE idpt = :idpt');
				$stmt->execute([':idpt' => $_idpt]);
				$row_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

				$_year = date('Y', strtotime($row_postcase['datepost']));
				$_mnth = date('F', strtotime($row_postcase['datepost']));
				$_day = date('d', strtotime($row_postcase['datepost']));
				$postdate = $_mnth . ' ' . $_day . ', ' .  $_year;

				$postbody = file_get_contents($row_postcase['filepath']);

				$_bc_names = $this->get_addedname_bookcases($_idpt);
				$_sh_names = $this->get_addedname_shelves($_idpt);
				$_sort_eachother = $this->sortshelves_tobookcases($_bc_names, $_sh_names);

				$_netxpost = $this->get_nextpost($_idpt);

				return array('PostDate' => $postdate, 'PostName' => $row_postcase['postname'], 'PostBody' => $postbody, 'PostBcSh' => $_sort_eachother, 'NextPost' => $_netxpost);
		}

		private function get_nextpost($_idpt) {
				$arr_posts = array(); $i = 0;

				$_stmt = $this->_dba->query('SELECT idpt, postname FROM postcase');

				while($row_postcase = $_stmt->fetch(PDO::FETCH_ASSOC)) {
						$arr_posts[$i] = array($row_postcase['idpt'], $row_postcase['postname']);

						++$i;
				}

				$_pages = count($arr_posts);

				for ($i=0; $i < $_pages; $i++) {
						if ($_idpt == $arr_posts[$i][0]) {
							$j = ++$i;

								if ($j == $_pages) {
										$_netxpost = $arr_posts[0][0];
										$_postname = $arr_posts[0][1];
								} else {
										$_netxpost = $arr_posts[$j][0];
										$_postname = $arr_posts[$j][1];
								}
						}
				}

				return array($_postname, $_netxpost);
		}

		function get_title($_id) {
				$_menu =$this->get_left_menu($_id);
				$_title = array('title' => 'Blog Admin · Made Easy');

				return array_merge($_title, $_menu);
		}
}
