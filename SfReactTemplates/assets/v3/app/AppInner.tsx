import React, { Fragment, useEffect, useState } from "react";
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import '@newageerp/v3.app.main-bundle/dist/main-bundle.css'
import { Switch } from "@newageerp/v3.templates.templates-core";
import { useDidMount } from '@newageerp/v3.bundles.hooks-bundle'
import { NaeApiFunctions } from "../../_custom/config/NaePaths";
import NavigationComponent, { SFSSocketService } from "../navigation/NavigationComponent";
import AppRouter from "./AppRouter";
import { UIBuilderProvider } from "../old-ui/builder/OldUIBuilderProvider";
import { DataCacheProvider } from "@newageerp/v3.app.data-cache-provider";
import UserSpaceWrapper from "./UserSpace/UserSpaceWrapper";
import AppRoutes from "../../routes/AppRoutes";
import CustomUserWrapperRoutes from "../../_custom/routes/CustomUserWrapperRoutes";
import { getHookForSchema } from "../../_custom/models-cache-data/ModelFields";
import { WindowProvider } from "@newageerp/v3.bundles.popup-bundle";
import { useDfValue } from "../hooks/useDfValue";

function AppInner() {
    const [isLoaded, setIsLoaded] = useState(false);

    OpenApi.naePaths = NaeApiFunctions;
    const isMount = useDidMount();

    useEffect(() => {
        if (isMount) {
            setIsLoaded(true);
        }
    }, []);

    useEffect(() => {
        if (isLoaded) {
            SFSSocketService.connect();
        }
    }, [isLoaded]);

    const redirectToLogin = () => {
        window.location.href = '/login/';
        return <Fragment />
    }

    return (
        <AppRouter
            authRoutes={
                <Fragment>
                    <UIBuilderProvider>
                        <DataCacheProvider getHookForSchema={getHookForSchema} useDfValue={useDfValue}>
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
                    </UIBuilderProvider>
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
