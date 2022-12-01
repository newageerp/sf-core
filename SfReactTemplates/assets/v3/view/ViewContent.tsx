import React, { Fragment, useState } from "react";
// import { useTranslation } from 'react-i18next'
import TemplateLoader, { Template, useTemplateLoader } from "../templates/TemplateLoader";
import { fieldVisibility } from "../../_custom/fields/fieldVisibility";
import { useTranslation } from "react-i18next";
import TasksWidget from "../../apps/tasks/TasksWidget";
import { ElementToolbar } from "@newageerp/ui.components.element.element-toolbar";
import { useRecoilValue } from "recoil";
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import { ToolbarButtonWithMenu } from "@newageerp/v3.bundles.buttons-bundle";
import { WhiteCard } from "@newageerp/v3.bundles.widgets-bundle";
import {
  SFSOpenEditModalWindowProps,
  SFSOpenEditWindowProps,
} from "@newageerp/v3.popups.mvc-popup";
import { checkIsEditable, INaeWidget, WidgetType } from "../utils";
import OldAlert, { AlertBgColor } from "../old-ui/OldAlert";
import OldNeWidgets from "../old-ui/OldNeWidgets";
import { useNaeRecord } from "../old-ui/OldNaeRecord";
import { useNaePopup } from "../old-ui/OldPopupProvider";
import classNames from 'classnames';
import { LogoLoader } from "@newageerp/ui.loaders.logo-loader";
import { NaeWidgets } from "../../_custom/widgets";

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
  const { data: tdata } = useTemplateLoader();

  const { t } = useTranslation();
  const userState = useRecoilValue(OpenApi.naeUserState);

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

  const { isPopup } = useNaePopup();
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

  const widgets = NaeWidgets;

  const middleWidgets = widgets.filter(
    (w: INaeWidget) =>
      w.type === WidgetType.viewMiddle &&
      (w.schema === props.schema || w.schema === "all")
  );

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
          tasksContent={
            <TasksWidget
              element={element}
              options={{}}
              schema={props.schema}
              userState={userState}
            />
          }
          showRemind={true}
          showBookmark={true}
          contentBefore1Line={
            <OldNeWidgets
              type={WidgetType.viewMainTop1LineBefore}
              schema={props.schema}
              element={element}
            />
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
                      <TemplateLoader
                        templates={elementToolbarMoreMenuContent}
                        templateData={{ element: element }}
                      />
                    ),
                  }}
                />
              )}

              <TemplateLoader
                templates={elementToolbarLine2BeforeContent}
                templateData={{ element: element }}
              />

              <OldNeWidgets
                type={WidgetType.viewMainTop2LineBefore}
                schema={props.schema}
                element={element}
              />
            </Fragment>
          }
          contentAfter1Line={
            <Fragment>
              <OldNeWidgets
                type={WidgetType.viewMainTop1LineAfter}
                schema={props.schema}
                element={element}
              />
              <TemplateLoader
                templates={elementToolbarAfter1Line}
                templateData={{ element: element }}
              />
            </Fragment>
          }
          contentAfter2Line={
            <OldNeWidgets
              type={WidgetType.viewMainTop2LineAfter}
              schema={props.schema}
              element={element}
            />
          }
          contentAfterFields2Line={
            <TemplateLoader
              templates={elementToolbarAfterFieldsContent}
              templateData={{ element: element }}
            />
          }
        />
      )}
      <div className={"tw3-space-y-4"}>
        {canShowElement ? (
          <Fragment>
            <TemplateLoader
              templates={afterTitleBlockContent}
              templateData={{ element: element }}
            />

            <div className={"tw3-flex tw3-gap-2"}>
              <div className={classNames(props.layoutLeftColClassName ? props.layoutLeftColClassName : "tw3-flex-grow", "tw3-space-y-2")}>
                <WhiteCard className={"tw3-relative"}>
                  {element ? (
                    <TemplateLoader
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
                <OldNeWidgets
                  type={WidgetType.viewBottom}
                  schema={props.schema}
                  element={element}
                />

                <TemplateLoader
                  templates={bottomContent}
                  templateData={{
                    element: element,
                    updateElement: () => { },
                    fieldVisibility: fieldVisibility,
                    pushHiddenFields: () => { },
                  }}
                />
              </div>
              {(middleWidgets.length > 0 || middleContent.length > 0) && (
                <div style={{ width: 700, minWidth: 700, maxWidth: 700 }}>
                  {middleContent.length > 0 && (
                    <div className="tw3-space-y-2">
                      <TemplateLoader
                        templates={middleContent}
                        templateData={{
                          element: element,
                          updateElement: () => { },
                          fieldVisibility: fieldVisibility,
                          pushHiddenFields: () => { },
                        }}
                      />
                    </div>
                  )}

                  <OldNeWidgets
                    type={WidgetType.viewMiddle}
                    schema={props.schema}
                    element={element}
                  />
                </div>
              )}
              <div
                className={props.layoutRightColClassName ? props.layoutRightColClassName : "tw3-w-[420px] tw3-min-w-[420px] tw3-max-w-[420px]"}
              >
                <div className={"tw3-grid tw3-grid-cols-1 tw3-gap-1"}>
                  <OldNeWidgets
                    type={WidgetType.viewRightTop}
                    schema={props.schema}
                    element={element}
                  />

                  <OldNeWidgets
                    type={WidgetType.viewRightButtons}
                    schema={props.schema}
                    element={element}
                  />

                  {/* {props.pdfButtons?.map(
                      (btn: MvcViewPdf, pdfIndex: number) => {
                        return (
                          <PdfButtonsLine pdf={btn} key={'pdf-btn-' + pdfIndex} />
                        )
                      }
                    )} */}

                  <OldNeWidgets
                    type={WidgetType.viewAfterPdfButton}
                    schema={props.schema}
                    element={element}
                  />

                  <OldNeWidgets
                    type={WidgetType.viewAfterConvertButton}
                    schema={props.schema}
                    element={element}
                  />

                  <OldNeWidgets
                    type={WidgetType.viewAfterCreateButton}
                    schema={props.schema}
                    element={element}
                  />

                  <OldNeWidgets
                    type={WidgetType.viewAfterEditButton}
                    schema={props.schema}
                    element={element}
                  />

                  <OldNeWidgets
                    type={WidgetType.viewRight}
                    schema={props.schema}
                    element={element}
                  />

                  <div className="tw3-space-y-2">
                    <TemplateLoader
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
                <TemplateLoader
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
            <OldNeWidgets
              type={WidgetType.viewExtraBottom}
              schema={props.schema}
              element={element}
            />
          </Fragment>
        ) : reloading ? (
          <LogoLoader />
        ) : (
          <OldAlert bgColor={AlertBgColor.red}>
            {t("Neturite teisių matyti šį įrašą")}
          </OldAlert>
        )}
      </div>
    </Fragment>
  );
}
