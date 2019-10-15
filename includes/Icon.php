<?php

namespace Onyx;

use \Html;

class Icon {
	private static $icons = [];

	private static $iconSources = [
		// TODO: Make this 28x28
		'avatar' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 14 2 Q 20 2 20 8 Q 20 14 14 14 Q 8 14 8 8 Q 8 2 14 2 Z M 2 26 L 2 23 Q 2 17 8 17 L 20 17 Q 26 17 26 23 L 26 26 Z'
					]
				]
			]
		],
		'edit' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 20 2 L 26 8 L 8 26 L 2 26 L 2 20 Z M 16 6 L 22 12'
					]
				]
			]
		],
		'talk' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 2 2 L 26 2 L 26 20 L 14 20 L 8 26 L 8 20 L 2 20 Z'
					]
				]
			]
		],
		'view' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 2 14 Q 14 0 26 14 Q 14 28 2 14 Z M 14 10 Q 18 10 18 14 Q 18 18 14 18 Q 10 18 10 14 Q 10 10 14 10 Z'
					]
				]
			]
		],
		'sidebar' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 2 2 L 15 2 L 15 26 L 2 26 Z'
					]
				],
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 19 2 L 26 2 L 26 26 L 19 26 Z'
					]
				]
			]
		],
		'back' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 10 2 L 10 6 Q 26 6 26 16 Q 26 26 14 26 Q 22 26 22 20 Q 22 14 10 14 L 10 18 L 2 10 Z'
					]
				]
			]
		],
		'cancel' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 6 2 L 14 10 L 22 2 L 26 6 L 18 14 L 26 22 L 22 26 L 14 18 L 6 26 L 2 22 L 10 14 L 2 6 Z'
					]
				]
			]
		],
		'notification' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 2 18 Q 8 18 8 12 L 8 8 Q 8 2 14 2 Q 20 2 20 8 L 20 12 Q 20 18 26 18 Z M 18 22 Q 18 26 14 26 Q 10 26 10 22 Z'
					]
				]
			]
		],
		'message' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 2 6 L 26 6 L 26 24 L 2 24 Z M 2 7 L 14 19 L 26 7 L 26 24 L 2 24 Z'
					]
				]
			]
		],
		'activity' => [
			'width' => 28,
			'height' => 28,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 2 14 L 6 14 L 10 6 L 18 22 L 22 14 L 26 14'
					]
				]
			]
		],
		'dropdown' => [
			'width' => 14,
			'height' => 14,
			'content' => [
				[
					'type' => 'path',
					'attributes' => [
						'd' => 'M 2 5 L 12 5 L 7 10 Z'
					]
				]
			]
		],
		'close' => [
			'width' => 14,
			'height' => 14,
			'content' => [
				[
					'type' => 'line',
					'attributes' => [
						'x1' => 2, 'y1' => 2, 'x2' => 12, 'y2' => 12
					]
				],
				[
					'type' => 'line',
					'attributes' => [
						'x1' => 2, 'y1' => 12, 'x2' => 12, 'y2' => 2
					]
				],
			]
		]
	];

	public static function getIcon( string $iconName ) : ?Icon {
		if ( isset( Icon::$icons[$iconName] ) ) {
			// If the requested icon is already part of the icon array, just return
			// it immediately
			return Icon::$icons[$iconName];
		} elseif ( isset( Icon::$iconSources[$iconName] ) ) {
			// Otherwise, if the requested icon is part of the iconSources array,
			// construct a new Icon object using the iconSources info, add it to the
			// icon array and return it
			$source = Icon::$iconSources[$iconName];
			Icon::$icons[$iconName] = new Icon( $source['width'], $source['height'],
					$source['content'] );
			return Icon::$icons[$iconName];
		} else {
			// Finally, if the requested icon is not part of either array, just
			// return null - no such icon exists
			return null;
		}
	}

	private $defaultWidth;
	private $defaultHeight;
	private $content;

	public function __construct( int $defaultWidth, int $defaultHeight,
			array $content ) {
		$this->defaultWidth = $defaultWidth;
		$this->defaultHeight = $defaultHeight;
		$this->content = $content;
	}

	public function makeSvg( int $width = -1, int $height = -1,
			array $attributes = [] ) : string {
		if ( $width < 0 ) {
			$width = $this->defaultWidth;
		}

		if ( $height < 0 ) {
			$height = $this->defaultWidth;
		}

		$attributes['width'] = $width;
		$attributes['height'] = $height;
		$attributes['viewBox'] = "0 0 $width $height";

		$result = Html::openElement( 'svg', $attributes );
		$result .= $this->makeInnerSvg( $width, $height );
		$result .= Html::closeElement( 'svg' );

		return $result;
	}

	public function makeInnerSvg( int $width = -1, int $height = -1 ) : string {
		if ( $width < 0 ) {
			$width = $this->defaultWidth;
		}

		if ( $height < 0 ) {
			$height = $this->defaultHeight;
		}

		$result = '';

		foreach ( $this->content as $element ) {
			$this->makeElement( $result, $element, $width, $height );
		}

		return $result;
	}

	protected function makeElement( string &$result, array $element,
			int $width, int $height ) : void {

		// TODO: Implement rescaling of element to match the given width and height

		$result .= Html::element( $element['type'], $element['attributes'] );
	}

}
