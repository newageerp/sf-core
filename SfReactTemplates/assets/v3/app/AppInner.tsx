import React, { Fragment, useEffect, useState } from "react";
import { OpenApi } from '@newageerp/nae-react-auth-wrapper';
import { Provider } from 'react-redux';
import '@newageerp/v3.app.main-bundle/dist/main-bundle.css'
import { Switch } from "react-router-dom";
import { useDidMount } from '@newageerp/v3.bundles.hooks-bundle'
import { NaeApiFunctions } from "../../_custom/config/NaePaths";
import NavigationComponent, { SFSSocketService } from "../navigation/NavigationComponent";
import { RoutesWrapper } from "../../routes/wrappers/RoutesWrapper";
import AppRouter from "../../routes/wrappers/AppRouter";
import { UIBuilderProvider } from "../old-ui/builder/OldUIBuilderProvider";
import { DataCacheProvider } from "../../_custom/hooks/DataCacheProvider";
import { NaeWindowProvider } from "../old-ui/OldNaeWindowProvider";
import UserSpaceWrapper from "../../routes/wrappers/UserSpaceWrapper";
import AppRoutes from "../../routes/AppRoutes";
import CustomUserWrapperRoutes from "../../_custom/routes/CustomUserWrapperRoutes";
import { store } from "../../_custom/models/ormstore";

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

        <Provider store={store}>
            <RoutesWrapper>
                <AppRouter
                    authRoutes={
                        <Fragment>
                            <UIBuilderProvider>
                                <DataCacheProvider>
                                    <NaeWindowProvider>
                                        <UserSpaceWrapper>
                                            <Switch>
                                                <AppRoutes />
                                            </Switch>
                                            <Switch>
                                                <CustomUserWrapperRoutes />
                                            </Switch>
                                        </UserSpaceWrapper>
                                        <NavigationComponent />
                                    </NaeWindowProvider>
                                </DataCacheProvider>
                            </UIBuilderProvider>
                        </Fragment>
                    }
                    noAuthRoutes={(isLoggedIn) => {
                        return (
                            <Fragment>
                                {!isLoggedIn && redirectToLogin()}
                            </Fragment>
                        )
                    }

                    }
                />
            </RoutesWrapper>
        </Provider>
    );
}

export default AppInner;
