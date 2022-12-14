import React from "react";
import { Compact, Wide } from "@newageerp/ui.form.base.form-pack";
import { Template, TemplatesParser } from '@newageerp/v3.templates.templates-core';

interface Props {
  children: Template[];
  isCompact?: boolean;
}

export default function EditableForm(props: Props) {
  if (props.isCompact) {
    return (
      <Compact>
        <TemplatesParser templates={props.children} />
      </Compact>
    );
  }
  return (
    <Wide>
      <TemplatesParser templates={props.children} />
    </Wide>
  );
}
