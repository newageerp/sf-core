import React, { Fragment, useEffect, useState } from "react";
import { useTemplatesLoader } from '@newageerp/v3.templates.templates-core';
import { FieldHtmlEditor } from "@newageerp/v3.bundles.form-bundle";

interface Props {
  fieldKey: string;
}

export default function HtmlEditorEditableField(props: Props) {
  const { data: tData } = useTemplatesLoader();
  const { element, updateElement } = tData;

  const value = element[props.fieldKey] ? element[props.fieldKey] : undefined;

  return <FieldHtmlEditor
    value={value}
    onChange={(v) => updateElement(props.fieldKey, v)}
  />
}
