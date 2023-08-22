import React, { useEffect } from "react";
import { TemplatesCoreProvider, TemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { componentsMap } from "../templates/TemplateLoader";
import { store } from "../../_custom/models/ormstore";
import i18n from "../../_custom/lang/i18";
import { cacheData } from "../../_custom/hooks/DataCacheProviderCacheData";
import { getDataCacheForSchema } from '../../_custom/hooks/DataCacheSocketMap';
import { NaePathsMap } from "../../_custom/config/NaePaths";
import { selectorBySchemaClassName, selectorBySchemaSlug } from "../../_custom/models/ormSelectors";

import { MainBundle } from "@newageerp/v3.app.main-bundle";
import '@newageerp/v3.app.main-bundle/dist/main-bundle.css'

import { getPropertyForPath, getSchemaTitle, INaeStatus } from "../utils";
import { NaeSStatuses } from "../../_custom/config/NaeSStatuses";
import { getDepenciesForField } from "../../_custom/fields/fieldDependencies";
import { NaeSSchema } from "../../_custom/config/NaeSSchema";
import { onEditElementUpdate } from "../../_custom/fields/onEditElementUpdate";
import { fieldVisibility, resetFieldsToDefValues } from "../../_custom/fields/fieldVisibility";
import { PluginsMap } from "../../../Plugins/PluginsMap";
import { getHookForSchema } from "../../_custom/models-cache-data/ModelFields";

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
                templatesMap={componentsMap}
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
                        title: getSchemaTitle,
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
                    enums: {
                        color: (schema: string, field: string, val: any) => {
                            const prop = getPropertyForPath(`${schema}.${field}`);
                            if (prop && prop.enum) {
                                const v = prop.enum.find(el => el.value === val);
                                if (v) {
                                    // @ts-ignore
                                    return v.color;
                                }
                            }
                            return 'slate';
                        },
                        title: (schema: string, field: string, val: any) => {
                            const prop = getPropertyForPath(`${schema}.${field}`);
                            if (prop && prop.enum) {
                                const v = prop.enum.find(el => el.value === val);
                                if (v) {
                                    return v.label;
                                }
                            }
                            return '';
                        },
                    },
                    properties: {
                        path: (p) => getPropertyForPath(p),
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
