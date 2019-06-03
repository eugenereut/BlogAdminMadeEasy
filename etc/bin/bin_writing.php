<?php
/*
* bin_main.php - model writing
*
* maintained by:  Eugene Reut <eugene.reut@gmail.com>
* Please ALWAYS copy eugene.reut@gmail.com
* on emails.
*
*/
class Bin_Writing extends Bin
{
		function __construct() {
				$this->_dba = $this->db_access();
				$this->_dba->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		function get_data($_id = null, $_listing = null) {
				if (isset($_POST['Addnewpost'])) {
						$message = $this->insert_newPost($_POST['Datefrom'], $_POST['Headerpost'], $_POST['Bodypost']);
				} else {
						$message = null;
				}

				$content = $this->clear_textareacontent();
				$message = array('houston' => $message);

				return array_merge($content, $message);
		}

		private function insert_newPost($_datefrom, $_headerpost, $_bodypost) {
				if (!empty($_headerpost) and !empty($_bodypost)) {
						$_year = date('Y', strtotime($_datefrom));
						$_month = date('M', strtotime($_datefrom));
						$_datefrom = date('Y-m-d', strtotime($_datefrom));

						$_filename = $this->make_new_filename($_year, $_month);

						if (file_exists($_filename)) {
								# if true, make DB query
								if ($this->makepost($_filename, $_bodypost)) {
										$_idquery = $this->make_newpost_dbaquery($_datefrom, $_filename, $_headerpost);

										# redirection to the setup page
										if ($_idquery['error']) {
												$message = $_idquery['error'];
										} else {
												$this->setup_newpost_intobookcase($_idquery['result']);
										}
								} else {
										$message = 'New post not added, cant write to the new file.';
								}
						} else {
								$message = 'New post not added, cant create file name for the new post.';
						}
				} else {
						$message = 'New post not added, Post name or post bosy is empty.';
				}

				return $message;
		}

		private function make_newpost_dbaquery($_datefrom, $_filename, $_headerpost) {
				try {
						$this->_dba->beginTransaction();

						$_stmt = $this->_dba->prepare('INSERT INTO postcase (datepost, filepath, postname) VALUES (?, ?, ?)');
						$_stmt->execute(array($_datefrom, $_filename, $_headerpost));

						$message = array('error' => null, 'result' => $this->_dba->lastInsertId());

						# commit the transaction
						$this->_dba->commit();
				} catch (PDOException $e) {
						$this->_dba->rollBack();
						$message = array('error' => 'Post not added, some error exception happened. ' . $e->getMessage(), 'result' => null);
				}

				return $message;
		}

		private function setup_newpost_intobookcase($_idquery) {
				header('Location: /setuppost?idpt=' . $_idquery);
		}

		//---------------------------- files function -----------------
		private function make_new_filename($_year, $_month) {
				$_dir = "opt/posts";
				$_dir = $this->makedir_theme($_dir . "/" . $_year);
				$_dir = $this->makedir_theme($_dir . "/" . $_month);

				if(file_exists($_dir)) {
						# create an unique file name
						$_tmpfname = tempnam($_dir, "yakov.works");
						# get a filename here
						$_tmpfname = basename($_tmpfname);
						# make path opt/posts/Year/Month/unique_filename
						$_tmpfname = $_dir . "/" . $_tmpfname;
				} else {
						$_tmpfname = false;
				}

				return $_tmpfname;
		}

		private function makepost ($filename, $story) {
				//make the post
				$fh = fopen($filename, "w+");

				if ($fh) {
						$story = $story . "\r\n";
						fputs($fh, $story);
						fclose($fh);

						return true;
				} else {
						return false;
				}
		}

		private function makedir_theme($_dir)	{
				if(file_exists($_dir)) {
						return $_dir;
				} else {
						mkdir($_dir, 0777);

						return $_dir;
				}
		}

		private function clear_textareacontent() {
				return array('Headerpost' => null, 'Bodypost' => 'Text goes here!');
		}

		function get_title() {
				$_title = array('title' => 'Blog Admin · Made Easy');
		}
}
