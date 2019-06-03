<?php
/*
* bin_main.php - model shelve
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Shelve extends Bin
{
		public $_dba;

		function __construct() {
				$this->_dba = $this->db_access();
		}

		# Model show all posts added only to shelves
		function get_data($_idsh = null, $_listing = null) {
				if (isset($_POST['iDShelve']) and isset($_POST['iDPage'])) {
						$_iDPage = $_POST['iDPage'];
						$_iDShelve = $_POST['iDShelve'];

						$message = $this->modalwindow_posts($_iDShelve, $_iDPage);

						$message = '[' . "'" . $message . "'" . ']';

						die($message);
				}

				$_topshelves_menu = $this->get_topshelves_menu($_idbc = null, $_idsh);

				$_postonshelve = $this->get_postonshelve($_idsh, $_listing);
				$_postonshelve = array_merge($_topshelves_menu, $_postonshelve);

				$_postin_modalwindow = array('wrapped_html' => $this->modalwindow_posts($_idsh, $_listing));

				return array_merge($_postonshelve, $_postin_modalwindow);
		}

		private function get_postonshelve($_idsh, $_listing) {
				$arr_posts = array(); $_all_posts = array(); $i = 0;

				$_stmt = $this->_dba->prepare('SELECT idpt FROM postonshelve WHERE idsh = :idsh ORDER BY datepost DESC');
				$_stmt->execute([':idsh' => $_idsh]);

				while($_shelve = $_stmt->fetch(PDO::FETCH_ASSOC)) {
						$_posts = $this->get_posts($_shelve['idpt']);

						$_bc_names = $this->get_addedname_bookcases($_shelve['idpt']);
						$_sh_names = $this->get_addedname_shelves($_shelve['idpt']);
						$_sort_eachother = $this->sortshelves_tobookcases($_bc_names, $_sh_names);

						$arr_posts[$i] = array('PostID' => $_shelve['idpt'], 'PostDate' => $_posts[0], 'PostName' => $_posts[1], 'PostBody' => $_posts[2], 'PostBcSh' => $_sort_eachother);

						++$i;
				}

				# $_size_arr = 7, if changed here go to shelve_view.php and change there
				$arr_posts = $this->pagination_posts($i, $arr_posts, $_idsh, $_listing, 7);

				return $arr_posts;
		}

		private function get_posts($_idpt) {
				$stmt = $this->_dba->prepare('SELECT datepost, postname, filepath FROM postcase WHERE idpt = :idpt');
				$stmt->execute([':idpt' => $_idpt]);
				$_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

				$_year = date('Y', strtotime($_postcase['datepost']));
				$_mnth = date('F', strtotime($_postcase['datepost']));
				$_day = date('d', strtotime($_postcase['datepost']));

				$postdate = $_mnth . ' ' . $_day . ', ' .  $_year;
				$postname = $_postcase['postname'];
				$postbody = $this->get_content_file($_postcase['filepath']);

				return array($postdate, $postname, $postbody);
		}

		private function get_content_file($_filepath) {
				$_postbody = file_get_contents($_filepath);
				$routes = explode('</p>', $_postbody);
				$_postbody =null;

				$j = rand(3, 5);

				for ($i=0; $i < $j; $i++) {
						if (isset($routes[$i])) {
								$_postbody .= $routes[$i];
						}
				}

				return $_postbody;
		}

		private function modalwindow_posts($_idsh, $_listing) {
				$_all_posts = array(); $i = 0;

				$_stmt = $this->_dba->prepare('SELECT idpt FROM postonshelve WHERE idsh = :idsh ORDER BY datepost DESC');
				$_stmt->execute([':idsh' => $_idsh]);

				while($_shelve = $_stmt->fetch(PDO::FETCH_ASSOC)) {
						$_stmt_post = $this->_dba->prepare('SELECT postname FROM postcase WHERE idpt = :idpt');
						$_stmt_post->execute([':idpt' => $_shelve['idpt']]);
						$_postcase = $_stmt_post->fetch(PDO::FETCH_ASSOC);

						# this array for the right modal window where all posts names
						$_all_posts[$i] = array('post_str' => '<p><a href="/post?idpt=' . $_shelve['idpt'] . '">' . $_postcase['postname'] . '</a></p>' );

						++$i;
				}

				$_shstmt = $this->_dba->prepare('SELECT nameshelve FROM shelves WHERE idsh = :idsh');
				$_shstmt->execute([':idsh' => $_idsh]);

				$_nsh = $_shstmt->fetch(PDO::FETCH_ASSOC);
				$_nsh = $_nsh['nameshelve'];

				# $_size_arr = 14 for modal window, if changed here go to function wrap_to_htmlstr and change there
				$arr_posts = $this->pagination_posts($i, $_all_posts, $_idsh, $_listing, 14);

				$arr_posts = array('modal_window' => $arr_posts, 'ShelveName' => $_nsh);

				return $this->wrap_to_htmlstr($arr_posts);
		}

		private function pagination_posts($_i, $arr_posts, $_idsh, $_listing, $_size_arr) {
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

		    $page_active = array('active_page' => $_listing, 'entries' => $_i, 'iDsh' => $_idsh, 'pages' => $_pages, 'Posts' => $arr_posts);

				return $page_active;
		}

		private function wrap_to_htmlstr($arr_posts) {
				$_htmlstr = null;

				if (!empty($arr_posts['ShelveName'])) {
						$_htmlstr .= '<h2>' . $arr_posts['ShelveName'] . ' · all posts</h2>';
				}

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
					            	$_btngroup .= '<a name="'.$arr_posts['modal_window']['iDsh'].'" id="'.$i.'" onclick="getpostin_modalwindow(this.name, this.id)">'.$i.'</a>';
					          }
				        }
			      }

						# 14 defined in Bin_Shelve function modalwindow_posts
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
				    } else {
								$_htmlstr .= '<a name="'.$arr_posts['modal_window']['iDsh'].'" id="'.$_next_less.'" onclick="getpostin_modalwindow(this.name, this.id)" class="next">« here</a>';
				    }

			      $_htmlstr .= $_btngroup;

				    if ($_next_more <= $arr_posts['modal_window']['pages']) {
								$_htmlstr .= '<a name="'.$arr_posts['modal_window']['iDsh'].'" id="'.$_next_more.'" onclick="getpostin_modalwindow(this.name, this.id)" class="next">there »</a>';
				    } else {
								$_htmlstr .= '<span class="prev">there »</span>';
				    }

			      $_htmlstr .= '</div><small class="smallshelve">Posts&nbsp;' . $_entriesfrom . '&nbsp;and&nbsp;' . $_entriesto . ',&nbsp;from&nbsp;' . $_entries . '</small>';
			  }

				return $_htmlstr;
		}

		function get_title($_idsh) {
				$_shstmt = $this->_dba->prepare('SELECT idbc FROM shelves WHERE idsh = :idsh');
				$_shstmt->execute([':idsh' => $_idsh]);

				$_idbc = $_shstmt->fetch(PDO::FETCH_ASSOC);
				$_idbc = $_idbc['idbc'];

				$_menu =$this->get_left_menu($_idbc);
				$_title = array('title' => 'Blog Admin · Made Easy');

				return array_merge($_title, $_menu);
		}
}
