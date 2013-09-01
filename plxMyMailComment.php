<?php
/**
 * Plugin plxMyMailComment
 *
 **/
class plxMyMailComment extends plxPlugin {

	/**
	 * Constructeur de la classe
	 *
	 * @param	default_lang	langue par d�faut utilis�e par PluXml
	 * @return	null
	 * @author	Stephane F
	 **/
	public function __construct($default_lang) {

		# Appel du constructeur de la classe plxPlugin (obligatoire)
		parent::__construct($default_lang);

		# droits pour acc�der � la page config.php et admin.php du plugin
		$this->setConfigProfil(PROFIL_ADMIN);
		$this->setAdminProfil(PROFIL_ADMIN);

		# ajout du hook
		$this->addHook('plxMotorDemarrageNewCommentaire', 'plxMotorDemarrageNewCommentaire');

	}

	/**
	 * Méthode qui envoie un mail à l'auteur de l'article
	 *
	 * @return	stdio
	 * @author	St�phane F, Deevad, petitchevalroux
	 **/
	public function plxMotorDemarrageNewCommentaire() {

		$string = '
		if($retour[0]=="c" OR $retour=="mod") {
			$article_author = $this->plxRecord_arts->f("author");
			$article_author_email = $this->aUsers[$article_author]["email"];
			$from = "'.$this->getParam('from').'";
			$to = "'.$this->getParam('to').'";
			$cc = "'.$this->getParam('cc').'";
			$bcc = "'.$this->getParam('bcc').'";
			$eSubject = "'.$this->getLang("L_EMAIL_NEW_COMMENT").' ".$this->plxRecord_arts->f("title");
			$eBody  = "'.$this->getLang("L_EMAIL_NEW_COMMENT_BY").' <strong>".plxUtils::unSlash($_POST["name"])."</strong><br /><br />";
			$eBody .= plxUtils::unSlash($_POST["content"])."<br /><br />";
			$eBody .= "-----------<br />";
			if($retour[0]=="c") {
				$eBody .= "'.$this->getLang("L_EMAIL_READ_ONLINE").' : <a href=\"".$url."/#".$retour."\">".$this->plxRecord_arts->f("title")."</a>";
				$eBody .= "<br /><a href=\"".$this->racine."core/admin/comments.php\">'.$this->getLang("L_EMAIL_MANAGE_COMMENTS").'</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;";
				$eBody .= "<a href=\"".$this->racine."core/admin/comment_new.php?c=".$this->plxRecord_arts->f("numero").".".substr($retour,1)."\">'.$this->getLang("L_EMAIL_DIRECT_ANSWER").'</a>";
			} else {
				$eBody .= "'.$this->getLang("L_EMAIL_WAITING_MODERATION").'<br />";
				$eBody .= "<a href=\"".$this->racine."core/admin/comments.php?sel=offline&page=1\">'.$this->getLang("L_EMAIL_MANAGE_COMMENTS").'</a>&nbsp;&nbsp;&nbsp;";
			}
			plxUtils::sendMail($this->aConf["title"], $from, $to, $eSubject, $eBody, "html", $cc, $bcc);
			# envoi d\'un email à l\'auteur de l\'article
			$author_email = "'.$this->getParam('author_email').'";
			if($article_author_email!="" AND $author_email=="1") {
				plxUtils::sendMail($this->aConf["title"], $from, $article_author_email, $eSubject, $eBody, "html");
			}
		}
		';
		echo '<?php '.$string.' ?>';
	}

}
?>