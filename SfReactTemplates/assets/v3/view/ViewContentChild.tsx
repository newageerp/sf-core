import { UI, UIConfig } from "@newageerp/nae-react-ui";
import { INaeWidget } from "@newageerp/nae-react-ui/dist/interfaces";
import React, { Fragment, useState } from "react";
// import { useTranslation } from 'react-i18next'
import TemplateLoader, { Template } from "../templates/TemplateLoader";
import { fieldVisibility } from "../../../config/fields/fieldVisibility";
import { useTranslation } from "react-i18next";
import TasksWidget from "../../apps/tasks/TasksWidget";
import { ElementToolbar } from "@newageerp/ui.components.element.element-toolbar";
import { useRecoilValue } from "recoil";
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import { ToolbarButtonWithMenu } from "@newageerp/v3.buttons.toolbar-button-with-menu";
import { WhiteCard } from "@newageerp/v3.widgets.white-card";

interface Props {
  onBack?: () => void;
  onEdit?: () => void;
  forceEditInPopup?: boolean;

  schema: string;
  type: string;
  id: string;

  formContent: Template[];
  editable: boolean;
  removable: boolean;

  rightContent: Template[];
  bottomContent: Template[];

  afterTitleBlockContent: Template[];
  elementToolbarAfterFieldsContent: Template[];
  elementToolbarLine2BeforeContent: Template[];
  elementToolbarMoreMenuContent: Template[];
}

export default function ViewContentChild(props: Props) {
  const { t } = useTranslation();
  const userState = useRecoilValue(OpenApi.naeUserState);

  const { element, reloadData, reloading } = UI.Record.useNaeRecord();

  const {
    rightContent,
    bottomContent,
    afterTitleBlockContent,
    elementToolbarAfterFieldsContent,
    elementToolbarLine2BeforeContent,
    elementToolbarMoreMenuContent,
  } = props;

  const { isPopup } = UI.Popup.useNaePopup();
  const { showEditPopup } = UI.Window.useNaeWindow();
  const [viewKey, setViewKey] = useState(0);

  const isEditInPopup = props.forceEditInPopup
    ? props.forceEditInPopup
    : isPopup;

  const [doRemove] = UIConfig.useURemove(props.schema);

  const { editable, removable } = props;

  const onEdit = editable
    ? () => {
        if (props.onEdit) {
          props.onEdit();
        } else {
          if (isEditInPopup) {
            showEditPopup({
              id: props.id,
              schema: props.schema,
              onSaveCallback: (_el, backFunc) => {
                reloadData().then(() => {
                  setViewKey(viewKey + 1);
                  backFunc();
                });
              },
            });
          } else {
            UIConfig.router({
              path:
                "/u/" + props.schema + "/" + props.type + "/edit/" + props.id,
            });
          }
        }
      }
    : undefined;

  const onRemove = removable
    ? () => {
        doRemove(props.id).then(() => {
          if (props.onBack) {
            props.onBack();
          }
        });
      }
    : undefined;

  const middleWidgets = UIConfig.widgets().filter(
    (w: INaeWidget) =>
      w.type === UI.Widget.WidgetType.viewMiddle &&
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
          onBack={props.onBack ? props.onBack : () => {}}
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
            <UI.Widget.Widget
              type={UI.Widget.WidgetType.viewMainTop1LineBefore}
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

              <UI.Widget.Widget
                type={UI.Widget.WidgetType.viewMainTop2LineBefore}
                schema={props.schema}
                element={element}
              />
            </Fragment>
          }
          contentAfter1Line={
            <UI.Widget.Widget
              type={UI.Widget.WidgetType.viewMainTop1LineAfter}
              schema={props.schema}
              element={element}
            />
          }
          contentAfter2Line={
            <UI.Widget.Widget
              type={UI.Widget.WidgetType.viewMainTop2LineAfter}
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
      <div className={"space-y-4"}>
        {canShowElement ? (
          <Fragment>
            <TemplateLoader
              templates={afterTitleBlockContent}
              templateData={{ element: element }}
            />

            <div className={"flex gap-2"}>
              <div className={"flex-grow space-y-2"}>
                <WhiteCard className={"relative"}>
                  {element ? (
                    <TemplateLoader
                      templates={props.formContent}
                      templateData={{
                        element: element,
                        updateElement: () => {},
                        fieldVisibility: fieldVisibility,
                        pushHiddenFields: () => {},
                      }}
                    />
                  ) : (
                    <Fragment />
                  )}
                </WhiteCard>
                <UI.Widget.Widget
                  type={UI.Widget.WidgetType.viewBottom}
                  schema={props.schema}
                  element={element}
                />

                <TemplateLoader
                  templates={bottomContent}
                  templateData={{
                    element: element,
                    updateElement: () => {},
                    fieldVisibility: fieldVisibility,
                    pushHiddenFields: () => {},
                  }}
                />
              </div>
              {middleWidgets.length > 0 && (
                <div style={{ width: 700, minWidth: 700, maxWidth: 700 }}>
                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewMiddle}
                    schema={props.schema}
                    element={element}
                  />
                </div>
              )}
              <div className={"tw3-w-[420px] tw3-min-w-[420px] tw3-max-w-[420px]"}>
                <div className={"grid grid-cols-1 gap-1"}>
                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewRightTop}
                    schema={props.schema}
                    element={element}
                  />

                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewRightButtons}
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

                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewAfterPdfButton}
                    schema={props.schema}
                    element={element}
                  />

                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewAfterConvertButton}
                    schema={props.schema}
                    element={element}
                  />

                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewAfterCreateButton}
                    schema={props.schema}
                    element={element}
                  />

                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewAfterEditButton}
                    schema={props.schema}
                    element={element}
                  />

                  <UI.Widget.Widget
                    type={UI.Widget.WidgetType.viewRight}
                    schema={props.schema}
                    element={element}
                  />

                  <div className="tw3-space-y-2">
                    <TemplateLoader
                      templates={rightContent}
                      templateData={{
                        element: element,
                        updateElement: () => {},
                        fieldVisibility: fieldVisibility,
                        pushHiddenFields: () => {},
                      }}
                    />
                  </div>
                </div>
              </div>
            </div>
            <UI.Widget.Widget
              type={UI.Widget.WidgetType.viewExtraBottom}
              schema={props.schema}
              element={element}
            />
          </Fragment>
        ) : reloading ? (
          <UI.Loader.Logo />
        ) : (
          <UI.Alerts.Alert bgColor={UI.Alerts.AlertBgColor.red}>
            {t("Neturite teisių matyti šį įrašą")}
          </UI.Alerts.Alert>
        )}
      </div>
    </Fragment>
  );
}
