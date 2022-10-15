import React, { Fragment } from "react";
import { WideRow as WideRowT } from "@newageerp/ui.form.base.form-pack";
import { Template, TemplatesParser, useTemplateLoader } from '../../templates/TemplateLoader';
import { useRecoilValue } from "recoil";
import { OpenApi } from "@newageerp/nae-react-auth-wrapper";
import { MainEditTemplateData } from "../../../v2/edit-forms/MainEdit";

interface Props {
  labelClassName?: string;
  labelAutoWidth?: boolean;
  controlClassName?: string;
  labelContent: Template[];
  controlContent: Template[];
  fieldVisibilityData?: {
    fieldKey: string;
    fieldSchema: string;
  }
}

export default function WideRow(props: Props) {
  const userState = useRecoilValue(OpenApi.naeUserState);

  const { data: tData } = useTemplateLoader();
  const mainTemplateData : MainEditTemplateData = tData;
  const { element, fieldVisibility, updateElement, parentElement, hasChanges, formType, pushHiddenFields } = mainTemplateData;

  const isVisible = () => {
    if (!props.fieldVisibilityData) {
      return true;
    }
    const fData = props.fieldVisibilityData;

    const fVisibility =
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
    <WideRowT
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
