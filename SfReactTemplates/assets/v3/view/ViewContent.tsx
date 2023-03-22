import React, { Fragment, useState } from "react";

import { TemplatesLoader, Template, useTemplatesLoader } from "@newageerp/v3.templates.templates-core";
import { fieldVisibility } from "../../_custom/fields/fieldVisibility";
import { useTranslation } from "@newageerp/v3.templates.templates-core";

import { ToolbarButton, ToolbarButtonWithMenu } from "@newageerp/v3.bundles.buttons-bundle";
import { AlertWidget, WhiteCard } from "@newageerp/v3.bundles.widgets-bundle";
import {
  SFSOpenEditModalWindowProps,
  SFSOpenEditWindowProps,
  usePopup,
} from "@newageerp/v3.bundles.popup-bundle";
import { checkIsEditable } from "../utils";
import classNames from 'classnames';
import { BackBtn, LogoLoader } from "@newageerp/v3.bundles.layout-bundle";
import { useTemplatesCore } from '@newageerp/v3.templates.templates-core';
import { useNaeRecord } from "@newageerp/v3.app.mvc.record-provider";
import { useURemove } from "@newageerp/v3.bundles.hooks-bundle";
import { TextElementTitle } from "@newageerp/v3.bundles.typography-bundle";
import { Date, Time } from "@newageerp/v3.bundles.data-bundle";

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
  const { userState } = useTemplatesCore()

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

  const [doRemove] = useURemove(props.schema);

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

export type ElementToolbarProps = {
  onBack: () => void;
  onEdit?: () => void;

  onRemove?: () => void;


  parentSchema?: string;
  parentId?: number;

  contentBefore1Line?: any;
  contentAfter1Line?: any;

  contentBefore2Line?: any;
  contentAfter2Line?: any;


  contentAfterFields2Line?: any;

  element: any;

  baseUrl?: string,
};

function ElementToolbar(props: ElementToolbarProps) {
  const { t } = useTranslation();

  const { element } = props;

  const voidFunc = () => { }

  return (
    <div>
      <div
        className={classNames(
          'tw3-flex tw3-pb-6',
          'tw3-border-b',
          'tw3-border-slate-300'
        )}
      >
        <BackBtn onClick={props.onBack} />
        <span className="tw3-flex-grow tw3-text-center"></span>
        <div className="tw3-flex tw3-items-center">
          {props.contentBefore1Line}

          {props.contentAfter1Line}
        </div>
      </div>
      <div className="tw3-pt-6 tw3-pb-8 tw3-flex tw3-items-start tw3-gap-10">
        <TextElementTitle>
          {element._viewTitle ? element._viewTitle : element.serialNumber}
        </TextElementTitle>

        {!!element.createdAt && (
          <div className={classNames('tw3-text-sm')}>
            <p className={classNames('tw3-text-slate-700')}>
              <Date value={element.createdAt} format="YY-MM-DD" />
            </p>
            <p
              className={classNames(
                'tw3-text-slate-400',
                'tw3-flex tw3-justify-between tw3-items-center'
              )}
            >
              <Time value={element.createdAt} />
              <i className="fa fa-plus fa-thin tw3-text-[11px]" />

            </p>
          </div>
        )}
        {!!element.updatedAt && (
          <div className={classNames('tw3-text-sm')}>
            <p className={classNames('tw3-text-slate-700')}>
              <Date value={element.updatedAt} format="YY-MM-DD" />
            </p>
            <p
              className={classNames(
                'tw3-text-slate-400',
                'tw3-flex tw3-justify-between tw3-items-center'
              )}
            >
              <Time value={element.updatedAt} />
              <i className="fa fa-pencil fa-thin tw3-text-[11px]" />
            </p>
          </div>
        )}

        {!!element.creator && element.creator?.id !== element.doer?.id && (
          <div className={classNames('tw3-text-sm')}>
            <p className={classNames('tw3-text-slate-700')}>{t('Created by')}</p>
            <p className={classNames('tw3-text-slate-400')}>
              {element.creator.fullName}
            </p>
          </div>
        )}

        {!!element.doer && (
          <div className={classNames('tw3-text-sm')}>
            <p className={classNames('tw3-text-slate-700')}>
              {t('Responsible')}
            </p>
            <p className={classNames('tw3-text-slate-400')}>
              {element.doer.fullName}
            </p>
          </div>
        )}

        {props.contentAfterFields2Line}

        <span className="tw3-flex-grow"></span>

        <div className="tw3-flex tw3-items-center">
          {props.contentBefore2Line}

          <ToolbarButton
            onClick={props.onEdit}
            iconName={"edit"}
            title={t('Update')}
          />

          <ToolbarButton
            onClick={props.onRemove}
            iconName={"trash"}
            title={t('Remove')}
            confirmation={true}
          />

          {props.contentAfter2Line}
        </div>
      </div>
    </div>
  );
}