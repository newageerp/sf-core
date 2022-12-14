import React, { Fragment } from "react";

import {TemplatesLoader} from '@newageerp/v3.templates.templates-core';
import { NaePopupProvider } from "../old-ui/OldPopupProvider";
import { MvcEditModalContentProps, MvcViewModalContentProps } from "../old-ui/OldNaeWindowProvider";
import { Popup } from '@newageerp/v3.bundles.popup-bundle'

interface PopupProps {
  onClose: () => void;
  editProps: MvcEditModalContentProps;
  setViewProps?: (v: MvcViewModalContentProps) => void;
}

export const editPopupBySchemaAndType = (
  schema: string,
  type: string,
  props: PopupProps
) => {
  const { editProps } = props;

  return (
    <NaePopupProvider isPopup={true} onClose={props.onClose}>
      <Popup title="" isPopup={true} onClick={props.onClose} className={'tw3-min-w-[50vw]'}>
        <TemplatesLoader
          key={`${editProps.schema}-${editProps.type}-${editProps.id}`}
          templateName="PageMainEdit"
          data={editProps}
          templateData={
            {
            onBack: props.onClose,
            onSaveCallback: editProps.onSaveCallback,
          }
          }
        />
      </Popup>
    </NaePopupProvider>
  );
};
