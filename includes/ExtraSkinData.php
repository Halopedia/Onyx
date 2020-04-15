<?php

namespace Onyx;

use \Html;

class ExtraSkinData {

	// The time, in seconds, before cached data should be considered expired
	// and hence should be refreshed from the database
	private const CACHE_EXPIRY_TIME = 30;

	public static function extractAndUpdate( array &$data,
			Config $config ) : void {
		self::getNotifications( $data, $config );

		self::getNavigation( $data, $config );

		if ( $config->isEnabled( 'enable-recent-changes-module' ) ) {
			self::getRecentChanges( $data, $config );
		}
	}

	protected static function getNotifications( array &$data,
			Config $config ) : void {
		$data['onyx_notifications'] = [
			'numNotifs' => 0,
			'numMessages' => 0,
			'notifs' => [],
			'messages' => []
		];

		$numNotifs = &$data['onyx_notifications']['numNotifs'];
		$numMessages = &$data['onyx_notifications']['numMessages'];
		$notifs = &$data['onyx_notifications']['notifs'];
		$messages = &$data['onyx_notifications']['messages'];

		if ( !empty( $data['newtalk'] ) ) {
			$messages[] = [
				'text' => Html::rawElement( 'div', [], $data['newtalk'] )
			];
			$numMessages++;
		}

		// TODO: Implement support for notifications in other extensions - such as
		// Echo notifications and SocialProfile
	}

	protected static function getNavigation( array &$data,
			Config $config ) : void {
		// TODO: Implement fetching the Onyx-specific navigation menus (if applicable)
	}

	protected static function getRecentChanges( array &$data,
			Config $config ) : void {
		global $wgMemc;

		$amount = $config->getInteger( 'recent-changes-amount' );

		$cacheExpiryTime = $config->getInteger( 'recent-changes-cache-expiry-time' );

		$cacheKey = $wgMemc->makeKey( 'onyx_recentChanges', $amount );

		$recentChanges = $wgMemc->get( $cacheKey );

		if ( empty( $recentChanges ) ) {
			// If the recentChanges variable is empty, then we will need to fetch the
			// data from the database itself, rather than relying on the cache

			$database = wfGetDB( DB_REPLICA );

			$rawRecentChanges = $database->select(
				'recentchanges',
				[ 'rc_timestamp', 'rc_actor', 'rc_namespace', 'rc_title', 'rc_type',
					'rc_user', 'rc_user_text' ],
				[ 'rc_bot <> 1', 'rc_type <> '.RC_EXTERNAL, 'rc_type <> '.RC_LOG,
				'rc_id IN (SELECT MAX(rc_id) FROM recentchanges GROUP BY rc_namespace, rc_title)' ],
				__METHOD__,
				[ 'ORDER BY' => 'rc_id DESC', 'LIMIT' => $amount, 'OFFSET' => 0 ]
			);

			$actors = [];

			$recentChanges = [];

			foreach ( $rawRecentChanges as $recentChange ) {
				if ( empty( $recentChange->rc_actor ) ) {
					$actorId = $recentChange->rc_user_text;
				} else {
					$actorId = $recentChange->rc_actor;
				}

				$actor = isset( $actors[$actorId] ) ? $actors[$actorId] : '';

				if ( empty( $actor ) ) {
					$actorRaw = $database->selectRow(
						'actor',
						[ 'actor_user', 'actor_name' ],
						[ "actor_id = $recentChange->rc_actor" ]
					);

					$actor = [];

					if ( empty( $actorRaw ) ) {
						// If no results are found in the actors table, default to the
						// deprecated rc_user_text and rc_user fields
						$actor['name'] = $recentChange->rc_user_text;
						$actor['anon'] = empty( $recentChange->rc_user );
					} else {
						$actor['name'] = $actorRaw->actor_name;
						$actor['anon'] = empty( $actorRaw->actor_user );
					}

					$actors[$actorId] = $actor;
				}

				$recentChanges[] = [
					'timestamp' => $recentChange->rc_timestamp,
					'user' => $actor['name'],
					'anon' => $actor['anon'],
					'namespace' => $recentChange->rc_namespace,
					'title' => $recentChange->rc_title,
					'type' => $recentChange->rc_type
				];
			}

			$wgMemc->set( $cacheKey, $recentChanges, $cacheExpiryTime );

		}

		$data['onyx_recentChanges'] = $recentChanges;
	}

	protected static function recursivelyParsePageContents(
			array &$result, int &$index, int $level, string $prefix,
			array $headings ) : void {

		if ( isset( $headings['levels'][$index] ) && $level < $headings['levels'][$index] ) {
			self::recursivelyParsePageContents( $result, $index, $level + 1,
					$prefix, $headings );
		}

		$numHeadings = 1;

		while ( isset( $headings['levels'][$index] )
				&& $level <= $headings['levels'][$index] ) {

			$currentHeading = [
				'href-id' => $headings['ids'][$index],
				'prefix' => $prefix . $numHeadings,
				'name' => $headings['names'][$index],
				'children' => []
			];

			$index++;

			self::recursivelyParsePageContents( $currentHeading['children'],
					$index, $level + 1, $currentHeading['prefix'] . '.', $headings );

			array_push( $result, $currentHeading );

			$numHeadings++;
		}
	}

}
