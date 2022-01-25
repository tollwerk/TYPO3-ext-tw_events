# customsubcategory=ids=LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:ids
# customsubcategory=file=LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:file

plugin.tx_twevents {
    view {
        # cat=plugin.tx_twevents/file/a; type=string; label=LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:file.templateRootPath
        templateRootPath = EXT:tw_events/Resources/Private/Templates/
        # cat=plugin.tx_twevents/file/b; type=string; label=LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:file.partialRootPath
        partialRootPath = EXT:tw_events/Resources/Private/Partials/
        # cat=plugin.tx_twevents/file/c; type=string; label=LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:file.layoutRootPath
        layoutRootPath = EXT:tw_events/Resources/Private/Layouts/
    }

    persistence {
        # cat=plugin.tx_twevents//a; type=string; label=LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:ids.storage
        storagePid =

    }

    settings {
        events {
            # cat=plugin.tx_twevents//a; type=string; label=LLL:EXT:tw_events/Resources/Private/Language/locallang_db.xlf:ids.events.details
            details =
        }
    }
}
