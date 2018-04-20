<?php
/*
* bin_main.php - model bookcase
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Bookcase extends Bin
{

	public $_dba;

	function __construct() {
		$this->_dba = $this->db_access();
	}

	# Model show all posts added only to bookcase
	function get_data($_idbc = null, $_listing = null) {
		if (isset($_POST['iDBookcase']) and isset($_POST['iDPage'])) {
			$_iDPage = $_POST['iDPage'];
			$_iDBookcase = $_POST['iDBookcase'];

			$message = $this->modalwindow_posts($_iDBookcase, $_iDPage);

			$message = '[' . "'" . $message . "'" . ']';

			die($message);
		}

		# this cookie needs for left menu
		$cookie_name = 'idbc';
		$cookie_value = $_idbc;
		setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

		$_topshelves_menu = $this->get_topshelves_menu($_idbc, $_idsh = null);
		$_postinbookcase = $this->get_postinbookcase($_idbc, $_listing);
		$_postinbookcase = array_merge($_topshelves_menu, $_postinbookcase);
		$_postin_modalwindow = array('wrapped_html' => $this->modalwindow_posts($_idbc, $_listing));

		return array_merge($_postinbookcase, $_postin_modalwindow);
	}

	private function get_postinbookcase($_idbc, $_listing) {
		$arr_posts = array(); $i = 0;

		$_stmt = $this->_dba->prepare('SELECT idpt FROM postinbookcase WHERE idbc = :idbc ORDER BY datepost DESC');
		$_stmt->execute([':idbc' => $_idbc]);

		while($_bookcase = $_stmt->fetch(PDO::FETCH_ASSOC)) {
			$_posts = $this->get_posts($_bookcase['idpt']);

			$_bc_names = $this->get_addedname_bookcases($_bookcase['idpt']);
			$_sh_names = $this->get_addedname_shelves($_bookcase['idpt']);
			$_sort_eachother = $this->sortshelves_tobookcases($_bc_names, $_sh_names);

			$arr_posts[$i] = array('PostID' => $_bookcase['idpt'], 'PostDate' => $_posts[0], 'PostName' => $_posts[1], 'PostBody' => $_posts[2], 'PostBcSh' => $_sort_eachother);

			++$i;
		}

		# $_size_arr = 7;
		$arr_posts = $this->pagination_posts($i, $arr_posts, $_idbc, $_listing, 7);

		return $arr_posts;
	}

	private function get_posts($_idpt) {
		$stmt = $this->_dba->prepare('SELECT datepost, postname, filepath FROM postcase WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);
		$_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

		$monthes = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль',
							8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');

		$_year = date('Y', strtotime($_postcase['datepost']));
		$_mnth = abs(date('m', strtotime($_postcase['datepost'])));
		$_day = date('d', strtotime($_postcase['datepost']));

		$postdate = $monthes[$_mnth] . ' ' . $_day . ', ' .  $_year;
		$postname = $_postcase['postname'];
		$postbody = $this->get_content_file($_postcase['filepath']);

		return array($postdate, $postname, $postbody);
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

	private function modalwindow_posts($_idbc, $_listing) {
		$_all_posts = array(); $i = 0;

		$_stmt = $this->_dba->prepare('SELECT idpt FROM postinbookcase WHERE idbc = :idbc ORDER BY datepost DESC');
		$_stmt->execute([':idbc' => $_idbc]);

		while($_bookcase = $_stmt->fetch(PDO::FETCH_ASSOC)) {

			$_stmt_post = $this->_dba->prepare('SELECT postname FROM postcase WHERE idpt = :idpt');
			$_stmt_post->execute([':idpt' => $_bookcase['idpt']]);
			$_postcase = $_stmt_post->fetch(PDO::FETCH_ASSOC);

			# this array for the right modal window where all posts names
			$_all_posts[$i] = array('post_str' => '<p><a href="/post?idpt=' . $_bookcase['idpt'] . '">' . $_postcase['postname'] . '</a></p>' );

			++$i;
		}

		$_bcstmt = $this->_dba->prepare('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
		$_bcstmt->execute([':idbc' => $_idbc]);

		$_nbc = $_bcstmt->fetch(PDO::FETCH_ASSOC);
		$_nbc = $_nbc['namebookcase'];

		# $_size_arr = 14 for modal window;
		$arr_posts = $this->pagination_posts($i, $_all_posts, $_idbc, $_listing, 14);

		$arr_posts = array('modal_window' => $arr_posts, 'BookcaseName' => $_nbc);

		return $this->wrap_to_htmlstr($arr_posts);
	}

	private function pagination_posts($_i, $arr_posts, $_idbc, $_listing, $_size_arr) {
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

    $page_active = array('active_page' => $_listing, 'entries' => $_i, 'iDbc' => $_idbc, 'pages' => $_pages, 'Posts' => $arr_posts);

		return $page_active;
	}

	private function wrap_to_htmlstr($arr_posts) {
		$_htmlstr = null;
		if (!empty($arr_posts['BookcaseName'])) {
			$_htmlstr .= '<h2>' . $arr_posts['BookcaseName'] . ' · Все тексты</h2>';
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
            $_btngroup .= '<a name="'.$arr_posts['modal_window']['iDbc'].'" id="'.$i.'" onclick="getpostin_modalwindow(this.name, this.id)">'.$i.'</a>';
          }
        }
      }

      # 14 defined in Bin_Bookcase function  modalwindow_posts
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
        $_htmlstr .= '<span class="prev">« Туда</span>';
      }
      else {
        $_htmlstr .= '<a name="'.$arr_posts['modal_window']['iDbc'].'" id="'.$_next_less.'" onclick="getpostin_modalwindow(this.name, this.id)" class="next">« Туда</a>';
      }

      $_htmlstr .= $_btngroup;

      if ($_next_more <= $arr_posts['modal_window']['pages']) {
        $_htmlstr .= '<a name="'.$arr_posts['modal_window']['iDbc'].'" id="'.$_next_more.'" onclick="getpostin_modalwindow(this.name, this.id)" class="next">Сюда »</a>';
      }
      else {
        $_htmlstr .= '<span class="prev">Сюда »</span>';
      }

      $_htmlstr .= '</div><small class="smallshelve">Блоги&nbsp;' . $_entriesfrom . '&nbsp;и&nbsp;' . $_entriesto . ',&nbsp;из&nbsp;' . $_entries . '</small>';
    }

		return $_htmlstr;
	}

	# functions get added names bookcases and shelves for under post menu
	private function get_addedname_bookcases($_idpt) {
		$_str = array(); $i = 0;

		$stmt = $this->_dba->prepare('SELECT idbc FROM postinbookcase WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);

		while($row_stmt = $stmt->fetch(PDO::FETCH_ASSOC)) {

			$_bcstmt = $this->_dba->prepare('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
			$_bcstmt->execute([':idbc' => $row_stmt['idbc']]);
			$row_bookcase = $_bcstmt->fetch(PDO::FETCH_ASSOC);

			$_str[$i] = array('IdBC' =>  $row_stmt['idbc'], 'StrBC' => '<div id="addedbcid' . $row_stmt['idbc'] . '"><small class="smallbookcase"><a href="/bookcase?idbc='. $row_stmt['idbc'] .'">' . $row_bookcase['namebookcase'] . '</a></small></div>');
			++$i;
		}

		return array('NameBookcase' => $_str);
	}

	private function get_addedname_shelves($_idpt) {
		$_str = array(); $i = 0;

		$stmt = $this->_dba->prepare('SELECT idsh FROM postonshelve WHERE idpt = :idpt');
		$stmt->execute([':idpt' => $_idpt]);

		while($row_stmt = $stmt->fetch(PDO::FETCH_ASSOC)) {

			$_shstmt = $this->_dba->prepare('SELECT nameshelve, idbc FROM shelves WHERE idsh = :idsh');
			$_shstmt->execute([':idsh' => $row_stmt['idsh']]);
			$row_shelve = $_shstmt->fetch(PDO::FETCH_ASSOC);

			$_shelve = '<div id="addedshlvid' . $row_stmt['idsh'] . '"><small class="smallshelve"><a href="/shelve?idsh='.$row_stmt['idsh'].'">' . $row_shelve['nameshelve'] . '</a></small></div>';

			$_str[$i] = array('IdBC' =>  $row_shelve['idbc'], 'StrSH' => $_shelve);
			++$i;
		}

		return array('NameShelves' => $_str);
	}

	private function sortshelves_tobookcases($_bc_names, $_sh_names) {
		$_str = null;
		if (!empty($_bc_names['NameBookcase'])) {
	    foreach ($_bc_names['NameBookcase'] as $value) {
				$_str .= $value['StrBC'];
				# cut shelves if idbc from postinbookcase == idbc in shelves
				$_strsh = $this->cut_shelves($value['IdBC'], $_sh_names);
				$_str .= $_strsh[0];
				$_sh_names = $_strsh[1];
	    }

			if (!empty($_sh_names['NameShelves'])) {
				$_str .= '.';
				foreach ($_sh_names['NameShelves'] as $value) {
						$_str .= $value['StrSH'];
				}
		  }
	  } else {
			if (!empty($_sh_names['NameShelves'])) {
				foreach ($_sh_names['NameShelves'] as $value) {
						$_str .= $value['StrSH'];
				}
		  }
		}

		return $_str;
	}

	private function cut_shelves($_idbc, $_sh_names) {
		$_str = null; $i = 0;

		if (!empty($_sh_names)) {
			foreach ($_sh_names['NameShelves'] as $key => $value) {
				if($value['IdBC'] == $_idbc) {
					$_str .= $value['StrSH'];
					# if was any bookcase then shelve can repeats twice
					unset($_sh_names['NameShelves'][$key]);
				}
			}
	  }

		return array($_str, $_sh_names);
	}

	function get_title($_id) {
		$_menu =$this->get_left_menu($_id);
		$_title = array('title' => 'Тексты и Книги · Священник Яков Кротов');
		return array_merge($_title, $_menu);
	}

}
