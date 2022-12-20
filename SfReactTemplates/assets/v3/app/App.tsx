import React from "react";
import { TemplatesCoreProvider, TemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { componentsMap } from "../templates/TemplateLoader";
import { store } from "../../_custom/models/ormstore";
import i18n from "../../_custom/lang/i18";

function App() {
    
    const redirectToLogin = () => {
        window.location.href = '/login/';
    }

    return (
        <TemplatesCoreProvider templatesMap={componentsMap} store={store} i18n={i18n}>
            <TemplatesLoader templateName="App" onError={redirectToLogin} />
        </TemplatesCoreProvider>
    );
}

export default App;
