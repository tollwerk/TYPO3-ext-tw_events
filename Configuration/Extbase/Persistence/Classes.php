<?php

/**
 * Tables of the domain models
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

use Tollwerk\TwEvents\Domain\Model\Coverage;
use Tollwerk\TwEvents\Domain\Model\Event;
use Tollwerk\TwEvents\Domain\Model\Note;
use Tollwerk\TwEvents\Domain\Model\Organization;
use Tollwerk\TwEvents\Domain\Model\Participation;
use Tollwerk\TwEvents\Domain\Model\Person;
use Tollwerk\TwEvents\Domain\Model\Series;
use Tollwerk\TwEvents\Domain\Model\Session;

return [
    Coverage::class => ['tableName' => 'tx_twevents_coverage'],
    Event::class => ['tableName' => 'tx_twevents_event'],
    Note::class => ['tableName' => 'tx_twevents_note'],
    Organization::class => ['tableName' => 'tx_twevents_organization'],
    Participation::class => ['tableName' => 'tx_twevents_participation'],
    // Persons are frontend users with the fields of tw-accounts
    Person::class => [
        'tableName' => 'fe_users',
        'properties' => [
            'givenName' => ['fieldName' => 'first_name'],
            'familyName' => ['fieldName' => 'last_name'],
            'slug' => ['fieldName' => 'tx_twaccounts_slug'],
            'public' => ['fieldName' => 'tx_twaccounts_public'],
            'photo' => ['fieldName' => 'image'],
            'summary' => ['fieldName' => 'tx_twaccounts_summary'],
            'teaser' => ['fieldName' => 'tx_twaccounts_teaser'],
            'biography' => ['fieldName' => 'tx_twaccounts_biography'],
            'website' => ['fieldName' => 'www'],
            'mastodon' => ['fieldName' => 'tx_twaccounts_mastodon'],
            'github' => ['fieldName' => 'tx_twaccounts_github'],
            'linkedin' => ['fieldName' => 'tx_twaccounts_linkedin'],
            'twitter' => ['fieldName' => 'tx_twaccounts_twitter'],
            'facebook' => ['fieldName' => 'tx_twaccounts_facebook'],
            'instagram' => ['fieldName' => 'tx_twaccounts_instagram'],
        ],
    ],
    Series::class => ['tableName' => 'tx_twevents_series'],
    Session::class => ['tableName' => 'tx_twevents_session'],
];
