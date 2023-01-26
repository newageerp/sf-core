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


function App() {
    const { t } = useTranslation();

    const redirectToLogin = () => {
        window.location.href = '/login/';
    }

    useEffect(() => {
        window.document.title = `${t('Loading')}...`;
    }, []);

    return (
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
                selectorBySchemaSlug: selectorBySchemaSlug
            }}
        >
            <TemplatesLoader templateName="App" onError={redirectToLogin} />
        </TemplatesCoreProvider>
    );
}

export default App;
