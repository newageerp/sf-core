import React, { Fragment, useContext, useEffect, useState } from 'react'
import { NaeWidgets } from '../../../_custom/widgets'
import { getPropertyForPath, getTextAlignForProperty, INaeEditField, INaeEditSettings, INaeFormEditRow, INaeFormViewRow, INaeTab, INaeTabField, INaeViewField, INaeViewSettings, INaeWidget } from '../../utils'
import { BuilderWidgetProvider } from './OldBuilderWidgetProvider'

export interface UIBuilderProviderValue {
    config: IUIBuilderRecordItem[]
    widgets: IUIBuilderWidgetRecordItem[]
    tabs: IUIBuilderTabItem[]
    edit: IUIBuilderEditItem[]
    view: IUIBuilderViewItem[]
    defaults: IUIBuilderDefaultsItem[],

    getEditFieldsForSchema: (schema: string, type?: string) => any
    getViewFieldsForSchema: (schema: string, type?: string) => any
    getTabFromSchemaAndType: (schema: string, type?: string) => any
    getTransformedWidgets: () => INaeWidget[],
}

export const UIBuilderProviderContext =
    React.createContext<UIBuilderProviderValue>({
        config: [],
        widgets: [],
        tabs: [],
        edit: [],
        view: [],
        defaults: [],
        getEditFieldsForSchema: (schema: string, type?: string) => { },
        getViewFieldsForSchema: (schema: string, type?: string) => { },
        getTabFromSchemaAndType: (schema: string, type?: string) => { },
        getTransformedWidgets: () => [],
    })

export const useUIBuilder = () => useContext(UIBuilderProviderContext)

export interface UIBuilderProviderProps {
    children: any
}

export const UIBuilderProvider = (props: UIBuilderProviderProps) => {
    const [loaded, setLoaded] = useState(false)
    const [data, setData] = useState({
        builder: [],
        widgets: [],
        tabs: [],
        edit: [],
        view: [],
        defaults: []
    })

    useEffect(() => {
        const url = '/app/nae-core/config-cache/getLocalConfig'
        fetch(url)
            .then((res) => res.json())
            .then((res) => {
                setData(res.data)
                setLoaded(true)
            })
    }, [])

    const builderData: IUIBuilderRecordItem[] = data.builder
    const widgetData: IUIBuilderWidgetRecordItem[] = data.widgets
    const tabsData: IUIBuilderTabItem[] = data.tabs
    const editData: IUIBuilderEditItem[] = data.edit
    const viewData: IUIBuilderViewItem[] = data.view

    const defaultsData: IUIBuilderDefaultsItem[] = data.defaults

    const getEditFieldsForSchema = (
        _schema: string,
        type: string = 'main'
    ) => {
        const editFields = editData.map((e) => configEditToINaeEditSettings(e, getTabFromSchemaAndType))

        const editFieldsFilter = editFields.filter(
            (edit: INaeEditSettings) => edit.schema === _schema && edit.type === type
        )
        if (editFieldsFilter.length > 0) {
            return editFieldsFilter[0]
        }
        return null
    }

    const getViewFieldsForSchema = (
        _schema: string,
        type: string = 'main'
    ) => {
        const viewFields = viewData.map((e) => configViewToINaeViewSettings(e, getTabFromSchemaAndType))
        const viewFieldsFilter = viewFields.filter(
            (view: INaeViewSettings) => view.schema === _schema && view.type === type
        )
        if (viewFieldsFilter.length > 0) {
            return viewFieldsFilter[0]
        }
        return null
    }

    const getTabFromSchemaAndType = (
        schema: string,
        type: string = 'main',
    ): any => {
        const tabs = tabsData.map((t: IUIBuilderTabItem) =>
            configTabToInaeTab(t, defaultsData)
        );
        const founded = tabs.filter(
            (tab) => tab.schema === schema && tab.type === type,
        );
        if (founded.length > 0) {
            return founded[0];
        }
        return {
            fields: [],
            schema: schema,
            type: type,
            sort: [],
        };
    };

    const getTransformedWidgets = () => {
        return NaeWidgets.concat(
            widgetData.map((w: IUIBuilderWidgetRecordItem) =>
                configWidgetToINaeWidget(w)
            )
        )
    }

    return (
        <UIBuilderProviderContext.Provider
            value={{
                config: builderData,
                widgets: widgetData,
                tabs: tabsData,
                edit: editData,
                view: viewData,
                defaults: defaultsData,
                getEditFieldsForSchema: getEditFieldsForSchema,
                getViewFieldsForSchema: getViewFieldsForSchema,
                getTabFromSchemaAndType: getTabFromSchemaAndType,
                getTransformedWidgets: getTransformedWidgets,
            }}
        >
            {loaded ? props.children : <Fragment />}
        </UIBuilderProviderContext.Provider>
    )
}

export interface IUIBuilderViewItem {
    title: string,
    tag: string,
    config: IUIBuilderViewOptions,
    id: string,
}
export interface IUIBuilderEditItem {
    title: string,
    tag: string,
    config: IUIBuilderEditOptions,
    id: string,
}
export interface IUIBuilderDefaultsItem {
    title: string,
    tag: string,
    config: IUIBuilderDefaultsOptions,
    id: string,
}
export interface IUIBuilderRecordItem {
    title: string
    tag: string
    config: IUIBuilderItem[]
    id: string
}
export interface IUIBuilderWidgetRecordItem {
    title: string
    tag: string
    config: IUIBuilderWidgetOptions
    id: string
}
export interface IUIBuilderTabItem {
    title: string,
    tag: string,
    config: IUIBuilderTabOptions,
    id: string,
}
export interface IUIBuilderViewOptions {
    title: string,
    schema: string,
    type: string,
    fields: MainListViewField[],
}
export interface IUIBuilderEditOptions {
    title: string,
    schema: string,
    type: string,
    fields: MainListEditField[],
}
export interface IUIBuilderDefaultsOptions {
    schema: string,
    defaultSort: string,
    defaultQuickSearch: string,
    defaultPath: string,
    fields: IUIBuilderDefaultsField[],
}
export interface IUIBuilderItem {
    type: string
    props: any
    children?: any
}
export interface IUIBuilderWidgetOptions {
    schema: string
    type: string
    builderId: string
    sort: number
    hideScopes: string
    showScopes: string
}
export interface IUIBuilderTabOptions {
    title: string,
    tabGroup: string,
    tabGroupTitle: string,
    schema: string,
    type: string,
    predefinedFilter: string,
    quickSearchFilterKeys: string,
    columns: MainListColumn[],
    sort: string,
    disableCreate: boolean,
    filterDateKey?: string,
    allowExport: boolean,
    pageSize?: number,
    showPivot?: boolean,
}
export interface MainListViewField {
    path: string,
    titlePath?: string,
    customTitle?: string,
    hideLabel?: boolean,

    text?: string,
    type: string,

    arrayRelTab?: string,

    relKeyExtraSelect?: string,

    lineGroup?: string,

    extraFieldsToReturn?: string,
}
export interface MainListColumn {
    path: string,
    titlePath?: string,
    sortPath?: string,
    filterPath?: string,
    customTitle?: string,
    link?: number,
    builderId?: string,
    editable?: false,

    extraFieldsToReturn?: string;
}
export interface IUIBuilderDefaultsField {
    path: string,
}

export interface MainListEditField {
    path: string
    titlePath?: string
    customTitle?: string
    hideLabel?: boolean

    text?: string
    type: string

    lineGroup?: string,

    tagCloudAction?: string,
    tagCloudField?: string,

    fieldDependency?: string,

    inputClassName?: string,
    labelClassName?: string,
    arrayRelTab?: string,

    relKeyExtraSelect?: string,

    extraFieldsToReturn?: string,
}

const configEditToINaeEditSettings = (
    c: IUIBuilderEditItem,
    getTabFromSchemaAndType: (schema: string, type?: string) => any
): INaeEditSettings => {
    const configFieldToINae = (f: MainListEditField) => {
        const pathA = f.path.split('.').slice(1)
        const path = pathA.join('.')
        let prop: INaeEditField = {
            key: path,
            hideLabel: f.hideLabel,
            type: f.type,
            text: f.text,
            fieldDependency: f.fieldDependency,
            inputClassName: f.inputClassName,
            labelClassName: f.labelClassName
        }
        if (pathA.length === 2) {
            prop = {
                key: pathA[0],
                relKey: pathA[1],
                hideLabel: f.hideLabel,
                type: f.type,
                text: f.text,
                fieldDependency: f.fieldDependency,
                inputClassName: f.inputClassName,
                labelClassName: f.labelClassName,
                relKeyExtraSelect: f.relKeyExtraSelect
                    ? f.relKeyExtraSelect.split(',')
                    : undefined
            }
        }

        if (f.tagCloudAction && f.tagCloudField) {
            prop.tagCloud = {
                action: f.tagCloudAction,
                field: f.tagCloudField
            }
        }
        if (f.arrayRelTab) {
            prop.relListObjFunc = () => {
                const [arrayRelSchema, arrayRelType] = (
                    f.arrayRelTab ? f.arrayRelTab : ''
                ).split(':')

                // @ts-ignore
                const tab: INaeTab = getTabFromSchemaAndType(
                    arrayRelSchema,
                    arrayRelType
                )
                return tab
            }
        }
        if (f.extraFieldsToReturn) {
            if (!prop.custom) {
                prop.custom = {};
            }
            prop.custom.fieldsToReturn = JSON.parse(f.extraFieldsToReturn)
        }

        return prop
    }

    const lines: INaeFormEditRow[] = []
    const usedGroups: string[] = []
    c.config.fields.forEach((f: MainListEditField) => {
        const lineGroup = f.lineGroup ? f.lineGroup : ''
        if (usedGroups.indexOf(lineGroup) > -1) {
            return
        }
        if (!lineGroup) {
            lines.push([configFieldToINae(f)])
        } else {
            usedGroups.push(lineGroup)
            lines.push(
                c.config.fields
                    .filter((f) => f.lineGroup === lineGroup)
                    .map((f) => configFieldToINae(f))
            )
        }
    })

    return {
        fields: lines,
        schema: c.config.schema,
        // horizontal?: boolean
        type: c.config.type
    }
}

const configViewToINaeViewSettings = (
    c: IUIBuilderViewItem,
    getTabFromSchemaAndType: (schema: string, type?: string) => any
): INaeViewSettings => {
    const configFieldToINae = (f: MainListViewField) => {
        const pathA = f.path.split('.').slice(1)
        const path = pathA.join('.')
        let prop: INaeViewField = {
            key: path,
            hideLabel: f.hideLabel,
            type: f.type,
            text: f.text
        }
        if (pathA.length === 2) {
            prop = {
                key: pathA[0],
                relKey: pathA[1],
                hideLabel: f.hideLabel,
                type: f.type,
                text: f.text,
                relKeyExtraSelect: f.relKeyExtraSelect
                    ? f.relKeyExtraSelect.split(',')
                    : undefined
            }

        }
        if (f.arrayRelTab) {
            prop.relListObjFunc = () => {
                const [arrayRelSchema, arrayRelType] = (
                    f.arrayRelTab ? f.arrayRelTab : ''
                ).split(':')

                // @ts-ignore
                const tab: INaeTab = getTabFromSchemaAndType(
                    arrayRelSchema,
                    arrayRelType
                )
                return tab
            }
        }
        if (f.extraFieldsToReturn) {
            if (!prop.custom) {
                prop.custom = {};
            }
            prop.custom.fieldsToReturn = JSON.parse(f.extraFieldsToReturn)
        }
        return prop
    }

    const lines: INaeFormViewRow[] = []
    const usedGroups: string[] = []
    c.config.fields.forEach((f: MainListViewField) => {
        const lineGroup = f.lineGroup ? f.lineGroup : ''
        if (usedGroups.indexOf(lineGroup) > -1) {
            return
        }
        if (!lineGroup) {
            lines.push([configFieldToINae(f)])
        } else {
            usedGroups.push(lineGroup)
            lines.push(
                c.config.fields
                    .filter((f) => f.lineGroup === lineGroup)
                    .map((f) => configFieldToINae(f))
            )
        }
    })

    return {
        fields: lines,
        schema: c.config.schema,
        // horizontal?: boolean
        type: c.config.type
    }
}

const configTabToInaeTab = (
    tab: IUIBuilderTabItem,
    builderDefaults?: IUIBuilderDefaultsItem[]
): INaeTab => {
    const defaultRecords = builderDefaults
        ? builderDefaults.filter((f) => f.config.schema === tab.config.schema)
        : []

    const f = tab.config

    let tabSort = undefined
    let tabQuickSearch = undefined

    if (f.sort) {
        try {
            tabSort = JSON.parse(f.sort)
        } catch (e) {
            console.log('configTabToInaeTab error', f.sort, e)
        }
    }

    if (f.quickSearchFilterKeys) {
        try {
            tabQuickSearch = JSON.parse(f.quickSearchFilterKeys)
        } catch (e) {
            console.log('configTabToInaeTab error', f.quickSearchFilterKeys, e)
        }
    }

    if (defaultRecords.length > 0) {
        const defaultRecord = defaultRecords[0]
        if (defaultRecord) {
            if (!tabSort && defaultRecord.config.defaultSort) {
                try {
                    tabSort = JSON.parse(defaultRecord.config.defaultSort)
                } catch (e) {
                    console.log(
                        'configTabToInaeTab error',
                        defaultRecord.config.defaultSort,
                        e
                    )
                }
            }
            if (!tabQuickSearch && defaultRecord.config.defaultQuickSearch) {
                try {
                    tabQuickSearch = JSON.parse(defaultRecord.config.defaultQuickSearch)
                } catch (e) {
                    console.log(
                        'configTabToInaeTab error',
                        defaultRecord.config.defaultQuickSearch,
                        e
                    )
                }
            }
        }
    }

    let filterDateKey = undefined
    if (f.filterDateKey) {
        let filterDateKeyTmp = f.filterDateKey.split('.')
        filterDateKeyTmp[0] = 'i'
        filterDateKey = filterDateKeyTmp.join('.')
    }

    let tabResponse: INaeTab = {
        filterDateKey: filterDateKey,
        schema: f.schema,
        type: f.type,
        sort: tabSort,
        predefinedFilter: f.predefinedFilter
            ? JSON.parse(f.predefinedFilter)
            : undefined,
        quickSearchFilterKeys: tabQuickSearch,
        tabGroup: f.tabGroup,
        tabGroupTitle: f.tabGroupTitle,
        title: f.title,
        fields: f.columns.map((c) => {
            const newF: INaeTabField = {
                key: c.path.split('.').slice(1).join('.'),
                titlePath: c.titlePath
                    ? c.titlePath.split('.').slice(1).join('.')
                    : undefined,
                sortPath: c.sortPath,
                filterPath: c.filterPath,
                link: !!c.link && c.link > 0,
                editable: c.editable,
            }

            if (c.customTitle) {
                const property = getPropertyForPath(c.path)
                newF.custom = {
                    thead: {
                        content: c.customTitle,
                        props: {
                            className: property
                                ? getTextAlignForProperty(property, newF.link)
                                : 'text-left'
                        }
                    }
                }
            }

            if (c.extraFieldsToReturn) {
                if (!newF.custom) {
                    newF.custom = {};
                }
                newF.custom.fieldsToReturn = JSON.parse(c.extraFieldsToReturn)
            }

            return newF
        }),
        disableCreate: f.disableCreate,
        pageExport: f.allowExport,
        pageSize: f.pageSize ? f.pageSize : undefined,
        showPivot: f.showPivot
    }

    return tabResponse
}

export const configWidgetToINaeWidget = (
    w: IUIBuilderWidgetRecordItem
): INaeWidget => {
    return {
        schema: w.config.schema,
        type: w.config.type,
        comp: BuilderWidgetProvider,
        options: {
            builderId: w.config.builderId
        },
        sort: w.config.sort,
        hideScopes: w.config.hideScopes
            ? JSON.parse(w.config.hideScopes)
            : undefined,
        showScopes: w.config.showScopes
            ? JSON.parse(w.config.showScopes)
            : undefined
    }
}
