import React, { useEffect } from "react";
import { TemplatesCoreProvider, TemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { templatesComponentsMap } from "@newageerp/v3.templates.templates-components-map"
import { MainBundle, INaeSchema, INaeStatus, INaeProperty } from "@newageerp/v3.app.main-bundle";
import '@newageerp/v3.app.main-bundle/dist/main-bundle.css'

import { PluginsMap } from "../../../Plugins/PluginsMap";

import { store } from "../../_custom/models/ormstore";
import i18n from "../../_custom/lang/i18";
import { cacheData } from "../../_custom/hooks/DataCacheProviderCacheData";
import { getDataCacheForSchema } from '../../_custom/hooks/DataCacheSocketMap';
import { NaePathsMap } from "../../_custom/config/NaePaths";
import { selectorBySchemaClassName, selectorBySchemaSlug } from "../../_custom/models/ormSelectors";

import { NaeSStatuses } from "../../_custom/config/NaeSStatuses";
import { getDepenciesForField } from "../../_custom/fields/fieldDependencies";
import { NaeSSchema } from "../../_custom/config/NaeSSchema";
import { onEditElementUpdate } from "../../_custom/fields/onEditElementUpdate";
import { fieldVisibility, resetFieldsToDefValues } from "../../_custom/fields/fieldVisibility";
import { getHookForSchema } from "../../_custom/models-cache-data/ModelFields";
import { NaeSProperties } from "../../_custom/config/NaeSProperties";

function App() {
    const redirectToLogin = () => {
        window.location.href = '/login/';
    }

    useEffect(() => {
        window.document.title = `Loading...`;
    }, []);

    return (
        <MainBundle>
            <TemplatesCoreProvider
                templatesMap={templatesComponentsMap}
                pluginsMap={PluginsMap}
                store={store}
                i18n={i18n}
                dataCache={{
                    cacheData: cacheData,
                    getDataCacheForSchema: getDataCacheForSchema,
                }}
                pathMap={NaePathsMap}
                orm={{
                    selectorBySchemaClassName: selectorBySchemaClassName,
                    selectorBySchemaSlug: selectorBySchemaSlug,
                    getHookForSchema: getHookForSchema,
                }}
                modules={{
                    entities: {
                        title: (_schema: string, plural: boolean) => {
                            const schemas = NaeSSchema;

                            let title: string = _schema
                            schemas.forEach((s: INaeSchema) => {
                                if (s.schema === _schema) {
                                    if (plural) {
                                        title = s.titlePlural
                                    } else {
                                        title = s.title
                                    }
                                }
                            })
                            return title
                        },
                        classNameBySlug: (schema: string) => {
                            const _schemaA = NaeSSchema.filter((s) => s.schema === schema)
                            if (_schemaA.length > 0) {
                                return _schemaA[0].className
                            }
                            return '-'
                        },
                        slugByClassName: (className: string) => {
                            const schemas = NaeSSchema;

                            const _schemaA = schemas.filter((s) => s.className === className)
                            if (_schemaA.length > 0) {
                                return _schemaA[0].schema
                            }
                            return '-'
                        }
                    },
                    properties: {
                        path: (_path: string) => {
                            const getDefProperty = (key: string, schema: string) => {
                                return {
                                    key: key,
                                    type: 'NA',
                                    title: '',
                                    schema: schema,
                                    className: schema
                                }
                            }

                            const getPropertyDataForSchema = (
                                _schema: string,
                                key: string
                            ): INaeProperty => {
                                const properties = NaeSProperties
                                const filterProperties = properties.filter((p: INaeProperty) => {
                                    return p.schema === _schema && p.key === key
                                })
                                if (filterProperties.length > 0) {
                                    return filterProperties[0]
                                }
                                return getDefProperty(key, _schema)
                            }


                            const path = _path.split('.')

                            if (path.length === 1) {
                                return null
                            } else {
                                let _schema = ''
                                for (let i = 0; i < path.length; i++) {
                                    if (i === 0) {
                                        _schema = path[0]
                                    } else if (i === path.length - 1) {
                                        const prop = getPropertyDataForSchema(_schema, path[i])
                                        if (!!prop && prop.type !== 'NA') {
                                            return prop
                                        } else {
                                            // console.log('no prop', _schema, path[i])
                                            return null
                                        }
                                    } else {
                                        const _lastSchema = _schema
                                        _schema = ''
                                        const prop = getPropertyDataForSchema(_lastSchema, path[i])
                                        if (!!prop && prop.type !== 'NA') {
                                            if ((prop.type === 'array' || prop.type === 'rel') && !!prop.format) {
                                                _schema = prop.format
                                            }
                                        } else {
                                            // console.log('no prop rel', _schema, path[i])
                                        }
                                    }
                                }
                            }
                            return null
                        },
                        depenciesForField: getDepenciesForField,
                        onUpdate: onEditElementUpdate,
                        resetToDefaults: resetFieldsToDefValues,
                        visibility: fieldVisibility,
                    },
                    statuses: {
                        color: (schema, field, value) => {
                            const s: INaeStatus | undefined = NaeSStatuses.find(e => e.schema === schema && e.type === field && e.status === value)
                            return s && s.variant ? s.variant : 'slate';
                        },
                        title: (schema, field, value) => {
                            const s: INaeStatus | undefined = NaeSStatuses.find(e => e.schema === schema && e.type === field && e.status === value)
                            return s ? s.text : '';
                        },
                    },
                    tabs: {
                        get: (schema: string, type: string) => {
                            return {
                                fields: [],
                                schema: schema,
                                type: type,
                                sort: [],
                            };
                        }
                    }
                }}
            >
                <TemplatesLoader templateName="App" onError={redirectToLogin} />
            </TemplatesCoreProvider>
        </MainBundle>
    );
}

export default App;
