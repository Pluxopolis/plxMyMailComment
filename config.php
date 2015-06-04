<?php if (!defined('PLX_ROOT')) exit; ?>
<?php
# Control du token du formulaire
plxToken::validateFormToken($_POST);

if (!empty($_POST)) {
	$plxPlugin->setParam('from', $_POST['from'], 'string');
	$plxPlugin->setParam('to', $_POST['to'], 'string');
	$plxPlugin->setParam('cc', $_POST['cc'], 'string');
	$plxPlugin->setParam('bcc', $_POST['bcc'], 'string');
	$plxPlugin->setParam('author_email', $_POST['author_email'], 'string');	
	$plxPlugin->saveParams();
	header('Location: parametres_plugin.php?p=plxMyMailComment');
	exit;
}
$from = $plxPlugin->getParam('from') == '' ? 'no-reply@domaine.com' : $plxPlugin->getParam('from');
$to = $plxPlugin->getParam('to') == '' ? '' : $plxPlugin->getParam('to');
$cc = $plxPlugin->getParam('cc') == '' ? '' : $plxPlugin->getParam('cc');
$bcc = $plxPlugin->getParam('bcc') == '' ? '' : $plxPlugin->getParam('bcc');
$author_email = $plxPlugin->getParam('author_email') == '' ? 0 : $plxPlugin->getParam('author_email');
?>

<?php
if (function_exists('mail')) {
	echo '<p style="color:green"><strong>'.$plxPlugin->getLang('L_MAIL_AVAILABLE').'</strong></p>';
} else {
	echo '<p style="color:#ff0000"><strong>'.$plxPlugin->getLang('L_MAIL_NOT_AVAILABLE').'</strong></p>';
}
?>
<form id="form_plxMyMailComment" action="parametres_plugin.php?p=plxMyMailComment" method="post">
	<fieldset>
		<p class="field"><label for="id_from"><?php $plxPlugin->lang('L_SENDER_FROM') ?>&nbsp;:</label></p>
		<?php plxUtils::printInput('from', $from, 'text', '60-255') ?>
		<br /><br />
		<p class="field"><label for="id_to"><?php $plxPlugin->lang('L_SENDER_TO') ?>&nbsp;:</label></p>
		<?php plxUtils::printInput('to', $to, 'text', '60-255') ?>
		<p class="field"><label for="id_cc"><?php $plxPlugin->lang('L_SENDER_CC') ?>&nbsp;:</label></p>
		<?php plxUtils::printInput('cc', $cc, 'text', '60-255') ?>
		<p class="field"><label for="id_bcc"><?php $plxPlugin->lang('L_SENDER_BCC') ?>&nbsp;:</label></p>
			<?php plxUtils::printInput('bcc', $bcc, 'text', '60-255') ?>
		<p class="field"><label for="id_author_email"><?php echo $plxPlugin->lang('L_AUTHOR_EMAIL') ?></label></p>
		<?php plxUtils::printSelect('author_email',array('1'=>L_YES,'0'=>L_NO), $author_email);?>
		<br /><br />
		<?php $plxPlugin->lang('L_COMMAS') ?>
		<p class="in-action-bar">
			<?php echo plxToken::getTokenPostMethod() ?>
			<input type="submit" name="submit" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
		</p>
	</fieldset>
</form>
