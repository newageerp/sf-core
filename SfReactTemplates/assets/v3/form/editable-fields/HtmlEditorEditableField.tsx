import React, { Fragment, useEffect, useState } from "react";
import { useTemplateLoader } from "../../templates/TemplateLoader";
import { Input } from "@newageerp/ui.form.base.form-pack";

interface Props {
  fieldKey: string;
}

export default function HtmlEditorEditableField(props: Props) {
  const { data: tData } = useTemplateLoader();
  const { element, updateElement } = tData;

  return <div>test</div>
}
