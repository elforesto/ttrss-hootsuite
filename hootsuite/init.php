<?php
class Hootsuite extends Plugin {
	private $host;

	function about() {
		return array(1.6,
			"Share article on Hootsuite",
			"elforesto, ebell451");
	}

	function api_version() {
		return 2;
	}

	function init($host) {
		$this->host = $host;
		$host->add_hook($host::HOOK_ARTICLE_BUTTON, $this);
	}

	function get_js() {
		return file_get_contents(dirname(__FILE__) . "/hootsuite.js");
	}

	function hook_article_button($line) {
		return "<img src=\"plugins/hootsuite/hootsuite.png\"
			class='tagsPic' style=\"cursor : pointer\"
			onclick=\"hootsuiteArticle(".$line["id"].")\"
			title='".__('Share on Hootsuite')."'>";
	}

	function getInfo() {
		$id = db_escape_string($_REQUEST['id']);
		$result = db_query("SELECT title, link
				FROM ttrss_entries, ttrss_user_entries
				WHERE id = '$id' AND ref_id = id AND owner_uid = " .$_SESSION['uid']);
		if (db_num_rows($result) != 0) {
			$title = truncate_string(strip_tags(db_fetch_result($result, 0, 'title')), 100, '...');
			$article_link = db_fetch_result($result, 0, 'link');
		}
		print json_encode(array("title" => $title, "link" => $article_link,	"id" => $id));
	}
}
?>
