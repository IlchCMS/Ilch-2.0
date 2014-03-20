<?php
/**
 * @copyright Balthazar3k 2014
 * @package Eventplaner 2.0
 */
 
$charaktere = $this->get('eventplaner');

if (!empty($charaktere)) {
    foreach($charaktere as $charakter) {
?>
		<ul>
			<li><a href="#<?php echo $charakter->getId(); ?>"><?php echo $charakter->getName(); ?></a></li>
			<li><?php echo $charakter->getName(); ?></li>
			<li><?php echo $charakter->getCreate(); ?></li>
			<li><?php echo $charakter->getEdit(); ?></li>
		</ul>
<?php
    }
	}else
	{
		$this->addMessage('no_charaktere');
	}

?>
