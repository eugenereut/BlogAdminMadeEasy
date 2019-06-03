<?php
/*
* bin_main.php - model Setuppost
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Setuppost extends Bin
{
		public $_dba;

		function __construct() {
			$this->_dba = $this->db_access();
			$this->_dba->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		//setcookie("idpost", "", time() - 3600); // delete cookies
		function get_data($_idpost = null, $_listing = null) {

				if (isset($_POST['addposttobcs'])) {
						$_idbc = $_POST['addposttobcs'];

						$echorespond = $this->addpost_tobcs($_idbc,  $_idpost);
						die($echorespond);
				}	elseif (isset($_POST['deletepostfrombcs'])) {
						$_idbc = $_POST['deletepostfrombcs'];

						$echorespond = $this->deletepost_frombcs($_idbc,  $_idpost);
						die($echorespond);
				}

				if (isset($_POST['addposttoshelve'])) {
						$_idshlv = $_POST['addposttoshelve'];

						$echorespond = $this->addpost_toshelve($_idshlv,  $_idpost);
						die($echorespond);
				}	elseif (isset($_POST['deletepostfromshelve'])) {
						$_idshlv = $_POST['deletepostfromshelve'];

						$echorespond = $this->deletepost_fromshelve($_idshlv,  $_idpost);
						die($echorespond);
				}

				# this cookie needs for JavaScript, see Setuppost_view, when post goes to bookcase or on shelves, see Run_Setuppost controller
				if(!isset($_COOKIE['idpost'])) {
		    		$message = array('houston' => 'Cookie is not set! Please allow cookies in browser settings.');
						$content = array_merge($message, $this->get_bookcases($_idpost));
				} else {
						$content = $this->get_post($_idpost);
						$content = array_merge($content, $this->get_bookcases($_idpost));
				}

				return $content; //array_merge($content, $message);
		}

		private function get_post($_idpt) {

				$stmt = $this->_dba->prepare('SELECT datepost, postname FROM postcase WHERE idpt = :idpt');
				$stmt->execute([':idpt' => $_idpt]);

				if ($row_postcase = $stmt->fetch(PDO::FETCH_ASSOC)) {
						# this cookie needs when post goes to bookcase or on shelves
						$cookie_name = 'datepost';
						$cookie_value = $row_postcase['datepost'];
						setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); // 86400 = 1 day

						$_year = date('Y', strtotime($row_postcase['datepost']));
						$_mnth = date('F', strtotime($row_postcase['datepost']));
						$_day = date('d', strtotime($row_postcase['datepost']));

						$_postdate = $_mnth . ' ' . $_day . ', ' .  $_year;
						$_bc_names = $this->get_addedname_bookcases($_idpt);
						$_sh_names = $this->get_addedname_shelves($_idpt);
						$_sort_eachother = $this->sortshelves_tobookcases($_bc_names, $_sh_names);

						return array('PostDate' => $_postdate, 'PostName' => $row_postcase['postname'], 'PostBcSh' => $_sort_eachother, 'iDPost' => $_idpt);

				} else {
						return array('PostDate' =>null, 'PostName' => null, 'PostBcSh' => null, 'iDPost' => null);
				}
		}

		# this functions calls from JavaScript
		private function addpost_tobcs($_idbc,  $_idpost) {
				if (isset($_COOKIE['datepost'])) {
						$_datepost = $_COOKIE['datepost'];
				} else {
						$_datepost = 0;
				}

				if ($_idpost != 0 and $_datepost != 0) {
						$_idbc = ltrim($_idbc, "bcid");

						try {
								$this->_dba->beginTransaction();

								$_stmt = $this->_dba->prepare('INSERT INTO postinbookcase (datepost, idpt, idbc) VALUES (?, ?, ?)');
								$_stmt->execute(array($_datepost, $_idpost, $_idbc));

								# commit the transaction
								$this->_dba->commit();

								$statement = $this->_dba->query('SELECT namebookcase FROM bookcase WHERE idbc = :idbc');
								$statement->execute([':idbc' => $_idbc]);
								$row_bookcase = $statement->fetch(PDO::FETCH_ASSOC);

								$_namebookcase = '<div id="addedbcid' . $_idbc . '"><small class="smallbookcase">' . $row_bookcase['namebookcase'] . '</small></div>';

								$echorespond = '[' . "'" . $_namebookcase  . "'" . ']';

						} catch (PDOException $e) {
								$this->_dba->rollBack();
								$echorespond = null;
						}
				} else {
						$echorespond = null;
				}

				return $echorespond;
		}

		private function deletepost_frombcs($_idbc,  $_idpost) {
				if ($_idpost != 0) {
						$_idbc = ltrim($_idbc, "bcid");

						try {
								$this->_dba->beginTransaction();

								$statement = $this->_dba->query('DELETE FROM postinbookcase WHERE idpt = :idpt  AND idbc = :idbc');
								$statement->execute(array(':idpt' => $_idpost, ':idbc' => $_idbc));

								# commit the transaction
								$this->_dba->commit();

								$_idbc = 'addedbcid' . $_idbc;
								$echorespond = '['  . "'" . $_idbc  . "'" . ']';
						} catch (PDOException $e) {
								$this->_dba->rollBack();
								$echorespond = null;
						}
				} else {
						$echorespond = null;
				}

				return $echorespond;
		}

		private function addpost_toshelve($_idshlv,  $_idpost) {
				if (isset($_COOKIE['datepost'])) {
						$_datepost = $_COOKIE['datepost'];
				} else {
						$_datepost = 0;
				}

				if ($_idpost != 0 and $_datepost != 0) {
						$_idshlv = ltrim($_idshlv, "shlvid");

						try {
								$this->_dba->beginTransaction();

								$_stmt = $this->_dba->prepare('INSERT INTO postonshelve (datepost, idpt, idsh) VALUES (?, ?, ?)');
								$_stmt->execute(array($_datepost, $_idpost, $_idshlv));

								# commit the transaction
								$this->_dba->commit();

								$statement = $this->_dba->prepare('SELECT nameshelve FROM shelves WHERE idsh = :idshlv');
								$statement->execute([':idshlv' => $_idshlv]);
								$row_shelve = $statement->fetch(PDO::FETCH_ASSOC);

								$_nameshelve = '<div id="addedshlvid' . $_idshlv . '"><small class="smallshelve">' . $row_shelve['nameshelve'] . '</small></div>';
								// $_idbc = 'bcid' . $_idbc;
								$echorespond = '[' . "'" . $_nameshelve  . "'" . ']';

						} catch (PDOException $e) {
								$this->_dba->rollBack();
								$echorespond = null;
						}
				} else {
						$echorespond = null;
				}

				return $echorespond;
		}

		private function deletepost_fromshelve($_idshlv,  $_idpost) {
				if ($_idpost != 0) {
						$_idshlv = ltrim($_idshlv, "shlvid");

						try {
								$this->_dba->beginTransaction();

								$statement = $this->_dba->query('DELETE FROM postonshelve WHERE idpt = :idpt  AND idsh = :idsh');
								$statement->execute(array(':idpt' => $_idpost, ':idsh' => $_idshlv));

								# commit the transaction
								$this->_dba->commit();

								$_idshlv = 'addedshlvid' . $_idshlv;
								$echorespond = '['  . "'" . $_idshlv  . "'" . ']';
						} catch (PDOException $e) {
								$this->_dba->rollBack();
								$echorespond = null;
						}
				} else {
						$echorespond = null;
				}

				return $echorespond;
		}

		# get Bookcases and Shelves
		private function get_bookcases($_idpost) {
				$arr_bookcase = array(); $i = 0;

				$statement = $this->_dba->query('SELECT idbc, namebookcase, aboutbookcase FROM bookcase');

				while($row_bookcase = $statement->fetch(PDO::FETCH_ASSOC)) {
						$_shelve = $this->get_shelves($_idpost, $row_bookcase['idbc']);

						$stmt = $this->_dba->prepare('SELECT idpt, idbc FROM postinbookcase WHERE idpt = :idpt AND idbc = :idbc');
						$stmt->execute(array(':idpt' => $_idpost, ':idbc' => $row_bookcase['idbc']));

						if ($stmt->fetch(PDO::FETCH_ASSOC)) {
								$_checked  = 'checked';
						} else {
								$_checked  = null;
						}

						$arr_bookcase[$i] = array('Record' => $row_bookcase['idbc'], 'Checked' => $_checked, 'NameBookcase' => $row_bookcase['namebookcase'], 'AboutBC' => $row_bookcase['aboutbookcase'], 'Shelve' => $_shelve);

						++$i;
				}

				return array('bookcase' => $arr_bookcase);
		}

		private function get_shelves($_idpost, $_idbc) {
				$str_shelves = null; $i = 0;

				$statement = $this->_dba->prepare('SELECT idsh, nameshelve FROM shelves WHERE idbc = :idbc');
				$statement->execute([':idbc' => $_idbc]);

				while($row_shelve = $statement->fetch(PDO::FETCH_ASSOC)) {
						$stmt = $this->_dba->prepare('SELECT idpt, idsh FROM postonshelve WHERE idpt = :idpt AND idsh = :idsh');
						$stmt->execute(array(':idpt' => $_idpost, ':idsh' => $row_shelve['idsh']));

						if ($stmt->fetch(PDO::FETCH_ASSOC)) {
								$_checked  = 'checked';
						} else {
								$_checked  = null;
						}

						$str_shelves  .= '<li><input type="checkbox" id="shlvid' . $row_shelve['idsh'].'" onchange="shelve_chechbox(this.id)" ' . $_checked . '>' . $row_shelve['nameshelve'] . '</li>';
				}

				return $str_shelves;
		}

		# Edit Post
		function edit_post($_idpost) {
				if (isset($_POST['Editpost'])) {
						$message = $this->update_post($_POST['iDPost'], $_POST['Datefrom'], $_POST['Headerpost'], $_POST['Bodypost']);
				} else {
						$message = null;
				}

				$content = $this->get_editcontent($_idpost);
				$message = array('houston' => $message);

				return array_merge($content, $message);
		}

		private function get_editcontent($_idpost) {
				$stmt = $this->_dba->prepare('SELECT datepost, postname, filepath FROM postcase WHERE idpt = :idpt');
				$stmt->execute([':idpt' => $_idpost]);
				$row_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

				$postbody = file_get_contents($row_postcase['filepath']);
				$PostDate = $_DateSelected = date('D M d Y', strtotime($row_postcase['datepost']));

				return array('iDPost' => $_idpost, 'PostDate' => $PostDate, 'PostName' => $row_postcase['postname'], 'PostBody' => $postbody);
		}

		private function update_post($_iDPost, $_datefrom, $_Headerpost, $_Bodypost) {
				# make a directory-1 from Datefrom
				$_year = date('Y', strtotime($_datefrom));
				$_month = date('M', strtotime($_datefrom));
				$_datefrom = date('Y-m-d', strtotime($_datefrom));

				$_dir_one = $this->get_dir_one($_year, $_month);

				# get a name directory-2 from file saved in db
				$_dir_origin = $this->get_dir_origin($_iDPost);

				$_file_origin = $_dir_origin[0];
				$_dir_origin = $_dir_origin[1];

				# message[0] true or false, message[1] file path
				$_message = $this->update_filepost($_file_origin, $_dir_one, $_dir_origin, $_Bodypost);

				if ($_message[0]) {
						$_message = $this->update_postcase($_iDPost, $_datefrom, $_message[1], $_Headerpost);
				} else {
						$_message = 'Post not updated, some error exception happened.';
				}

				# if file and db updateb then back to settuppost
				if($_message) {
						header('Location: /setuppost?idpt=' . $_iDPost);
				} else {
						return $_message;
				}
		}

		private function get_dir_one($_year, $_month) {
				$_dir = "opt/posts";
				$_dir = $this->makedir_theme($_dir . "/" . $_year);
				$_dir = $this->makedir_theme($_dir . "/" . $_month);

				return $_dir;
		}

		private function makedir_theme($_dir)	{
				if(file_exists($_dir)) {
						return $_dir;
				} else {
						mkdir($_dir, 0777);
						return $_dir;
				}
		}

		private function get_dir_origin($_iDPost) {
				$stmt = $this->_dba->prepare('SELECT filepath FROM postcase WHERE idpt = :idpt');
				$stmt->execute([':idpt' => $_iDPost]);
				$row_postcase = $stmt->fetch(PDO::FETCH_ASSOC);

				$_filename = $row_postcase['filepath'];
				$_dir = dirname($_filename);

				return array($_filename, $_dir);
		}

		private function update_filepost($_file_origin, $_dir_one, $_dir_origin, $story)	{
				# compare dir-1 and dir-2 if equal then open file for update
				if (file_exists($_file_origin) and $_dir_one == $_dir_origin) {
						$_message = $this->file_puts($_file_origin, $story);
						$_file = $_file_origin;

				}	elseif ((file_exists($_file_origin) and $_dir_one != $_dir_origin)) {
						# if not then move file from dir-2 to dir-1 then open file for update
						$_file_new = $_dir_one."/".basename($_file_origin);
						$_movefile = rename ($_file_origin, $_file_new);

						if ($_movefile) {
								$_message = $this->file_puts($_file_new, $story);
								$_file = $_file_new;
						} else {
								$_message = 'Post not updated, some error exception happened.';
						}
				} else {
						$_message = 'Post not updated, some error exception happened.';
				}

				return array($_message, $_file);
		}

		private function file_puts($file, $story) {
				if (file_exists($file)) {
						$fh = fopen($file, "w+");
						fputs($fh, $story);
						fclose($fh);

						$fn = true;
				} else {
						$fn = false;
				}

				return $fn;
		}

		private function update_postcase($_iDPost, $_datefrom, $_file_path, $_Headerpost) {
				try {
						$this->_dba->beginTransaction();

						$stmt = $this->_dba->prepare('UPDATE postcase SET datepost = :dt, filepath = :fpth, postname = :pn WHERE idpt = :idpt');
						$stmt->execute(array(':idpt' => $_iDPost, ':dt' => $_datefrom, ':fpth' => $_file_path, ':pn' => $_Headerpost));

						// update all date in bookcases
						$stmt_two = $this->_dba->query('SELECT postinbookcase_id FROM postinbookcase WHERE idpt = :idpt ');
						$stmt_two->execute([':idpt' => $_iDPost]);

						while($row_post = $stmt_two->fetch(PDO::FETCH_ASSOC)) {
								$stmt = $this->_dba->prepare('UPDATE postinbookcase SET datepost = :dt WHERE postinbookcase_id = :ptbc');
								$stmt->execute(array(':dt' => $_datefrom, ':ptbc' => $row_post['postinbookcase_id']));
						}

						// update all date in shelves
						$stmt_tree = $this->_dba->query('SELECT postonshelve_id FROM postonshelve WHERE idpt = :idpt ');
						$stmt_tree->execute([':idpt' => $_iDPost]);

						while($row_post = $stmt_tree->fetch(PDO::FETCH_ASSOC)) {
								$stmt = $this->_dba->prepare('UPDATE postonshelve SET datepost = :dt WHERE postonshelve_id = :ptsh');
								$stmt->execute(array(':dt' => $_datefrom, ':ptsh' => $row_post['postonshelve_id']));
						}

						# commit the transaction
						$this->_dba->commit();
						$message = true;
				} catch (PDOException $e) {
						$this->_dba->rollBack();
						$message = 'Post not updated, some error exception happened. ' . $e->getMessage();
				}

				return $message;
		}

		# Delete Post from website
		function delete_post($_idpost) {
				if (isset($_POST['DeletePost'])) {
						if (isset($_POST['agreetodelete']) != 'Yes') {
								$message = 'You have to agree to delete post.';
						} else {
								$message = $this->delete_frompostcase($_POST['iDPost']);
						}
				} else {
						$message = null;
				}

				$content = $this->get_post($_idpost);
				$message = array('houston' => $message);

				return array_merge($content, $message);
		}

		private function delete_frompostcase($_iDPost) {
				$stmt_one = $this->_dba->query('SELECT filepath FROM postcase WHERE idpt = :idpt ');
				$stmt_one->execute([':idpt' => $_iDPost]);

				if ($row_postcase = $stmt_one->fetch(PDO::FETCH_ASSOC)) {
						# this row goes to unlink($_filepath);
						$_filepath = $row_postcase['filepath'];

						try {
								$this->_dba->beginTransaction();

								$statement = $this->_dba->query('DELETE FROM postcase WHERE idpt = :idpt');
								$statement->execute([':idpt' => $_iDPost]);

								// update all date in bookcases
								$stmt_two = $this->_dba->query('SELECT postinbookcase_id FROM postinbookcase WHERE idpt = :idpt ');
								$stmt_two->execute([':idpt' => $_iDPost]);

								while($row_post = $stmt_two->fetch(PDO::FETCH_ASSOC)) {
										$stmt = $this->_dba->prepare('DELETE FROM postinbookcase WHERE postinbookcase_id = :ptbc');
										$stmt->execute([':ptbc' => $row_post['postinbookcase_id']]);
								}

								// update all date in shelves
								$stmt_tree = $this->_dba->query('SELECT postonshelve_id FROM postonshelve WHERE idpt = :idpt ');
								$stmt_tree->execute([':idpt' => $_iDPost]);

								while($row_post = $stmt_tree->fetch(PDO::FETCH_ASSOC)) {
										$stmt = $this->_dba->prepare('DELETE FROM postonshelve WHERE postonshelve_id = :ptsh');
										$stmt->execute([':ptsh' => $row_post['postonshelve_id']]);
								}

								# commit the transaction
								$this->_dba->commit();
								unlink($_filepath);
								$message = 'Post ' . $_filepath . ' deleted.';

						} catch (PDOException $e) {
								$this->_dba->rollBack();
								$_exception = 'Post not deleted , some error exception happened. ' . $e->getMessage();
						}
				} else {
						$message = 'Post deleted.';
				}

				return $message;
		}

		function get_title() {
			$_menu =$this->get_left_menu();
			$_title = array('title' => 'Blog Admin · Made Easy');

			return array_merge($_title, $_menu);
		}
}
