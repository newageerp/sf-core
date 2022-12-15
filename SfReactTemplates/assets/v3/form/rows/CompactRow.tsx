import React, { Fragment } from "react";
import { CompactRow as CompactRowT } from "@newageerp/ui.form.base.form-pack";
import { Template, TemplatesParser, useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { useRecoilValue } from "@newageerp/v3.templates.templates-core";
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import { MainEditTemplateData } from "../../edit/MainEdit";

interface Props {
  labelClassName?: string;
  labelAutoWidth?: boolean;
  controlClassName?: string;
  labelContent: Template[];
  controlContent: Template[];
  fieldVisibilityData?: {
    fieldKey: string;
    fieldSchema: string;
  },
  skipCheckFieldVisibility?: boolean,
}

export default function CompactRow(props: Props) {
  const userState = useRecoilValue(OpenApi.naeUserState);

  const { data: tData } = useTemplatesLoader();
  const mainTemplateData : MainEditTemplateData = tData;
  const { element, fieldVisibility, updateElement, parentElement, hasChanges, formType, pushHiddenFields } = mainTemplateData;

  const isVisible = () => {
    if (!props.fieldVisibilityData) {
      return true;
    }
    const fData = props.fieldVisibilityData;

    const fVisibility =
      !props.skipCheckFieldVisibility &&
      !!fieldVisibility && 
      !!fieldVisibility[fData.fieldSchema] &&
        !!fieldVisibility[fData.fieldSchema][fData.fieldKey]
        ? fieldVisibility[fData.fieldSchema][fData.fieldKey]
        : null;

    if (
      !!fVisibility &&
      fVisibility({
        schema: fData.fieldSchema,
        element,
        user: userState,
        parentElement,
        hasChanges,
        updateElement,
        type: formType,
        isEdit: true,
      })
    ) {
      pushHiddenFields(fData.fieldKey);
      return false;
    }
    return true;
  }

  if (!isVisible()) {
    return <Fragment />
  }

  return (
    <CompactRowT
      autoWidth={props.labelAutoWidth}
      controlClassName={props.controlClassName}
      labelClassName={props.labelClassName}
      label={
        props.labelContent.length > 0 ? (
          <TemplatesParser templates={props.labelContent} />
        ) : undefined
      }
      control={
        props.controlContent.length > 0 ? (
          <TemplatesParser templates={props.controlContent} />
        ) : undefined
      }
    />
  );
}