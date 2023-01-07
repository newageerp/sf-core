import React, { useEffect } from "react";
import { TextToolbarTitle } from "@newageerp/v3.bundles.typography-bundle";
import CustomToolbarBefore from "../../../_custom/layout/CustomToolbarBefore";
import CustomToolbarAfter from "../../../_custom/layout/CustomToolbarAfter";
import { useRecoilValue } from "@newageerp/v3.templates.templates-core";
import { useComponentVisible } from '@newageerp/v3.bundles.hooks-bundle';
import { Menu } from '@newageerp/v3.bundles.modal-bundle';
import classNames from "classnames";
import { MainToolbar } from "@newageerp/v3.bundles.layout-bundle";
import { TemplatesParser, useTemplatesCore, useTemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { UserSpaceWrapperToolbarState } from '@newageerp/v3.bundles.layout-bundle';

interface Props {
        children?: any
}

function UserSpaceWrapperToolbar(props: Props) {
        const { userState } = useTemplatesCore();
        const { data: tData } = useTemplatesLoader();

        const settingsVisibleData = useComponentVisible(false);

        const toolbarTitle = useRecoilValue(UserSpaceWrapperToolbarState)

        const toggleDarkMode = () => {
                if (localStorage.getItem('NAEDarkMode') === "dark") {
                        localStorage.setItem("NAEDarkMode", "light");
                } else {
                        localStorage.setItem("NAEDarkMode", "light");
                }
                checkDarkMode();
        }

        const checkDarkMode = () => {
                if (localStorage.getItem('NAEDarkMode') === "dark") {
                        window.document.querySelectorAll('body').forEach(i => {
                                i.classList.add('dark');
                        });
                } else {
                        window.document.querySelectorAll('body').forEach(i => {
                                i.classList.remove('dark');
                        });
                }
        }
        useEffect(() => {
                checkDarkMode();
        }, []);

        return (
                <MainToolbar
                        leftSideComponent={
                                <div className="tw3-pl-4 lg:tw3-pl-8 xl:tw3-pl-16">
                                        <CustomToolbarBefore />
                                        <TextToolbarTitle>{toolbarTitle}</TextToolbarTitle>
                                </div>
                        }
                >
                        <TemplatesParser
                                templates={tData.userSpaceWrapperToolbarButtons}
                        />
                        <button onClick={toggleDarkMode} className="tw3-ml-20">
                                <i className="fa-duotone fa-circle-half-stroke tw3-text-sky-50"></i>
                        </button>
                        <span className="tw3-relative tw3-ml-4" ref={settingsVisibleData.ref} style={{ width: 200 }}>
                                <div className="tw3-flex tw3-gap-2 tw3-items-center tw3-text-white tw3-cursor-pointer" onClick={() => settingsVisibleData.setIsComponentVisible(!settingsVisibleData.isComponentVisible)}>
                                        <span className={classNames('tw3-flex tw3-items-center tw3-justify-center', 'tw3-text-sky-50 tw3-bg-sky-50/20', 'tw3-text-xs', 'tw3-rounded-full')} style={{ width: 24, height: 24, minWidth: 24, }}>
                                                <i className="fa-fw fa-regular fa-user" />
                                        </span>
                                        <p className="tw3-flex-grow tw3-text-xs tw3-text-sky-50">{userState.fullName}</p>
                                        <i className="fa-fw fa-regular fa-angle-down tw3-text-xs tw3-text-sky-50/50" />
                                </div>
                                {!!settingsVisibleData.isComponentVisible && <Menu isAbsolute={true}>
                                        <TemplatesParser
                                                templates={tData.userSpaceWrapperToolbarMenu}
                                        />
                                </Menu>}

                        </span>
                        <CustomToolbarAfter />
                </MainToolbar>
        );
}

export default UserSpaceWrapperToolbar;
