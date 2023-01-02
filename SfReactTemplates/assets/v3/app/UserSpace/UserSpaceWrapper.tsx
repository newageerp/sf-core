import React, { Fragment } from "react";
import { LayoutLeftMenuToolbar } from "@newageerp/ui.ui-bundle";
import UserSpaceWrapperToolbar from "./UserSpaceWrapperToolbar";
import { TemplatesParser, useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import DataCacheSocketComponent from "../../../_custom/hooks/DataCacheSocketComponent";

interface Props {
  children?: any;
}

function UserSpaceWrapper(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const isDev = (!process.env.NODE_ENV || process.env.NODE_ENV === 'development');

  return (
    <LayoutLeftMenuToolbar
      leftSideContent={
        <Fragment>
          <div>
            <TemplatesParser
              templates={tData.userSpaceWrapperLeft}
            />
          </div>
          {isDev &&
            <div>
              <a className="tw3-text-white tw3-mt-20 tw3-text-xs tw3-underline tw3-decoration-wavy" href="/app/nae-core/config-menu/regenerate" target="_blank">Regenerate menu</a>
            </div>
          }
          <DataCacheSocketComponent />
        </Fragment>
      }
      toolbar={<UserSpaceWrapperToolbar />}
    >
      {props.children}
    </LayoutLeftMenuToolbar>
  );
}

export default UserSpaceWrapper;