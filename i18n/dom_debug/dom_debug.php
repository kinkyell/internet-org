<?php
/**
 * This is a tool for dumping DomDocument Nodes in a somewhat readable format
 *
 * Shamelessly snagged from the interwebs (stackoverflow)
 *
 * @see https://stackoverflow.com/a/8631974
 *
 * @author Adam <arichard@nerdery.com>
 */

require_once( __DIR__ . '/IteratorDecoratorStub.php' );
require_once( __DIR__ . '/DomIterator.php' );
require_once( __DIR__ . '/DomRecursiveIterator.php' );
require_once( __DIR__ . '/RecursiveIteratorDecoratorStub.php' );
require_once( __DIR__ . '/DomRecursiveDecoratorStringAsCurrent.php' );

function xmltree_dump( DOMNode $node )
{
	$iterator = new DOMRecursiveIterator( $node );
	$decorated = new DOMRecursiveDecoratorStringAsCurrent( $iterator );
	$tree = new RecursiveTreeIterator( $decorated );

	echo '<pre>';
	foreach ( $tree as $key => $value ) {
		echo htmlentities( $value, ENT_QUOTES ) . "\n";
	}
	echo '</pre>';
}

