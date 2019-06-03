<?php
/*
* bin_main.php - model newblogs
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Newblogs extends Bin
{
		public $_dba;

		function __construct() {
				$this->_dba = $this->db_access();
		}

		function get_data($_idpt = null, $_listing = null) {
				if (isset($_POST['iDPage'])) {
						$_iDPage = $_POST['iDPage'];

						$message = $this->modalwindow_posts($_iDPage);

						$message = '[' . "'" . $message . "'" . ']';

						die($message);
				}

				# this cookie needs for left menu
				$cookie_name = 'idbc';
				$cookie_value = 'new';
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

				$_posts = $this->get_post($_listing);
				$_postin_modalwindow = array('wrapped_html' => $this->modalwindow_posts($_listing));

				return array_merge($_posts, $_postin_modalwindow);
		}

		private function get_post($_listing) {
				$arr_posts = array(); $i = 0;

				$stmt = $this->_dba->query('SELECT idpt, datepost, postname, filepath FROM postcase ORDER BY datepost DESC');

				while($row_postcase = $stmt->fetch(PDO::FETCH_ASSOC)) {
						$_year = date('Y', strtotime($row_postcase['datepost']));
						$_mnth = date('F', strtotime($row_postcase['datepost']));
						$_day = date('d', strtotime($row_postcase['datepost']));
						$postdate = $_mnth . ' ' . $_day . ', ' .  $_year;

						$_bc_names = $this->get_addedname_bookcases($row_postcase['idpt']);
						$_sh_names = $this->get_addedname_shelves($row_postcase['idpt']);
						$_sort_eachother = $this->sortshelves_tobookcases($_bc_names, $_sh_names);

						$_postbody = $this->get_content_file($row_postcase['filepath']);

						$arr_posts[$i] = array('PostID' => $row_postcase['idpt'], 'PostDate' => $postdate, 'PostName' => $row_postcase['postname'], 'PostBody' => $_postbody, 'PostBcSh' => $_sort_eachother);

						$i++;
				}

				# $_size_arr = 7, if changed here go to newblogs_view.php and change there
				$arr_posts = $this->pagination_posts($i, $arr_posts, $_listing, 7);

				return $arr_posts;
		}

		private function get_content_file($_filepath) {
				$_postbody = file_get_contents($_filepath);
				$routes = explode('</p>', $_postbody);
				$_postbody =null;

				$j = rand(3, 5);

				for ($i=0; $i < $j; ++$i) {
						if (isset($routes[$i])) {
								$_postbody .= $routes[$i];
						}
				}

				return $_postbody;
		}

		# posts in modal window
		private function modalwindow_posts($_listing) {
				$_all_posts = array(); $i = 0;

				$_stmt = $this->_dba->query('SELECT idpt, postname FROM postcase ORDER BY datepost DESC');

				while($_postcase = $_stmt->fetch(PDO::FETCH_ASSOC)) {
						# this array for the right modal window where all posts names
						$_all_posts[$i] = array('post_str' => '<p><a href="/post?idpt=' . $_postcase['idpt'] . '">' . $_postcase['postname'] . '</a></p>' );

						++$i;
				}

				# $_size_arr = 14 for modal window, if changed here go to function wrap_to_htmlstr and change there
				$arr_posts = $this->pagination_posts($i, $_all_posts, $_listing, 14);

				$arr_posts = array('modal_window' => $arr_posts);

				return $this->wrap_to_htmlstr($arr_posts);
		}

		private function wrap_to_htmlstr($arr_posts) {
			$_htmlstr = '<h2>new · all posts</h2>';

		    if (!empty($arr_posts['modal_window']['Posts'])) {
			      foreach ($arr_posts['modal_window']['Posts'] as $value) {
								$_htmlstr .= $value['post_str'];
			      }
		    }

		    if (!empty($arr_posts['modal_window']['pages'])) {
			      $_htmlstr .= '<br><div class="pagination">';

			      $_limit_less = $arr_posts['modal_window']['active_page'] - 4;
						$_limit_more = $arr_posts['modal_window']['active_page'] + 4;
						$_next_less = 1; $_next_more = 1; $_btngroup = null;

			      for ($i = 1; $i <= $arr_posts['modal_window']['pages']; $i++) {
				        if ($arr_posts['modal_window']['active_page'] == $i) {
										$_activepage = '<span class="currentpg">'. $i .'</span>';
										$_next_less = $i - 1;
										$_next_more = $i + 1;
				        } else {
										$_activepage = null;
				        }

				        if ($i > $_limit_less and $i < $_limit_more) {
					          if ($_activepage) {
												$_btngroup .= $_activepage;
					          } else {
												$_btngroup .= '<a id="'.$i.'" onclick="getpostin_modalwindow(this.id)">'.$i.'</a>';
					          }
				        }
			      }

			      # 14 defined in Bin_Bookcase function modalwindow_posts
			      if ($arr_posts['modal_window']['entries'] <= 14 ) {
								$_entries = $arr_posts['modal_window']['entries'];
							 	$_entriesfrom = 1;
							 	$_entriesto = $arr_posts['modal_window']['entries'];
			      } else {
								$_entriesto = $arr_posts['modal_window']['active_page'] * 14;
							 	$_entries = $arr_posts['modal_window']['entries'];
							 	$_entriesfrom = $_entriesto - 13;
			      }

			      if ($_entriesto > $_entries) {
								$_entriesto = $_entries;
			      }

			      if ($_next_less < 1) {
								$_htmlstr .= '<span class="prev">« here</span>';
			      }
			      else {
								$_htmlstr .= '<a id="'.$_next_less.'" onclick="getpostin_modalwindow(this.id)" class="next">« here</a>';
			      }

			      $_htmlstr .= $_btngroup;

			      if ($_next_more <= $arr_posts['modal_window']['pages']) {
								$_htmlstr .= '<a id="'.$_next_more.'" onclick="getpostin_modalwindow(this.id)" class="next">there »</a>';
			      }
			      else {
								$_htmlstr .= '<span class="prev">there »</span>';
			      }

		      	$_htmlstr .= '</div><small class="smallshelve">Posts&nbsp;' . $_entriesfrom . '&nbsp;and&nbsp;' . $_entriesto . ',&nbsp;from&nbsp;' . $_entries . '</small>';
		    }

				return $_htmlstr;
		}

		private function pagination_posts($_i, $arr_posts, $_listing, $_size_arr) {
				if ($_i > 0) {
						$_arr_posts_short = array_chunk($arr_posts, $_size_arr);
						# how much elements with $_size_arr
						$_pages = count($_arr_posts_short);

						# check what comes from controller
						if ($_listing > $_pages) {
								$_listing = 1;
						} elseif ($_listing <= 0) {
								$_listing = 1;
						}

						$arr_posts = $_arr_posts_short[$_listing - 1];
				} else {
						$_listing = 1;  $_pages = 1;
				}

		    $page_active = array('active_page' => $_listing, 'entries' => $_i, 'pages' => $_pages, 'Posts' => $arr_posts);

				return $page_active;
		}

		function get_title($_id) {
				$_menu =$this->get_left_menu($_id);
				$_title = array('title' => 'Blog Admin · Made Easy');

				return array_merge($_title, $_menu);
		}
}
