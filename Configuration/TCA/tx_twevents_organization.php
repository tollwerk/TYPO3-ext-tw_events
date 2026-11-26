<?php

/**
 * TCA of organisations, which take part in events or host them as a venue
 *
 * @category  Tollwerk
 * @package   Tollwerk\TwEvents
 * @author    Joschi Kuphal <joschi@tollwerk.de>
 * @copyright 2026 tollwerk GmbH
 * @license   https://spdx.org/licenses/GPL-2.0-or-later.html GPL-2.0-or-later
 * @link      https://code.tollwerk.net/tollwerk/typo3/extensions/tw-events
 */

declare(strict_types=1);

use Tollwerk\TwEvents\Tca\LatitudeEvaluation;
use Tollwerk\TwEvents\Tca\LongitudeEvaluation;

return [
    'ctrl' => [
        'title' => 'tw_events.db:tx_twevents_organization',
        'label' => 'name',
        'label_alt' => 'locality',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'delete' => 'deleted',
        'default_sortby' => 'name',
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'translationSource' => 'l10n_source',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'typeicon_classes' => [
            'default' => 'actions-building',
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;core.form.tabs:general,
                    name, short_name, slug, logo, description,
                --div--;tw_events.db:tabs.address,
                    street_address, --palette--;;locality, country, --palette--;;geo, map_url,
                --div--;tw_events.db:tabs.links,
                    website, email, phone, mastodon, linkedin, twitter, facebook,
                --div--;core.form.tabs:media,
                    photos,
                --div--;core.form.tabs:language,
                    sys_language_uid, l10n_parent,
                --div--;core.form.tabs:access,
                    hidden,
            ',
        ],
    ],
    'palettes' => [
        'locality' => ['showitem' => 'postal_code, locality, region'],
        'geo' => ['showitem' => 'latitude, longitude'],
    ],
    'columns' => [
        'name' => [
            'label' => 'tw_events.db:tx_twevents_organization.name',
            'config' => [
                'type' => 'input',
                'required' => true,
                'eval' => 'trim',
            ],
        ],
        'short_name' => [
            'label' => 'tw_events.db:tx_twevents_organization.short_name',
            'description' => 'tw_events.db:tx_twevents_organization.short_name.description',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'slug' => [
            'label' => 'tw_events.db:slug',
            'config' => [
                'type' => 'slug',
                'generatorOptions' => [
                    'fields' => ['name'],
                    'replacements' => ['/' => '-'],
                ],
                'fallbackCharacter' => '-',
                'eval' => 'unique',
            ],
        ],
        'logo' => [
            'label' => 'tw_events.db:tx_twevents_organization.logo',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
                'maxitems' => 1,
            ],
        ],
        'description' => [
            'label' => 'tw_events.db:description',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
            ],
        ],
        'street_address' => [
            'label' => 'tw_events.db:tx_twevents_organization.street_address',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'postal_code' => [
            'label' => 'tw_events.db:tx_twevents_organization.postal_code',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'size' => 10,
                'max' => 20,
            ],
        ],
        'locality' => [
            'label' => 'tw_events.db:tx_twevents_organization.locality',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'region' => [
            'label' => 'tw_events.db:tx_twevents_organization.region',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
            ],
        ],
        'country' => [
            'label' => 'tw_events.db:tx_twevents_organization.country',
            'config' => [
                'type' => 'country',
                'labelField' => 'localizedOfficialName',
            ],
        ],
        'latitude' => [
            'label' => 'tw_events.db:tx_twevents_organization.latitude',
            'description' => 'tw_events.db:coordinate.description',
            'config' => [
                // The number field rounds decimals to two places, about a kilometre
                'type' => 'input',
                'size' => 12,
                'eval' => LatitudeEvaluation::class,
                'nullable' => true,
            ],
        ],
        'longitude' => [
            'label' => 'tw_events.db:tx_twevents_organization.longitude',
            'description' => 'tw_events.db:coordinate.description',
            'config' => [
                // The number field rounds decimals to two places, about a kilometre
                'type' => 'input',
                'size' => 12,
                'eval' => LongitudeEvaluation::class,
                'nullable' => true,
            ],
        ],
        'map_url' => [
            'label' => 'tw_events.db:tx_twevents_organization.map_url',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'website' => [
            'label' => 'tw_events.db:website',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'email' => [
            'label' => 'tw_events.db:tx_twevents_organization.email',
            'config' => [
                'type' => 'email',
            ],
        ],
        'phone' => [
            'label' => 'tw_events.db:tx_twevents_organization.phone',
            'config' => [
                'type' => 'input',
                'eval' => 'trim',
                'max' => 50,
            ],
        ],
        'mastodon' => [
            'label' => 'tw_events.db:mastodon',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'linkedin' => [
            'label' => 'tw_events.db:linkedin',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'twitter' => [
            'label' => 'tw_events.db:twitter',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
        'photos' => [
            'label' => 'tw_events.db:tx_twevents_organization.photos',
            'config' => [
                'type' => 'file',
                'allowed' => 'common-image-types',
            ],
        ],
        'facebook' => [
            'label' => 'tw_events.db:facebook',
            'config' => [
                'type' => 'link',
                'allowedTypes' => ['url'],
            ],
        ],
    ],
];
