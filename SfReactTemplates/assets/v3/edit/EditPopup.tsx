import React, { Fragment } from "react";

import {TemplatesLoader} from '@newageerp/v3.templates.templates-core';
import { MvcEditModalContentProps, MvcViewModalContentProps, Popup, PopupProvider } from '@newageerp/v3.bundles.popup-bundle'

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

  const onCloseWithPrompt = () => {
    if (!window.confirm('Are you sure?')) return false;
    props.onClose();
  }

  return (
    <PopupProvider isPopup={true} onClose={onCloseWithPrompt}>
      <Popup title="" isPopup={true} onClick={onCloseWithPrompt} className={'tw3-min-w-[50vw]'}>
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
    </PopupProvider>
  );
};
