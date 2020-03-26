plugin.tx_twevents {
    view {
        templateRootPaths {
            0 = EXT:tw_events/Resources/Private/Templates/
            10 = {$plugin.tx_twevents.view.templateRootPath}
        }

        partialRootPaths {
            0 = EXT:tw_events/Resources/Private/Partials/
            10 = {$plugin.tx_twevents.view.partialRootPath}
        }

        layoutRootPaths {
            0 = EXT:tw_events/Resources/Private/Layouts/
            10 = {$plugin.tx_twevents.view.layoutRootPath}
        }
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

lib.content {
    render = CONTENT
    render {
        table = tt_content
        select {
            orderBy = sorting
            where.cObject = COA
            where.cObject {
                10 = TEXT
                10 {
                    field = colPos
                    intval = 1
                    ifEmpty = 0
                    noTrimWrap = | AND colPos = ||
                }

                20 = TEXT
                20 {
                    field = stage
                    intval = 1
                    ifEmpty = 7
                    noTrimWrap = | AND tx_twevents_stages & ||
                }
            }
        }
    }
}

