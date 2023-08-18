import React, { Fragment, useEffect, useState } from "react";
import '@newageerp/v3.app.main-bundle/dist/main-bundle.css'
import { Switch } from "@newageerp/v3.templates.templates-core";
import { useDidMount } from '@newageerp/v3.bundles.hooks-bundle'
import {AppRouter, UserSpaceWrapper} from "@newageerp/v3.bundles.app-bundle";
import { DataCacheProvider } from "@newageerp/v3.app.data-cache-provider";
import { WindowProvider } from "@newageerp/v3.popups.window-provider";
import { UiBuilder } from "@newageerp/v3.app.mvc.ui-builder";

import AppRoutes from "../../routes/AppRoutes";
import CustomUserWrapperRoutes from "../../_custom/routes/CustomUserWrapperRoutes";
import { getHookForSchema } from "../../_custom/models-cache-data/ModelFields";
import { NaeApiFunctions } from "../../_custom/config/NaePaths";
import NavigationComponent from "../navigation/NavigationComponent"]

function AppInner() {
    const [isLoaded, setIsLoaded] = useState(false);

    const isMount = useDidMount();

    useEffect(() => {
        if (isMount) {
            setIsLoaded(true);
        }
    }, []);

    const redirectToLogin = () => {
        window.location.href = '/login/';
        return <Fragment />
    }

    return (
        <AppRouter
            authRoutes={
                <Fragment>
                    <UiBuilder>
                        <DataCacheProvider getHookForSchema={getHookForSchema}>
                            <WindowProvider>
                                <UserSpaceWrapper>

                                    <Switch>
                                        <AppRoutes />
                                    </Switch>
                                    <Switch>
                                        <CustomUserWrapperRoutes />
                                    </Switch>

                                </UserSpaceWrapper>
                                <NavigationComponent />
                            </WindowProvider>
                        </DataCacheProvider>
                    </UiBuilder>
                </Fragment>
            }
            noAuthRoutes={(isLoggedIn) => {
                if (!isLoggedIn) {
                    redirectToLogin();
                }
                return (
                    <Fragment></Fragment>
                )
            }

            }
        />
    );
}

export default AppInner;
