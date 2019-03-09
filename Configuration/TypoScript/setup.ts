plugin.tx_twevents {
    view {
        templateRootPaths.0 = EXT:tw_events/Resources/Private/Templates/
        templateRootPaths.1 = {$plugin.tx_twevents.view.templateRootPath}
        partialRootPaths.0 = EXT:tw_events/Resources/Private/Partials/
        partialRootPaths.1 = {$plugin.tx_twevents.view.partialRootPath}
        layoutRootPaths.0 = EXT:tw_events/Resources/Private/Layouts/
        layoutRootPaths.1 = {$plugin.tx_twevents.view.layoutRootPath}
    }

    persistence {
        storagePid = {$plugin.tx_twevents.persistence.storagePid}
        #recursive = 1
    }

    settings {
        events {
            details = {$plugin.tx_twevents.settings.events.details}
        }
    }

    features {
        #skipDefaultArguments = 1
        # if set to 1, the enable fields are ignored in BE context
        ignoreAllEnableFieldsInBe = 0
        # Should be on by default, but can be disabled if all action in the plugin are uncached
        requireCHashArgumentForActionArguments = 1
    }

    mvc {
        #callDefaultActionIfActionCantBeResolved = 1
    }
}

