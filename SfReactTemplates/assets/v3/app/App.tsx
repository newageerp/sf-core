import React, { useEffect } from "react";
import { TemplatesCoreProvider, TemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { componentsMap } from "../templates/TemplateLoader";
import { store } from "../../_custom/models/ormstore";
import i18n from "../../_custom/lang/i18";
import { useTranslation } from 'react-i18next';
import { cacheData } from "../../_custom/hooks/DataCacheProviderCacheData";
import { getDataCacheForSchema } from '../../_custom/hooks/DataCacheSocketMap';
import { NaePathsMap } from "../../_custom/config/NaePaths";
import { selectorBySchemaClassName, selectorBySchemaSlug } from "../../_custom/models/ormSelectors";

import { MainBundle } from "@newageerp/v3.app.main-bundle";
import { getPropertyForPath, getSchemaTitle, INaeStatus } from "../utils";
import { NaeSStatuses } from "../../_custom/config/NaeSStatuses";

function App() {
    const { t } = useTranslation();

    const redirectToLogin = () => {
        window.location.href = '/login/';
    }

    useEffect(() => {
        window.document.title = `${t('Loading')}...`;
    }, []);

    return (
        <MainBundle>
            <TemplatesCoreProvider
                templatesMap={componentsMap}
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
                }}
                modules={{
                    entities: {
                        title: getSchemaTitle
                    },
                    enums: {
                        color: (schema: string, field: string, val: any) => {
                            const prop = getPropertyForPath(`${schema}.${field}`);
                            // if (prop && prop.enum) {
                            //     const v = prop.enum.find(el => el.value === val);
                            //     if (v) {
                            //         return v.color;
                            //     }
                            // }
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
                        path: (p) => getPropertyForPath(p)
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
                    }
                }}
            >
                <TemplatesLoader templateName="App" onError={redirectToLogin} />
            </TemplatesCoreProvider>
        </MainBundle>
    );
}

export default App;
