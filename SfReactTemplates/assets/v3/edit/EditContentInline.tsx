import React from "react";
import {TemplatesLoader} from '@newageerp/v3.templates.templates-core';

interface Props {
  schema: string;
  type: string;
  id: string;
  isCompact?: boolean;

  onBack?: () => void;
}

export default function EditContentInline(props: Props) {
  return (
    <TemplatesLoader
      key={`${props.schema}-${props.type}-${props.id}`}
      templateName="PageMainEdit"
      data={{ ...props }}
      templateData={{
        onBack: props.onBack,
        onSaveCallback: () => {
          
        }
      }}
    />
  );
}
