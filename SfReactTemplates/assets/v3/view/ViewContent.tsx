import React, { Fragment, useState } from "react";

import { TemplatesLoader, Template, useTemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { fieldVisibility } from "../../_custom/fields/fieldVisibility";
import { useTranslation } from "@newageerp/v3.templates.templates-core";
import { ElementToolbar } from "@newageerp/ui.ui-bundle";
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import { ToolbarButtonWithMenu } from "@newageerp/v3.bundles.buttons-bundle";
import { AlertWidget, WhiteCard } from "@newageerp/v3.bundles.widgets-bundle";
import {
  SFSOpenEditModalWindowProps,
  SFSOpenEditWindowProps,
  usePopup,
} from "@newageerp/v3.bundles.popup-bundle";
import { checkIsEditable, INaeWidget, WidgetType } from "../utils";
import classNames from 'classnames';
import { LogoLoader } from "@newageerp/v3.bundles.layout-bundle";
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';
import { useNaeRecord } from "@newageerp/v3.app.mvc.record-provider";

interface Props {
  schema: string;
  type: string;
  id: string;

  formContent: Template[];
  editable: boolean;
  removable: boolean;

  rightContent: Template[];
  middleContent: Template[];
  bottomContent: Template[];
  bottomExtraContent: Template[];

  afterTitleBlockContent: Template[];
  elementToolbarAfterFieldsContent: Template[];
  elementToolbarLine2BeforeContent: Template[];
  elementToolbarMoreMenuContent: Template[];
  elementToolbarAfter1Line: Template[];

  layoutLeftColClassName?: string,
  layoutRightColClassName?: string,
}

export default function ViewContent(props: Props) {
  const { data: tdata } = useTemplatesLoader();

  const { t } = useTranslation();
  const {userState} = useTemplatesCore()

  const { element, reloadData, reloading } = useNaeRecord();

  const {
    rightContent,
    middleContent,
    bottomContent,
    bottomExtraContent,
    afterTitleBlockContent,
    elementToolbarAfterFieldsContent,
    elementToolbarLine2BeforeContent,
    elementToolbarMoreMenuContent,
    elementToolbarAfter1Line,
  } = props;

  const { isPopup } = usePopup();
  const [viewKey, setViewKey] = useState(0);

  const isEditInPopup = tdata.forceEditInPopup
    ? tdata.forceEditInPopup
    : isPopup;

  const [doRemove] = OpenApi.useURemove(props.schema);

  const { removable } = props;

  const editable = checkIsEditable(element ? element.scopes : [], userState);

  const onEdit = editable
    ? () => {
      if (tdata.onEdit) {
        tdata.onEdit();
      } else {
        if (isEditInPopup) {
          SFSOpenEditModalWindowProps({
            id: props.id,
            schema: props.schema,
            type: props.type,
            onSaveCallback: (_el: any, backFunc: any) => {
              reloadData().then(() => {
                setViewKey(viewKey + 1);
                backFunc();
              });
            },
          });
        } else {
          SFSOpenEditWindowProps({
            id: props.id,
            schema: props.schema,
            type: props.type,
          });
        }
      }
    }
    : undefined;

  const onRemove = removable
    ? () => {
      doRemove(props.id).then(() => {
        if (tdata.onBack) {
          tdata.onBack();
        }
      });
    }
    : undefined;

  const scopes = !!element && element.scopes ? element.scopes : [];
  const canShowElement =
    !!element && element.id > 0 && scopes.indexOf("disable-view") === -1;

  return (
    <Fragment>
      {canShowElement && (
        <ElementToolbar
          parentId={element.id}
          parentSchema={props.schema}
          onBack={tdata.onBack ? tdata.onBack : () => { }}
          element={element}
          onEdit={onEdit}
          onRemove={onRemove}
          contentBefore1Line={
            <Fragment />
          }
          contentBefore2Line={
            <Fragment>
              {elementToolbarMoreMenuContent.length > 0 && (
                <ToolbarButtonWithMenu
                  button={{
                    iconName: "circle-ellipsis-vertical",
                  }}
                  menu={{
                    children: (
                      <TemplatesLoader
                        templates={elementToolbarMoreMenuContent}
                        templateData={{ element: element }}
                      />
                    ),
                  }}
                />
              )}

              <TemplatesLoader
                templates={elementToolbarLine2BeforeContent}
                templateData={{ element: element }}
              />
            </Fragment>
          }
          contentAfter1Line={
            <TemplatesLoader
              templates={elementToolbarAfter1Line}
              templateData={{ element: element }}
            />
          }
          contentAfter2Line={
            <Fragment />
          }
          contentAfterFields2Line={
            <TemplatesLoader
              templates={elementToolbarAfterFieldsContent}
              templateData={{ element: element }}
            />
          }
        />
      )}
      <div className={"tw3-space-y-4"}>
        {canShowElement ? (
          <Fragment>
            <TemplatesLoader
              templates={afterTitleBlockContent}
              templateData={{ element: element }}
            />

            <div className={"tw3-flex tw3-gap-2"}>
              <div className={classNames(props.layoutLeftColClassName ? props.layoutLeftColClassName : "tw3-flex-grow", "tw3-space-y-2")}>
                <WhiteCard className={"tw3-relative"}>
                  {element ? (
                    <TemplatesLoader
                      templates={props.formContent}
                      templateData={{
                        element: element,
                        updateElement: () => { },
                        fieldVisibility: fieldVisibility,
                        pushHiddenFields: () => { },
                      }}
                    />
                  ) : (
                    <Fragment />
                  )}
                </WhiteCard>

                <TemplatesLoader
                  templates={bottomContent}
                  templateData={{
                    element: element,
                    updateElement: () => { },
                    fieldVisibility: fieldVisibility,
                    pushHiddenFields: () => { },
                  }}
                />
              </div>
              
              <div
                className={props.layoutRightColClassName ? props.layoutRightColClassName : "tw3-w-[420px] tw3-min-w-[420px] tw3-max-w-[420px]"}
              >
                <div className={"tw3-grid tw3-grid-cols-1 tw3-gap-1"}>
                  <div className="tw3-space-y-2">
                    <TemplatesLoader
                      templates={rightContent}
                      templateData={{
                        element: element,
                        updateElement: () => { },
                        fieldVisibility: fieldVisibility,
                        pushHiddenFields: () => { },
                      }}
                    />
                  </div>
                </div>
              </div>
            </div>


            {bottomExtraContent.length > 0 && (
              <div className="tw3-space-y-2">
                <TemplatesLoader
                  templates={bottomExtraContent}
                  templateData={{
                    element: element,
                    updateElement: () => { },
                    fieldVisibility: fieldVisibility,
                    pushHiddenFields: () => { },
                  }}
                />
              </div>
            )}
            
          </Fragment>
        ) : reloading ? (
          <LogoLoader />
        ) : (
          <AlertWidget color="danger" width="tw3-w-full">
            {t("You do not have permission to view this record")}
          </AlertWidget>
        )}
      </div>
    </Fragment>
  );
}
